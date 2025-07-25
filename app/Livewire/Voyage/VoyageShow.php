<?php

namespace App\Livewire\Voyage;

use Livewire\Component;
use App\Models\Voyage;
use App\Models\Chargement;
use App\Models\Dechargement;
use App\Models\Produit;
use App\Models\User;
use App\Models\Lieu;

class VoyageShow extends Component
{
    public Voyage $voyage;
    public $activeTab = 'chargements';
    
    // Chargement form
    public $showChargementModal = false;
    public $editingChargement = null;
    
    // Déchargement form
    public $showDechargementModal = false;
    public $editingDechargement = null;
    
    // ✅ NOUVEAUX : Aperçu avant sauvegarde
    public $showPreviewModal = false;
    public $previewData = [];
    
    // Form fields pour chargement
    public $chargement_reference = '';
    public $chargeur_nom = '';
    public $chargeur_contact = '';
    public $proprietaire_nom = 'Mme TINAH';
    public $proprietaire_contact = '0349045769';
    public $produit_id = '';
    public $sacs_pleins_depart = '';
    public $sacs_demi_depart = 0;
    public $poids_depart_kg = '';
    public $chargement_observation = '';

    // Form fields pour déchargement
    public $dechargement_reference = '';
    public $chargement_id = ''; 
    public $type_dechargement = 'vente';
    public $interlocuteur_nom = '';
    public $interlocuteur_contact = '';
    public $pointeur_nom = '';
    public $pointeur_contact = '';
    public $lieu_livraison_id = '';
    public $sacs_pleins_arrivee = '';
    public $sacs_demi_arrivee = 0;
    public $poids_arrivee_kg = '';
    public $prix_unitaire_mga = '';
    public $montant_total_mga = '';
    public $paiement_mga = '';
    public $reste_mga = '';
    public $statut_commercial = 'en_attente';
    public $dechargement_observation = '';

    protected $rules = [
        // Déchargement rules
        'dechargement_reference' => 'required|string|max:255',
        'chargement_id' => 'required|exists:chargements,id',
        'type_dechargement' => 'required|in:vente,retour,depot,transfert',
        'pointeur_nom' => 'nullable|string|max:255',
        'lieu_livraison_id' => 'nullable|exists:lieux,id',
        'sacs_pleins_arrivee' => 'nullable|integer|min:0',
        'sacs_demi_arrivee' => 'nullable|integer|min:0',
        'poids_arrivee_kg' => 'nullable|numeric|min:0',
        'prix_unitaire_mga' => 'nullable|numeric|min:0',
        'montant_total_mga' => 'nullable|numeric|min:0',
        'paiement_mga' => 'nullable|numeric|min:0',
        'statut_commercial' => 'required|in:en_attente,vendu,retourne,transfere',
        'dechargement_observation' => 'nullable|string',
    ];

    public function mount(Voyage $voyage)
    {
        $this->voyage = $voyage;
    }

    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
    }

    // ✅ NOUVELLE MÉTHODE : Aperçu avant sauvegarde
    public function showPreview()
    {
        // Validation des champs obligatoires avant aperçu
        $this->validate([
            'dechargement_reference' => 'required|string|max:255',
            'chargement_id' => 'required|exists:chargements,id',
            'type_dechargement' => 'required',
        ]);

        // Calculs automatiques
        $this->calculateFinancials();

        // Récupérer le chargement sélectionné
        $chargement = $this->voyage->chargements->find($this->chargement_id);
        
        if (!$chargement) {
            session()->flash('error', 'Chargement introuvable');
            return;
        }

        // Préparer les données pour l'aperçu
        $this->previewData = [
            'chargement' => [
                'reference' => $chargement->reference,
                'proprietaire_nom' => $chargement->proprietaire_nom,
                'produit' => $chargement->produit->nom ?? 'N/A',
                'sacs_pleins_depart' => $chargement->sacs_pleins_depart,
                'sacs_demi_depart' => $chargement->sacs_demi_depart,
                'poids_depart_kg' => $chargement->poids_depart_kg,
            ],
            'dechargement' => [
                'reference' => $this->dechargement_reference,
                'type' => $this->type_dechargement,
                'pointeur_nom' => $this->pointeur_nom ?: 'Non renseigné',
                'lieu_livraison' => $this->lieu_livraison_id ? 
                    Lieu::find($this->lieu_livraison_id)->nom : 'Non renseigné',
                'sacs_pleins_arrivee' => $this->sacs_pleins_arrivee ?: 0,
                'sacs_demi_arrivee' => $this->sacs_demi_arrivee ?: 0,
                'poids_arrivee_kg' => $this->poids_arrivee_kg ?: 0,
                'prix_unitaire_mga' => $this->prix_unitaire_mga ?: 0,
                'montant_total_mga' => $this->montant_total_mga ?: 0,
                'paiement_mga' => $this->paiement_mga ?: 0,
                'reste_mga' => $this->reste_mga ?: 0,
                'statut_commercial' => $this->statut_commercial,
            ],
            'calculs' => [
                'ecart_sacs_pleins' => $chargement->sacs_pleins_depart - ($this->sacs_pleins_arrivee ?: 0),
                'ecart_sacs_demi' => $chargement->sacs_demi_depart - ($this->sacs_demi_arrivee ?: 0),
                'ecart_poids_kg' => $chargement->poids_depart_kg - ($this->poids_arrivee_kg ?: 0),
                'pourcentage_perte' => $chargement->poids_depart_kg > 0 ? 
                    (($chargement->poids_depart_kg - ($this->poids_arrivee_kg ?: 0)) / $chargement->poids_depart_kg) * 100 : 0,
            ]
        ];

        $this->showPreviewModal = true;
    }

    // ✅ CONFIRMER ET SAUVEGARDER après aperçu
    public function confirmSaveDechargement()
    {
        $this->showPreviewModal = false;
        $this->proceedWithSave();
    }

    // ✅ MÉTHODE DE SAUVEGARDE PRINCIPALE
    public function saveDechargement()
    {
        // Validation complète
        $this->validate();

        // Vérifier si un déchargement existe déjà pour ce chargement_id
        if (!$this->editingDechargement) {
            $existingDechargement = Dechargement::where('chargement_id', $this->chargement_id)->first();
            if ($existingDechargement) {
                session()->flash('error', 'Un déchargement existe déjà pour ce chargement.');
                return;
            }
        }

        // Afficher l'aperçu d'abord
        $this->showPreview();
    }

    // ✅ SAUVEGARDE EFFECTIVE (après confirmation)
    private function proceedWithSave()
    {
        try {
            // Calculs automatiques
            $this->calculateFinancials();

            $data = [
                'voyage_id' => $this->voyage->id,
                'reference' => $this->dechargement_reference,
                'chargement_id' => $this->chargement_id,
                'type' => $this->type_dechargement,
                'interlocuteur_nom' => $this->interlocuteur_nom,
                'interlocuteur_contact' => $this->interlocuteur_contact,
                'pointeur_nom' => $this->pointeur_nom,
                'pointeur_contact' => $this->pointeur_contact,
                'lieu_livraison_id' => $this->lieu_livraison_id ?: null,
                'sacs_pleins_arrivee' => $this->sacs_pleins_arrivee ?: null,
                'sacs_demi_arrivee' => $this->sacs_demi_arrivee ?: 0,
                'poids_arrivee_kg' => $this->poids_arrivee_kg ?: null,
                'prix_unitaire_mga' => $this->prix_unitaire_mga ?: null,
                'montant_total_mga' => $this->montant_total_mga ?: null,
                'paiement_mga' => $this->paiement_mga ?: null,
                'reste_mga' => $this->reste_mga ?: null,
                'statut_commercial' => $this->statut_commercial,
                'observation' => $this->dechargement_observation,
            ];

            if ($this->editingDechargement) {
                $this->editingDechargement->update($data);
                session()->flash('success', 'Déchargement modifié avec succès');
            } else {
                Dechargement::create($data);
                session()->flash('success', 'Déchargement ajouté avec succès');
            }

            $this->closeDechargementModal();
            $this->voyage->refresh();

        } catch (\Exception $e) {
            session()->flash('error', 'Erreur lors de la sauvegarde: ' . $e->getMessage());
        }
    }

    // CHARGEMENTS (méthodes existantes)
    public function createChargement()
    {
        $this->resetChargementForm();
        $this->editingChargement = null;
        $this->chargement_reference = $this->generateChargementReference();
        $this->showChargementModal = true;
    }

    public function saveChargement()
    {
        $this->validate([
            'chargement_reference' => 'required|string|max:255',
            'chargeur_nom' => 'required|string|max:255',
            'proprietaire_nom' => 'required|string|max:255',
            'produit_id' => 'required|exists:produits,id',
            'sacs_pleins_depart' => 'required|integer|min:0',
            'poids_depart_kg' => 'required|numeric|min:0',
        ]);

        $data = [
            'voyage_id' => $this->voyage->id,
            'reference' => $this->chargement_reference,
            'chargeur_nom' => $this->chargeur_nom,
            'chargeur_contact' => $this->chargeur_contact,
            'proprietaire_nom' => $this->proprietaire_nom,
            'proprietaire_contact' => $this->proprietaire_contact,
            'produit_id' => $this->produit_id,
            'sacs_pleins_depart' => $this->sacs_pleins_depart,
            'sacs_demi_depart' => $this->sacs_demi_depart ?: 0,
            'poids_depart_kg' => $this->poids_depart_kg,
            'observation' => $this->chargement_observation,
        ];

        if ($this->editingChargement) {
            $this->editingChargement->update($data);
            session()->flash('success', 'Chargement modifié avec succès');
        } else {
            Chargement::create($data);
            session()->flash('success', 'Chargement ajouté avec succès');
        }

        $this->closeChargementModal();
        $this->voyage->refresh();
    }

    // DÉCHARGEMENTS
    public function createDechargement()
    {
        $this->resetDechargementForm();
        $this->editingDechargement = null;
        $this->dechargement_reference = $this->generateDechargementReference();
        $this->showDechargementModal = true;
    }

    public function editDechargement(Dechargement $dechargement)
    {
        $this->editingDechargement = $dechargement;
        $this->dechargement_reference = $dechargement->reference;
        $this->chargement_id = $dechargement->chargement_id;
        $this->type_dechargement = $dechargement->type;
        $this->interlocuteur_nom = $dechargement->interlocuteur_nom;
        $this->interlocuteur_contact = $dechargement->interlocuteur_contact;
        $this->pointeur_nom = $dechargement->pointeur_nom;
        $this->pointeur_contact = $dechargement->pointeur_contact;
        $this->lieu_livraison_id = $dechargement->lieu_livraison_id;
        $this->sacs_pleins_arrivee = $dechargement->sacs_pleins_arrivee;
        $this->sacs_demi_arrivee = $dechargement->sacs_demi_arrivee;
        $this->poids_arrivee_kg = $dechargement->poids_arrivee_kg;
        $this->prix_unitaire_mga = $dechargement->prix_unitaire_mga;
        $this->montant_total_mga = $dechargement->montant_total_mga;
        $this->paiement_mga = $dechargement->paiement_mga;
        $this->reste_mga = $dechargement->reste_mga;
        $this->statut_commercial = $dechargement->statut_commercial;
        $this->dechargement_observation = $dechargement->observation;
        $this->showDechargementModal = true;
    }

    public function deleteDechargement(Dechargement $dechargement)
    {
        $dechargement->delete();
        session()->flash('success', 'Déchargement supprimé avec succès');
        $this->voyage->refresh();
    }

    // ✅ NOUVEAU : Remplir automatiquement le paiement complet
    public function setFullPayment()
    {
        if ($this->montant_total_mga) {
            $this->paiement_mga = $this->montant_total_mga;
            $this->calculateFinancials();
        }
    }

    // ✅ CALCULS AUTOMATIQUES AMÉLIORÉS
    public function calculateFinancials()
    {
        // Conversion explicite en float
        $this->montant_total_mga = (float)$this->prix_unitaire_mga * (float)$this->poids_arrivee_kg;
        
        // Utilisation de l'opérateur Elvis pour les calculs
        $montant_total = is_numeric($this->montant_total_mga) ? (float)$this->montant_total_mga : 0;
        $paiement = is_numeric($this->paiement_mga) ? (float)$this->paiement_mga : 0;
        
        $this->reste_mga = $montant_total - $paiement;
    }

    // ✅ LISTENERS AUTOMATIQUES pour tous les champs qui impactent les calculs
    public function updatedPrixUnitaireMga() 
    { 
        $this->calculateFinancials(); 
    }
    
    public function updatedPoidsArriveeKg() 
    { 
        $this->calculateFinancials(); 
    }
    
    public function updatedPaiementMga() 
    { 
        $this->calculateFinancials(); 
    }

    // ✅ NOUVEAU : Calcul aussi quand on change les sacs (avec les bons noms de méthodes Livewire)
    public function updatedSacsPleinsArrivee() 
    { 
        $this->calculateFinancials(); 
    }
    
    public function updatedSacsDemiArrivee() 
    { 
        $this->calculateFinancials(); 
    }

    // MODAL MANAGEMENT
    public function closeChargementModal()
    {
        $this->showChargementModal = false;
        $this->resetChargementForm();
        $this->editingChargement = null;
    }

    public function closeDechargementModal()
    {
        $this->showDechargementModal = false;
        $this->resetDechargementForm();
        $this->editingDechargement = null;
    }

    public function closePreviewModal()
    {
        $this->showPreviewModal = false;
    }

    // FORM RESET
    private function resetChargementForm()
    {
        $this->chargement_reference = '';
        $this->chargeur_nom = '';
        $this->chargeur_contact = '';
        $this->proprietaire_nom = 'Mme TINAH';
        $this->proprietaire_contact = '0349045769';
        $this->produit_id = '';
        $this->sacs_pleins_depart = '';
        $this->sacs_demi_depart = 0;
        $this->poids_depart_kg = '';
        $this->chargement_observation = '';
        $this->resetErrorBag();
    }

    private function resetDechargementForm()
    {
        $this->dechargement_reference = '';
        $this->chargement_id = '';
        $this->type_dechargement = 'vente';
        $this->interlocuteur_nom = '';
        $this->interlocuteur_contact = '';
        $this->pointeur_nom = '';
        $this->pointeur_contact = '';
        $this->lieu_livraison_id = '';
        $this->sacs_pleins_arrivee = '';
        $this->sacs_demi_arrivee = 0;
        $this->poids_arrivee_kg = '';
        $this->prix_unitaire_mga = '';
        $this->montant_total_mga = '';
        $this->paiement_mga = '';
        $this->reste_mga = '';
        $this->statut_commercial = 'en_attente';
        $this->dechargement_observation = '';
        $this->resetErrorBag();
    }

    // GENERATORS
    private function generateChargementReference()
    {
        $count = $this->voyage->chargements()->count() + 1;
        return 'CH' . str_pad($count, 3, '0', STR_PAD_LEFT);
    }

    private function generateDechargementReference()
    {
        $count = $this->voyage->dechargements()->count() + 1;
        return 'OP' . str_pad($count, 3, '0', STR_PAD_LEFT);
    }

    public function render()
    {
        $voyage = $this->voyage->load([
            'origine', 
            'vehicule', 
            'chargements.produit',
            'dechargements.chargement.produit',
            'dechargements.lieuLivraison'
        ]);

        $produits = Produit::where('actif', true)->get();
        $destinations = Lieu::whereIn('type', ['destination', 'depot'])->where('actif', true)->get();

        $totalPoidsCharge = $voyage->chargements->sum('poids_depart_kg');
        $totalPoidsDecharge = $voyage->dechargements->sum('poids_arrivee_kg');
        $ecartPoids = $totalPoidsCharge - $totalPoidsDecharge;
        $totalVentes = $voyage->dechargements->where('type', 'vente')->sum('montant_total_mga');
        $totalPaiements = $voyage->dechargements->where('type', 'vente')->sum('paiement_mga');
        $totalReste = $voyage->dechargements->where('type', 'vente')->sum('reste_mga');

        return view('livewire.voyage.voyage-show', compact(
            'voyage', 
            'produits', 
            'destinations',
            'totalPoidsCharge',
            'totalPoidsDecharge', 
            'ecartPoids',
            'totalVentes',
            'totalPaiements',
            'totalReste'
        ));
    }
}
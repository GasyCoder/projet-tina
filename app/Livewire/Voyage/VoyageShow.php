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
    public $chargement_reference = '';
    public $chargeur_nom = '';
    public $chargeur_contact = '';
    
    // VALEURS PAR DÉFAUT CONSTANTES POUR LE PROPRIÉTAIRE
    public $proprietaire_nom = 'Mme TINAH';
    public $proprietaire_contact = '0349045769';
    
    public $produit_id = '';
    public $sacs_pleins_depart = '';
    public $sacs_demi_depart = 0;
    public $poids_depart_kg = '';
    public $chargement_observation = '';

    // Déchargement form - LOGIQUE CORRIGÉE
    public $showDechargementModal = false;
    public $editingDechargement = null;
    public $dechargement_reference = '';
    public $chargement_id = ''; 
    public $type_dechargement = 'vente';
    public $interlocuteur_nom = '';
    public $interlocuteur_contact = '';
    public $pointeur_nom = '';
    public $pointeur_contact = '';
    public $lieu_livraison_id = '';
    // SEULEMENT quantités arrivée (pas de redondance)
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
        // Chargement rules
        'chargement_reference' => 'required|string|max:255',
        'chargeur_nom' => 'required|string|max:255',
        'chargeur_contact' => 'nullable|string|max:255',
        'proprietaire_nom' => 'required|string|max:255',
        'proprietaire_contact' => 'nullable|string|max:255',
        'produit_id' => 'required|exists:produits,id',
        'sacs_pleins_depart' => 'required|integer|min:0',
        'sacs_demi_depart' => 'integer|min:0',
        'poids_depart_kg' => 'required|numeric|min:0',
        'chargement_observation' => 'nullable|string',

        // Déchargement rules - LOGIQUE CORRIGÉE
        'dechargement_reference' => 'required|string|max:255',
        'chargement_id' => 'required|exists:chargements,id',
        'type_dechargement' => 'required|in:vente,retour,depot,transfert',
        'interlocuteur_nom' => 'nullable|string|max:255',
        'interlocuteur_contact' => 'nullable|string|max:255',
        'pointeur_nom' => 'nullable|string|max:255',
        'pointeur_contact' => 'nullable|string|max:255',
        'lieu_livraison_id' => 'nullable|exists:lieux,id',
        'sacs_pleins_arrivee' => 'nullable|integer|min:0',
        'sacs_demi_arrivee' => 'integer|min:0',
        'poids_arrivee_kg' => 'nullable|numeric|min:0',
        'prix_unitaire_mga' => 'nullable|numeric|min:0',
        'montant_total_mga' => 'nullable|numeric|min:0',
        'paiement_mga' => 'nullable|numeric|min:0',
        'reste_mga' => 'nullable|numeric',
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

    // CHARGEMENTS
    public function createChargement()
    {
        $this->resetChargementForm();
        $this->editingChargement = null;
        $this->chargement_reference = $this->generateChargementReference();
        $this->showChargementModal = true;
    }

    public function editChargement(Chargement $chargement)
    {
        $this->editingChargement = $chargement;
        $this->chargement_reference = $chargement->reference;
        $this->chargeur_nom = $chargement->chargeur_nom;
        $this->chargeur_contact = $chargement->chargeur_contact;
        $this->proprietaire_nom = $chargement->proprietaire_nom;
        $this->proprietaire_contact = $chargement->proprietaire_contact;
        $this->produit_id = $chargement->produit_id;
        $this->sacs_pleins_depart = $chargement->sacs_pleins_depart;
        $this->sacs_demi_depart = $chargement->sacs_demi_depart;
        $this->poids_depart_kg = $chargement->poids_depart_kg;
        $this->chargement_observation = $chargement->observation;
        $this->showChargementModal = true;
    }

    public function saveChargement()
    {
        $this->validate([
            'chargement_reference' => 'required|string|max:255',
            'chargeur_nom' => 'required|string|max:255',
            'chargeur_contact' => 'nullable|string|max:255',
            'proprietaire_nom' => 'required|string|max:255',
            'proprietaire_contact' => 'nullable|string|max:255',
            'produit_id' => 'required|exists:produits,id',
            'sacs_pleins_depart' => 'required|integer|min:0',
            'sacs_demi_depart' => 'integer|min:0',
            'poids_depart_kg' => 'required|numeric|min:0',
            'chargement_observation' => 'nullable|string',
        ]);

        if ($this->editingChargement) {
            $this->editingChargement->update([
                'reference' => $this->chargement_reference,
                'chargeur_nom' => $this->chargeur_nom,
                'chargeur_contact' => $this->chargeur_contact,
                'proprietaire_nom' => $this->proprietaire_nom,
                'proprietaire_contact' => $this->proprietaire_contact,
                'produit_id' => $this->produit_id,
                'sacs_pleins_depart' => $this->sacs_pleins_depart,
                'sacs_demi_depart' => $this->sacs_demi_depart,
                'poids_depart_kg' => $this->poids_depart_kg,
                'observation' => $this->chargement_observation,
            ]);
            session()->flash('success', 'Chargement modifié avec succès');
        } else {
            Chargement::create([
                'voyage_id' => $this->voyage->id,
                'reference' => $this->chargement_reference,
                'chargeur_nom' => $this->chargeur_nom,
                'chargeur_contact' => $this->chargeur_contact,
                'proprietaire_nom' => $this->proprietaire_nom,
                'proprietaire_contact' => $this->proprietaire_contact,
                'produit_id' => $this->produit_id,
                'sacs_pleins_depart' => $this->sacs_pleins_depart,
                'sacs_demi_depart' => $this->sacs_demi_depart,
                'poids_depart_kg' => $this->poids_depart_kg,
                'observation' => $this->chargement_observation,
            ]);
            session()->flash('success', 'Chargement ajouté avec succès');
        }

        $this->closeChargementModal();
        $this->voyage->refresh();
    }

    public function deleteChargement(Chargement $chargement)
    {
        $chargement->delete();
        session()->flash('success', 'Chargement supprimé avec succès');
        $this->voyage->refresh();
    }

    // DÉCHARGEMENTS - LOGIQUE CORRIGÉE
    public function createDechargement()
    {
        $this->resetDechargementForm();
        $this->editingDechargement = null;
        $this->dechargement_reference = $this->generateDechargementReference();
        $this->showDechargementModal = true;

        // Si un chargement_id est déjà sélectionné, vérifier s'il a un déchargement
        if ($this->chargement_id) {
            $existingDechargement = Dechargement::where('chargement_id', $this->chargement_id)->first();
            if ($existingDechargement) {
                // Si un déchargement existe, passer en mode édition
                $this->editDechargement($existingDechargement);
                session()->flash('info', 'Un déchargement existe déjà pour ce chargement. Vous pouvez modifier les informations.');
            }
        }
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

    public function calculateFinancials()
    {
        if ($this->prix_unitaire_mga && $this->poids_arrivee_kg) {
            $this->montant_total_mga = $this->prix_unitaire_mga * $this->poids_arrivee_kg;
        } else {
            $this->montant_total_mga = null;
        }

        if ($this->montant_total_mga && $this->paiement_mga) {
            $this->reste_mga = $this->montant_total_mga - $this->paiement_mga;
        } else {
            $this->reste_mga = null;
        }
    }

    public function saveDechargement()
    {
        $this->validate();

        // Vérifier si un déchargement existe déjà pour ce chargement_id (sauf en mode édition)
        if (!$this->editingDechargement) {
            $existingDechargement = Dechargement::where('chargement_id', $this->chargement_id)->first();
            if ($existingDechargement) {
                session()->flash('error', 'Un déchargement existe déjà pour ce chargement. Veuillez modifier le déchargement existant.');
                $this->editDechargement($existingDechargement);
                return;
            }
        }

        // Calculs automatiques
        $this->calculateFinancials();

        if ($this->editingDechargement) {
            $this->editingDechargement->update([
                'reference' => $this->dechargement_reference,
                'chargement_id' => $this->chargement_id,
                'type' => $this->type_dechargement,
                'interlocuteur_nom' => $this->interlocuteur_nom,
                'interlocuteur_contact' => $this->interlocuteur_contact,
                'pointeur_nom' => $this->pointeur_nom,
                'pointeur_contact' => $this->pointeur_contact,
                'lieu_livraison_id' => $this->lieu_livraison_id ?: null,
                'sacs_pleins_arrivee' => $this->sacs_pleins_arrivee ?: null,
                'sacs_demi_arrivee' => $this->sacs_demi_arrivee,
                'poids_arrivee_kg' => $this->poids_arrivee_kg ?: null,
                'prix_unitaire_mga' => $this->prix_unitaire_mga ?: null,
                'montant_total_mga' => $this->montant_total_mga ?: null,
                'paiement_mga' => $this->paiement_mga ?: null,
                'reste_mga' => $this->reste_mga ?: null,
                'statut_commercial' => $this->statut_commercial,
                'observation' => $this->dechargement_observation,
            ]);
            session()->flash('success', 'Déchargement modifié avec succès');
        } else {
            Dechargement::create([
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
                'sacs_demi_arrivee' => $this->sacs_demi_arrivee,
                'poids_arrivee_kg' => $this->poids_arrivee_kg ?: null,
                'prix_unitaire_mga' => $this->prix_unitaire_mga ?: null,
                'montant_total_mga' => $this->montant_total_mga ?: null,
                'paiement_mga' => $this->paiement_mga ?: null,
                'reste_mga' => $this->reste_mga ?: null,
                'statut_commercial' => $this->statut_commercial,
                'observation' => $this->dechargement_observation,
            ]);
            session()->flash('success', 'Déchargement ajouté avec succès');
        }

        $this->closeDechargementModal();
        $this->voyage->refresh();
    }

    public function updatedChargementId($value)
    {
        if ($value && !$this->editingDechargement) {
            $existingDechargement = Dechargement::where('chargement_id', $value)->first();
            if ($existingDechargement) {
                $this->editDechargement($existingDechargement);
                session()->flash('info', 'Un déchargement existe déjà pour ce chargement. Vous pouvez modifier les informations.');
            }
        }
    }

    public function deleteDechargement(Dechargement $dechargement)
    {
        $dechargement->delete();
        session()->flash('success', 'Déchargement supprimé avec succès');
        $this->voyage->refresh();
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

    // MÉTHODE CORRIGÉE : Garder les valeurs par défaut du propriétaire
    private function resetChargementForm()
    {
        $this->chargement_reference = '';
        $this->chargeur_nom = '';
        $this->chargeur_contact = '';
        // GARDER LES VALEURS PAR DÉFAUT DU PROPRIÉTAIRE
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

    // CALCULS AUTOMATIQUES
    public function updatedPrixUnitaireMga()
    {
        $this->calculateFinancials();
    }

    public function updatedPoidsArriveeKg()
    {
        $this->calculateFinancials();
    }

    public function updatedMontantTotalMga()
    {
        $this->calculateReste();
    }

    public function updatedPaiementMga()
    {
        $this->calculateFinancials();
    }

    private function calculateReste()
    {
        if ($this->montant_total_mga && $this->paiement_mga) {
            $this->reste_mga = $this->montant_total_mga - $this->paiement_mga;
        }
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

        // Calculs de synthèse corrigés
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
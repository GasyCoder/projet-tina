<?php
// app/Livewire/Voyage/Modal/DechargementDepotModal.php

namespace App\Livewire\Voyage\Modal;

use Livewire\Component;
use App\Models\Voyage;
use App\Models\Dechargement;
use App\Models\Chargement;
use App\Models\Lieu;
use App\Models\Produit;
// 🏬 IMPORTS pour l'intégration dépôt
use App\Models\StockDepot;
use App\Models\HistoriqueStock;
use App\Models\AlerteStock;
use App\Models\TransactionFinanciere;
use App\Models\PrixMarche;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DechargementDepotModal extends Component
{
    public Voyage $voyage;
    public $showModal = false;
    public $editingDechargement = null;

    // Form fields spécifiques au dépôt
    public $dechargement_reference = '';
    public $chargement_id = '';
    public $destination_depot_id = '';
    public $pointeur_nom = '';
    public $responsable_depot = '';
    public $numero_bon_livraison = '';
    public $sacs_pleins_arrivee = '';
    public $sacs_demi_arrivee = 0;
    public $poids_arrivee_kg = '';
    public $controle_qualite = '';
    public $statut_commercial = 'stocke';
    public $notes_depot = '';
    public $observation = '';

    protected $listeners = [
        'openDepotModal' => 'openModal',
        'closeDepotModal' => 'closeModal',
    ];

    public function mount(Voyage $voyage)
    {
        $this->voyage = $voyage;
    }

    /**
     * 🔓 Ouvrir le modal
     */
    public function openModal($dechargementId = null)
    {
        if ($dechargementId) {
            $this->editingDechargement = Dechargement::findOrFail($dechargementId);
            $this->fillForm();
        } else {
            $this->resetForm();
            $this->generateReference();
        }
        
        $this->showModal = true;
        
        // Dispatcher pour ouvrir la modale globale avec le type dépôt
        $this->dispatch('show-dechargement-modal', [
            'type' => 'depot',
            'data' => $this->getFormData()
        ]);
    }

    /**
     * ❌ Fermer le modal
     */
    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
        $this->editingDechargement = null;
        
        // Dispatcher pour fermer la modale globale
        $this->dispatch('hide-dechargement-modal');
    }

    /**
     * 💾 Sauvegarder le déchargement dépôt avec intégration
     */
    public function saveDepot()
    {
        $this->validate([
            'dechargement_reference' => 'required|string|max:255',
            'chargement_id' => 'required|exists:chargements,id',
            'destination_depot_id' => 'required|exists:lieux,id',
            'poids_arrivee_kg' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        
        try {
            // Préparer les données
            $data = [
                'voyage_id' => $this->voyage->id,
                'reference' => $this->dechargement_reference,
                'chargement_id' => $this->chargement_id,
                'type' => 'depot',
                'lieu_livraison_id' => $this->destination_depot_id,
                'pointeur_nom' => $this->pointeur_nom,
                'responsable_depot' => $this->responsable_depot,
                'numero_bon_livraison' => $this->numero_bon_livraison ?: 'BL-' . time(),
                'sacs_pleins_arrivee' => $this->sacs_pleins_arrivee ?: 0,
                'sacs_demi_arrivee' => $this->sacs_demi_arrivee ?: 0,
                'poids_arrivee_kg' => $this->poids_arrivee_kg,
                'controle_qualite' => $this->controle_qualite ?: 'en_attente',
                'statut_commercial' => $this->statut_commercial,
                'notes_depot' => $this->notes_depot,
                'observation' => $this->observation,
                'date_dechargement' => now(),
                'user_id' => auth()->id(),
            ];

            // Sauvegarder le déchargement
            if ($this->editingDechargement) {
                $dechargement = tap($this->editingDechargement)->update($data);
                $action = 'modifié';
            } else {
                $dechargement = Dechargement::create($data);
                $action = 'créé';
            }

            // 🏬 INTÉGRATION AUTOMATIQUE AVEC LE DÉPÔT
            $this->integrerAvecDepot($dechargement);

            DB::commit();

            // Notification et fermeture
            session()->flash('success', "🏬 Déchargement dépôt {$action} avec succès ! Stock mis à jour automatiquement.");
            
            // Notifier le parent (VoyageShow)
            $this->dispatch('dechargement-saved');
            
            $this->closeModal();

        } catch (\Exception $e) {
            DB::rollBack();
            
            session()->flash('error', 'Erreur lors de la sauvegarde : ' . $e->getMessage());
            Log::error('Erreur sauvegarde déchargement dépôt', [
                'voyage_id' => $this->voyage->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * 🏬 MÉTHODE PRINCIPALE D'INTÉGRATION AVEC LE DÉPÔT
     */
    private function integrerAvecDepot($dechargement)
    {
        try {
            // Récupérer les informations
            $chargement = Chargement::findOrFail($this->chargement_id);
            $produit = $chargement->produit;
            $destination = Lieu::findOrFail($this->destination_depot_id);

            // 1. Créer/Mettre à jour le stock
            $stockDepot = StockDepot::updateOrCreate(
                [
                    'produit_id' => $produit->id,
                    'destination_id' => $destination->id,
                ],
                [
                    'nom_produit' => $produit->nom,
                    'categorie' => $produit->categorie ?? 'general',
                    'unite' => $produit->unite ?? 'kg',
                    'seuil_min' => $produit->seuil_min ?? 100,
                    'prix_unitaire' => $produit->prix_unitaire ?? 2500,
                    'last_updated' => now(),
                    'updated_by' => auth()->id(),
                ]
            );

            // 2. Enregistrer l'historique
            HistoriqueStock::create([
                'stock_depot_id' => $stockDepot->id,
                'produit_id' => $chargement->produit_id,
                'type_mouvement' => 'entree',
                'sous_type' => 'dechargement_voyage',
                'quantite_avant' => $stockDepot->stock_actuel ?? 0,
                'quantite_mouvement' => $this->poids_arrivee_kg,
                'quantite_apres' => ($stockDepot->stock_actuel ?? 0) + $this->poids_arrivee_kg,
                'prix_unitaire' => $stockDepot->prix_unitaire,
                'valeur_mouvement' => $this->poids_arrivee_kg * $stockDepot->prix_unitaire,
                'reference_externe' => $dechargement->reference,
                'voyage_id' => $this->voyage->id,
                'chargement_id' => $chargement->id,
                'dechargement_id' => $dechargement->id,
                'fournisseur' => $chargement->proprietaire_nom,
                'pointeur' => $this->pointeur_nom,
                'destination' => $destination->nom,
                'notes' => "Dépôt via voyage {$this->voyage->reference} - Responsable: {$this->responsable_depot}",
                'date_mouvement' => now(),
                'user_id' => auth()->id(),
                'poids_depart' => $chargement->poids_depart_kg,
                'poids_arrivee' => $this->poids_arrivee_kg,
                'ecart_transport' => $chargement->poids_depart_kg - $this->poids_arrivee_kg,
                'taux_perte' => $chargement->poids_depart_kg > 0 ? 
                    (($chargement->poids_depart_kg - $this->poids_arrivee_kg) / $chargement->poids_depart_kg) * 100 : 0,
            ]);

            // 3. Mettre à jour les quantités
            $nouveauStock = ($stockDepot->stock_actuel ?? 0) + $this->poids_arrivee_kg;
            $stockDepot->update([
                'stock_actuel' => $nouveauStock,
                'valeur_stock' => $nouveauStock * $stockDepot->prix_unitaire,
                'derniere_entree' => now(),
                'last_updated' => now(),
            ]);

            // 4. Créer transaction financière
            TransactionFinanciere::create([
                'type' => 'entree_stock',
                'reference' => "DEPOT-" . $dechargement->reference,
                'description' => "Entrée stock dépôt - {$produit->nom}",
                'montant' => $this->poids_arrivee_kg * $stockDepot->prix_unitaire,
                'devise' => 'MGA',
                'dechargement_id' => $dechargement->id,
                'stock_depot_id' => $stockDepot->id,
                'voyage_id' => $this->voyage->id,
                'quantite' => $this->poids_arrivee_kg,
                'prix_unitaire' => $stockDepot->prix_unitaire,
                'date_transaction' => now(),
                'user_id' => auth()->id(),
                'statut' => 'valide',
            ]);

            Log::info("✅ Intégration dépôt réussie", [
                'dechargement_id' => $dechargement->id,
                'stock_depot_id' => $stockDepot->id,
                'produit' => $produit->nom,
                'quantite' => $this->poids_arrivee_kg
            ]);

        } catch (\Exception $e) {
            Log::error("❌ Erreur intégration dépôt: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * 📋 Obtenir les données du formulaire
     */
    public function getFormData()
    {
        return [
            'dechargement_reference' => $this->dechargement_reference,
            'chargement_id' => $this->chargement_id,
            'destination_depot_id' => $this->destination_depot_id,
            'pointeur_nom' => $this->pointeur_nom,
            'responsable_depot' => $this->responsable_depot,
            'poids_arrivee_kg' => $this->poids_arrivee_kg,
            'statut_commercial' => $this->statut_commercial,
            'observation' => $this->observation,
        ];
    }

    /**
     * 🔄 Méthodes utilitaires
     */
    private function fillForm()
    {
        $d = $this->editingDechargement;
        $this->dechargement_reference = $d->reference;
        $this->chargement_id = $d->chargement_id;
        $this->destination_depot_id = $d->lieu_livraison_id;
        $this->pointeur_nom = $d->pointeur_nom;
        $this->responsable_depot = $d->responsable_depot ?? '';
        $this->poids_arrivee_kg = $d->poids_arrivee_kg;
        $this->statut_commercial = $d->statut_commercial;
        $this->observation = $d->observation;
    }

    private function resetForm()
    {
        $this->reset([
            'dechargement_reference', 'chargement_id', 'destination_depot_id',
            'pointeur_nom', 'responsable_depot', 'poids_arrivee_kg',
            'statut_commercial', 'observation'
        ]);
        $this->statut_commercial = 'stocke';
    }

    private function generateReference()
    {
        $count = $this->voyage->dechargements()->where('type', 'depot')->count() + 1;
        $this->dechargement_reference = 'DEPOT-' . str_pad($count, 3, '0', STR_PAD_LEFT);
    }

    public function render()
    {
        return '<div></div>'; // Composant inline - pas de vue séparée
    }
}
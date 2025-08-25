<?php

namespace App\Livewire\Voyage\Modal;

use Livewire\Component;
use App\Models\Voyage;
use App\Models\Dechargement;
use App\Models\Stock;
use Illuminate\Support\Facades\DB;

class DechargementRetourModal extends Component
{
    public Voyage $voyage;
    public $showModal = false;
    public $editingDechargement = null;

    // Form fields
    public $dechargement_reference = '';
    public $chargement_id = '';
    public $motif_retour = '';
    public $responsable_retour = '';
    public $pointeur_nom = '';
    public $poids_arrivee_kg = '';
    public $etat_marchandise = 'bon';
    public $decision_retour = 'restockage';
    public $remboursement_mga = 0;
    public $statut_commercial = 'retourne';
    public $observation = '';
    public $lieu_stockage_id = '';
    public $quantite_sacs_pleins = 0;
    public $quantite_sacs_demi = 0;

    // Pour la gestion des stocks
    public $selectedProducts = [];
    public $quantities = [];

    protected $listeners = [
        'openRetourModal' => 'openModal',
        'closeRetourModal' => 'closeModal',
    ];

    protected $rules = [
        'chargement_id' => 'required|exists:chargements,id',
        'motif_retour' => 'required|string|min:3',
        'responsable_retour' => 'required|string',
        'poids_arrivee_kg' => 'required|numeric|min:0',
        'etat_marchandise' => 'required|in:bon,deteriore,impropre',
        'decision_retour' => 'required|in:restockage,remboursement,perte',
        'lieu_stockage_id' => 'required_if:decision_retour,restockage|exists:lieux,id',
        'quantite_sacs_pleins' => 'nullable|integer|min:0',
        'quantite_sacs_demi' => 'nullable|integer|min:0',
    ];

    public function mount(Voyage $voyage)
    {
        $this->voyage = $voyage;
    }

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
        $this->dispatch('show-dechargement-modal', [
            'type' => 'retour',
            'data' => $this->getFormData()
        ]);
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
        $this->dispatch('hide-dechargement-modal');
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);

        if (in_array($propertyName, ['decision_retour', 'poids_arrivee_kg', 'quantite_sacs_pleins', 'quantite_sacs_demi'])) {
            $this->updateCalculations();
        }
    }

    protected function updateCalculations()
    {
        // Calculs spécifiques si nécessaire
    }

    public function saveRetour()
    {
        $this->validate();

        DB::beginTransaction();
        try {
            $data = [
                'voyage_id' => $this->voyage->id,
                'reference' => $this->dechargement_reference,
                'chargement_id' => $this->chargement_id,
                'type' => 'retour',
                'interlocuteur_nom' => $this->responsable_retour,
                'pointeur_nom' => $this->pointeur_nom,
                'poids_arrivee_kg' => $this->poids_arrivee_kg,
                'sacs_pleins_arrivee' => $this->quantite_sacs_pleins,
                'sacs_demi_arrivee' => $this->quantite_sacs_demi,
                'motif_retour' => $this->motif_retour,
                'etat_marchandise' => $this->etat_marchandise,
                'decision_retour' => $this->decision_retour,
                'remboursement_mga' => $this->remboursement_mga,
                'lieu_livraison_id' => $this->lieu_stockage_id,
                'statut_commercial' => $this->statut_commercial,
                'observation' => $this->observation,
                'date' => now(),
                'user_id' => auth()->id(),
            ];

            if ($this->editingDechargement) {
                $dechargement = $this->editingDechargement;
                $dechargement->update($data);
                $action = 'modifié';
            } else {
                $dechargement = Dechargement::create($data);
                $action = 'créé';
            }

            // Gestion des stocks pour les retours
            $this->handleStockManagement($dechargement);

            DB::commit();
            session()->flash('success', "↩️ Retour {$action} avec succès !");
            $this->dispatch('dechargement-saved');
            $this->closeModal();

        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Erreur : ' . $e->getMessage());
        }
    }

    protected function handleStockManagement(Dechargement $dechargement)
    {
        // Si c'est un restockage, on ajoute au stock
        if ($this->decision_retour === 'restockage' && $dechargement->chargement) {
            Stock::create([
                'voyage_id' => $this->voyage->id,
                'produit_id' => $dechargement->chargement->produit_id,
                'type_mouvement' => 'retour',
                'quantite_kg' => $this->poids_arrivee_kg,
                'quantite_sacs_pleins' => $this->quantite_sacs_pleins,
                'quantite_sacs_demi' => $this->quantite_sacs_demi,
                'lieu_stockage_id' => $this->lieu_stockage_id,
                'motif_retour' => $this->motif_retour,
                'reference' => $this->dechargement_reference,
                'date_mouvement' => now(),
                'statut' => 'en_stock'
            ]);
        }
    }

    private function fillForm()
    {
        $d = $this->editingDechargement;
        $this->dechargement_reference = $d->reference;
        $this->chargement_id = $d->chargement_id;
        $this->motif_retour = $d->motif_retour ?? '';
        $this->responsable_retour = $d->interlocuteur_nom;
        $this->pointeur_nom = $d->pointeur_nom;
        $this->poids_arrivee_kg = $d->poids_arrivee_kg;
        $this->quantite_sacs_pleins = $d->sacs_pleins_arrivee;
        $this->quantite_sacs_demi = $d->sacs_demi_arrivee;
        $this->etat_marchandise = $d->etat_marchandise ?? 'bon';
        $this->decision_retour = $d->decision_retour ?? 'restockage';
        $this->remboursement_mga = $d->remboursement_mga ?? 0;
        $this->lieu_stockage_id = $d->lieu_livraison_id;
        $this->observation = $d->observation;
    }

    private function resetForm()
    {
        $this->reset([
            'dechargement_reference',
            'chargement_id',
            'motif_retour',
            'responsable_retour',
            'pointeur_nom',
            'poids_arrivee_kg',
            'etat_marchandise',
            'decision_retour',
            'remboursement_mga',
            'lieu_stockage_id',
            'quantite_sacs_pleins',
            'quantite_sacs_demi',
            'observation',
            'selectedProducts',
            'quantities'
        ]);
        $this->statut_commercial = 'retourne';
    }

    private function generateReference()
    {
        $count = $this->voyage->dechargements()->where('type', 'retour')->count() + 1;
        $this->dechargement_reference = 'RET-' . str_pad($count, 3, '0', STR_PAD_LEFT);
    }

    public function getFormData()
    {
        return [
            'dechargement_reference' => $this->dechargement_reference,
            'chargement_id' => $this->chargement_id,
            'motif_retour' => $this->motif_retour,
            'responsable_retour' => $this->responsable_retour,
            'poids_arrivee_kg' => $this->poids_arrivee_kg,
            'etat_marchandise' => $this->etat_marchandise,
            'decision_retour' => $this->decision_retour,
            'lieu_stockage_id' => $this->lieu_stockage_id,
            'observation' => $this->observation,
        ];
    }

    public function render()
    {
        return view('livewire.voyage.modal.dechargement-retour-modal');
    }
}
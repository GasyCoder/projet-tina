<?php

namespace App\Livewire\Voyage\Modal;

use Livewire\Component;
use App\Models\Voyage;
use App\Models\Stock;

class DechargementRetourModal extends Component
{
    public $voyage;
    public $isOpen = false;
    public $selectedProducts = [];
    public $quantities = [];
    public $motifRetour;
    public $fournisseur;
    public $numeroRetour;
    
    protected $listeners = [
        'openRetourModal' => 'openModal',
        'closeRetourModal' => 'closeModal'
    ];

    protected $rules = [
        'motifRetour' => 'required|string|min:3',
        'fournisseur' => 'required|string',
        'numeroRetour' => 'required|string',
        'selectedProducts' => 'required|array|min:1',
        'quantities.*' => 'required|numeric|min:1'
    ];

    public function mount($voyage = null)
    {
        $this->voyage = $voyage;
    }

    public function openModal($voyageId = null)
    {
        if ($voyageId) {
            $this->voyage = Voyage::find($voyageId);
        }
        
        $this->resetForm();
        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->selectedProducts = [];
        $this->quantities = [];
        $this->motifRetour = '';
        $this->fournisseur = '';
        $this->numeroRetour = '';
        $this->resetValidation();
    }

    public function toggleProduct($productId)
    {
        if (in_array($productId, $this->selectedProducts)) {
            $this->selectedProducts = array_diff($this->selectedProducts, [$productId]);
            unset($this->quantities[$productId]);
        } else {
            $this->selectedProducts[] = $productId;
            $this->quantities[$productId] = 1;
        }
    }

    public function processRetour()
    {
        $this->validate();

        try {
            foreach ($this->selectedProducts as $productId) {
                Stock::create([
                    'voyage_id' => $this->voyage->id,
                    'produit_id' => $productId,
                    'type_mouvement' => 'retour',
                    'quantite' => $this->quantities[$productId],
                    'motif_retour' => $this->motifRetour,
                    'fournisseur' => $this->fournisseur,
                    'numero_retour' => $this->numeroRetour,
                    'date_mouvement' => now(),
                    'statut' => 'en_attente'
                ]);
            }

            session()->flash('success', 'Retour enregistré avec succès!');
            $this->dispatch('retour-processed');
            $this->closeModal();
            
        } catch (\Exception $e) {
            session()->flash('error', 'Erreur lors du retour: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.stocks.retour');
    }
}
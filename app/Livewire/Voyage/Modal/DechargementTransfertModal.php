<?php

namespace App\Livewire\Voyage\Modal;

use Livewire\Component;
use App\Models\Voyage;
use App\Models\Stock;

class DechargementTransfertModal extends Component
{
    public $voyage;
    public $isOpen = false;
    public $selectedProducts = [];
    public $quantities = [];
    public $destinationDepot;
    public $motif;
    
    protected $listeners = [
        'openTransfertModal' => 'openModal',
        'closeTransfertModal' => 'closeModal'
    ];

    protected $rules = [
        'destinationDepot' => 'required|string',
        'motif' => 'required|string|min:3',
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
        $this->destinationDepot = '';
        $this->motif = '';
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

    public function processTransfert()
    {
        $this->validate();

        try {
            foreach ($this->selectedProducts as $productId) {
                Stock::create([
                    'voyage_id' => $this->voyage->id,
                    'produit_id' => $productId,
                    'type_mouvement' => 'transfert',
                    'quantite' => $this->quantities[$productId],
                    'destination_depot' => $this->destinationDepot,
                    'motif' => $this->motif,
                    'date_mouvement' => now(),
                    'statut' => 'en_attente'
                ]);
            }

            session()->flash('success', 'Transfert programmé avec succès!');
            $this->dispatch('transfert-processed');
            $this->closeModal();
            
        } catch (\Exception $e) {
            session()->flash('error', 'Erreur lors du transfert: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.stocks.transfert');
    }
}
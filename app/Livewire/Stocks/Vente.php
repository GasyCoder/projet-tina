<?php

namespace App\Livewire\Stocks;

use Livewire\Component;
use Livewire\WithPagination;

class Vente extends Component
{
    use WithPagination;

    // Propriétés pour la recherche et les filtres
    public $search = '';
    public $filterStatus = '';
    
    // Propriétés pour le tri
    public $sortField = 'date';
    public $sortDirection = 'desc';
    
    // Propriétés pour les modales
    public $showModal = false;
    public $editingId = null;
    
    // Propriétés pour le formulaire
    public $form = [
        'produit' => '',
        'client' => '',
        'poids' => '',
        'prix_unitaire' => '',
        'vehicule' => '',
    ];
    
    // Statistiques (données factices)
    public $ventesJour = 12;
    public $caJournalier = 45280;
    public $commandesAttente = 3;
    public $caMensuel = 1245680;
    public $totalVentes = 25;

    // Listeners Livewire
    protected $listeners = ['$refresh'];

    // Méthodes pour réinitialiser la pagination lors des recherches
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterStatus()
    {
        $this->resetPage();
    }

    // Méthode pour le tri
    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    // Méthodes pour les modales
    public function openModal()
    {
        $this->showModal = true;
        $this->resetForm();
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->editingId = null;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->form = [
            'produit' => '',
            'client' => '',
            'poids' => '',
            'prix_unitaire' => '',
            'vehicule' => '',
        ];
    }

    // Méthode pour sauvegarder
    public function save()
    {
        $this->validate([
            'form.produit' => 'required',
            'form.client' => 'required',
            'form.poids' => 'required|numeric|min:0',
            'form.prix_unitaire' => 'required|numeric|min:0',
            'form.vehicule' => 'required',
        ], [
            'form.produit.required' => 'Le produit est obligatoire.',
            'form.client.required' => 'Le client est obligatoire.',
            'form.poids.required' => 'Le poids est obligatoire.',
            'form.poids.numeric' => 'Le poids doit être un nombre.',
            'form.prix_unitaire.required' => 'Le prix unitaire est obligatoire.',
            'form.prix_unitaire.numeric' => 'Le prix unitaire doit être un nombre.',
            'form.vehicule.required' => 'Le véhicule est obligatoire.',
        ]);

        // Ici vous pourrez sauvegarder en base de données
        // Vente::create($this->form);

        session()->flash('message', 'Vente enregistrée avec succès !');
        $this->closeModal();
        $this->dispatch('$refresh');
    }

    // Méthodes pour les actions sur les ventes
    public function edit($id)
    {
        $this->editingId = $id;
        // Charger les données de la vente
        // $vente = Vente::find($id);
        // $this->form = $vente->toArray();
        $this->showModal = true;
    }

    public function delete($id)
    {
        // Supprimer la vente
        // Vente::find($id)->delete();
        session()->flash('message', 'Vente supprimée avec succès !');
        $this->dispatch('$refresh');
    }

    public function render()
    {
        // Pour l'instant on retourne des données factices
        // Plus tard vous pourrez faire une vraie requête :
        // $ventes = Vente::query()
        //     ->when($this->search, fn($query) => $query->where('client', 'like', '%'.$this->search.'%'))
        //     ->when($this->filterStatus, fn($query) => $query->where('statut', $this->filterStatus))
        //     ->orderBy($this->sortField, $this->sortDirection)
        //     ->paginate(10);

        return view('livewire.stocks.vente', [
            'ventes' => collect([]) // Collection vide pour l'instant
        ]);
    }
}
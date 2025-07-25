<?php

namespace App\Livewire\Stocks;

use Livewire\Component;
use Livewire\WithPagination;

class Retour extends Component
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
        'vente_id' => '',
        'quantite' => '',
        'type' => '',
        'motif' => '',
    ];
    
    // Statistiques (données factices)
    public $retoursMois = 8;
    public $retoursTraitement = 3;
    public $retoursAcceptes = 4;
    public $retoursRefuses = 1;
    public $totalRetours = 8;

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
            'vente_id' => '',
            'quantite' => '',
            'type' => '',
            'motif' => '',
        ];
    }

    // Méthode pour sauvegarder
    public function save()
    {
        $this->validate([
            'form.vente_id' => 'required',
            'form.quantite' => 'required|numeric|min:0',
            'form.type' => 'required',
            'form.motif' => 'required|min:10',
        ], [
            'form.vente_id.required' => 'Veuillez sélectionner une vente.',
            'form.quantite.required' => 'La quantité est obligatoire.',
            'form.quantite.numeric' => 'La quantité doit être un nombre.',
            'form.type.required' => 'Le type de retour est obligatoire.',
            'form.motif.required' => 'Le motif est obligatoire.',
            'form.motif.min' => 'Le motif doit contenir au moins 10 caractères.',
        ]);

        // Ici vous pourrez sauvegarder en base de données
        // Retour::create(array_merge($this->form, ['statut' => 'en_traitement']));

        session()->flash('message', 'Retour enregistré avec succès !');
        $this->closeModal();
        $this->dispatch('$refresh');
    }

    // Méthodes pour les actions sur les retours
    public function accepterRetour($id)
    {
        // Mettre à jour le statut en base
        // Retour::find($id)->update(['statut' => 'accepté']);
        
        session()->flash('message', 'Retour accepté avec succès !');
        $this->dispatch('$refresh');
    }

    public function refuserRetour($id)
    {
        // Mettre à jour le statut en base
        // Retour::find($id)->update(['statut' => 'refusé']);
        
        session()->flash('message', 'Retour refusé.');
        $this->dispatch('$refresh');
    }

    public function voirDetails($id)
    {
        // Rediriger vers la page de détails ou ouvrir une modale
        // return redirect()->route('retours.show', $id);
    }

    public function render()
    {
        // Pour l'instant on retourne des données factices
        // Plus tard vous pourrez faire une vraie requête :
        // $retours = Retour::query()
        //     ->when($this->search, fn($query) => $query->whereHas('vente', fn($q) => $q->where('client', 'like', '%'.$this->search.'%')))
        //     ->when($this->filterStatus, fn($query) => $query->where('statut', $this->filterStatus))
        //     ->orderBy($this->sortField, $this->sortDirection)
        //     ->paginate(10);

        return view('livewire.stocks.retour', [
            'retours' => collect([]) // Collection vide pour l'instant
        ]);
    }
}
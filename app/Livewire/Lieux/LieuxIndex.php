<?php

namespace App\Livewire\Lieux;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Lieu;

class LieuxIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $filterType = '';
    public $sortField = 'nom';
    public $sortDirection = 'asc';
    public $showModal = false;
    public $editingLieu = null;

    // Form fields
    public $nom = '';
    public $type = 'origine';
    public $region = '';
    public $adresse = '';
    public $actif = true;

    protected $rules = [
        'nom' => 'required|string|max:255',
        'type' => 'required|in:origine,destination,depot',
        'region' => 'nullable|string|max:255',
        'adresse' => 'nullable|string',
        'actif' => 'boolean'
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatedFilterType()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }
        $this->sortField = $field;
    }

    public function create()
    {
        $this->resetForm();
        $this->editingLieu = null;
        $this->showModal = true;
    }

    public function edit(Lieu $lieu)
    {
        $this->editingLieu = $lieu;
        $this->nom = $lieu->nom;
        $this->type = $lieu->type;
        $this->region = $lieu->region;
        $this->adresse = $lieu->adresse;
        $this->actif = $lieu->actif;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        if ($this->editingLieu) {
            $this->editingLieu->update([
                'nom' => $this->nom,
                'type' => $this->type,
                'region' => $this->region,
                'adresse' => $this->adresse,
                'actif' => $this->actif,
            ]);
            session()->flash('success', 'Lieu modifié avec succès');
        } else {
            Lieu::create([
                'nom' => $this->nom,
                'type' => $this->type,
                'region' => $this->region,
                'adresse' => $this->adresse,
                'actif' => $this->actif,
            ]);
            session()->flash('success', 'Lieu créé avec succès');
        }

        $this->closeModal();
    }

    public function delete(Lieu $lieu)
    {
        $lieu->delete();
        session()->flash('success', 'Lieu supprimé avec succès');
    }

    public function toggleActif(Lieu $lieu)
    {
        $lieu->update(['actif' => !$lieu->actif]);
        session()->flash('success', 'Statut mis à jour');
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
        $this->editingLieu = null;
    }

    private function resetForm()
    {
        $this->nom = '';
        $this->type = 'origine';
        $this->region = '';
        $this->adresse = '';
        $this->actif = true;
        $this->resetErrorBag();
    }

    public function render()
    {
        $lieux = Lieu::query()
            ->when($this->search, function ($query) {
                $query->where('nom', 'like', '%' . $this->search . '%')
                      ->orWhere('region', 'like', '%' . $this->search . '%');
            })
            ->when($this->filterType, function ($query) {
                $query->where('type', $this->filterType);
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

        $stats = [
            'total' => Lieu::count(),
            'origines' => Lieu::where('type', 'origine')->count(),
            'destinations' => Lieu::where('type', 'destination')->count(),
            'depots' => Lieu::where('type', 'depot')->count(),
        ];

        return view('livewire.lieux.lieux-index', compact('lieux', 'stats'));
    }
}


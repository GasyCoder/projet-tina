<?php

namespace App\Livewire\Vehicules;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Vehicule;

class VehiculeIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $filterType = '';
    public $filterStatut = '';
    public $sortField = 'immatriculation';
    public $sortDirection = 'asc';
    public $showModal = false;
    public $editingVehicule = null;

    // Form fields
    public $immatriculation = '';
    public $type = 'camion';
    public $marque = '';
    public $modele = '';
    public $chauffeur = '';
    public $capacite_max_kg = '';
    public $statut = 'actif';

    protected $rules = [
        'immatriculation' => 'required|string|max:255|unique:vehicules,immatriculation',
        'type' => 'required|in:camion,semi-remorque,pick-up,tracteur,autre',
        'marque' => 'nullable|string|max:255',
        'modele' => 'nullable|string|max:255',
        'chauffeur' => 'nullable|string|max:255',
        'capacite_max_kg' => 'nullable|integer|min:0',
        'statut' => 'required|in:actif,maintenance,inactif'
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatedFilterType()
    {
        $this->resetPage();
    }

    public function updatedFilterStatut()
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
        $this->editingVehicule = null;
        $this->showModal = true;
    }

    public function edit(Vehicule $vehicule)
    {
        $this->editingVehicule = $vehicule;
        $this->immatriculation = $vehicule->immatriculation;
        $this->type = $vehicule->type;
        $this->marque = $vehicule->marque;
        $this->chauffeur = $vehicule->chauffeur;
        $this->modele = $vehicule->modele;
        $this->capacite_max_kg = $vehicule->capacite_max_kg;
        $this->statut = $vehicule->statut;
        $this->showModal = true;
    }

    public function save()
    {
        if ($this->editingVehicule) {
            $this->rules['immatriculation'] = 'required|string|max:255|unique:vehicules,immatriculation,' . $this->editingVehicule->id;
        }

        $this->validate();

        if ($this->editingVehicule) {
            $this->editingVehicule->update([
                'immatriculation' => $this->immatriculation,
                'type' => $this->type,
                'marque' => $this->marque,
                'chauffeur' => $this->chauffeur,
                'modele' => $this->modele,
                'capacite_max_kg' => $this->capacite_max_kg,
                'statut' => $this->statut,
            ]);
            session()->flash('success', 'Véhicule modifié avec succès');
        } else {
            Vehicule::create([
                'immatriculation' => $this->immatriculation,
                'type' => $this->type,
                'marque' => $this->marque,
                'chauffeur' => $this->chauffeur,
                'modele' => $this->modele,
                'capacite_max_kg' => $this->capacite_max_kg,
                'statut' => $this->statut,
            ]);
            session()->flash('success', 'Véhicule créé avec succès');
        }

        $this->closeModal();
    }

    public function delete(Vehicule $vehicule)
    {
        $vehicule->delete();
        session()->flash('success', 'Véhicule supprimé avec succès');
    }

    public function changeStatut(Vehicule $vehicule, $nouveauStatut)
    {
        $vehicule->update(['statut' => $nouveauStatut]);
        session()->flash('success', 'Statut mis à jour');
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
        $this->editingVehicule = null;
    }

    private function resetForm()
    {
        $this->immatriculation = '';
        $this->type = 'camion';
        $this->marque = '';
        $this->chauffeur = '';
        $this->modele = '';
        $this->capacite_max_kg = '';
        $this->statut = 'actif';
        $this->resetErrorBag();
    }

    public function render()
    {
        $vehicules = Vehicule::query()
            ->when($this->search, function ($query) {
                $query->where('immatriculation', 'like', '%' . $this->search . '%')
                      ->orWhere('marque', 'like', '%' . $this->search . '%')
                      ->orWhere('chauffeur', 'like', '%' . $this->search . '%')
                      ->orWhere('modele', 'like', '%' . $this->search . '%');
            })
            ->when($this->filterType, function ($query) {
                $query->where('type', $this->filterType);
            })
            ->when($this->filterStatut, function ($query) {
                $query->where('statut', $this->filterStatut);
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

        $stats = [
            'total' => Vehicule::count(),
            'actifs' => Vehicule::where('statut', 'actif')->count(),
            'maintenance' => Vehicule::where('statut', 'maintenance')->count(),
            'inactifs' => Vehicule::where('statut', 'inactif')->count(),
            'capacite_totale' => Vehicule::where('statut', 'actif')->sum('capacite_max_kg'),
        ];

        return view('livewire.vehicules.vehicule-index', compact('vehicules', 'stats'));
    }
}
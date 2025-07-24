<?php
namespace App\Livewire\Voyage;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Voyage;
use App\Models\Lieu;
use App\Models\Vehicule;
use App\Models\User;

class VoyageIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $filterStatut = '';
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    public $showModal = false;
    public $editingVoyage = null;

    // Form fields
    public $reference = '';
    public $date = '';
    public $origine_id = '';
    public $vehicule_id = '';
    public $chauffeur_id = '';
    public $statut = 'en_cours';
    public $observation = '';

    protected $rules = [
        'reference' => 'required|string|max:255|unique:voyages,reference',
        'date' => 'required|date',
        'origine_id' => 'required|exists:lieux,id',
        'vehicule_id' => 'required|exists:vehicules,id',
        'chauffeur_id' => 'nullable|exists:users,id',
        'statut' => 'required|in:en_cours,termine,annule',
        'observation' => 'nullable|string'
    ];

    public function mount()
    {
        $this->date = now()->format('Y-m-d');
    }

    public function updatingSearch()
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
        $this->editingVoyage = null;
        $this->reference = $this->generateReference();
        $this->showModal = true;
    }

    public function edit(Voyage $voyage)
    {
        $this->editingVoyage = $voyage;
        $this->reference = $voyage->reference;
        $this->date = $voyage->date->format('Y-m-d');
        $this->origine_id = $voyage->origine_id;
        $this->vehicule_id = $voyage->vehicule_id;
        $this->chauffeur_id = $voyage->chauffeur_id;
        $this->statut = $voyage->statut;
        $this->observation = $voyage->observation;
        $this->showModal = true;
    }

    public function save()
    {
        if ($this->editingVoyage) {
            $this->rules['reference'] = 'required|string|max:255|unique:voyages,reference,' . $this->editingVoyage->id;
        }

        $this->validate();

        if ($this->editingVoyage) {
            $this->editingVoyage->update([
                'reference' => $this->reference,
                'date' => $this->date,
                'origine_id' => $this->origine_id,
                'vehicule_id' => $this->vehicule_id,
                'chauffeur_id' => $this->chauffeur_id ?: null,
                'statut' => $this->statut,
                'observation' => $this->observation,
            ]);
            session()->flash('success', 'Voyage modifié avec succès');
        } else {
            Voyage::create([
                'reference' => $this->reference,
                'date' => $this->date,
                'origine_id' => $this->origine_id,
                'vehicule_id' => $this->vehicule_id,
                'chauffeur_id' => $this->chauffeur_id ?: null,
                'statut' => $this->statut,
                'observation' => $this->observation,
            ]);
            session()->flash('success', 'Voyage créé avec succès');
        }

        $this->closeModal();
    }

    public function delete(Voyage $voyage)
    {
        $voyage->delete();
        session()->flash('success', 'Voyage supprimé avec succès');
    }

    public function changeStatut(Voyage $voyage, $nouveauStatut)
    {
        $voyage->update(['statut' => $nouveauStatut]);
        session()->flash('success', 'Statut mis à jour');
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
        $this->editingVoyage = null;
    }

    private function resetForm()
    {
        $this->reference = '';
        $this->date = now()->format('Y-m-d');
        $this->origine_id = '';
        $this->vehicule_id = '';
        $this->chauffeur_id = '';
        $this->statut = 'en_cours';
        $this->observation = '';
        $this->resetErrorBag();
    }

    private function generateReference()
    {
        $year = date('y');
        $count = Voyage::whereYear('created_at', date('Y'))->count() + 1;
        return 'V' . str_pad($count, 3, '0', STR_PAD_LEFT) . '/' . $year;
    }

    public function render()
    {
        $voyages = Voyage::query()
            ->with(['origine', 'vehicule', 'chauffeur', 'chargements', 'dechargements'])
            ->when($this->search, function ($query) {
                $query->where('reference', 'like', '%' . $this->search . '%')
                      ->orWhereHas('origine', function ($q) {
                          $q->where('nom', 'like', '%' . $this->search . '%');
                      })
                      ->orWhereHas('vehicule', function ($q) {
                          $q->where('immatriculation', 'like', '%' . $this->search . '%');
                      })
                      ->orWhereHas('chauffeur', function ($q) {
                          $q->where('name', 'like', '%' . $this->search . '%');
                      });
            })
            ->when($this->filterStatut, function ($query) {
                $query->where('statut', $this->filterStatut);
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

        $stats = [
            'total' => Voyage::count(),
            'en_cours' => Voyage::where('statut', 'en_cours')->count(),
            'termine' => Voyage::where('statut', 'termine')->count(),
            'annule' => Voyage::where('statut', 'annule')->count(),
            'aujourd_hui' => Voyage::whereDate('date', today())->count(),
            'cette_semaine' => Voyage::whereBetween('date', [now()->startOfWeek(), now()->endOfWeek()])->count(),
        ];

        // ✅ OPTIMISÉ : Utilisation des scopes du modèle Lieu
        $origines = Lieu::where('actif', true)->get();
        
        // ✅ OPTIMISÉ : Autres requêtes aussi
        $vehicules = Vehicule::where('statut', 'actif')->get();
        $chauffeurs = User::where('type', 'chauffeur')->where('actif', true)->get();

        return view('livewire.voyage.voyage-index', compact('voyages', 'stats', 'origines', 'vehicules', 'chauffeurs'));
    }
}
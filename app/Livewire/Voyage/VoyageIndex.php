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

    // Propriétés de recherche et filtres
    public $search = '';
    public $filterStatut = '';
    public $filterDepart= '';
    public $filterVehicule = '';
    public $filterDateDebut = '';
    public $filterDateFin = '';
    
    // Propriétés de tri
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    
    // Propriétés de modal
    public $showModal = false;
    public $editingVoyage = null;

    // Propriétés de formulaire - AVEC LES NOUVEAUX CHAMPS CHAUFFEUR
    public $reference = '';
    public $date = '';
    public $vehicule_id = '';
    public $chauffeur_nom = '';
    public $chauffeur_phone = '';
    public $statut = 'en_cours';
    public $observation = '';

    // RÈGLES DE VALIDATION MISES À JOUR
    protected $rules = [
        'reference' => 'required|string|max:255|unique:voyages,reference',
        'date' => 'required|date',
        'vehicule_id' => 'required|exists:vehicules,id',
        'chauffeur_nom' => 'required|string|max:255',
        'chauffeur_phone' => 'nullable|string|max:20',
        'statut' => 'required|in:en_cours,termine,annule',
        'observation' => 'nullable|string|max:1000'
    ];

    // MESSAGES DE VALIDATION EN FRANÇAIS
    protected $messages = [
        'reference.required' => 'La référence est obligatoire.',
        'reference.unique' => 'Cette référence existe déjà.',
        'date.required' => 'La date est obligatoire.',
        'vehicule_id.required' => 'Le véhicule est obligatoire.',
        'chauffeur_nom.required' => 'Le nom du chauffeur est obligatoire.',
        'chauffeur_nom.max' => 'Le nom du chauffeur ne peut pas dépasser 255 caractères.',
        'chauffeur_phone.max' => 'Le numéro de téléphone ne peut pas dépasser 20 caractères.',
        'statut.required' => 'Le statut est obligatoire.',
    ];

    public function mount()
    {
        $this->date = now()->format('Y-m-d');
    }

    // MÉTHODES DE RÉINITIALISATION ET PAGINATION
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatedFilterStatut()
    {
        $this->resetPage();
    }

    public function updatedFilterDepart()
    {
        $this->resetPage();
    }

    public function updatedFilterVehicule()
    {
        $this->resetPage();
    }

    public function updatedFilterDateDebut()
    {
        $this->resetPage();
    }

    public function updatedFilterDateFin()
    {
        $this->resetPage();
    }

    // MÉTHODE DE TRI
    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }
        $this->sortField = $field;
    }

    // MÉTHODES DE GESTION DES FILTRES
    public function clearFilters()
    {
        $this->search = '';
        $this->filterStatut = '';
        $this->filterVehicule = '';
        $this->filterDateDebut = '';
        $this->filterDateFin = '';
        $this->resetPage();
    }

    // NOUVELLE MÉTHODE POUR GÉRER LE STATUT (radio buttons)
    public function setStatut($nouveauStatut)
    {
        $statutsAutorises = ['en_cours', 'termine', 'annule'];
        
        if (in_array($nouveauStatut, $statutsAutorises)) {
            $this->statut = $nouveauStatut;
        }
    }

    // MÉTHODES CRUD - MISES À JOUR AVEC LES CHAMPS CHAUFFEUR
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
        $this->vehicule_id = $voyage->vehicule_id;
        $this->chauffeur_nom = $voyage->chauffeur_nom;
        $this->chauffeur_phone = $voyage->chauffeur_phone;
        $this->statut = $voyage->statut;
        $this->observation = $voyage->observation;
        $this->showModal = true;
    }

    public function save()
    {
        // Ajustement des règles de validation pour l'édition
        if ($this->editingVoyage) {
            $this->rules['reference'] = 'required|string|max:255|unique:voyages,reference,' . $this->editingVoyage->id;
        }

        $this->validate();

        // DONNÉES MISES À JOUR AVEC LES CHAMPS CHAUFFEUR
        $data = [
            'reference' => $this->reference,
            'date' => $this->date,
            'vehicule_id' => $this->vehicule_id,
            'chauffeur_nom' => $this->chauffeur_nom,
            'chauffeur_phone' => $this->chauffeur_phone,
            'statut' => $this->statut,
            'observation' => $this->observation,
        ];

        if ($this->editingVoyage) {
            $this->editingVoyage->update($data);
            session()->flash('success', 'Voyage modifié avec succès');
        } else {
            Voyage::create($data);
            session()->flash('success', 'Voyage créé avec succès');
        }

        $this->closeModal();
    }

    public function delete(Voyage $voyage)
    {
        // Vérifier s'il y a des chargements ou déchargements
        if ($voyage->chargements()->exists() || $voyage->dechargements()->exists()) {
            session()->flash('error', 'Impossible de supprimer ce voyage car il contient des chargements ou déchargements.');
            return;
        }

        $voyage->delete();
        session()->flash('success', 'Voyage supprimé avec succès');
    }

    public function changeStatut(Voyage $voyage, $nouveauStatut)
    {
        $ancienStatut = $voyage->statut;
        
        $voyage->update(['statut' => $nouveauStatut]);
        
        $statutLabels = [
            'en_cours' => 'En cours',
            'termine' => 'Terminé',
            'annule' => 'Annulé'
        ];
        
        session()->flash('success', 
            'Statut changé de "' . $statutLabels[$ancienStatut] . '" vers "' . $statutLabels[$nouveauStatut] . '"'
        );
    }

    public function duplicate(Voyage $voyage)
    {
        $this->resetForm();
        $this->editingVoyage = null;
        $this->reference = $this->generateReference();
        $this->date = now()->format('Y-m-d'); 
        $this->vehicule_id = $voyage->vehicule_id;
        $this->chauffeur_nom = $voyage->chauffeur_nom;
        $this->chauffeur_phone = $voyage->chauffeur_phone;
        $this->statut = 'en_cours'; 
        $this->observation = $voyage->observation;
        $this->showModal = true;
        
        session()->flash('info', 'Voyage dupliqué. Modifiez les informations si nécessaire.');
    }

    // MÉTHODES DE MODAL
    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
        $this->editingVoyage = null;
    }

    // MÉTHODES PRIVÉES - MISES À JOUR AVEC LES CHAMPS CHAUFFEUR
    private function resetForm()
    {
        $this->reference = '';
        $this->date = now()->format('Y-m-d');
        $this->vehicule_id = '';
        $this->chauffeur_nom = '';
        $this->chauffeur_phone = '';
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

    // MÉTHODES D'EXPORT/ACTIONS EN MASSE
    public function exportSelected($voyageIds)
    {
        // Logique d'export (CSV, PDF, etc.)
        session()->flash('success', count($voyageIds) . ' voyages exportés avec succès');
    }

    public function deleteSelected($voyageIds)
    {
        $count = 0;
        foreach ($voyageIds as $id) {
            $voyage = Voyage::find($id);
            if ($voyage && !$voyage->chargements()->exists() && !$voyage->dechargements()->exists()) {
                $voyage->delete();
                $count++;
            }
        }
        
        session()->flash('success', $count . ' voyages supprimés avec succès');
    }

    public function render()
    {
        // REQUÊTE PRINCIPALE OPTIMISÉE - AVEC RECHERCHE SUR LES CHAMPS CHAUFFEUR
        $voyages = Voyage::query()
            ->with(['vehicule', 'chargements', 'dechargements'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('reference', 'like', '%' . $this->search . '%')
                      ->orWhere('observation', 'like', '%' . $this->search . '%')
                      ->orWhere('chauffeur_nom', 'like', '%' . $this->search . '%')
                      ->orWhere('chauffeur_phone', 'like', '%' . $this->search . '%')
                      ->orWhereHas('vehicule', function ($vehicule) {
                          $vehicule->where('immatriculation', 'like', '%' . $this->search . '%');
                      });
                });
            })
            ->when($this->filterStatut, function ($query) {
                $query->where('statut', $this->filterStatut);
            })
            ->when($this->filterVehicule, function ($query) {
                $query->where('vehicule_id', $this->filterVehicule);
            })
            ->when($this->filterDateDebut, function ($query) {
                $query->whereDate('date', '>=', $this->filterDateDebut);
            })
            ->when($this->filterDateFin, function ($query) {
                $query->whereDate('date', '<=', $this->filterDateFin);
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(15);

        // STATISTIQUES AMÉLIORÉES
        $stats = [
            'total' => Voyage::count(),
            'en_cours' => Voyage::where('statut', 'en_cours')->count(),
            'termine' => Voyage::where('statut', 'termine')->count(),
            'annule' => Voyage::where('statut', 'annule')->count(),
            'aujourd_hui' => Voyage::whereDate('date', today())->count(),
            'cette_semaine' => Voyage::whereBetween('date', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'ce_mois' => Voyage::whereMonth('date', now()->month)
                               ->whereYear('date', now()->year)->count(),
            'poids_total_charge' => Voyage::with('chargements')
                                         ->get()
                                         ->sum(function ($voyage) {
                                             return $voyage->chargements->sum('poids_depart_kg');
                                         }),
            'poids_total_decharge' => Voyage::with('dechargements')
                                           ->get()
                                           ->sum(function ($voyage) {
                                               return $voyage->dechargements->sum('poids_arrivee_kg');
                                           }),
        ];

        // DONNÉES POUR LES SELECTS
        $vehicules = Vehicule::where('statut', 'actif')
                            ->orderBy('immatriculation')
                            ->get();

        // OPTIONS POUR LES FILTRES
        $statutOptions = [
            'en_cours' => 'En cours',
            'termine' => 'Terminé',
            'annule' => 'Annulé'
        ];

        return view('livewire.voyage.voyage-index', compact(
            'voyages', 
            'stats', 
            'vehicules',
            'statutOptions'
        ));
    }
}
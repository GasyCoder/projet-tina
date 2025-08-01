<?php

namespace App\Livewire\Voyage;

use App\Models\Lieu;
use App\Models\User;
use App\Models\Voyage;
use Livewire\Component;
use App\Models\Vehicule;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule; // AJOUT IMPORTANT
use Illuminate\Database\UniqueConstraintViolationException;

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

    // Propriétés de formulaire
    public $reference = '';
    public $date = '';
    public $vehicule_id = '';
    public $chauffeur_nom = '';
    public $chauffeur_phone = '';
    public $statut = 'en_cours';
    public $observation = '';

    // MESSAGES DE VALIDATION EN FRANÇAIS
    protected $messages = [
        'reference.required' => 'La référence est obligatoire.',
        'reference.unique' => 'Cette référence existe déjà parmi les voyages actifs.',
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

    // MÉTHODE POUR OBTENIR LES RÈGLES DE VALIDATION DYNAMIQUES
    public function getRules()
    {
        $rules = [
            'reference' => ['required', 'string', 'max:255'],
            'date' => 'required|date',
            'vehicule_id' => 'required|exists:vehicules,id',
            'chauffeur_nom' => 'required|string|max:255',
            'chauffeur_phone' => 'nullable|string|max:20',
            'statut' => 'required|in:en_cours,termine,annule',
            'observation' => 'nullable|string|max:1000'
        ];

        // RÈGLE D'UNICITÉ ADAPTÉE AVEC SOFTDELETES
        if ($this->editingVoyage) {
            // Pour l'édition : ignore l'enregistrement actuel ET exclut les soft deleted
            $rules['reference'][] = Rule::unique('voyages', 'reference')
                ->ignore($this->editingVoyage->id)
                ->whereNull('deleted_at');
        } else {
            // Pour la création : exclut seulement les soft deleted
            $rules['reference'][] = Rule::unique('voyages', 'reference')
                ->whereNull('deleted_at');
        }

        return $rules;
    }

    // ALTERNATIVE AVEC SYNTAXE STRING (moins lisible mais plus compacte)
    public function getRulesAlternative()
    {
        if ($this->editingVoyage) {
            $uniqueRule = 'unique:voyages,reference,' . $this->editingVoyage->id . ',id,deleted_at,NULL';
        } else {
            $uniqueRule = 'unique:voyages,reference,NULL,id,deleted_at,NULL';
        }

        return [
            'reference' => 'required|string|max:255|' . $uniqueRule,
            'date' => 'required|date',
            'vehicule_id' => 'required|exists:vehicules,id',
            'chauffeur_nom' => 'required|string|max:255',
            'chauffeur_phone' => 'nullable|string|max:20',
            'statut' => 'required|in:en_cours,termine,annule',
            'observation' => 'nullable|string|max:1000'
        ];
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

    // MÉTHODE POUR GÉRER LE STATUT (radio buttons)
    public function setStatut($nouveauStatut)
    {
        $statutsAutorises = ['en_cours', 'termine', 'annule'];
        
        if (in_array($nouveauStatut, $statutsAutorises)) {
            $this->statut = $nouveauStatut;
        }
    }

    // MÉTHODES CRUD
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
    $maxRetries = 5;
    $retryCount = 0;
    $success = false;

    while (!$success && $retryCount < $maxRetries) {
        try {
            DB::beginTransaction();

            $this->validate($this->getRules(), $this->messages);

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
                // Edition normale
                $this->editingVoyage->update($data);
                session()->flash('success', 'Voyage modifié avec succès');
                $success = true;
            } else {
                // NOUVEAU : On cherche la référence, même supprimée
                $voyage = Voyage::withTrashed()
                    ->where('reference', $this->reference)
                    ->first();

                if ($voyage && $voyage->trashed()) {
                    // Si existe mais soft deleted : on restaure et MAJ les champs
                    $voyage->restore();
                    $voyage->update($data);
                    session()->flash('success', 'Voyage restauré et mis à jour avec succès');
                    $success = true;
                } else if (!$voyage) {
                    // Si aucune référence, on crée
                    Voyage::create($data);
                    session()->flash('success', 'Voyage créé avec succès');
                    $success = true;
                } else {
                    // La référence existe déjà et est active => régénère
                    $this->reference = $this->generateReference();
                    $retryCount++;
                    usleep(100000);
                }
            }

            DB::commit();
            $this->closeModal();
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            $this->addError('general', 'Erreur SQL : ' . $e->getMessage());
            break;
        } catch (\Exception $e) {
            DB::rollBack();
            $this->addError('general', 'Erreur : ' . $e->getMessage());
            break;
        }
    }

    if (!$success && !$this->editingVoyage) {
        $this->addError('reference', 'La référence générée existe déjà. Veuillez réessayer.');
        $this->showModal = true;
    }
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

    // MÉTHODES PRIVÉES
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
        $maxRetries = 5;
        $retryCount = 0;

        while ($retryCount < $maxRetries) {
            $count = Voyage::withoutTrashed()
                ->whereYear('created_at', date('Y'))
                ->count() + 1;

            $reference = 'V' . str_pad($count, 3, '0', STR_PAD_LEFT) . '/' . $year;

            $exists = Voyage::withoutTrashed()
                ->where('reference', $reference)
                ->exists();

            if (!$exists) {
                return $reference;
            }

            $retryCount++;
            usleep(100000); // Petite pause avant de réessayer
        }

        throw new \Exception('Impossible de générer une référence unique après ' . $maxRetries . ' tentatives.');
    }

    
    // MÉTHODES D'EXPORT/ACTIONS EN MASSE
    public function exportSelected($voyageIds)
    {
        session()->flash('success', count($voyageIds) . ' voyages exportés avec succès');
    }

    public function delete(Voyage $voyage)
    {
        try {
            if ($voyage->trashed()) {
                $this->addError("delete.{$voyage->id}", 'Ce voyage est déjà supprimé.');
                return;
            }

            // Supprimer les chargements et déchargements associés
            $voyage->chargements()->delete();
            $voyage->dechargements()->delete();

            $voyage->delete();
            session()->flash('success', 'Voyage et ses chargements/déchargements supprimés avec succès');
        } catch (\Exception $e) {
            $this->addError("delete.{$voyage->id}", 'Erreur lors de la suppression : ' . $e->getMessage());
        }
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

    public function restore($voyageId)
    {
        $voyage = Voyage::withTrashed()->findOrFail($voyageId);

        if ($voyage->trashed()) {
            // Check if the reference is already used by an active voyage
            $existingVoyage = Voyage::withoutTrashed()
                ->where('reference', $voyage->reference)
                ->first();

            if ($existingVoyage) {
                session()->flash('error', 'Impossible de restaurer : la référence "' . $voyage->reference . '" est déjà utilisée.');
                return;
            }

            $voyage->restore();
            session()->flash('success', 'Voyage restauré avec succès');
        }
    }

    public function render()
    {
        $voyages = Voyage::withoutTrashed()
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

        $stats = [
            'total' => Voyage::withoutTrashed()->count(),
            'en_cours' => Voyage::withoutTrashed()->where('statut', 'en_cours')->count(),
            'termine' => Voyage::withoutTrashed()->where('statut', 'termine')->count(),
            'annule' => Voyage::withoutTrashed()->where('statut', 'annule')->count(),
            'aujourd_hui' => Voyage::withoutTrashed()->whereDate('date', today())->count(),
            'cette_semaine' => Voyage::withoutTrashed()->whereBetween('date', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'ce_mois' => Voyage::withoutTrashed()->whereMonth('date', now()->month)
                            ->whereYear('date', now()->year)->count(),
            'poids_total_charge' => Voyage::withoutTrashed()->with('chargements')
                                        ->get()
                                        ->sum(function ($voyage) {
                                            return $voyage->chargements->sum('poids_depart_kg');
                                        }),
            'poids_total_decharge' => Voyage::withoutTrashed()->with('dechargements')
                                        ->get()
                                        ->sum(function ($voyage) {
                                            return $voyage->dechargements->sum('poids_arrivee_kg');
                                        }),
        ];

        $vehicules = Vehicule::where('statut', 'actif')
                            ->orderBy('immatriculation')
                            ->get();

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
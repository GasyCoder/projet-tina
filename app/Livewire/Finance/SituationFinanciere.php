<?php

namespace App\Livewire\Finance;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\SituationFinanciere as SituationModel;
use Carbon\Carbon;

class SituationFinanciere extends Component
{
    

    use WithPagination;

    // Propriétés de filtrage
    public $lieuSelectionne = '';
    public $dateDebut = '';
    public $dateFin = '';
    
    // Propriétés du modal
    public $showSituationModal = false;
    public $editingSituation = false;
    public $situationId = null;
    
    // Propriétés du formulaire
    public $dateSituation = '';
    public $lieu = '';
    public $description = '';
    public $montantInitial = 0;
    public $montantFinal = 0;
    public $commentaire = '';
    // Dans votre fichier de composant Livewire (par exemple app/Livewire/SituationManager.php)

    public $newDescription = '';
    public $newAmount = 0;

    public function addDescriptionItem()
    {
        $this->validate([
            'newDescription' => 'required|string|max:255',
            'newAmount' => 'required|numeric',
        ]);

        $this->descriptionsList[] = [
            'text' => $this->newDescription,
            'amount' => $this->newAmount
        ];

        $this->newDescription = '';
        $this->newAmount = 0;
    }

    public function removeDescription($index)
    {
        unset($this->descriptionsList[$index]);
        $this->descriptionsList = array_values($this->descriptionsList); // Réindexer le tableau
    }
    protected $rules = [
        'dateSituation' => 'required|date',
        'lieu' => 'required|in:mahajanga,antananarivo,autre',
        'description' => 'required|string|max:255',
        'montantInitial' => 'required|numeric|min:0',
        'montantFinal' => 'required|numeric|min:0',
        'commentaire' => 'nullable|string',
    ];

    protected $messages = [
        'dateSituation.required' => 'La date est obligatoire.',
        'lieu.required' => 'Le lieu est obligatoire.',
        'description.required' => 'La description est obligatoire.',
        'montantInitial.required' => 'Le montant initial est obligatoire.',
        'montantFinal.required' => 'Le montant final est obligatoire.',
    ];

    public function mount()
    {
        // Initialiser les dates par défaut
        $this->dateDebut = Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->dateFin = Carbon::now()->endOfMonth()->format('Y-m-d');
        $this->dateSituation = Carbon::now()->format('Y-m-d');
        
    }

    public function render()
    {
        $query = SituationModel::query();

        // Appliquer les filtres
        if ($this->lieuSelectionne) {
            $query->where('lieu', $this->lieuSelectionne);
        }

        if ($this->dateDebut) {
            $query->whereDate('date_situation', '>=', $this->dateDebut);
        }

        if ($this->dateFin) {
            $query->whereDate('date_situation', '<=', $this->dateFin);
        }

        $situations = $query->orderBy('date_situation', 'desc')
                           ->orderBy('created_at', 'desc')
                           ->paginate(20);

        // Grouper par date pour l'affichage
        $situationsGroupees = [];
        foreach ($situations->items() as $situation) {
            $date = $situation->date_situation->format('Y-m-d');
            if (!isset($situationsGroupees[$date])) {
                $situationsGroupees[$date] = [];
            }
            $situationsGroupees[$date][] = $situation;
        }

        return view('livewire.finance.situation-financiere', [
            'situations' => $situations,
            'situationsGroupees' => $situationsGroupees,
        ]);
    }

    public function resetFiltres()
    {
        $this->lieuSelectionne = '';
        $this->dateDebut = Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->dateFin = Carbon::now()->endOfMonth()->format('Y-m-d');
        $this->resetPage();
    }

    public function openSituationModal()
    {
        $this->resetForm();
        $this->showSituationModal = true;
    }

    public function closeSituationModal()
    {
        $this->showSituationModal = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->editingSituation = false;
        $this->situationId = null;
        $this->dateSituation = Carbon::now()->format('Y-m-d');
        $this->lieu = '';
        $this->description = '';
        $this->montantInitial = 0;
        $this->montantFinal = 0;
        $this->commentaire = '';
        $this->resetValidation();
    }

    public function saveSituation()
    {
        $this->validate();

        try {
            if ($this->editingSituation) {
                // Mise à jour
                $situation = SituationModel::findOrFail($this->situationId);
                $situation->update([
                    'date_situation' => $this->dateSituation,
                    'lieu' => $this->lieu,
                    'description' => $this->description,
                    'montant_initial' => $this->montantInitial,
                    'montant_final' => $this->montantFinal,
                    'commentaire' => $this->commentaire,
                ]);

                session()->flash('message', '✅ Situation modifiée avec succès !');
            } else {
                // Création
                SituationModel::create([
                    'date_situation' => $this->dateSituation,
                    'lieu' => $this->lieu,
                    'description' => $this->description,
                    'montant_initial' => $this->montantInitial,
                    'montant_final' => $this->montantFinal,
                    'commentaire' => $this->commentaire,
                ]);

                session()->flash('message', '✅ Situation ajoutée avec succès !');
            }

            $this->closeSituationModal();
            $this->resetPage();

        } catch (\Exception $e) {
            session()->flash('error', '❌ Erreur lors de l\'enregistrement : ' . $e->getMessage());
        }
    }
    // Dans votre composant Livewire
    public $previousDescriptions = []; // Remplir ce tableau avec les descriptions historiques
    public $descriptionsList = [];

    public function addNewDescription()
    {
        $this->descriptionsList[] = ['text' => $this->description, 'amount' => null];
        $this->description = '';
    }

    // public function removeDescription($index)
    // {
    //     unset($this->descriptionsList[$index]);
    //     $this->descriptionsList = array_values($this->descriptionsList); // Réindexer le tableau
    // }

    public function editSituation($id)
    {
        $situation = SituationModel::findOrFail($id);
        
        $this->editingSituation = true;
        $this->situationId = $id;
        $this->dateSituation = $situation->date_situation->format('Y-m-d');
        $this->lieu = $situation->lieu;
        $this->description = $situation->description;
        $this->montantInitial = $situation->montant_initial;
        $this->montantFinal = $situation->montant_final;
        $this->commentaire = $situation->commentaire;
        
        $this->showSituationModal = true;
    }

    public function deleteSituation($id)
    {
        try {
            SituationModel::findOrFail($id)->delete();
            session()->flash('message', '✅ Situation supprimée avec succès !');
        } catch (\Exception $e) {
            session()->flash('error', '❌ Erreur lors de la suppression : ' . $e->getMessage());
        }
    }

    // Méthode utilitaire pour calculer les totaux par lieu
    public function getTotalParLieu()
    {
        $query = SituationModel::query();

        if ($this->dateDebut) {
            $query->whereDate('date_situation', '>=', $this->dateDebut);
        }

        if ($this->dateFin) {
            $query->whereDate('date_situation', '<=', $this->dateFin);
        }

        return $query->selectRaw('
                lieu,
                SUM(montant_initial) as total_initial,
                SUM(montant_final) as total_final,
                SUM(montant_final - montant_initial) as ecart_total,
                COUNT(*) as nombre_entrees
            ')
            ->groupBy('lieu')
            ->get();
    }

    // Méthode pour obtenir le résumé global
    public function getResumeGlobal()
    {
        $query = SituationModel::query();

        if ($this->lieuSelectionne) {
            $query->where('lieu', $this->lieuSelectionne);
        }

        if ($this->dateDebut) {
            $query->whereDate('date_situation', '>=', $this->dateDebut);
        }

        if ($this->dateFin) {
            $query->whereDate('date_situation', '<=', $this->dateFin);
        }

        return $query->selectRaw('
                SUM(montant_initial) as total_initial,
                SUM(montant_final) as total_final,
                SUM(montant_final - montant_initial) as ecart_total,
                COUNT(*) as nombre_entrees
            ')
            ->first();
    }
}
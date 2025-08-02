<?php
// app/Livewire/Finance/MouvementsFinanciers.php

namespace App\Livewire\Finance;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\MouvementFinancier;
use App\Models\Compte;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class MouvementsFinanciers extends Component
{
    use WithPagination;

    // Propriétés de navigation et filtrage
    public $dateActuelle;
    public $lieuSelectionne = 'antananarivo';
    public $modeAffichage = 'saisie'; // saisie, tableau, historique
    
    // Propriétés du formulaire de saisie
    public $showMouvementModal = false;
    public $editingMouvement = false;
    public $mouvementId = null;
    
    // Propriétés du mouvement
    public $dateMouvement = '';
    public $compteId = '';
    public $typeMouvement = 'entree';
    public $description = '';
    public $montant = 0;
    public $commentaire = '';
    
    // Propriétés pour l'historique et filtres
    public $dateDebut = '';
    public $dateFin = '';
    public $compteFiltre = '';
    public $typeFiltre = '';
    
    // Propriétés pour saisie rapide
    public $saisieRapide = [
        'compte_id' => '',
        'type' => 'entree',
        'description' => '',
        'montant' => 0,
    ];

    protected $rules = [
        'dateMouvement' => 'required|date',
        'compteId' => 'required|exists:comptes,id',
        'typeMouvement' => 'required|in:entree,sortie',
        'description' => 'required|string|max:255',
        'montant' => 'required|numeric|min:0.01',
        'commentaire' => 'nullable|string|max:500',
    ];

    protected $messages = [
        'dateMouvement.required' => 'La date est obligatoire.',
        'compteId.required' => 'Veuillez sélectionner un compte.',
        'typeMouvement.required' => 'Le type de mouvement est obligatoire.',
        'description.required' => 'La description est obligatoire.',
        'montant.required' => 'Le montant est obligatoire.',
        'montant.min' => 'Le montant doit être supérieur à 0.',
    ];

    public function mount()
    {
        $this->dateActuelle = Carbon::now()->format('Y-m-d');
        $this->dateDebut = Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->dateFin = Carbon::now()->endOfMonth()->format('Y-m-d');
        $this->dateMouvement = $this->dateActuelle;
        
        Log::info('MouvementsFinanciers mounted', [
            'dateActuelle' => $this->dateActuelle,
            'lieu' => $this->lieuSelectionne
        ]);
    }

    public function render()
    {
        // Récupérer tous les comptes actifs
        $comptes = Compte::actif()->orderBy('type_compte')->get();
        
        $data = [
            'comptes' => $comptes,
            'resumeJour' => $this->getResumeJour(),
        ];

        // Ajouter les données selon le mode d'affichage
        if ($this->modeAffichage === 'saisie') {
            $data['mouvementsJour'] = $this->getMouvementsJour();
        } elseif ($this->modeAffichage === 'tableau') {
            $data['tableauSemaine'] = $this->getTableauSemaine();
            Log::info('Mode tableau - données récupérées', [
                'tableau_count' => count($data['tableauSemaine']),
                'comptes_count' => $comptes->count()
            ]);
        } elseif ($this->modeAffichage === 'historique') {
            $data['mouvements'] = $this->getMouvementsHistorique();
        }

        return view('livewire.finance.mouvements-financiers', $data);
    }

    public function changerDate($direction)
    {
        $date = Carbon::parse($this->dateActuelle);
        
        if ($direction === 'precedent') {
            $this->dateActuelle = $date->subDay()->format('Y-m-d');
        } else {
            $this->dateActuelle = $date->addDay()->format('Y-m-d');
        }
        
        $this->dateMouvement = $this->dateActuelle;
        
        Log::info('Date changée', [
            'nouvelle_date' => $this->dateActuelle,
            'direction' => $direction
        ]);
    }

    public function changerLieu()
    {
        $this->resetPage();
        Log::info('Lieu changé', ['nouveau_lieu' => $this->lieuSelectionne]);
    }

    public function openMouvementModal()
    {
        $this->resetForm();
        $this->showMouvementModal = true;
    }

    public function closeMouvementModal()
    {
        $this->showMouvementModal = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->editingMouvement = false;
        $this->mouvementId = null;
        $this->dateMouvement = $this->dateActuelle;
        $this->compteId = '';
        $this->typeMouvement = 'entree';
        $this->description = '';
        $this->montant = 0;
        $this->commentaire = '';
        $this->resetValidation();
    }

    public function sauvegarderMouvement()
    {
        $this->validate();

        try {
            if ($this->editingMouvement) {
                // Mise à jour
                $mouvement = MouvementFinancier::findOrFail($this->mouvementId);
                $mouvement->update([
                    'date_mouvement' => $this->dateMouvement,
                    'compte_id' => $this->compteId,
                    'type_mouvement' => $this->typeMouvement,
                    'description' => $this->description,
                    'montant' => $this->montant,
                    'commentaire' => $this->commentaire,
                    'lieu' => $this->lieuSelectionne,
                ]);

                session()->flash('message', '✅ Mouvement modifié avec succès !');
                Log::info('Mouvement modifié', ['id' => $mouvement->id]);
            } else {
                // Création
                $mouvement = MouvementFinancier::create([
                    'date_mouvement' => $this->dateMouvement,
                    'compte_id' => $this->compteId,
                    'type_mouvement' => $this->typeMouvement,
                    'description' => $this->description,
                    'montant' => $this->montant,
                    'commentaire' => $this->commentaire,
                    'lieu' => $this->lieuSelectionne,
                ]);

                session()->flash('message', '✅ Mouvement ajouté avec succès !');
                Log::info('Mouvement créé', [
                    'id' => $mouvement->id,
                    'compte_id' => $this->compteId,
                    'type' => $this->typeMouvement,
                    'montant' => $this->montant
                ]);
            }

            $this->closeMouvementModal();
            $this->resetPage();

        } catch (\Exception $e) {
            session()->flash('error', '❌ Erreur : ' . $e->getMessage());
            Log::error('Erreur sauvegarde mouvement', ['error' => $e->getMessage()]);
        }
    }

    public function saisieRapideMouvement()
    {
        $this->validate([
            'saisieRapide.compte_id' => 'required|exists:comptes,id',
            'saisieRapide.type' => 'required|in:entree,sortie',
            'saisieRapide.description' => 'required|string|max:255',
            'saisieRapide.montant' => 'required|numeric|min:0.01',
        ]);

        try {
            $mouvement = MouvementFinancier::create([
                'date_mouvement' => $this->dateActuelle,
                'compte_id' => $this->saisieRapide['compte_id'],
                'type_mouvement' => $this->saisieRapide['type'],
                'description' => $this->saisieRapide['description'],
                'montant' => $this->saisieRapide['montant'],
                'lieu' => $this->lieuSelectionne,
            ]);

            // Reset formulaire saisie rapide
            $this->saisieRapide = [
                'compte_id' => $this->saisieRapide['compte_id'], // Garder le compte
                'type' => 'entree',
                'description' => '',
                'montant' => 0,
            ];

            session()->flash('message', '⚡ Mouvement ajouté rapidement !');
            Log::info('Saisie rapide', [
                'mouvement_id' => $mouvement->id,
                'compte_id' => $mouvement->compte_id,
                'montant' => $mouvement->montant
            ]);

        } catch (\Exception $e) {
            session()->flash('error', '❌ Erreur : ' . $e->getMessage());
            Log::error('Erreur saisie rapide', ['error' => $e->getMessage()]);
        }
    }

    public function editMouvement($id)
    {
        $mouvement = MouvementFinancier::findOrFail($id);
        
        $this->editingMouvement = true;
        $this->mouvementId = $id;
        $this->dateMouvement = $mouvement->date_mouvement->format('Y-m-d');
        $this->compteId = $mouvement->compte_id;
        $this->typeMouvement = $mouvement->type_mouvement;
        $this->description = $mouvement->description;
        $this->montant = $mouvement->montant;
        $this->commentaire = $mouvement->commentaire;
        
        $this->showMouvementModal = true;
    }

    public function deleteMouvement($id)
    {
        try {
            MouvementFinancier::findOrFail($id)->delete();
            session()->flash('message', '✅ Mouvement supprimé !');
            Log::info('Mouvement supprimé', ['id' => $id]);
        } catch (\Exception $e) {
            session()->flash('error', '❌ Erreur : ' . $e->getMessage());
            Log::error('Erreur suppression', ['id' => $id, 'error' => $e->getMessage()]);
        }
    }

    public function validerJour()
    {
        $count = MouvementFinancier::whereDate('date_mouvement', $this->dateActuelle)
                          ->where('lieu', $this->lieuSelectionne)
                          ->update(['valide' => true]);
        
        session()->flash('message', "✅ Journée validée ! ($count mouvements)");
        Log::info('Journée validée', ['date' => $this->dateActuelle, 'count' => $count]);
    }

    public function deverrouillerJour()
    {
        $count = MouvementFinancier::whereDate('date_mouvement', $this->dateActuelle)
                          ->where('lieu', $this->lieuSelectionne)
                          ->update(['valide' => false]);
        
        session()->flash('message', "🔓 Journée déverrouillée ! ($count mouvements)");
        Log::info('Journée déverrouillée', ['date' => $this->dateActuelle, 'count' => $count]);
    }

    // Méthodes utilitaires
    private function getResumeJour()
    {
        $resume = MouvementFinancier::getResumeJour($this->dateActuelle, $this->lieuSelectionne);
        
        Log::info('Résumé du jour', [
            'date' => $this->dateActuelle,
            'lieu' => $this->lieuSelectionne,
            'entrees' => $resume->total_entrees ?? 0,
            'sorties' => $resume->total_sorties ?? 0,
            'ecart' => $resume->ecart ?? 0
        ]);
        
        return $resume;
    }

    private function getMouvementsJour()
    {
        $mouvements = MouvementFinancier::with('compte')
                                ->whereDate('date_mouvement', $this->dateActuelle)
                                ->where('lieu', $this->lieuSelectionne)
                                ->orderBy('created_at', 'desc')
                                ->get();
        
        // Grouper par nom de compte pour l'affichage
        $grouped = $mouvements->groupBy(function($mouvement) {
            return $mouvement->compte->type_compte . ' - ' . ($mouvement->compte->nom_proprietaire ?: 'Compte');
        });
        
        Log::info('Mouvements du jour', [
            'date' => $this->dateActuelle,
            'total' => $mouvements->count(),
            'groupes' => $grouped->keys()
        ]);
        
        return $grouped;
    }

    private function getTableauSemaine()
    {
        $dateDebut = Carbon::parse($this->dateActuelle)->startOfWeek();
        $dateFin = Carbon::parse($this->dateActuelle)->endOfWeek();
        
        $tableau = MouvementFinancier::getTableauSemaine(
            $dateDebut->format('Y-m-d'), 
            $dateFin->format('Y-m-d'), 
            $this->lieuSelectionne
        );
        
        Log::info('Tableau semaine généré', [
            'date_debut' => $dateDebut->format('Y-m-d'),
            'date_fin' => $dateFin->format('Y-m-d'),
            'lieu' => $this->lieuSelectionne,
            'jours_count' => count($tableau)
        ]);
        
        return $tableau;
    }

    private function getMouvementsHistorique()
    {
        $query = MouvementFinancier::with('compte');

        if ($this->lieuSelectionne) {
            $query->where('lieu', $this->lieuSelectionne);
        }

        if ($this->dateDebut) {
            $query->whereDate('date_mouvement', '>=', $this->dateDebut);
        }

        if ($this->dateFin) {
            $query->whereDate('date_mouvement', '<=', $this->dateFin);
        }

        if ($this->compteFiltre) {
            $query->where('compte_id', $this->compteFiltre);
        }

        if ($this->typeFiltre) {
            $query->where('type_mouvement', $this->typeFiltre);
        }

        return $query->orderBy('date_mouvement', 'desc')
                    ->orderBy('created_at', 'desc')
                    ->paginate(20);
    }

    public function resetFiltres()
    {
        $this->dateDebut = Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->dateFin = Carbon::now()->endOfMonth()->format('Y-m-d');
        $this->compteFiltre = '';
        $this->typeFiltre = '';
        $this->resetPage();
    }

    public function copierDernierMouvement($compteId)
    {
        $dernierMouvement = MouvementFinancier::where('compte_id', $compteId)
                                             ->orderBy('created_at', 'desc')
                                             ->first();

        if ($dernierMouvement) {
            $this->saisieRapide['compte_id'] = $compteId;
            $this->saisieRapide['type'] = $dernierMouvement->type_mouvement;
            $this->saisieRapide['description'] = $dernierMouvement->description;
            $this->saisieRapide['montant'] = $dernierMouvement->montant;
            
            session()->flash('message', '📋 Dernier mouvement copié !');
        } else {
            session()->flash('error', '❌ Aucun mouvement trouvé pour ce compte');
        }
    }

    // Méthode pour recalculer tous les soldes (debug/maintenance)
    public function recalculerSoldes()
    {
        try {
            MouvementFinancier::recalculerTousLesSoldes();
            session()->flash('message', '✅ Tous les soldes ont été recalculés !');
            Log::info('Recalcul des soldes effectué');
        } catch (\Exception $e) {
            session()->flash('error', '❌ Erreur lors du recalcul : ' . $e->getMessage());
            Log::error('Erreur recalcul soldes', ['error' => $e->getMessage()]);
        }
    }

    // Méthode pour débugger le tableau
    public function debugTableau()
    {
        $dateDebut = Carbon::parse($this->dateActuelle)->startOfWeek();
        $dateFin = Carbon::parse($this->dateActuelle)->endOfWeek();
        
        $comptes = Compte::actif()->get();
        $mouvements = MouvementFinancier::whereBetween('date_mouvement', [$dateDebut, $dateFin])
                                       ->where('lieu', $this->lieuSelectionne)
                                       ->with('compte')
                                       ->get();
        
        Log::info('Debug tableau', [
            'periode' => $dateDebut->format('Y-m-d') . ' à ' . $dateFin->format('Y-m-d'),
            'lieu' => $this->lieuSelectionne,
            'comptes_actifs' => $comptes->count(),
            'mouvements_periode' => $mouvements->count(),
            'comptes_list' => $comptes->pluck('type_compte', 'id'),
            'mouvements_sample' => $mouvements->take(3)->map(function($m) {
                return [
                    'date' => $m->date_mouvement->format('Y-m-d'),
                    'compte' => $m->compte->type_compte,
                    'type' => $m->type_mouvement,
                    'montant' => $m->montant
                ];
            })
        ]);
        
        session()->flash('message', '🔍 Debug info envoyé aux logs');
    }
}
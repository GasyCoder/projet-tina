<?php

namespace App\Livewire\Finance;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Compte;
use App\Models\Voyage;
use App\Models\Produit;
use App\Models\Dechargement;
use App\Models\Chargement; // AJOUT
use App\Models\Lieu;
use Livewire\Component;
use App\Models\Transaction;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FinanceIndex extends Component
{
    use WithPagination;

    // =====================================================
    // PROPRIÉTÉS DE L'INTERFACE
    // =====================================================
    public $activeTab = 'suivi';

    // =====================================================
    // FILTRES DE BASE
    // =====================================================
    public $searchTerm = '';
    public $filterType = '';
    public $filterStatut = '';
    public $dateDebut = '';
    public $dateFin = '';
    public $filterPersonne = '';

    // =====================================================
    // FILTRES AVANCÉS
    // =====================================================
    public $typeSuivi = 'tous';
    public $periodeRevenus = 'mois';
    public $dateDebutRevenus = '';
    public $dateFinRevenus = '';
    public $categorieDepense = '';
    public $periodeDepenses = 'mois';
    public $dateDebutDepenses = '';
    public $dateFinDepenses = '';

    // =====================================================
    // FORMULAIRE TRANSACTION
    // =====================================================
    public $showTransactionModal = false;
    public $editingTransaction = null;
    public $reference = '';
    public $date = '';
    public $type = '';
    public $from_nom = '';
    public $from_compte = '';
    public $to_nom = '';
    public $to_compte = '';
    public $montant_mga = '';
    public $voyage_id = '';
    public $chargement_ids = []; // MULTIPLE CHARGEMENTS
    public $dechargement_ids = []; // MULTIPLE DECHARGEMENTS
    public $mode_paiement = 'especes';
    public $statut = 'confirme';
    public $observation = '';
    public $reste_a_payer = '';
    public $lieux_display = ''; // AFFICHAGE DES LIEUX

    // =====================================================
    // FORMULAIRE COMPTE
    // =====================================================
    public $showCompteModal = false;
    public $editingCompte = null;
    public $nom_proprietaire = '';
    public $type_compte = 'principal';
    public $nom_compte = '';
    public $numero_compte = '';
    public $solde_actuel_mga = 0;
    public $compte_actif = true;

    // =====================================================
    // RÈGLES DE VALIDATION
    // =====================================================
    protected $rules = [
        'reference' => 'required|string|max:255',
        'date' => 'required|date',
        'montant_mga' => 'required|numeric|min:0',
        'mode_paiement' => 'required|in:especes,AirtelMoney,Mvola,OrangeMoney,banque',
        'statut' => 'required|in:attente,confirme,annule,payee,partiellement_payee',
        'type' => 'required|in:achat,vente,transfert,frais,commission,paiement,avance,depot,retrait,Autre',
        'voyage_id' => 'nullable|exists:voyages,id',
        'reste_a_payer' => 'required_if:statut,partiellement_payee|numeric|min:0',
        'type_compte' => 'required|in:principal,AirtelMoney,Mvola,OrangeMoney,banque',
        'nom_compte' => 'required|string|max:255',
        'solde_actuel_mga' => 'required|numeric',
    ];

    /**
     * Règles de validation améliorées
     */
    protected function getValidationRules()
    {
        $rules = [
            'reference' => 'required|string|max:255',
            'date' => 'required|date',
            'type' => 'required|in:achat,vente,Autre',
            'montant_mga' => 'required|numeric|min:0',
            'mode_paiement' => 'required|in:especes,AirtelMoney,Mvola,OrangeMoney,banque',
            'statut' => 'required|in:attente,confirme,annule,payee,partiellement_payee',
        ];

        // Validation spécifique selon le type
        if ($this->type === 'achat') {
            $rules['voyage_id'] = 'required|exists:voyages,id';
            $rules['chargement_ids'] = 'required|array|min:1';
            $rules['chargement_ids.*'] = 'exists:chargements,id';
        } elseif ($this->type === 'vente') {
            $rules['voyage_id'] = 'required|exists:voyages,id';
            $rules['dechargement_ids'] = 'required|array|min:1';
            $rules['dechargement_ids.*'] = 'exists:dechargements,id';
        }

        if ($this->statut === 'partiellement_payee') {
            $rules['reste_a_payer'] = 'required|numeric|min:0';
        }

        return $rules;
    }


    // =====================================================
    // LISTENERS POUR AUTO-COMPLETION
    // =====================================================
    public function updatedChargementIds()
    {
        Log::info('updatedChargementIds called', ['type' => $this->type, 'chargement_ids' => $this->chargement_ids]);
        
        if ($this->type === 'achat' && !empty($this->chargement_ids)) {
            // Vérifier que tous les chargements sont disponibles
            $chargementsIndisponibles = Chargement::whereIn('id', $this->chargement_ids)
                ->whereHas('transactions', function($q) {
                    $q->where('statut', '!=', 'annule');
                })
                ->pluck('reference')
                ->toArray();

            if (!empty($chargementsIndisponibles)) {
                $this->addError('chargement_ids', 'Les chargements suivants sont déjà utilisés : ' . implode(', ', $chargementsIndisponibles));
                $this->chargement_ids = array_diff($this->chargement_ids, 
                    Chargement::whereIn('reference', $chargementsIndisponibles)->pluck('id')->toArray()
                );
                return;
            }

            $chargements = Chargement::with(['depart', 'produit'])->whereIn('id', $this->chargement_ids)->get();
            
            Log::info('Chargements trouvés', ['count' => $chargements->count()]);
            
            // Calculer montant total basé sur le poids et prix fixe de 1500 MGA/kg
            $prixParKg = 1500;
            $poidsTotal = $chargements->sum('poids_depart_kg');
            $this->montant_mga = $poidsTotal * $prixParKg;
            
            Log::info('Calcul montant', ['poids_total' => $poidsTotal, 'prix_par_kg' => $prixParKg, 'montant' => $this->montant_mga]);
            
            // Afficher lieux de chargement
            $lieux = $chargements->pluck('depart.nom')->unique()->filter()->toArray();
            $this->lieux_display = implode(' + ', $lieux);
            $this->from_nom = $this->lieux_display;
            
            Log::info('Lieux calculés', ['lieux_display' => $this->lieux_display]);
        } else {
            // Reset si pas de chargements sélectionnés
            if ($this->type === 'achat') {
                $this->montant_mga = '';
                $this->lieux_display = '';
                $this->from_nom = '';
            }
        }
    }

    public function updatedDechargementIds()
    {
        Log::info('=== DEBUT updatedDechargementIds ===', ['type' => $this->type, 'dechargement_ids' => $this->dechargement_ids]);
        
        if ($this->type === 'vente' && !empty($this->dechargement_ids)) {
            // Vérifier que tous les déchargements sont disponibles
            $dechargementsIndisponibles = Dechargement::whereIn('id', $this->dechargement_ids)
                ->whereHas('transactions', function($q) {
                    $q->where('statut', '!=', 'annule');
                })
                ->pluck('reference')
                ->toArray();

            if (!empty($dechargementsIndisponibles)) {
                $this->addError('dechargement_ids', 'Les déchargements suivants sont déjà utilisés : ' . implode(', ', $dechargementsIndisponibles));
                $this->dechargement_ids = array_diff($this->dechargement_ids, 
                    Dechargement::whereIn('reference', $dechargementsIndisponibles)->pluck('id')->toArray()
                );
                return;
            }

            $dechargements = Dechargement::with(['lieuLivraison', 'chargement.produit'])->whereIn('id', $this->dechargement_ids)->get();
            
            Log::info('Dechargements trouvés', ['count' => $dechargements->count()]);
            
            // Calculer montant total
            $this->montant_mga = $dechargements->sum('montant_total_mga');
            
            Log::info('Calcul montant vente', ['montant' => $this->montant_mga]);
            
            // Récupérer interlocuteurs et lieux
            $interlocuteurs = [];
            $lieux = [];
            
            foreach($dechargements as $dechargement) {
                if ($dechargement->interlocuteur_nom) {
                    $interlocuteurs[] = $dechargement->interlocuteur_nom;
                }
                
                $lieuNom = $dechargement->lieuLivraison->nom ?? 'LIEU_NULL';
                $lieux[] = $lieuNom;
            }
            
            $interlocuteurs = array_unique(array_filter($interlocuteurs));
            $lieux = array_unique(array_filter($lieux));
            
            $this->from_nom = implode(', ', $interlocuteurs);
            $this->lieux_display = implode(', ', $lieux);
            $this->to_nom = '';
            
            Log::info('CORRECTION appliquée', [
                'from_nom' => $this->from_nom,
                'lieux_display' => $this->lieux_display,
                'to_nom' => $this->to_nom,
                'interlocuteurs' => $interlocuteurs,
                'lieux' => $lieux
            ]);
        } else {
            if ($this->type === 'vente') {
                Log::info('RESET lieux_display pour vente');
                $this->montant_mga = '';
                $this->lieux_display = '';
                $this->from_nom = '';
                $this->to_nom = '';
            }
        }
        
        Log::info('=== FIN updatedDechargementIds ===', [
            'final_from_nom' => $this->from_nom,
            'final_lieux_display' => $this->lieux_display,
            'final_to_nom' => $this->to_nom
        ]);
    }


    public function updatedVoyageId()
    {
        // Reset des sélections quand voyage change
        $this->chargement_ids = [];
        $this->dechargement_ids = [];
        $this->montant_mga = '';
        $this->lieux_display = '';
        $this->from_nom = '';
        $this->to_nom = '';

        // Vérifications selon le type
        if ($this->voyage_id && $this->type) {
            $voyage = Voyage::find($this->voyage_id);
            
            if (!$voyage) {
                $this->addError('voyage_id', 'Voyage introuvable.');
                $this->voyage_id = '';
                return;
            }

            if ($this->type === 'achat' && !$voyage->canBeUsedForAchat()) {
                $this->addError('voyage_id', 'Ce voyage n\'a pas de chargements disponibles pour une transaction d\'achat.');
                $this->voyage_id = '';
                return;
            }

            if ($this->type === 'vente' && !$voyage->canBeUsedForVente()) {
                $this->addError('voyage_id', 'Ce voyage n\'a pas de déchargements disponibles pour une transaction de vente.');
                $this->voyage_id = '';
                return;
            }

            // Log pour debug
            Log::info('Voyage sélectionné valide', [
                'voyage_id' => $this->voyage_id,
                'type' => $this->type,
                'chargements_disponibles' => $this->type === 'achat' ? $voyage->getChargementsDisponibles()->count() : 0,
                'dechargements_disponibles' => $this->type === 'vente' ? $voyage->getDechargementsDisponibles()->count() : 0
            ]);
        }
    }


    public function updatedType()
    {
        // Reset quand type change - TOUJOURS reset voyage_id
        $this->voyage_id = '';
        $this->chargement_ids = [];
        $this->dechargement_ids = [];
        $this->montant_mga = '';
        $this->lieux_display = '';
        $this->from_nom = '';
        $this->to_nom = '';
        
        Log::info('Type updated, FORCE reset ALL fields', ['new_type' => $this->type]);
    }

    // Méthode publique pour forcer le recalcul
    public function recalculerMontant()
    {
        if ($this->type === 'achat') {
            $this->updatedChargementIds();
        } elseif ($this->type === 'vente') {
            $this->updatedDechargementIds();
        }
    }

    // =====================================================
    // INITIALISATION ET GESTION DES ONGLETS
    // =====================================================
    public function mount()
    {
        $validTabs = ['suivi', 'revenus', 'depenses', 'transactions', 'comptes', 'rapports'];
        $requestedTab = request()->query('tab', 'suivi');
        $this->activeTab = in_array($requestedTab, $validTabs) ? $requestedTab : 'suivi';
        $this->dateDebut = Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->dateFin = Carbon::now()->endOfMonth()->format('Y-m-d');
        $this->dateDebutRevenus = Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->dateFinRevenus = Carbon::now()->endOfMonth()->format('Y-m-d');
        $this->dateDebutDepenses = Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->dateFinDepenses = Carbon::now()->endOfMonth()->format('Y-m-d');
        $this->dispatch('tab-changed', tab: $this->activeTab);
    }

    public function setActiveTab($tab)
    {
        $validTabs = ['suivi', 'revenus', 'depenses', 'transactions', 'comptes', 'rapports'];
        if (!in_array($tab, $validTabs)) {
            Log::warning("Invalid tab requested: {$tab}");
            return;
        }
        $this->activeTab = $tab;
        $this->resetPage();
        $this->dispatch('tab-changed', tab: $tab);
        $this->js("
            const url = new URL(window.location);
            url.searchParams.set('tab', '{$tab}');
            window.history.pushState({}, '', url);
        ");
    }

    // =====================================================
    // LISTENERS POUR MISE À JOUR AUTOMATIQUE
    // =====================================================
    public function updatedPeriodeRevenus()
    {
        $this->setDatesFromPeriod('revenus', $this->periodeRevenus);
        $this->resetPage();
    }

    public function updatedPeriodeDepenses()
    {
        $this->setDatesFromPeriod('depenses', $this->periodeDepenses);
        $this->resetPage();
    }

    public function updatedCategorieDepense()
    {
        $this->resetPage();
    }

    public function updatedSearchTerm()
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

    private function setDatesFromPeriod($type, $periode)
    {
        $now = Carbon::now();
        switch ($periode) {
            case 'semaine':
                $debut = $now->copy()->startOfWeek();
                $fin = $now->copy()->endOfWeek();
                break;
            case 'mois':
                $debut = $now->copy()->startOfMonth();
                $fin = $now->copy()->endOfMonth();
                break;
            case 'trimestre':
                $debut = $now->copy()->startOfQuarter();
                $fin = $now->copy()->endOfQuarter();
                break;
            case 'annee':
                $debut = $now->copy()->startOfYear();
                $fin = $now->copy()->endOfYear();
                break;
            default:
                $debut = $now->copy()->startOfMonth();
                $fin = $now->copy()->endOfMonth();
                break;
        }
        if ($type === 'revenus') {
            $this->dateDebutRevenus = $debut->format('Y-m-d');
            $this->dateFinRevenus = $fin->format('Y-m-d');
        } else {
            $this->dateDebutDepenses = $debut->format('Y-m-d');
            $this->dateFinDepenses = $fin->format('Y-m-d');
        }
    }

    // =====================================================
    // PROPRIÉTÉS CALCULÉES - STATISTIQUES GÉNÉRALES
    // =====================================================
    public function getTotalEntreesProperty()
    {
        return Transaction::whereIn('type', ['vente', 'depot', 'commission'])
            ->where('statut', '!=', 'annule')
            ->whereBetween('date', [$this->dateDebut, $this->dateFin])
            ->sum('montant_mga');
    }

    public function getTotalSortiesProperty()
    {
        return Transaction::whereIn('type', ['achat', 'frais', 'avance', 'paiement', 'retrait', 'transfert'])
            ->where('statut', '!=', 'annule')
            ->whereBetween('date', [$this->dateDebut, $this->dateFin])
            ->sum('montant_mga');
    }

    public function getBeneficeNetProperty()
    {
        return $this->totalEntrees - $this->totalSorties;
    }

    public function getTransactionsEnAttenteProperty()
    {
        return Transaction::where('statut', 'attente')
            ->whereBetween('date', [$this->dateDebut, $this->dateFin])
            ->count();
    }

    // =====================================================
    // PROPRIÉTÉS CALCULÉES - REVENUS
    // =====================================================
    public function getRevenusProperty()
    {
        $dates = $this->getDateRangeRevenus();
        return Transaction::with(['voyage'])
            ->whereIn('type', ['vente', 'depot', 'commission', 'transfert'])
            ->whereBetween('date', [$dates['debut'], $dates['fin']])
            ->where('statut', '!=', 'annule')
            ->orderBy('date', 'desc')
            ->paginate(10);
    }

    public function getTotalRevenusProperty()
    {
        $dates = $this->getDateRangeRevenus();
        return Transaction::whereIn('type', ['vente', 'depot', 'commission', 'transfert'])
            ->whereBetween('date', [$dates['debut'], $dates['fin']])
            ->where('statut', '!=', 'annule')
            ->sum('montant_mga');
    }

    public function getRevenuMoyenProperty()
    {
        $count = $this->nombreRevenus;
        return $count > 0 ? $this->totalRevenus / $count : 0;
    }

    public function getNombreRevenusProperty()
    {
        $dates = $this->getDateRangeRevenus();
        return Transaction::whereIn('type', ['vente', 'depot', 'commission', 'transfert'])
            ->whereBetween('date', [$dates['debut'], $dates['fin']])
            ->where('statut', '!=', 'annule')
            ->count();
    }

    public function getRevenusEnAttenteProperty()
    {
        $dates = $this->getDateRangeRevenus();
        return Transaction::whereIn('type', ['vente', 'depot', 'commission', 'transfert'])
            ->where('statut', 'attente')
            ->whereBetween('date', [$dates['debut'], $dates['fin']])
            ->sum('montant_mga');
    }

    public function getRepartitionRevenusProperty()
    {
        $dates = $this->getDateRangeRevenus();
        return Transaction::selectRaw('type, COUNT(*) as count, SUM(montant_mga) as total')
            ->whereIn('type', ['vente', 'depot', 'commission', 'transfert'])
            ->whereBetween('date', [$dates['debut'], $dates['fin']])
            ->where('statut', '!=', 'annule')
            ->groupBy('type')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->type => ['count' => $item->count, 'total' => $item->total]];
            });
    }

    private function getDateRangeRevenus()
    {
        if ($this->periodeRevenus === 'personnalise' && $this->dateDebutRevenus && $this->dateFinRevenus) {
            return [
                'debut' => $this->dateDebutRevenus,
                'fin' => $this->dateFinRevenus
            ];
        }
        switch ($this->periodeRevenus) {
            case 'semaine':
                return [
                    'debut' => Carbon::now()->startOfWeek()->format('Y-m-d'),
                    'fin' => Carbon::now()->endOfWeek()->format('Y-m-d')
                ];
            case 'trimestre':
                return [
                    'debut' => Carbon::now()->startOfQuarter()->format('Y-m-d'),
                    'fin' => Carbon::now()->endOfQuarter()->format('Y-m-d')
                ];
            case 'annee':
                return [
                    'debut' => Carbon::now()->startOfYear()->format('Y-m-d'),
                    'fin' => Carbon::now()->endOfYear()->format('Y-m-d')
                ];
            default: // mois
                return [
                    'debut' => Carbon::now()->startOfMonth()->format('Y-m-d'),
                    'fin' => Carbon::now()->endOfMonth()->format('Y-m-d')
                ];
        }
    }

    // =====================================================
    // PROPRIÉTÉS CALCULÉES - DÉPENSES
    // =====================================================
    public function getDepensesProperty()
    {
        $dates = $this->getDateRangeDepenses();
        $query = Transaction::with(['voyage'])
            ->whereIn('type', ['achat', 'frais', 'avance', 'paiement', 'retrait', 'transfert'])
            ->whereBetween('date', [$dates['debut'], $dates['fin']])
            ->where('statut', '!=', 'annule');
        if ($this->categorieDepense) {
            $query->where('type', $this->categorieDepense);
        }
        return $query->orderBy('date', 'desc')->paginate(10);
    }

    public function getTotalDepensesProperty()
    {
        $dates = $this->getDateRangeDepenses();
        $query = Transaction::whereIn('type', ['achat', 'frais', 'avance', 'paiement', 'retrait', 'transfert'])
            ->whereBetween('date', [$dates['debut'], $dates['fin']])
            ->where('statut', '!=', 'annule');
        if ($this->categorieDepense) {
            $query->where('type', $this->categorieDepense);
        }
        return $query->sum('montant_mga');
    }

    public function getDepenseMoyenneProperty()
    {
        $count = $this->nombreDepenses;
        return $count > 0 ? $this->totalDepenses / $count : 0;
    }

    public function getDepensesEnAttenteProperty()
    {
        $dates = $this->getDateRangeDepenses();
        return Transaction::whereIn('type', ['achat', 'frais', 'avance', 'paiement', 'retrait', 'transfert'])
            ->where('statut', 'attente')
            ->whereBetween('date', [$dates['debut'], $dates['fin']])
            ->sum('montant_mga');
    }

    public function getNombreDepensesProperty()
    {
        $dates = $this->getDateRangeDepenses();
        $query = Transaction::whereIn('type', ['achat', 'frais', 'avance', 'paiement', 'retrait', 'transfert'])
            ->whereBetween('date', [$dates['debut'], $dates['fin']])
            ->where('statut', '!=', 'annule');
        if ($this->categorieDepense) {
            $query->where('type', $this->categorieDepense);
        }
        return $query->count();
    }

    public function getRepartitionDepensesProperty()
    {
        $dates = $this->getDateRangeDepenses();
        return Transaction::selectRaw('type, COUNT(*) as count, SUM(montant_mga) as total')
            ->whereIn('type', ['achat', 'frais', 'avance', 'paiement', 'retrait', 'transfert'])
            ->whereBetween('date', [$dates['debut'], $dates['fin']])
            ->where('statut', '!=', 'annule')
            ->groupBy('type')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->type => ['count' => $item->count, 'total' => $item->total]];
            });
    }

    private function getDateRangeDepenses()
    {
        if ($this->periodeDepenses === 'personnalise' && $this->dateDebutDepenses && $this->dateFinDepenses) {
            return [
                'debut' => $this->dateDebutDepenses,
                'fin' => $this->dateFinDepenses
            ];
        }
        switch ($this->periodeDepenses) {
            case 'semaine':
                return [
                    'debut' => Carbon::now()->startOfWeek()->format('Y-m-d'),
                    'fin' => Carbon::now()->endOfWeek()->format('Y-m-d')
                ];
            case 'trimestre':
                return [
                    'debut' => Carbon::now()->startOfQuarter()->format('Y-m-d'),
                    'fin' => Carbon::now()->endOfQuarter()->format('Y-m-d')
                ];
            case 'annee':
                return [
                    'debut' => Carbon::now()->startOfYear()->format('Y-m-d'),
                    'fin' => Carbon::now()->endOfYear()->format('Y-m-d')
                ];
            default: // mois
                return [
                    'debut' => Carbon::now()->startOfMonth()->format('Y-m-d'),
                    'fin' => Carbon::now()->endOfMonth()->format('Y-m-d')
                ];
        }
    }

    // =====================================================
    // PROPRIÉTÉS CALCULÉES - SUIVI CONDITIONNEL
    // =====================================================
    public function getTransactionsVoyageProperty()
    {
        return Transaction::with(['voyage'])
            ->whereNotNull('voyage_id')
            ->whereBetween('date', [$this->dateDebut, $this->dateFin])
            ->where('statut', '!=', 'annule')
            ->orderBy('date', 'desc')
            ->get();
    }

    public function getTransactionsAutreProperty()
    {
        return Transaction::whereNull('voyage_id')
            ->whereBetween('date', [$this->dateDebut, $this->dateFin])
            ->where('statut', '!=', 'annule')
            ->orderBy('date', 'desc')
            ->get();
    }

    public function getRepartitionParTypeProperty()
    {
        return Transaction::whereBetween('date', [$this->dateDebut, $this->dateFin])
            ->where('statut', '!=', 'annule')
            ->select('type', DB::raw('COUNT(*) as count'), DB::raw('SUM(montant_mga) as total'))
            ->groupBy('type')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->type => ['count' => $item->count, 'total' => $item->total]];
            });
    }

    public function getRepartitionParStatutProperty()
    {
        return Transaction::whereBetween('date', [$this->dateDebut, $this->dateFin])
            ->select('statut', DB::raw('COUNT(*) as count'))
            ->groupBy('statut')
            ->pluck('count', 'statut');
    }

    // =====================================================
    // GESTION DES TRANSACTIONS
    // =====================================================
    public function createTransaction()
    {
        Log::info('createTransaction called', ['activeTab' => $this->activeTab]);
        $this->resetTransactionForm();
        $this->editingTransaction = null;
        $this->reference = $this->generateTransactionReference();
        $this->date = Carbon::now()->format('Y-m-d');
        $this->showTransactionModal = true;
        $this->dispatch('open-transaction-modal');
    }

    public function editTransaction(Transaction $transaction)
    {
        $this->editingTransaction = $transaction;
        $this->reference = $transaction->reference;
        $this->date = $transaction->date->format('Y-m-d');
        $this->type = $transaction->type;
        $this->from_nom = $transaction->from_nom;
        $this->from_compte = $transaction->from_compte;
        $this->to_nom = $transaction->to_nom;
        $this->to_compte = $transaction->to_compte;
        $this->montant_mga = $transaction->montant_mga;
        $this->voyage_id = $transaction->voyage_id;
        
        // ✅ CORRIGER: Récupérer tous les éléments correspondants au montant
        if ($transaction->type === 'achat' && $transaction->voyage_id) {
            // Pour les achats, essayer de retrouver les chargements correspondant au montant
            $chargements = Chargement::where('voyage_id', $transaction->voyage_id)->get();
            $this->chargement_ids = $this->findMatchingChargements($chargements, $transaction->montant_mga);
            
            // Recalculer l'affichage des lieux pour les achats
            if (!empty($this->chargement_ids)) {
                $this->updatedChargementIds();
            }
        } else {
            $this->chargement_ids = $transaction->chargement_id ? [$transaction->chargement_id] : [];
        }
        
        if ($transaction->type === 'vente' && $transaction->voyage_id) {
            // Pour les ventes, essayer de retrouver les déchargements correspondant au montant
            $dechargements = Dechargement::where('voyage_id', $transaction->voyage_id)->get();
            $this->dechargement_ids = $this->findMatchingDechargements($dechargements, $transaction->montant_mga);
            
            // ✅ CORRECTION: Recalculer l'affichage pour les ventes mais garder les valeurs existantes
            if (!empty($this->dechargement_ids)) {
                // Sauvegarder les valeurs actuelles de la transaction
                $originalFromNom = $transaction->from_nom;
                $originalToNom = $transaction->to_nom;
                
                // Recalculer pour avoir lieux_display à jour
                $this->updatedDechargementIds();
                
                // Mais garder les valeurs originales si elles existent
                // (car l'utilisateur a peut-être personnalisé)
                if ($originalFromNom) {
                    $this->from_nom = $originalFromNom;
                }
                if ($originalToNom) {
                    $this->to_nom = $originalToNom;
                }
            }
        } else {
            $this->dechargement_ids = $transaction->dechargement_id ? [$transaction->dechargement_id] : [];
        }
        
        $this->mode_paiement = $transaction->mode_paiement;
        $this->statut = $transaction->statut;
        $this->reste_a_payer = $transaction->reste_a_payer;
        $this->observation = $transaction->observation;
        $this->showTransactionModal = true;
        
        Log::info('Transaction éditée', [
            'id' => $transaction->id,
            'type' => $transaction->type,
            'montant' => $transaction->montant_mga,
            'chargement_ids_found' => $this->chargement_ids,
            'dechargement_ids_found' => $this->dechargement_ids,
            'from_nom' => $this->from_nom,
            'to_nom' => $this->to_nom
        ]);
    }

    // Méthode pour retrouver les chargements correspondant au montant
    private function findMatchingChargements($chargements, $montantTotal)
    {
        $prixParKg = 1500;
        $poidsTarget = $montantTotal / $prixParKg;
        $selected = [];
        $poidsActuel = 0;
        
        // Essayer de trouver une combinaison de chargements qui correspond au montant
        foreach ($chargements as $chargement) {
            if ($poidsActuel + $chargement->poids_depart_kg <= $poidsTarget + 1) { // +1 pour tolérance
                $selected[] = $chargement->id;
                $poidsActuel += $chargement->poids_depart_kg;
            }
            if (abs($poidsActuel - $poidsTarget) < 1) break; // Assez proche
        }
        
        return $selected;
    }

    // Méthode pour retrouver les déchargements correspondant au montant
    private function findMatchingDechargements($dechargements, $montantTotal)
    {
        $selected = [];
        $montantActuel = 0;
        $tolerance = 1000; // Tolérance de 1000 MGA
        
        // Essayer de trouver une combinaison de déchargements qui correspond au montant
        foreach ($dechargements as $dechargement) {
            if ($montantActuel + $dechargement->montant_total_mga <= $montantTotal + $tolerance) {
                $selected[] = $dechargement->id;
                $montantActuel += $dechargement->montant_total_mga;
            }
            
            // Si on est assez proche du montant cible, on s'arrête
            if (abs($montantActuel - $montantTotal) < $tolerance) {
                break;
            }
        }
        
        Log::info('findMatchingDechargements', [
            'montant_total' => $montantTotal,
            'montant_actuel' => $montantActuel,
            'selected_ids' => $selected,
            'tolerance' => $tolerance
        ]);
        
        return $selected;
    }

    public function saveTransaction()
    {
        $this->validate($this->getValidationRules());

        // Validations métier supplémentaires
        if ($this->type === 'achat' && !empty($this->chargement_ids)) {
            $chargementsUtilises = Chargement::whereIn('id', $this->chargement_ids)
                ->whereHas('transactions', function($q) {
                    $q->where('statut', '!=', 'annule');
                    if ($this->editingTransaction) {
                        $q->where('id', '!=', $this->editingTransaction->id);
                    }
                })
                ->exists();

            if ($chargementsUtilises) {
                $this->addError('chargement_ids', 'Un ou plusieurs chargements sont déjà utilisés dans une autre transaction.');
                return;
            }
        }

        if ($this->type === 'vente' && !empty($this->dechargement_ids)) {
            $dechargementsUtilises = Dechargement::whereIn('id', $this->dechargement_ids)
                ->whereHas('transactions', function($q) {
                    $q->where('statut', '!=', 'annule');
                    if ($this->editingTransaction) {
                        $q->where('id', '!=', $this->editingTransaction->id);
                    }
                })
                ->exists();

            if ($dechargementsUtilises) {
                $this->addError('dechargement_ids', 'Un ou plusieurs déchargements sont déjà utilisés dans une autre transaction.');
                return;
            }
        }

        // Sauvegarde (reste du code existant)
        $data = [
            'reference' => $this->reference,
            'date' => $this->date,
            'type' => $this->type,
            'from_nom' => $this->from_nom ?: null,
            'from_compte' => $this->from_compte ?: null,
            'to_nom' => $this->to_nom ?: null,
            'to_compte' => $this->to_compte ?: null,
            'montant_mga' => $this->montant_mga,
            'objet' => 'Transaction ' . $this->type . ' - ' . ($this->lieux_display ?: 'N/A'),
            'voyage_id' => $this->voyage_id ?: null,
            'chargement_id' => !empty($this->chargement_ids) ? $this->chargement_ids[0] : null,
            'dechargement_id' => !empty($this->dechargement_ids) ? $this->dechargement_ids[0] : null,
            'mode_paiement' => $this->mode_paiement,
            'statut' => $this->statut,
            'reste_a_payer' => $this->statut === 'partiellement_payee' ? $this->reste_a_payer : null,
            'observation' => $this->observation ?: null,
        ];

        if ($this->editingTransaction) {
            $this->editingTransaction->update($data);
            session()->flash('success', 'Transaction modifiée avec succès');
        } else {
            Transaction::create($data);
            session()->flash('success', 'Transaction ajoutée avec succès');
        }

        $this->closeTransactionModal();
    }

    public function deleteTransaction(Transaction $transaction)
    {
        $transaction->delete();
        session()->flash('success', 'Transaction supprimée avec succès');
    }

    public function confirmerTransaction(Transaction $transaction)
    {
        $transaction->update(['statut' => 'confirme']);
        session()->flash('success', 'Transaction confirmée');
    }

    public function marquerPayee($id)
    {
        $transaction = Transaction::findOrFail($id);
        $transaction->update(['statut' => 'payee']);
        session()->flash('success', 'Transaction marquée comme payée !');
    }

    // =====================================================
    // GESTION DES COMPTES
    // =====================================================
    
    public function createCompte()
    {
        Log::info('createCompte called', ['activeTab' => $this->activeTab]);
        $this->resetCompteForm();
        $this->editingCompte = null;
        $this->showCompteModal = true;
        $this->dispatch('open-compte-modal');
    }

    public function editCompte(Compte $compte)
    {
        $this->editingCompte = $compte;
        $this->nom_proprietaire = $compte->nom_proprietaire;
        $this->type_compte = $compte->type_compte;
        $this->nom_compte = $compte->nom_compte ?: $compte->nom;
        $this->numero_compte = $compte->numero_compte;
        $this->solde_actuel_mga = $compte->solde_actuel_mga;
        $this->compte_actif = $compte->actif;
        $this->showCompteModal = true;
    }

    public function saveCompte()
    {
        $this->validate([
            'type_compte' => 'required|in:principal,AirtelMoney,Mvola,OrangeMoney,banque',
            'nom_compte' => 'required|string|max:255',
            'solde_actuel_mga' => 'required|numeric',
        ]);

        $data = [
            'nom' => $this->nom_compte ?: 'NomInconnu',
            'nom_proprietaire' => $this->nom_proprietaire ?: null,
            'type_compte' => $this->type_compte,
            'nom_compte' => $this->nom_compte,
            'numero_compte' => $this->numero_compte ?: null,
            'solde_actuel_mga' => $this->solde_actuel_mga,
            'actif' => $this->compte_actif,
        ];

        if ($this->editingCompte) {
            $this->editingCompte->update($data);
            session()->flash('success', 'Compte modifié avec succès');
        } else {
            Compte::create($data);
            session()->flash('success', 'Compte ajouté avec succès');
        }
        $this->closeCompteModal();
    }

    public function deleteCompte(Compte $compte)
    {
        $compte->delete();
        session()->flash('success', 'Compte supprimé avec succès');
    }

    // =====================================================
    // GESTION DES MODALES
    // =====================================================
    public function closeTransactionModal()
    {
        Log::info('closeTransactionModal called');
        $this->showTransactionModal = false;
        $this->resetTransactionForm();
        $this->editingTransaction = null;
        $this->dispatch('close-transaction-modal');
    }

    public function closeCompteModal()
    {
        Log::info('closeCompteModal called');
        $this->showCompteModal = false;
        $this->resetCompteForm();
        $this->editingCompte = null;
        $this->dispatch('close-compte-modal');
    }

    private function resetTransactionForm()
    {
        $this->reference = '';
        $this->date = '';
        $this->type = '';
        $this->from_nom = '';
        $this->from_compte = '';
        $this->to_nom = '';
        $this->to_compte = '';
        $this->montant_mga = '';
        $this->voyage_id = '';
        $this->chargement_ids = [];
        $this->dechargement_ids = [];
        $this->lieux_display = '';
        $this->mode_paiement = 'especes';
        $this->statut = 'confirme';
        $this->reste_a_payer = '';
        $this->observation = '';
        $this->resetErrorBag();
    }

    private function resetCompteForm()
    {
        $this->nom_proprietaire = '';
        $this->type_compte = 'principal';
        $this->nom_compte = '';
        $this->numero_compte = '';
        $this->solde_actuel_mga = 0;
        $this->compte_actif = true;
        $this->resetErrorBag();
    }

    // =====================================================
    // MÉTHODES UTILITAIRES
    // =====================================================


    /**
     * Génère une référence unique de transaction en tenant compte des SoftDeletes
     */
    private function generateTransactionReference()
    {
        // ✅ SOLUTION 1: Compter TOUTES les transactions (même soft-deleted)
        $count = Transaction::withTrashed()
            ->whereDate('created_at', Carbon::today())
            ->count() + 1;
        
        $reference = 'TXN' . date('Ymd') . str_pad($count, 3, '0', STR_PAD_LEFT);
        
        // ✅ SOLUTION 2: Vérifier l'unicité et incrémenter si nécessaire
        while (Transaction::withTrashed()->where('reference', $reference)->exists()) {
            $count++;
            $reference = 'TXN' . date('Ymd') . str_pad($count, 3, '0', STR_PAD_LEFT);
        }
        
        Log::info('Nouvelle référence générée', [
            'reference' => $reference,
            'count_today' => $count - 1,
            'date' => Carbon::today()->format('Y-m-d')
        ]);
        
        return $reference;
    }


        public function clearFilters()
    {
        $this->searchTerm = '';
        $this->filterType = '';
        $this->filterStatut = '';
        $this->filterPersonne = '';
        $this->resetPage();
    }

    public function genererRapport()
    {
        $this->dispatch('rapportGenere', [
            'debut' => $this->dateDebut,
            'fin' => $this->dateFin,
        ]);
        session()->flash('success', 'Rapport généré avec succès !');
    }

    public function debugData()
    {
        $revenus = $this->revenus;
        $depenses = $this->depenses;
        session()->flash('debug', [
            'revenus_count' => $revenus->total(),
            'depenses_count' => $depenses->total(),
            'total_revenus' => $this->totalRevenus,
            'total_depenses' => $this->totalDepenses,
            'revenus_en_attente' => $this->revenusEnAttente,
        ]);
    }


    /**
     * Computed property pour les voyages selon le type de transaction
     */
    public function getVoyagesDisponiblesProperty()
    {
        if (!$this->type) {
            return collect();
        }

        $query = Voyage::select('id', 'reference', 'date', 'statut')
            ->with(['chargements', 'dechargements'])
            ->latest();

        if ($this->type === 'achat') {
            // Pour achat : voyages avec chargements disponibles
            $query->whereHas('chargements', function($q) {
                $q->disponibles(); // Scope ajouté dans Chargement
            });
        } elseif ($this->type === 'vente') {
            // Pour vente : voyages avec déchargements disponibles
            $query->whereHas('dechargements', function($q) {
                $q->disponibles(); // Scope ajouté dans Dechargement
            });
        }

        return $query->limit(50)->get()->filter(function($voyage) {
            if ($this->type === 'achat') {
                return $voyage->canBeUsedForAchat();
            } elseif ($this->type === 'vente') {
                return $voyage->canBeUsedForVente();
            }
            return true;
        });
    }

    /**
     * Computed property pour les chargements disponibles du voyage sélectionné
     */
    public function getChargementsDisponiblesProperty()
    {
        if (!$this->voyage_id || $this->type !== 'achat') {
            return collect();
        }

        $voyage = Voyage::find($this->voyage_id);
        if (!$voyage) {
            return collect();
        }

        return $voyage->getChargementsDisponibles()
            ->load(['depart', 'produit']);
    }


    /**
     * Computed property pour les déchargements disponibles du voyage sélectionné
     */
    public function getDechargementsDisponiblesProperty()
    {
        if (!$this->voyage_id || $this->type !== 'vente') {
            return collect();
        }

        $voyage = Voyage::find($this->voyage_id);
        if (!$voyage) {
            return collect();
        }

        return $voyage->getDechargementsDisponibles()
            ->load(['lieuLivraison', 'chargement.produit']);
    }


    // =====================================================
    // MÉTHODE DE RENDU PRINCIPALE
    // =====================================================
    public function render()
    {
        Log::info('Rendering FinanceIndex', ['activeTab' => $this->activeTab]);
        
        // Statistiques générales
        $totalEntrees = $this->totalEntrees;
        $totalSorties = $this->totalSorties;
        $beneficeNet = $this->beneficeNet;
        $transactionsEnAttente = $this->transactionsEnAttente;
        $revenusEnAttente = $this->revenusEnAttente;
        
        // Transactions principales
        $query = Transaction::with(['voyage'])
            ->when($this->searchTerm, function ($q) {
                $q->where(function ($subQ) {
                    $subQ->where('reference', 'like', '%' . $this->searchTerm . '%')
                        ->orWhere('objet', 'like', '%' . $this->searchTerm . '%');
                });
            })
            ->when($this->filterType, fn($q) => $q->where('type', $this->filterType))
            ->when($this->filterStatut, fn($q) => $q->where('statut', $this->filterStatut))
            ->when($this->filterPersonne, function ($q) {
                $q->where(function ($subQ) {
                    $subQ->where('from_nom', 'like', '%' . $this->filterPersonne . '%')
                        ->orWhere('to_nom', 'like', '%' . $this->filterPersonne . '%');
                });
            })
            ->when($this->dateDebut && $this->dateFin, fn($q) => $q->whereBetween('date', [$this->dateDebut, $this->dateFin]))
            ->orderBy('date', 'desc');
        
        $transactions = $query->paginate(15);
        $comptes = Compte::where('actif', true)->get();
        
        // ✅ UTILISATION DES NOUVELLES PROPRIÉTÉS COMPUTED
        $voyagesDisponibles = $this->voyagesDisponibles;
        $chargementsDisponibles = $this->chargementsDisponibles;
        $dechargementsDisponibles = $this->dechargementsDisponibles;
        
        // ANCIEN CODE REMPLACÉ :
        // $voyages = Voyage::select('id', 'reference')->latest()->limit(50)->get();
        // $dechargements = Dechargement::with(['lieuLivraison', 'chargement.produit'])...
        // $chargements = Chargement::with(['depart', 'produit'])...
        
        $users = collect();
        
        // Statistiques pour le dashboard
        $repartitionParType = $this->repartitionParType;
        $repartitionParStatut = $this->repartitionParStatut;
        $transactionsVoyage = $this->transactionsVoyage;
        $transactionsAutre = $this->transactionsAutre;
        
        // Revenus et dépenses
        $revenus = $this->revenus;
        $depenses = $this->depenses;
        $totalRevenus = $this->totalRevenus;
        $revenuMoyen = $this->revenuMoyen;
        $nombreRevenus = $this->nombreRevenus;
        $totalDepenses = $this->totalDepenses;
        $depenseMoyenne = $this->depenseMoyenne;
        $depensesEnAttente = $this->depensesEnAttente;
        $nombreDepenses = $this->nombreDepenses;
        $repartitionDepenses = $this->repartitionDepenses;
        $repartitionRevenus = $this->repartitionRevenus;

        return view('livewire.finance.finance-index', compact(
            'transactions',
            'comptes',
            'voyagesDisponibles',      // ✅ NOUVEAU
            'chargementsDisponibles',  // ✅ NOUVEAU  
            'dechargementsDisponibles', // ✅ NOUVEAU
            'users',
            'totalEntrees',
            'totalSorties',
            'beneficeNet',  
            'transactionsEnAttente',
            'repartitionParType',
            'repartitionParStatut',
            'transactionsVoyage',
            'transactionsAutre',
            'revenus',
            'depenses',
            'totalRevenus',
            'revenuMoyen',
            'nombreRevenus',
            'revenusEnAttente',
            'totalDepenses',
            'depenseMoyenne',
            'depensesEnAttente',
            'nombreDepenses',
            'repartitionDepenses',
            'repartitionRevenus'
        ));
    }
}
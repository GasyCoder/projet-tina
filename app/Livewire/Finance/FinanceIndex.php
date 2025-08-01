<?php

namespace App\Livewire\Finance;

use Carbon\Carbon;
use App\Models\Compte;
use App\Models\Voyage;
use App\Models\Produit;
use Livewire\Component;
use App\Models\Transaction;
use App\Models\Dechargement;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FinanceIndex extends Component
{
    use WithPagination;

    // Propriétés de l'interface
    public $activeTab = 'suivi';
    public $stock_actuel = 0;

    // Filtres de base
    public $searchTerm = '';
    public $filterType = '';
    public $filterStatut = '';
    public $dateDebut = '';
    public $dateFin = '';
    public $filterPersonne = '';

    // Filtres avancés
    public $typeSuivi = 'tous';
    public $periodeRevenus = 'mois';
    public $dateDebutRevenus = '';
    public $dateFinRevenus = '';
    public $categorieDepense = '';
    public $periodeDepenses = 'mois';
    public $dateDebutDepenses = '';
    public $dateFinDepenses = '';

    // Formulaire transaction
    public $showTransactionModal = false;
    public $editingTransaction = null;
    public $reference = '';
    public $date = '';
    public $type = '';
    public $from_nom = '';
    public $to_nom = '';
    public $to_compte = '';
    public $montant_mga = '';
    public $objet = '';
    public $voyage_id = '';
    public $dechargement_ids = [];
    public $produit_id = '';
    public $mode_paiement = 'especes';
    public $statut = 'confirme';
    public $quantite = '';
    public $unite = '';
    public $prix_unitaire_mga = '';
    public $reste_a_payer = '';
    public $observation = '';
    public $lieux_display = '';

    // Règles de validation
    protected $rules = [
        'reference' => 'required|string|max:255',
        'date' => 'required|date',
        'montant_mga' => 'required|numeric|min:0',
        'objet' => 'nullable|string|max:255',
        'mode_paiement' => 'required|in:especes,AirtelMoney,MVola,OrangeMoney,banque',
        'statut' => 'required|in:attente,confirme,partiellement_payee',
        'type' => 'required|in:achat,vente,autre',
        'voyage_id' => 'nullable|exists:voyages,id',
        'produit_id' => 'required_if:type,achat|nullable|exists:produits,id',
        'quantite' => 'required_if:type,achat|nullable|numeric|min:0',
        'prix_unitaire_mga' => 'required_if:type,achat|nullable|numeric|min:0',
        'reste_a_payer' => 'required_if:statut,partiellement_payee',
    ];

    // Hooks Livewire pour protection des arrays
    public function hydrate()
    {
        if (!is_array($this->dechargement_ids)) {
            $this->dechargement_ids = [];
        }
    }

    public function dehydrate()
    {
        if (!is_array($this->dechargement_ids)) {
            $this->dechargement_ids = [];
        }
    }

    // Computed Properties
    public function getVoyagesDisponiblesProperty()
    {
        if ($this->type !== 'vente') {
            return collect();
        }

        return Voyage::select('id', 'reference', 'date', 'statut')
            ->with(['dechargements'])
            ->whereHas('dechargements')
            ->latest()
            ->limit(50)
            ->get();
    }

    public function getDechargementsDisponiblesProperty()
    {
        if (!$this->voyage_id || $this->type !== 'vente') {
            return collect();
        }

        return Dechargement::with(['lieuLivraison', 'chargement.produit'])
            ->where('voyage_id', $this->voyage_id)
            ->get();
    }

    public function getProduitsDisponiblesProperty()
    {
        return Produit::actif()->get();
    }

    public function getProduitSelectionneProperty()
    {
        if (!$this->produit_id || !in_array($this->type, ['achat', 'vente'])) {
            $this->stock_actuel = 0;
            return null;
        }

        $produit = Produit::find($this->produit_id);
        if ($produit) {
            if ($this->type === 'vente') {
                $this->stock_actuel = $produit->qte_variable;
            } else {
                $this->stock_actuel = max(0, $produit->poids_moyen_sac_kg_max - $produit->qte_variable);
            }

            Log::info('Produit sélectionné', [
                'produit_id' => $this->produit_id,
                'nom' => $produit->nom_complet,
                'type_transaction' => $this->type,
                'stock_reel' => $produit->qte_variable,
                'capacite_max' => $produit->poids_moyen_sac_kg_max,
                'stock_actuel_affiche' => $this->stock_actuel,
            ]);
        } else {
            $this->stock_actuel = 0;
            Log::warning('Produit non trouvé', ['produit_id' => $this->produit_id]);
        }

        return $produit;
    }

    // Propriétés calculées - Statistiques générales
    public function getTotalEntreesProperty()
    {
        return Transaction::where('type', 'vente')
            ->where('statut', '!=', 'annule')
            ->whereBetween('date', [$this->dateDebut, $this->dateFin])
            ->sum('montant_mga');
    }

    public function getTotalSortiesProperty()
    {
        return Transaction::whereIn('type', ['achat', 'autre'])
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

    // Propriétés calculées - Revenus
    public function getRevenusProperty()
    {
        $dates = $this->getDateRangeRevenus();
        return Transaction::with(['voyage'])
            ->where('type', 'vente')
            ->whereBetween('date', [$dates['debut'], $dates['fin']])
            ->where('statut', '!=', 'annule')
            ->orderBy('date', 'desc')
            ->paginate(10);
    }

    public function getTotalRevenusProperty()
    {
        $dates = $this->getDateRangeRevenus();
        return Transaction::where('type', 'vente')
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
        return Transaction::where('type', 'vente')
            ->whereBetween('date', [$dates['debut'], $dates['fin']])
            ->where('statut', '!=', 'annule')
            ->count();
    }

    public function getRevenusEnAttenteProperty()
    {
        $dates = $this->getDateRangeRevenus();
        return Transaction::where('type', 'vente')
            ->where('statut', 'attente')
            ->whereBetween('date', [$dates['debut'], $dates['fin']])
            ->sum('montant_mga');
    }

    public function getRepartitionRevenusProperty()
    {
        $dates = $this->getDateRangeRevenus();
        return Transaction::selectRaw('type, COUNT(*) as count, SUM(montant_mga) as total')
            ->where('type', 'vente')
            ->whereBetween('date', [$dates['debut'], $dates['fin']])
            ->where('statut', '!=', 'annule')
            ->groupBy('type')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->type => ['count' => $item->count, 'total' => $item->total]];
            });
    }

    // Propriétés calculées - Dépenses
    public function getDepensesProperty()
    {
        $dates = $this->getDateRangeDepenses();
        $query = Transaction::with(['voyage'])
            ->whereIn('type', ['achat', 'autre'])
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
        $query = Transaction::whereIn('type', ['achat', 'autre'])
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
        return Transaction::whereIn('type', ['achat', 'autre'])
            ->where('statut', 'attente')
            ->whereBetween('date', [$dates['debut'], $dates['fin']])
            ->sum('montant_mga');
    }

    public function getNombreDepensesProperty()
    {
        $dates = $this->getDateRangeDepenses();
        $query = Transaction::whereIn('type', ['achat', 'autre'])
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
            ->whereIn('type', ['achat', 'autre'])
            ->whereBetween('date', [$dates['debut'], $dates['fin']])
            ->where('statut', '!=', 'annule')
            ->groupBy('type')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->type => ['count' => $item->count, 'total' => $item->total]];
            });
    }

    // Propriétés calculées - Suivi conditionnel
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

    // Méthodes utilitaires pour les dates
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

    // Listeners pour auto-complétion
    public function updatedDechargementIds()
    {
        if (!is_array($this->dechargement_ids)) {
            if (is_numeric($this->dechargement_ids)) {
                $this->dechargement_ids = [$this->dechargement_ids];
            } else {
                $this->dechargement_ids = [];
            }
        }

        $this->dechargement_ids = array_values(array_filter($this->dechargement_ids, 'is_numeric'));

        Log::info('=== DEBUT updatedDechargementIds ===', ['type' => $this->type, 'dechargement_ids' => $this->dechargement_ids]);

        if ($this->type === 'vente' && !empty($this->dechargement_ids)) {
            $dechargements = Dechargement::with(['lieuLivraison', 'chargement.produit'])->whereIn('id', $this->dechargement_ids)->get();

            Log::info('Dechargements trouvés', ['count' => $dechargements->count()]);

            $this->montant_mga = $dechargements->sum('montant_total_mga');

            Log::info('Calcul montant vente', ['montant' => $this->montant_mga]);

            $interlocuteurs = [];
            $lieux = [];

            foreach ($dechargements as $dechargement) {
                if ($dechargement->interlocuteur_nom) {
                    $interlocuteurs[] = $dechargement->interlocuteur_nom;
                }

                $lieuNom = $dechargement->lieuLivraison->nom ?? 'LIEU_NULL';
                $lieux[] = $lieuNom;
            }

            $interlocuteurs = array_unique(array_filter($interlocuteurs));
            $lieux = array_unique(array_filter($lieux));

            if (!$this->editingTransaction) {
                $this->from_nom = implode(', ', $interlocuteurs);
            }
            $this->lieux_display = implode(', ', $lieux);

            $this->objet = 'Vente - ' . $this->lieux_display;

            Log::info('CORRECTION appliquée', [
                'from_nom' => $this->from_nom,
                'lieux_display' => $this->lieux_display,
                'to_nom' => $this->to_nom,
                'objet' => $this->objet
            ]);
        } else {
            if ($this->type === 'vente') {
                Log::info('RESET lieux_display pour vente');
                $this->montant_mga = '';
                $this->lieux_display = '';
                $this->objet = '';
                if (!$this->editingTransaction) {
                    $this->from_nom = '';
                    $this->to_nom = '';
                }
            }
        }

        Log::info('=== FIN updatedDechargementIds ===', [
            'final_from_nom' => $this->from_nom,
            'final_lieux_display' => $this->lieux_display,
            'final_objet' => $this->objet
        ]);
    }

    public function updatedProduitId()
    {
        $this->resetErrorBag(['produit_id', 'quantite']);
        if (in_array($this->type, ['achat', 'vente']) && $this->produit_id) {
            $produit = Produit::find($this->produit_id);
            if ($produit) {
                $this->unite = $produit->unite;
                $this->prix_unitaire_mga = $produit->prix_reference_mga;

                if ($this->type === 'vente') {
                    $this->stock_actuel = $produit->qte_variable ?? 0;
                } else {
                    $this->stock_actuel = $produit->poids_moyen_sac_kg_max ?? 0;
                }

                if (!$this->editingTransaction) {
                    $this->objet = ($this->type === 'achat' ? 'Achat - ' : 'Vente - ') . $produit->nom_complet;
                }

                Log::info('Produit sélectionné pour ' . $this->type, [
                    'produit_id' => $this->produit_id,
                    'nom' => $produit->nom_complet,
                    'prix_ref' => $produit->prix_reference_mga,
                    'unite' => $produit->unite,
                    'stock_actuel_vente' => $produit->qte_variable,
                    'capacite_max_achat' => $produit->poids_moyen_sac_kg_max,
                    'stock_utilise' => $this->stock_actuel,
                ]);
            } else {
                $this->stock_actuel = 0;
                $this->unite = '';
                $this->prix_unitaire_mga = '';
                $this->quantite = '';
                if (!$this->editingTransaction) {
                    $this->objet = '';
                }
            }
        } else {
            if (in_array($this->type, ['achat', 'vente'])) {
                $this->unite = '';
                $this->prix_unitaire_mga = '';
                $this->quantite = '';
                $this->stock_actuel = 0;
                if (!$this->editingTransaction) {
                    $this->objet = '';
                }
            }
        }
    }

    public function updatedQuantite()
    {
        Log::info('=== VALIDATION QUANTITE ===', [
            'type' => $this->type,
            'produit_id' => $this->produit_id,
            'quantite' => $this->quantite,
            'stock_actuel' => $this->stock_actuel
        ]);

        if (in_array($this->type, ['achat', 'vente']) && $this->produit_id && $this->quantite) {
            $produit = Produit::find($this->produit_id);
            if ($produit) {
                $quantiteSaisie = floatval($this->quantite);

                if ($this->type === 'vente') {
                    $stockDisponible = floatval($produit->qte_variable);
                    $this->stock_actuel = $stockDisponible;

                    Log::info('Validation stock pour vente', [
                        'quantite_saisie' => $quantiteSaisie,
                        'stock_disponible' => $stockDisponible,
                        'produit_nom' => $produit->nom_complet
                    ]);

                    if ($quantiteSaisie > $stockDisponible) {
                        $message = 'La quantité à vendre (' . number_format($quantiteSaisie, 2, ',', ' ') . ' ' . $this->unite . ') ne peut pas dépasser le stock disponible (' . number_format($stockDisponible, 2, ',', ' ') . ' ' . $this->unite . ').';
                        $this->addError('quantite', $message);

                        Log::warning('Stock insuffisant pour vente', [
                            'quantite_demandee' => $quantiteSaisie,
                            'stock_disponible' => $stockDisponible,
                            'message' => $message
                        ]);
                    } else {
                        $this->resetErrorBag('quantite');
                        if ($this->prix_unitaire_mga) {
                            $this->montant_mga = $quantiteSaisie * floatval($this->prix_unitaire_mga);
                        }

                        Log::info('Validation stock OK pour vente', [
                            'quantite_valide' => $quantiteSaisie,
                            'stock_disponible' => $stockDisponible,
                            'montant_calcule' => $this->montant_mga
                        ]);
                    }
                } elseif ($this->type === 'achat') {
                    $capaciteMax = floatval($produit->poids_moyen_sac_kg_max);
                    $stockActuel = floatval($produit->qte_variable);
                    $capaciteDisponible = $capaciteMax - $stockActuel;
                    $this->stock_actuel = $capaciteDisponible;

                    Log::info('Validation capacité pour achat', [
                        'quantite_saisie' => $quantiteSaisie,
                        'capacite_max' => $capaciteMax,
                        'stock_actuel' => $stockActuel,
                        'capacite_disponible' => $capaciteDisponible,
                        'produit_nom' => $produit->nom_complet
                    ]);

                    if ($quantiteSaisie > $capaciteDisponible) {
                        $message = 'La quantité à acheter (' . number_format($quantiteSaisie, 2, ',', ' ') . ' ' . $this->unite . ') dépasse la capacité de stockage disponible (' . number_format($capaciteDisponible, 2, ',', ' ') . ' ' . $this->unite . '). Capacité max: ' . number_format($capaciteMax, 2, ',', ' ') . ' ' . $this->unite . ', Stock actuel: ' . number_format($stockActuel, 2, ',', ' ') . ' ' . $this->unite . '.';
                        $this->addError('quantite', $message);

                        Log::warning('Capacité de stockage dépassée pour achat', [
                            'quantite_demandee' => $quantiteSaisie,
                            'capacite_disponible' => $capaciteDisponible,
                            'capacite_max' => $capaciteMax,
                            'stock_actuel' => $stockActuel,
                            'message' => $message
                        ]);
                    } else {
                        $this->resetErrorBag('quantite');
                        if ($this->prix_unitaire_mga) {
                            $this->montant_mga = $quantiteSaisie * floatval($this->prix_unitaire_mga);
                        }

                        Log::info('Validation capacité OK pour achat', [
                            'quantite_valide' => $quantiteSaisie,
                            'capacite_disponible' => $capaciteDisponible,
                            'montant_calcule' => $this->montant_mga
                        ]);
                    }
                }
            }
        } elseif (in_array($this->type, ['achat', 'vente']) && $this->quantite && $this->prix_unitaire_mga) {
            $this->montant_mga = floatval($this->quantite) * floatval($this->prix_unitaire_mga);
            $this->resetErrorBag('quantite');
        }
    }

    public function updatedPrixUnitaireMga()
    {
        if (in_array($this->type, ['achat', 'vente']) && $this->quantite && $this->prix_unitaire_mga) {
            $this->montant_mga = $this->quantite * $this->prix_unitaire_mga;
        }
    }

    public function updatedVoyageId()
    {
        if ($this->editingTransaction) {
            Log::info('Mode édition : voyage changé mais pas de reset automatique');
            return;
        }

        if ($this->type === 'vente') {
            $this->dechargement_ids = [];
            $this->montant_mga = '';
            $this->lieux_display = '';
            $this->objet = '';
            $this->from_nom = '';
            $this->to_nom = '';
        }
    }

    public function updatedType()
    {
        if ($this->type === 'vente') {
            $this->voyage_id = '';
            $this->dechargement_ids = [];
            $this->lieux_display = '';
        } elseif ($this->type === 'achat') {
            $this->voyage_id = '';
            $this->dechargement_ids = [];
            $this->lieux_display = '';
        } else {
            $this->voyage_id = '';
            $this->dechargement_ids = [];
            $this->lieux_display = '';
            $this->produit_id = '';
        }

        $this->montant_mga = '';
        $this->objet = '';
        $this->from_nom = '';
        $this->to_nom = '';
        $this->quantite = '';
        $this->unite = '';
        $this->prix_unitaire_mga = '';
        $this->stock_actuel = 0;

        Log::info('Type updated', ['new_type' => $this->type]);
    }

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

    public function recalculerMontant()
    {
        if ($this->type === 'vente') {
            $this->updatedDechargementIds();
        }
    }

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
        Log::info('=== DÉBUT ÉDITION TRANSACTION ===', [
            'transaction_id' => $transaction->id,
            'type' => $transaction->type,
            'montant' => $transaction->montant_mga,
            'voyage_id' => $transaction->voyage_id
        ]);

        $this->editingTransaction = $transaction;
        $this->reference = $transaction->reference;
        $this->date = $transaction->date->format('Y-m-d');
        $this->type = $transaction->type;
        $this->from_nom = $transaction->from_nom;
        $this->to_nom = $transaction->to_nom;
        $this->to_compte = $transaction->to_compte;
        $this->montant_mga = $transaction->montant_mga;
        $this->objet = $transaction->objet;
        $this->voyage_id = $transaction->voyage_id;
        $this->produit_id = $transaction->produit_id;

        $this->dechargement_ids = [];

        if ($transaction->type === 'vente' && $transaction->voyage_id) {
            $this->dechargement_ids = $this->findAllMatchingDechargements($transaction);

            if (!empty($this->dechargement_ids)) {
                $originalFromNom = $transaction->from_nom;
                $originalToNom = $transaction->to_nom;

                $this->updatedDechargementIds();

                if ($originalFromNom) {
                    $this->from_nom = $originalFromNom;
                }
                if ($originalToNom) {
                    $this->to_nom = $originalToNom;
                }
            }
        }

        $this->mode_paiement = $transaction->mode_paiement;
        $this->statut = $transaction->statut;
        $this->quantite = $transaction->quantite;
        $this->unite = $transaction->unite;
        $this->prix_unitaire_mga = $transaction->prix_unitaire_mga;
        $this->reste_a_payer = $transaction->reste_a_payer;
        $this->observation = $transaction->observation;
        $this->showTransactionModal = true;

        Log::info('=== FIN ÉDITION TRANSACTION ===', [
            'dechargement_ids_final' => $this->dechargement_ids,
            'from_nom_final' => $this->from_nom,
            'to_nom_final' => $this->to_nom,
            'montant_final' => $this->montant_mga
        ]);
    }

    private function findAllMatchingDechargements($transaction)
    {
        if (!$transaction->voyage_id) {
            return [];
        }

        $montantTarget = floatval($transaction->montant_mga);
        $tolerance = 5000;

        $dechargements = Dechargement::where('voyage_id', $transaction->voyage_id)
            ->orderBy('created_at')
            ->get();

        $selected = [];
        $montantActuel = 0;

        foreach ($dechargements as $dechargement) {
            $nouveauMontant = $montantActuel + floatval($dechargement->montant_total_mga);

            if ($nouveauMontant <= $montantTarget + $tolerance) {
                $selected[] = intval($dechargement->id);
                $montantActuel = $nouveauMontant;
            }

            if (abs($montantActuel - $montantTarget) <= $tolerance) {
                break;
            }
        }

        if (empty($selected) || abs($montantActuel - $montantTarget) > $tolerance) {
            $bestMatch = $dechargements
                ->where('montant_total_mga', '<=', $montantTarget + $tolerance)
                ->sortByDesc('montant_total_mga')
                ->first();

            if ($bestMatch) {
                $selected = [intval($bestMatch->id)];
                $montantActuel = floatval($bestMatch->montant_total_mga);
            } else {
                $firstDechargement = $dechargements->first();
                if ($firstDechargement) {
                    $selected = [intval($firstDechargement->id)];
                    $montantActuel = floatval($firstDechargement->montant_total_mga);
                }
            }
        }

        $selected = array_values(array_map('intval', array_filter($selected, 'is_numeric')));

        Log::info('Déchargements trouvés pour édition', [
            'selected_ids' => $selected,
            'selected_count' => count($selected),
            'montant_total' => $montantActuel,
            'correspondance' => abs($montantActuel - $montantTarget) <= $tolerance ? 'EXACTE' : 'APPROXIMATIVE'
        ]);

        return $selected;
    }

    public function saveTransaction()
    {
        $this->validate();

        $data = [
            'reference' => $this->reference,
            'date' => $this->date,
            'type' => $this->type,
            'from_nom' => $this->from_nom ?: null,
            'to_nom' => $this->to_nom ?: null,
            'to_compte' => $this->to_compte ?: null,
            'montant_mga' => $this->montant_mga,
            'objet' => $this->objet,
            'voyage_id' => $this->voyage_id ?: null,
            'dechargement_id' => !empty($this->dechargement_ids) ? $this->dechargement_ids[0] : null,
            'produit_id' => $this->produit_id ?: null,
            'mode_paiement' => $this->mode_paiement,
            'statut' => $this->statut,
            'quantite' => $this->quantite ?: null,
            'unite' => $this->unite ?: null,
            'prix_unitaire_mga' => $this->prix_unitaire_mga ?: null,
            'reste_a_payer' => $this->statut === 'partiellement_payee' ? ($this->reste_a_payer ?: 0) : 0,
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

    public function closeTransactionModal()
    {
        Log::info('closeTransactionModal called');
        $this->showTransactionModal = false;
        $this->resetTransactionForm();
        $this->editingTransaction = null;
        $this->dispatch('close-transaction-modal');
    }

    private function resetTransactionForm()
    {
        $this->reference = '';
        $this->date = '';
        $this->type = '';
        $this->from_nom = '';
        $this->to_nom = '';
        $this->to_compte = '';
        $this->montant_mga = '';
        $this->objet = '';
        $this->voyage_id = '';
        $this->dechargement_ids = [];
        $this->produit_id = '';
        $this->lieux_display = '';
        $this->mode_paiement = 'especes';
        $this->statut = 'confirme';
        $this->quantite = '';
        $this->unite = '';
        $this->prix_unitaire_mga = '';
        $this->reste_a_payer = '';
        $this->observation = '';
        $this->stock_actuel = 0;
        $this->resetErrorBag();
    }

    private function generateTransactionReference()
    {
        $count = Transaction::withTrashed()
            ->whereDate('created_at', Carbon::today())
            ->count() + 1;

        $reference = 'TXN' . date('Ymd') . str_pad($count, 3, '0', STR_PAD_LEFT);

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

        $voyagesDisponibles = $this->voyagesDisponibles;
        $dechargementsDisponibles = $this->dechargementsDisponibles;
        $produitsDisponibles = $this->produitsDisponibles;
        $produitSelectionne = $this->produitSelectionne;

        $users = collect();
        $repartitionParType = $this->repartitionParType;
        $repartitionParStatut = $this->repartitionParStatut;
        $transactionsVoyage = $this->transactionsVoyage;
        $transactionsAutre = $this->transactionsAutre;
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
            'voyagesDisponibles',
            'dechargementsDisponibles',
            'produitsDisponibles',
            'produitSelectionne',
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
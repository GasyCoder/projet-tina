<?php

// Composant 1: Vente Enhanced
// app/Livewire/Stocks/VenteEnhanced.php

namespace App\Livewire\Stocks;

use App\Models\Vente;
use App\Models\Produit;
use App\Models\Lieu;
use App\Models\Chargement;
use App\Models\AlerteStock;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\TransfertDetail;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;




class DashboardStock extends Component
{
    // Période d'analyse
    public $periode = 'mois'; // jour, semaine, mois, trimestre, annee
    public $dateDebut = '';
    public $dateFin = '';

    // Filtres
    public $filterDepot = '';
    public $filterProduit = '';
    public $filterProprietaire = '';

    // Métriques principales
    public $metriques = [];
    public $tendances = [];
    public $alertes = [];
    public $indicateursPerformance = [];

    // Graphiques et données
    public $chartMouvements = [];
    public $chartValeurStock = [];
    public $chartRotationStock = [];
    public $chartAlertesEvolution = [];

    // Rapports rapides
    public $stocksCritiques = [];
    public $mouvementsRecents = [];
    public $topProduits = [];
    public $performanceDepots = [];

    protected $listeners = [
        'refreshDashboard' => '$refresh',
        'periodeChanged' => 'changePeriode'
    ];

    public function mount()
    {
        $this->initializeDates();
        $this->calculateMetriques();
        $this->loadChartData();
        $this->loadRapportsRapides();
    }

    public function changePeriode($nouvellePeriode)
    {
        $this->periode = $nouvellePeriode;
        $this->initializeDates();
        $this->calculateMetriques();
        $this->loadChartData();
    }

    public function updatedFilterDepot()
    {
        $this->calculateMetriques();
        $this->loadRapportsRapides();
    }

    public function updatedFilterProduit()
    {
        $this->calculateMetriques();
        $this->loadRapportsRapides();
    }

    private function initializeDates()
    {
        switch ($this->periode) {
            case 'jour':
                $this->dateDebut = today()->format('Y-m-d');
                $this->dateFin = today()->format('Y-m-d');
                break;
            case 'semaine':
                $this->dateDebut = now()->startOfWeek()->format('Y-m-d');
                $this->dateFin = now()->endOfWeek()->format('Y-m-d');
                break;
            case 'mois':
                $this->dateDebut = now()->startOfMonth()->format('Y-m-d');
                $this->dateFin = now()->endOfMonth()->format('Y-m-d');
                break;
            case 'trimestre':
                $this->dateDebut = now()->startOfQuarter()->format('Y-m-d');
                $this->dateFin = now()->endOfQuarter()->format('Y-m-d');
                break;
            case 'annee':
                $this->dateDebut = now()->startOfYear()->format('Y-m-d');
                $this->dateFin = now()->endOfYear()->format('Y-m-d');
                break;
        }
    }

    private function calculateMetriques()
    {
        $query = $this->getBaseQuery();

        $this->metriques = [
            // Métriques de stock
            'valeur_stock_total' => $this->calculerValeurStockTotal($query),
            'quantite_stock_total' => $this->calculerQuantiteStockTotal($query),
            'nb_produits_actifs' => $this->calculerNombreProduits($query),
            'nb_depots_actifs' => $this->calculerNombreDepots($query),
            
            // Métriques de mouvement
            'nb_entrees' => $this->calculerNombreEntrees($query),
            'nb_sorties' => $this->calculerNombreSorties($query),
            'valeur_entrees' => $this->calculerValeurEntrees($query),
            'valeur_sorties' => $this->calculerValeurSorties($query),
            
            // Métriques de vente
            'nb_ventes' => $this->calculerNombreVentes(),
            'ca_ventes' => $this->calculerChiffreAffaires(),
            'marge_moyenne' => $this->calculerMargeMovenne(),
            
            // Métriques de transfert
            'nb_transferts' => $this->calculerNombreTransferts(),
            'cout_transport' => $this->calculerCoutTransport(),
            'taux_reussite_transferts' => $this->calculerTauxReussiteTransferts(),
            
            // Métriques de qualité
            'nb_retours' => $this->calculerNombreRetours(),
            'taux_retour' => $this->calculerTauxRetour(),
            'perte_qualite' => $this->calculerPerteQualite(),
            
            // Alertes
            'nb_alertes_actives' => $this->calculerNombreAlertes(),
            'stocks_critiques' => $this->calculerStocksCritiques(),
            'peremptions_proches' => $this->calculerPeremptionsProches()
        ];

        $this->calculerTendances();
        $this->calculerIndicateursPerformance();
        $this->loadAlertes();
    }

    private function getBaseQuery()
    {
        $query = DepotStock::whereBetween('date_mouvement', [$this->dateDebut, $this->dateFin]);
        
        if ($this->filterDepot) {
            $query->where('depot_id', $this->filterDepot);
        }
        
        if ($this->filterProduit) {
            $query->where('produit_id', $this->filterProduit);
        }
        
        if ($this->filterProprietaire) {
            $query->where('proprietaire_id', $this->filterProprietaire);
        }
        
        return $query;
    }

    private function calculerValeurStockTotal($query)
    {
        return (clone $query)->where('statut_stock', 'actif')->sum('valeur_stock_mga');
    }

    private function calculerQuantiteStockTotal($query)
    {
        return (clone $query)->where('statut_stock', 'actif')->sum('quantite_kg');
    }

    private function calculerNombreProduits($query)
    {
        return (clone $query)->where('statut_stock', 'actif')->distinct('produit_id')->count();
    }

    private function calculerNombreDepots($query)
    {
        return (clone $query)->where('statut_stock', 'actif')->distinct('depot_id')->count();
    }

    private function calculerNombreEntrees($query)
    {
        return (clone $query)->where('type_mouvement', 'entree')->count();
    }

    private function calculerNombreSorties($query)
    {
        return (clone $query)->where('type_mouvement', 'sortie')->count();
    }

    private function calculerValeurEntrees($query)
    {
        return (clone $query)->where('type_mouvement', 'entree')->sum('valeur_stock_mga');
    }

    private function calculerValeurSorties($query)
    {
        return (clone $query)->where('type_mouvement', 'sortie')->sum('valeur_stock_mga');
    }

    private function calculerNombreVentes()
    {
        return Vente::whereBetween('date_vente', [$this->dateDebut, $this->dateFin])
            ->when($this->filterDepot, fn($q) => $q->where('lieu_livraison_id', $this->filterDepot))
            ->when($this->filterProduit, fn($q) => $q->where('produit_id', $this->filterProduit))
            ->count();
    }

    private function calculerChiffreAffaires()
    {
        return Vente::whereBetween('date_vente', [$this->dateDebut, $this->dateFin])
            ->when($this->filterDepot, fn($q) => $q->where('lieu_livraison_id', $this->filterDepot))
            ->when($this->filterProduit, fn($q) => $q->where('produit_id', $this->filterProduit))
            ->sum('prix_total_mga');
    }

    private function calculerMargeMovenne()
    {
        $ventes = Vente::with('chargement')
            ->whereBetween('date_vente', [$this->dateDebut, $this->dateFin])
            ->when($this->filterDepot, fn($q) => $q->where('lieu_livraison_id', $this->filterDepot))
            ->when($this->filterProduit, fn($q) => $q->where('produit_id', $this->filterProduit))
            ->get();

        $margeTotal = $ventes->sum(fn($v) => $v->benefice_estimee);
        $caTotal = $ventes->sum('prix_total_mga');
        
        return $caTotal > 0 ? ($margeTotal / $caTotal) * 100 : 0;
    }

    private function calculerNombreTransferts()
    {
        return Transfert::whereBetween('date_creation', [$this->dateDebut, $this->dateFin])
            ->when($this->filterDepot, function($q) {
                $q->where('depot_origine_id', $this->filterDepot)
                  ->orWhere('depot_destination_id', $this->filterDepot);
            })
            ->count();
    }

    private function calculerCoutTransport()
    {
        return Transfert::whereBetween('date_creation', [$this->dateDebut, $this->dateFin])
            ->when($this->filterDepot, function($q) {
                $q->where('depot_origine_id', $this->filterDepot)
                  ->orWhere('depot_destination_id', $this->filterDepot);
            })
            ->sum('cout_transport_reel_mga');
    }

    private function calculerTauxReussiteTransferts()
    {
        $total = $this->calculerNombreTransferts();
        $reussis = Transfert::whereBetween('date_creation', [$this->dateDebut, $this->dateFin])
            ->when($this->filterDepot, function($q) {
                $q->where('depot_origine_id', $this->filterDepot)
                  ->orWhere('depot_destination_id', $this->filterDepot);
            })
            ->where('statut_transfert', 'receptionne')
            ->count();
            
        return $total > 0 ? ($reussis / $total) * 100 : 0;
    }

    private function calculerNombreRetours()
    {
        return Retour::whereBetween('date_retour', [$this->dateDebut, $this->dateFin])
            ->when($this->filterDepot, fn($q) => $q->where('lieu_stockage_id', $this->filterDepot))
            ->when($this->filterProduit, fn($q) => $q->where('produit_id', $this->filterProduit))
            ->count();
    }

    private function calculerTauxRetour()
    {
        $nbVentes = $this->calculerNombreVentes();
        $nbRetours = $this->calculerNombreRetours();
        
        return $nbVentes > 0 ? ($nbRetours / $nbVentes) * 100 : 0;
    }

    private function calculerPerteQualite()
    {
        return Retour::whereBetween('date_retour', [$this->dateDebut, $this->dateFin])
            ->when($this->filterDepot, fn($q) => $q->where('lieu_stockage_id', $this->filterDepot))
            ->when($this->filterProduit, fn($q) => $q->where('produit_id', $this->filterProduit))
            ->sum('perte_estimee_mga');
    }

    private function calculerNombreAlertes()
    {
        return AlerteStock::actives()
            ->when($this->filterDepot, fn($q) => $q->where('depot_id', $this->filterDepot))
            ->when($this->filterProduit, fn($q) => $q->where('produit_id', $this->filterProduit))
            ->count();
    }

    private function calculerStocksCritiques()
    {
        return DepotStock::stockBas()
            ->when($this->filterDepot, fn($q) => $q->where('depot_id', $this->filterDepot))
            ->when($this->filterProduit, fn($q) => $q->where('produit_id', $this->filterProduit))
            ->count();
    }

    private function calculerPeremptionsProches()
    {
        return DepotStock::peremptionProche(30)
            ->when($this->filterDepot, fn($q) => $q->where('depot_id', $this->filterDepot))
            ->when($this->filterProduit, fn($q) => $q->where('produit_id', $this->filterProduit))
            ->count();
    }

    private function calculerTendances()
    {
        // Calculer les tendances par rapport à la période précédente
        $periodeComparaison = $this->getPeriodeComparaison();
        
        $this->tendances = [
            'valeur_stock' => $this->calculerTendance('valeur_stock_total', $periodeComparaison),
            'nb_ventes' => $this->calculerTendance('nb_ventes', $periodeComparaison),
            'ca_ventes' => $this->calculerTendance('ca_ventes', $periodeComparaison),
            'nb_alertes' => $this->calculerTendance('nb_alertes_actives', $periodeComparaison),
            'taux_retour' => $this->calculerTendance('taux_retour', $periodeComparaison)
        ];
    }

    private function getPeriodeComparaison()
    {
        $diff = Carbon::parse($this->dateFin)->diffInDays(Carbon::parse($this->dateDebut)) + 1;
        $dateFinComparaison = Carbon::parse($this->dateDebut)->subDay();
        $dateDebutComparaison = $dateFinComparaison->copy()->subDays($diff - 1);
        
        return [$dateDebutComparaison->format('Y-m-d'), $dateFinComparaison->format('Y-m-d')];
    }

    private function calculerTendance($metrique, $periodeComparaison)
    {
        // Sauvegarder les dates actuelles
        $dateDebutActuelle = $this->dateDebut;
        $dateFinActuelle = $this->dateFin;
        
        // Calculer la métrique pour la période de comparaison
        $this->dateDebut = $periodeComparaison[0];
        $this->dateFin = $periodeComparaison[1];
        
        $valeurComparaison = match($metrique) {
            'valeur_stock_total' => $this->calculerValeurStockTotal($this->getBaseQuery()),
            'nb_ventes' => $this->calculerNombreVentes(),
            'ca_ventes' => $this->calculerChiffreAffaires(),
            'nb_alertes_actives' => $this->calculerNombreAlertes(),
            'taux_retour' => $this->calculerTauxRetour(),
            default => 0
        };
        
        // Restaurer les dates actuelles
        $this->dateDebut = $dateDebutActuelle;
        $this->dateFin = $dateFinActuelle;
        
        $valeurActuelle = $this->metriques[$metrique];
        
        if ($valeurComparaison == 0) {
            return $valeurActuelle > 0 ? 100 : 0;
        }
        
        return (($valeurActuelle - $valeurComparaison) / $valeurComparaison) * 100;
    }

    private function calculerIndicateursPerformance()
    {
        $this->indicateursPerformance = [
            'rotation_stock' => $this->calculerRotationStock(),
            'couverture_stock' => $this->calculerCouvertureStock(),
            'efficacite_transport' => $this->calculerEfficaciteTransport(),
            'satisfaction_client' => $this->calculerSatisfactionClient(),
            'taux_disponibilite' => $this->calculerTauxDisponibilite()
        ];
    }

    private function calculerRotationStock()
    {
        $valeurSorties = $this->metriques['valeur_sorties'];
        $valeurStockMoyenne = $this->metriques['valeur_stock_total'] / 2; // Simplification
        
        return $valeurStockMoyenne > 0 ? $valeurSorties / $valeurStockMoyenne : 0;
    }

    private function calculerCouvertureStock()
    {
        $consommationMoyenneJour = $this->metriques['valeur_sorties'] / max(1, Carbon::parse($this->dateFin)->diffInDays(Carbon::parse($this->dateDebut)));
        $valeurStock = $this->metriques['valeur_stock_total'];
        
        return $consommationMoyenneJour > 0 ? $valeurStock / $consommationMoyenneJour : 0;
    }

    private function calculerEfficaciteTransport()
    {
        $coutTransport = $this->metriques['cout_transport'];
        $valeurTransferee = Transfert::whereBetween('date_creation', [$this->dateDebut, $this->dateFin])
            ->get()->sum(fn($t) => $t->calculerValeurTotale());
            
        return $valeurTransferee > 0 ? (1 - ($coutTransport / $valeurTransferee)) * 100 : 0;
    }

    private function calculerSatisfactionClient()
    {
        return max(0, 100 - $this->metriques['taux_retour'] * 10); // Simplification
    }

    private function calculerTauxDisponibilite()
    {
        $totalProduits = Produit::count();
        $produitsEnStock = DepotStock::where('statut_stock', 'actif')
            ->where('quantite_kg', '>', 0)
            ->distinct('produit_id')
            ->count();
            
        return $totalProduits > 0 ? ($produitsEnStock / $totalProduits) * 100 : 0;
    }

    private function loadAlertes()
    {
        $this->alertes = AlerteStock::actives()
            ->with(['produit', 'depot'])
            ->when($this->filterDepot, fn($q) => $q->where('depot_id', $this->filterDepot))
            ->when($this->filterProduit, fn($q) => $q->where('produit_id', $this->filterProduit))
            ->orderBy('niveau_urgence', 'desc')
            ->orderBy('date_alerte', 'desc')
            ->limit(10)
            ->get();
    }

    private function loadChartData()
    {
        $this->chartMouvements = $this->getChartMouvements();
        $this->chartValeurStock = $this->getChartValeurStock();
        $this->chartRotationStock = $this->getChartRotationStock();
        $this->chartAlertesEvolution = $this->getChartAlertesEvolution();
    }

    private function getChartMouvements()
    {
        $mouvements = DepotStock::selectRaw('DATE(date_mouvement) as date, type_mouvement, COUNT(*) as count')
            ->whereBetween('date_mouvement', [$this->dateDebut, $this->dateFin])
            ->when($this->filterDepot, fn($q) => $q->where('depot_id', $this->filterDepot))
            ->groupBy('date', 'type_mouvement')
            ->orderBy('date')
            ->get();

        return $mouvements->groupBy('date')->map(function ($group, $date) {
            return [
                'date' => $date,
                'entrees' => $group->where('type_mouvement', 'entree')->sum('count'),
                'sorties' => $group->where('type_mouvement', 'sortie')->sum('count'),
                'transferts_in' => $group->where('type_mouvement', 'transfert_in')->sum('count'),
                'transferts_out' => $group->where('type_mouvement', 'transfert_out')->sum('count')
            ];
        })->values();
    }

    private function getChartValeurStock()
    {
        // Évolution de la valeur du stock sur la période
        $periodes = $this->genererPeriodes();
        
        return $periodes->map(function ($periode) {
            $valeur = DepotStock::where('statut_stock', 'actif')
                ->where('date_mouvement', '<=', $periode['fin'])
                ->when($this->filterDepot, fn($q) => $q->where('depot_id', $this->filterDepot))
                ->sum('valeur_stock_mga');
                
            return [
                'date' => $periode['fin'],
                'valeur' => $valeur
            ];
        });
    }

    private function getChartRotationStock()
    {
        return Produit::with(['depotStocks' => function ($query) {
                $query->whereBetween('date_mouvement', [$this->dateDebut, $this->dateFin])
                      ->when($this->filterDepot, fn($q) => $q->where('depot_id', $this->filterDepot));
            }])
            ->get()
            ->map(function ($produit) {
                $entrees = $produit->depotStocks->where('type_mouvement', 'entree')->sum('quantite_kg');
                $sorties = $produit->depotStocks->where('type_mouvement', 'sortie')->sum('quantite_kg');
                $stockMoyen = $produit->depotStocks->avg('quantite_kg') ?: 1;
                
                return [
                    'produit' => $produit->nom,
                    'rotation' => $sorties / $stockMoyen,
                    'entrees' => $entrees,
                    'sorties' => $sorties
                ];
            })
            ->sortByDesc('rotation')
            ->take(10)
            ->values();
    }

    private function getChartAlertesEvolution()
    {
        return AlerteStock::selectRaw('DATE(date_alerte) as date, type_alerte, COUNT(*) as count')
            ->whereBetween('date_alerte', [$this->dateDebut, $this->dateFin])
            ->when($this->filterDepot, fn($q) => $q->where('depot_id', $this->filterDepot))
            ->groupBy('date', 'type_alerte')
            ->orderBy('date')
            ->get()
            ->groupBy('date')
            ->map(function ($group, $date) {
                return [
                    'date' => $date,
                    'stock_bas' => $group->where('type_alerte', 'stock_bas')->sum('count'),
                    'stock_zero' => $group->where('type_alerte', 'stock_zero')->sum('count'),
                    'peremption_proche' => $group->where('type_alerte', 'peremption_proche')->sum('count'),
                    'qualite_degradee' => $group->where('type_alerte', 'qualite_degradee')->sum('count')
                ];
            })
            ->values();
    }

    private function genererPeriodes()
    {
        $periodes = collect();
        $debut = Carbon::parse($this->dateDebut);
        $fin = Carbon::parse($this->dateFin);
        
        $interval = match($this->periode) {
            'jour' => 'PT1H', // Par heure
            'semaine' => 'P1D', // Par jour
            'mois' => 'P1W', // Par semaine
            'trimestre' => 'P1M', // Par mois
            'annee' => 'P1M', // Par mois
            default => 'P1D'
        };
        
        $current = $debut->copy();
        while ($current <= $fin) {
            $periodes->push([
                'debut' => $current->format('Y-m-d'),
                'fin' => $current->copy()->add(new \DateInterval($interval))->format('Y-m-d')
            ]);
            $current->add(new \DateInterval($interval));
        }
        
        return $periodes;
    }

    private function loadRapportsRapides()
    {
        $this->loadStocksCritiques();
        $this->loadMouvementsRecents();
        $this->loadTopProduits();
        $this->loadPerformanceDepots();
    }

    private function loadStocksCritiques()
    {
        $this->stocksCritiques = DepotStock::with(['produit', 'depot'])
            ->where(function($q) {
                $q->where('alerte_stock_bas', true)
                  ->orWhere('alerte_peremption', true)
                  ->orWhere('statut_stock', 'quarantaine');
            })
            ->when($this->filterDepot, fn($q) => $q->where('depot_id', $this->filterDepot))
            ->when($this->filterProduit, fn($q) => $q->where('produit_id', $this->filterProduit))
            ->orderBy('quantite_kg', 'asc')
            ->limit(10)
            ->get();
    }

    private function loadMouvementsRecents()
    {
        $this->mouvementsRecents = HistoriqueMouvement::with(['produit', 'depot', 'user'])
            ->whereBetween('date_operation', [$this->dateDebut, $this->dateFin])
            ->when($this->filterDepot, fn($q) => $q->where('depot_id', $this->filterDepot))
            ->when($this->filterProduit, fn($q) => $q->where('produit_id', $this->filterProduit))
            ->orderBy('created_at', 'desc')
            ->limit(15)
            ->get();
    }

    private function loadTopProduits()
    {
        $this->topProduits = Vente::with('produit')
            ->selectRaw('produit_id, SUM(quantite_kg) as total_quantite, SUM(prix_total_mga) as total_ca, COUNT(*) as nb_ventes')
            ->whereBetween('date_vente', [$this->dateDebut, $this->dateFin])
            ->when($this->filterDepot, fn($q) => $q->where('lieu_livraison_id', $this->filterDepot))
            ->when($this->filterProduit, fn($q) => $q->where('produit_id', $this->filterProduit))
            ->groupBy('produit_id')
            ->orderBy('total_ca', 'desc')
            ->limit(10)
            ->get();
    }

    private function loadPerformanceDepots()
    {
        $this->performanceDepots = Lieu::where('type', 'depot')
            ->withCount(['depotStocks as nb_mouvements' => function($q) {
                $q->whereBetween('date_mouvement', [$this->dateDebut, $this->dateFin]);
            }])
            ->withSum(['depotStocks as valeur_stock' => function($q) {
                $q->where('statut_stock', 'actif');
            }], 'valeur_stock_mga')
            ->withCount(['alertesStock as nb_alertes' => function($q) {
                $q->where('alerte_active', true);
            }])
            ->when($this->filterDepot, fn($q) => $q->where('id', $this->filterDepot))
            ->orderBy('valeur_stock', 'desc')
            ->get()
            ->map(function($depot) {
                $depot->performance_score = $this->calculerScorePerformance($depot);
                return $depot;
            });
    }

    private function calculerScorePerformance($depot)
    {
        $score = 100;
        
        // Pénalités
        $score -= $depot->nb_alertes * 5; // -5 points par alerte
        
        if ($depot->nb_mouvements == 0) {
            $score -= 20; // Dépôt inactif
        }
        
        // Bonus pour forte activité
        if ($depot->nb_mouvements > 50) {
            $score += 10;
        }
        
        return max(0, min(100, $score));
    }

    // Propriétés calculées
    public function getProduitsProperty()
    {
        return Produit::orderBy('nom')->get();
    }

    public function getDepotsProperty()
    {
        return Lieu::where('type', 'depot')->orderBy('nom')->get();
    }

    public function getProprietairesProperty()
    {
        return User::orderBy('name')->get();
    }

    public function render()
    {
        return view('livewire.stocks.dashboard-stock', [
            'metriques' => $this->metriques,
            'tendances' => $this->tendances,
            'alertes' => $this->alertes,
            'indicateursPerformance' => $this->indicateursPerformance,
            'chartMouvements' => $this->chartMouvements,
            'chartValeurStock' => $this->chartValeurStock,
            'chartRotationStock' => $this->chartRotationStock,
            'chartAlertesEvolution' => $this->chartAlertesEvolution,
            'stocksCritiques' => $this->stocksCritiques,
            'mouvementsRecents' => $this->mouvementsRecents,
            'topProduits' => $this->topProduits,
            'performanceDepots' => $this->performanceDepots,
            'produits' => $this->produits,
            'depots' => $this->depots,
            'proprietaires' => $this->proprietaires
        ]);
    }
}Vente($venteId, $motif = 'Annulation demandée')
    {
        $vente = Vente::findOrFail($venteId);
        $vente->annuler($motif);
        
        session()->flash('success', '❌ Vente annulée avec succès !');
        $this->calculateStats();
    }

    // Gestion des paiements
    public function ajouterPaiement($venteId, $montant, $mode = 'especes')
    {
        $vente = Vente::findOrFail($venteId);
        $vente->ajouterPaiement($montant, $mode);
        
        session()->flash('success', '💵 Paiement ajouté avec succès !');
        $this->calculateStats();
    }

    // Export et impression
    public function openExportModal()
    {
        $this->showExportModal = true;
    }

    public function closeExportModal()
    {
        $this->showExportModal = false;
    }

    public function exportVentes()
    {
        $this->validate([
            'exportDateDebut' => 'required|date',
            'exportDateFin' => 'required|date|after_or_equal:exportDateDebut',
            'exportFormat' => 'required|in:excel,pdf,csv'
        ]);

        $ventes = Vente::with(['produit', 'lieuLivraison'])
            ->whereBetween('date_vente', [$this->exportDateDebut, $this->exportDateFin])
            ->when($this->filterStatut, fn($q) => $q->where('statut_vente', $this->filterStatut))
            ->orderBy('date_vente', 'desc')
            ->get();

        // Logique d'export selon le format
        switch ($this->exportFormat) {
            case 'excel':
                return $this->exportToExcel($ventes);
            case 'pdf':
                return $this->exportToPdf($ventes);
            case 'csv':
                return $this->exportToCsv($ventes);
        }
    }

    public function imprimerVente($venteId)
    {
        $vente = Vente::with(['produit', 'lieuLivraison', 'chargement'])->findOrFail($venteId);
        
        // Génération du PDF de la vente
        session()->flash('success', '🖨️ Impression en cours...');
    }

    // Méthodes utilitaires
    private function fillForm()
    {
        $v = $this->editingVente;
        $this->numero_vente = $v->numero_vente;
        $this->date_vente = $v->date_vente->format('Y-m-d');
        $this->chargement_id = $v->chargement_id;
        $this->produit_id = $v->produit_id;
        $this->lieu_livraison_id = $v->lieu_livraison_id;
        $this->client_nom = $v->client_nom;
        $this->client_contact = $v->client_contact;
        $this->client_adresse = $v->client_adresse;
        $this->client_type = $v->client_type;
        $this->quantite_kg = $v->quantite_kg;
        $this->sacs_pleins = $v->sacs_pleins;
        $this->sacs_demi = $v->sacs_demi;
        $this->prix_unitaire_mga = $v->prix_unitaire_mga;
        $this->prix_total_mga = $v->prix_total_mga;
        $this->montant_paye_mga = $v->montant_paye_mga;
        $this->montant_restant_mga = $v->montant_restant_mga;
        $this->statut_paiement = $v->statut_paiement;
        $this->mode_paiement = $v->mode_paiement;
        $this->transporteur_nom = $v->transporteur_nom;
        $this->vehicule_immatriculation = $v->vehicule_immatriculation;
        $this->frais_transport_mga = $v->frais_transport_mga;
        $this->date_livraison_prevue = $v->date_livraison_prevue?->format('Y-m-d');
        $this->statut_vente = $v->statut_vente;
        $this->qualite_produit = $v->qualite_produit;
        $this->observations = $v->observations;
        $this->remarques_client = $v->remarques_client;
        $this->tva_taux = $v->tva_taux;
    }

    private function resetForm()
    {
        $this->reset([
            'numero_vente', 'chargement_id', 'produit_id', 'lieu_livraison_id',
            'client_nom', 'client_contact', 'client_adresse', 'quantite_kg',
            'sacs_pleins', 'sacs_demi', 'prix_unitaire_mga', 'prix_total_mga',
            'montant_paye_mga', 'montant_restant_mga', 'mode_paiement',
            'transporteur_nom', 'vehicule_immatriculation', 'frais_transport_mga',
            'date_livraison_prevue', 'observations', 'remarques_client'
        ]);
        
        $this->client_type = 'particulier';
        $this->statut_vente = 'brouillon';
        $this->statut_paiement = 'impaye';
        $this->qualite_produit = 'bon';
        $this->tva_taux = 0;
        $this->editingVente = null;
    }

    private function generateNumeroVente()
    {
        $count = Vente::whereDate('date_vente', $this->date_vente ?: today())->count() + 1;
        $this->numero_vente = 'VTE' . date('Ymd', strtotime($this->date_vente ?: today())) . str_pad($count, 4, '0', STR_PAD_LEFT);
    }

    private function calculateStats()
    {
        $today = Carbon::today();
        $thisMonth = Carbon::now()->startOfMonth();

        $this->stats = [
            'ventes_jour' => Vente::whereDate('date_vente', $today)->count(),
            'ca_journalier' => Vente::whereDate('date_vente', $today)->sum('prix_total_mga'),
            'ventes_mois' => Vente::where('date_vente', '>=', $thisMonth)->count(),
            'ca_mensuel' => Vente::where('date_vente', '>=', $thisMonth)->sum('prix_total_mga'),
            'ventes_en_attente' => Vente::where('statut_vente', 'brouillon')->count(),
            'montant_impaye' => Vente::where('statut_paiement', 'impaye')->sum('montant_restant_mga'),
            'ventes_en_retard' => Vente::enRetard()->count()
        ];
    }

    private function loadAlertes()
    {
        $this->alertes = AlerteStock::actives()
            ->where('user_destinataire_id', Auth::id())
            ->where('type_alerte', 'stock_bas')
            ->with(['produit', 'depot'])
            ->limit(5)
            ->get();
    }

    private function verifierAlerteStock($vente)
    {
        // Vérifier si la vente réduit le stock en dessous du seuil
        $stockActuel = DepotStock::getStockActuel($vente->produit_id, $vente->lieu_livraison_id);
        $nouveauStock = $stockActuel - $vente->quantite_kg;
        
        if ($nouveauStock <= 0) {
            AlerteStock::creerAlerte('stock_zero', $vente, $nouveauStock, 0);
        }
    }

    // Tri et filtres
    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
        $this->resetPage();
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedFilterStatut()
    {
        $this->resetPage();
    }

    public function updatedFilterPeriod()
    {
        $this->resetPage();
    }

    // Propriétés calculées
    public function getVentesProperty()
    {
        return Vente::with(['produit', 'lieuLivraison', 'chargement'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('numero_vente', 'like', '%' . $this->search . '%')
                        ->orWhere('client_nom', 'like', '%' . $this->search . '%')
                        ->orWhereHas('produit', function ($subQ) {
                            $subQ->where('nom', 'like', '%' . $this->search . '%');
                        });
                });
            })
            ->when($this->filterStatut, fn($q) => $q->where('statut_vente', $this->filterStatut))
            ->when($this->filterPaiement, fn($q) => $q->where('statut_paiement', $this->filterPaiement))
            ->when($this->filterClient, fn($q) => $q->where('client_nom', 'like', '%' . $this->filterClient . '%'))
            ->when($this->filterPeriod, function ($query) {
                switch ($this->filterPeriod) {
                    case 'today':
                        $query->whereDate('date_vente', Carbon::today());
                        break;
                    case 'week':
                        $query->whereBetween('date_vente', [
                            Carbon::now()->startOfWeek(),
                            Carbon::now()->endOfWeek()
                        ]);
                        break;
                    case 'month':
                        $query->whereMonth('date_vente', Carbon::now()->month)
                            ->whereYear('date_vente', Carbon::now()->year);
                        break;
                }
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);
    }

    public function getProduitsProperty()
    {
        return Produit::orderBy('nom')->get();
    }

    public function getDestinationsProperty()
    {
        return Lieu::where('type', 'depot')->orderBy('nom')->get();
    }

    public function getChargementsProperty()
    {
        return Chargement::with('produit')->where('statut', 'livre')->latest()->limit(50)->get();
    }

    public function render()
    {
        return view('livewire.stocks.vente-enhanced', [
            'ventes' => $this->ventes,
            'produits' => $this->produits,
            'destinations' => $this->destinations,
            'chargements' => $this->chargements,
            'stats' => $this->stats,
            'alertes' => $this->alertes
        ]);
    }
}
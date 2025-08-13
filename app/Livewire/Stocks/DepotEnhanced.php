<?php

// Composant 1: Vente Enhanced
// app/Livewire/Stocks/VenteEnhanced.php

namespace App\Livewire\Stocks;

use App\Models\Vente;
use App\Models\Produit;
use App\Models\Lieu;
use App\Models\Chargement;
use App\Models\AlerteStock;
use App\Models\Depots;
use App\Models\SeuilAlerte;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;



class DepotEnhanced extends Component
{
    use WithPagination;

    // Propriétés de filtrage
    public $search = '';
    public $filterStatut = '';
    public $filterProduit = '';
    public $filterDepot = '';
    public $filterProprietaire = '';
    public $perPage = 25;
    public $sortField = 'date_mouvement';
    public $sortDirection = 'desc';

    // Modals
    public $showEntreeModal = false;
    public $showSortieModal = false;
    public $showAjustementModal = false;
    public $showInventaireModal = false;
    public $showDetailsModal = false;
    public $showAlertesModal = false;

    public $selectedStock = null;
    public $stockDetails = null;

    // Formulaire d'entrée
    public $entree = [
        'numero_mouvement' => '',
        'date_mouvement' => '',
        'produit_id' => '',
        'depot_id' => '',
        'origine_mouvement' => '',
        'numero_bl' => '',
        'quantite_kg' => '',
        'sacs_pleins' => 0,
        'sacs_demi' => 0,
        'prix_unitaire_achat_mga' => '',
        'prix_unitaire_vente_mga' => '',
        'proprietaire_id' => '',
        'type_proprietaire' => 'interne',
        'commission_taux' => 0,
        'zone_stockage' => '',
        'temperature_stockage' => '',
        'humidite_stockage' => '',
        'date_peremption' => '',
        'qualite_produit' => 'bon',
        'observations_qualite' => '',
        'seuil_alerte_kg' => 0,
        'observations' => ''
    ];

    // Formulaire de sortie
    public $sortie = [
        'quantite_kg' => '',
        'destination_mouvement' => '',
        'observations' => ''
    ];

    // Formulaire d'ajustement
    public $ajustement = [
        'type_ajustement' => 'inventaire', // inventaire, correction, perte
        'nouvelle_quantite_kg' => '',
        'motif' => '',
        'observations' => ''
    ];

    // Inventaire
    public $inventaire = [
        'depot_id' => '',
        'date_inventaire' => '',
        'responsable_inventaire' => '',
        'observations' => ''
    ];

    // Alertes et seuils
    public $configAlertes = [
        'produit_id' => '',
        'depot_id' => '',
        'seuil_stock_minimum_kg' => 0,
        'seuil_stock_maximum_kg' => 0,
        'stock_securite_kg' => 0,
        'jours_avant_peremption' => 30,
        'alerte_stock_bas_active' => true,
        'alerte_peremption_active' => true
    ];

    // Statistiques et métriques
    public $stats = [];
    public $alertes = [];
    public $mouvementsRecents = [];

    protected $listeners = [
        'refreshDepot' => '$refresh',
        'stockUpdated' => 'handleStockUpdated'
    ];

    public function mount()
    {
        $this->entree['date_mouvement'] = today()->format('Y-m-d');
        $this->inventaire['date_inventaire'] = today()->format('Y-m-d');
        $this->calculateStats();
        $this->loadAlertes();
        $this->loadMouvementsRecents();
    }

    // Gestion des entrées
    public function openEntreeModal()
    {
        $this->resetEntreeForm();
        $this->generateNumeroMouvement('entree');
        $this->showEntreeModal = true;
    }

    public function closeEntreeModal()
    {
        $this->showEntreeModal = false;
        $this->resetEntreeForm();
    }

    public function saveEntree()
    {
        $this->validate([
            'entree.date_mouvement' => 'required|date',
            'entree.produit_id' => 'required|exists:produits,id',
            'entree.depot_id' => 'required|exists:lieux,id',
            'entree.origine_mouvement' => 'required|string|max:255',
            'entree.quantite_kg' => 'required|numeric|min:0.01',
            'entree.prix_unitaire_achat_mga' => 'required|numeric|min:0',
            'entree.proprietaire_id' => 'required|exists:users,id'
        ]);

        DB::beginTransaction();
        try {
            $stockData = array_merge($this->entree, [
                'type_mouvement' => 'entree',
                'statut_mouvement' => 'valide',
                'user_saisie_id' => Auth::id(),
                'valeur_stock_mga' => $this->entree['quantite_kg'] * $this->entree['prix_unitaire_achat_mga']
            ]);

            $stock = Depots::ajouterStock($stockData);

            // Créer ou mettre à jour les seuils d'alertes
            $this->configurerAlertes($stock);

            DB::commit();
            session()->flash('success', '📥 Entrée de stock enregistrée avec succès !');
            $this->closeEntreeModal();
            $this->calculateStats();
            $this->dispatch('stockUpdated');

        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Erreur lors de l\'entrée : ' . $e->getMessage());
        }
    }

    // Gestion des sorties
    public function openSortieModal($stockId)
    {
        $this->selectedStock = Depots::with(['produit', 'depot'])->findOrFail($stockId);
        $this->resetSortieForm();
        $this->showSortieModal = true;
    }

    public function closeSortieModal()
    {
        $this->showSortieModal = false;
        $this->resetSortieForm();
        $this->selectedStock = null;
    }

    public function saveSortie()
    {
        $this->validate([
            'sortie.quantite_kg' => 'required|numeric|min:0.01|max:' . $this->selectedStock->quantite_kg,
            'sortie.destination_mouvement' => 'required|string|max:255'
        ]);

        DB::beginTransaction();
        try {
            Depots::retirerStock(
                $this->selectedStock->produit_id,
                $this->selectedStock->depot_id,
                $this->sortie['quantite_kg'],
                $this->sortie['observations'] ?: 'Sortie manuelle'
            );

            DB::commit();
            session()->flash('success', '📤 Sortie de stock enregistrée avec succès !');
            $this->closeSortieModal();
            $this->calculateStats();
            $this->dispatch('stockUpdated');

        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Erreur lors de la sortie : ' . $e->getMessage());
        }
    }

    // Gestion des ajustements
    public function openAjustementModal($stockId)
    {
        $this->selectedStock = Depots::with(['produit', 'depot'])->findOrFail($stockId);
        $stockActuel = Depots::getStockActuel($this->selectedStock->produit_id, $this->selectedStock->depot_id);
        $this->ajustement['nouvelle_quantite_kg'] = $stockActuel;
        $this->showAjustementModal = true;
    }

    public function closeAjustementModal()
    {
        $this->showAjustementModal = false;
        $this->reset('ajustement');
        $this->selectedStock = null;
    }

    public function saveAjustement()
    {
        $this->validate([
            'ajustement.nouvelle_quantite_kg' => 'required|numeric|min:0',
            'ajustement.type_ajustement' => 'required|in:inventaire,correction,perte',
            'ajustement.motif' => 'required|string|min:10'
        ]);

        DB::beginTransaction();
        try {
            $stockActuel = Depots::getStockActuel($this->selectedStock->produit_id, $this->selectedStock->depot_id);
            $ecart = $this->ajustement['nouvelle_quantite_kg'] - $stockActuel;

            if ($ecart != 0) {
                Depots::create([
                    'numero_mouvement' => 'ADJ' . date('YmdHis'),
                    'type_mouvement' => 'ajustement',
                    'date_mouvement' => now(),
                    'produit_id' => $this->selectedStock->produit_id,
                    'depot_id' => $this->selectedStock->depot_id,
                    'quantite_kg' => $ecart,
                    'stock_avant_kg' => $stockActuel,
                    'stock_apres_kg' => $this->ajustement['nouvelle_quantite_kg'],
                    'observations' => $this->ajustement['type_ajustement'] . ': ' . $this->ajustement['motif'],
                    'statut_mouvement' => 'valide',
                    'user_saisie_id' => Auth::id()
                ]);
            }

            DB::commit();
            session()->flash('success', '⚖️ Ajustement effectué avec succès !');
            $this->closeAjustementModal();
            $this->calculateStats();

        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Erreur lors de l\'ajustement : ' . $e->getMessage());
        }
    }

    // Gestion de l'inventaire
    public function openInventaireModal()
    {
        $this->reset('inventaire');
        $this->inventaire['date_inventaire'] = today()->format('Y-m-d');
        $this->showInventaireModal = true;
    }

    public function closeInventaireModal()
    {
        $this->showInventaireModal = false;
        $this->reset('inventaire');
    }

    public function demarrerInventaire()
    {
        $this->validate([
            'inventaire.depot_id' => 'required|exists:lieux,id',
            'inventaire.responsable_inventaire' => 'required|string|max:255'
        ]);

        // Logique pour démarrer un inventaire complet
        session()->flash('success', '📊 Inventaire démarré avec succès !');
        $this->closeInventaireModal();
    }

    // Gestion des alertes
    public function openAlertesModal()
    {
        $this->showAlertesModal = true;
    }

    public function closeAlertesModal()
    {
        $this->showAlertesModal = false;
    }

    public function configurerAlertes($stock)
    {
        SeuilAlerte::updateOrCreate(
            [
                'produit_id' => $stock->produit_id,
                'depot_id' => $stock->depot_id
            ],
            [
                'seuil_stock_minimum_kg' => $this->entree['seuil_alerte_kg'] ?: 100,
                'alerte_stock_bas_active' => true,
                'alerte_peremption_active' => true
            ]
        );
    }

    public function marquerAlerteVue($alerteId)
    {
        $alerte = AlerteStock::findOrFail($alerteId);
        $alerte->marquerVue();
        $this->loadAlertes();
    }

    public function traiterAlerte($alerteId, $action)
    {
        $alerte = AlerteStock::findOrFail($alerteId);
        $alerte->traiter($action);
        $this->loadAlertes();
        session()->flash('success', '✅ Alerte traitée !');
    }

    // Détails du stock
    public function showDetails($stockId)
    {
        $this->stockDetails = Depots::with(['produit', 'depot', 'proprietaire'])
            ->findOrFail($stockId);
        $this->showDetailsModal = true;
    }

    public function closeDetailsModal()
    {
        $this->showDetailsModal = false;
        $this->stockDetails = null;
    }

    // Méthodes utilitaires
    private function resetEntreeForm()
    {
        $this->entree = [
            'numero_mouvement' => '',
            'date_mouvement' => today()->format('Y-m-d'),
            'produit_id' => '',
            'depot_id' => '',
            'origine_mouvement' => '',
            'numero_bl' => '',
            'quantite_kg' => '',
            'sacs_pleins' => 0,
            'sacs_demi' => 0,
            'prix_unitaire_achat_mga' => '',
            'prix_unitaire_vente_mga' => '',
            'proprietaire_id' => '',
            'type_proprietaire' => 'interne',
            'commission_taux' => 0,
            'zone_stockage' => '',
            'temperature_stockage' => '',
            'humidite_stockage' => '',
            'date_peremption' => '',
            'qualite_produit' => 'bon',
            'observations_qualite' => '',
            'seuil_alerte_kg' => 100,
            'observations' => ''
        ];
    }

    private function resetSortieForm()
    {
        $this->sortie = [
            'quantite_kg' => '',
            'destination_mouvement' => '',
            'observations' => ''
        ];
    }

    private function generateNumeroMouvement($type)
    {
        $prefix = strtoupper(substr($type, 0, 3));
        $count = Depots::whereDate('date_mouvement', today())->count() + 1;
        $this->entree['numero_mouvement'] = $prefix . date('Ymd') . str_pad($count, 4, '0', STR_PAD_LEFT);
    }

    private function calculateStats()
    {
        $this->stats = [
            'stock_total_kg' => Depots::where('statut_stock', 'actif')->sum('quantite_kg'),
            'valeur_stock_total' => Depots::where('statut_stock', 'actif')->sum('valeur_stock_mga'),
            'produits_differents' => Depots::where('statut_stock', 'actif')->distinct('produit_id')->count(),
            'depots_actifs' => Depots::where('statut_stock', 'actif')->distinct('depot_id')->count(),
            'entrees_mois' => Depots::where('type_mouvement', 'entree')
                ->whereMonth('date_mouvement', now()->month)->count(),
            'sorties_mois' => Depots::where('type_mouvement', 'sortie')
                ->whereMonth('date_mouvement', now()->month)->count(),
            'stocks_bas' => Depots::stockBas()->count(),
            'peremption_proche' => Depots::peremptionProche(30)->count()
        ];
    }

    private function loadAlertes()
    {
        $this->alertes = AlerteStock::actives()
            ->where('user_destinataire_id', Auth::id())
            ->with(['produit', 'depot'])
            ->orderBy('niveau_urgence', 'desc')
            ->orderBy('date_alerte', 'desc')
            ->limit(10)
            ->get();
    }

    private function loadMouvementsRecents()
    {
        $this->mouvementsRecents = Depots::with(['produit', 'depot', 'userSaisie'])
            ->where('statut_mouvement', 'valide')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
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

    // Propriétés calculées
    public function getStocksProperty()
    {
        return Depots::with(['produit', 'depot', 'proprietaire'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('numero_mouvement', 'like', '%' . $this->search . '%')
                        ->orWhere('origine_mouvement', 'like', '%' . $this->search . '%')
                        ->orWhereHas('produit', function ($subQ) {
                            $subQ->where('nom', 'like', '%' . $this->search . '%');
                        })
                        ->orWhereHas('depot', function ($subQ) {
                            $subQ->where('nom', 'like', '%' . $this->search . '%');
                        });
                });
            })
            ->when($this->filterStatut, fn($q) => $q->where('statut_stock', $this->filterStatut))
            ->when($this->filterProduit, fn($q) => $q->where('produit_id', $this->filterProduit))
            ->when($this->filterDepot, fn($q) => $q->where('depot_id', $this->filterDepot))
            ->when($this->filterProprietaire, fn($q) => $q->where('proprietaire_id', $this->filterProprietaire))
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);
    }

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
        return view('livewire.stocks.depot-enhanced', [
            'stocks' => $this->stocks,
            'produits' => $this->produits,
            'depots' => $this->depots,
            'proprietaires' => $this->proprietaires,
            'stats' => $this->stats,
            'alertes' => $this->alertes,
            'mouvementsRecents' => $this->mouvementsRecents
        ]);
    }
}
<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Voyage;
use App\Models\Transaction;
use App\Models\Produit;
use App\Models\User;
use App\Models\Vehicule;
use App\Models\StockDepot;
use App\Models\Facture;

class Dashboard extends Component
{
    public $selectedPeriod = 'month';
    public $stats = [];

    public function mount()
    {
        $this->loadStats();
    }

    public function updatedSelectedPeriod()
    {
        $this->loadStats();
    }

    public function loadStats()
    {
        $dateFilter = $this->getDateFilter();

        $this->stats = [
            // Logistique
            'voyages_total' => Voyage::count(),
            'voyages_en_cours' => Voyage::where('statut', 'en_cours')->count(),
            'voyages_periode' => Voyage::where('created_at', '>=', $dateFilter)->count(),
            'vehicules_actifs' => Vehicule::where('statut', 'actif')->count(),
            
            // Finance
            'chiffre_affaires' => Transaction::where('type', 'vente')
                ->where('created_at', '>=', $dateFilter)
                ->sum('montant_mga'),
            'benefice_brut' => $this->calculateBenefice($dateFilter),
            'transactions_jour' => Transaction::whereDate('created_at', today())->count(),
            'factures_impayees' => Facture::where('statut', '!=', 'payee')->sum('montant_restant_mga'),
            
            // Stock
            'produits_total' => Produit::count(),
            'stock_total_kg' => StockDepot::where('statut', 'en_stock')->sum('reste_kg'),
            'proprietaires_actifs' => User::where('type', 'proprietaire')->where('actif', true)->count(),
            
            // Activité récente
            'derniers_voyages' => Voyage::with(['origine', 'vehicule', 'chauffeur'])
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get(),
            'dernieres_transactions' => Transaction::with(['fromUser', 'toUser'])
                ->orderBy('created_at', 'desc')
                ->limit(8)
                ->get(),
            'top_produits' => $this->getTopProduits($dateFilter),
            'alertes' => $this->getAlertes()
        ];
    }

    private function getDateFilter()
    {
        return match($this->selectedPeriod) {
            'today' => today(),
            'week' => now()->subWeek(),
            'month' => now()->subMonth(),
            'year' => now()->subYear(),
            default => now()->subMonth()
        };
    }

    private function calculateBenefice($dateFilter)
    {
        $ventes = Transaction::where('type', 'vente')
            ->where('created_at', '>=', $dateFilter)
            ->sum('montant_mga');
            
        $achats = Transaction::where('type', 'achat')
            ->where('created_at', '>=', $dateFilter)
            ->sum('montant_mga');
            
        $frais = Transaction::where('type', 'frais')
            ->where('created_at', '>=', $dateFilter)
            ->sum('montant_mga');

        return $ventes - $achats - $frais;
    }

    private function getTopProduits($dateFilter)
    {
        return Transaction::where('type', 'vente')
            ->where('created_at', '>=', $dateFilter)
            ->whereNotNull('produit_id')
            ->selectRaw('produit_id, SUM(montant_mga) as total_ventes, SUM(quantite) as total_quantite')
            ->groupBy('produit_id')
            ->orderBy('total_ventes', 'desc')
            ->limit(5)
            ->with('produit')
            ->get();
    }

    private function getAlertes()
    {
        $alertes = collect();

        // Factures échues
        $facturesEchues = Facture::where('date_echeance', '<', now())
            ->where('statut', '!=', 'payee')
            ->count();
        if ($facturesEchues > 0) {
            $alertes->push([
                'type' => 'warning',
                'icon' => 'exclamation-triangle',
                'message' => "$facturesEchues factures échues",
                'action' => 'Voir factures'
            ]);
        }

        // Voyages en cours depuis plus de 3 jours
        $voyagesLongs = Voyage::where('statut', 'en_cours')
            ->where('created_at', '<', now()->subDays(3))
            ->count();
        if ($voyagesLongs > 0) {
            $alertes->push([
                'type' => 'info',
                'icon' => 'truck',
                'message' => "$voyagesLongs voyages en cours depuis +3 jours",
                'action' => 'Vérifier voyages'
            ]);
        }

        // Stock faible
        $stockFaible = StockDepot::where('reste_kg', '<', 1000)
            ->where('statut', 'en_stock')
            ->count();
        if ($stockFaible > 0) {
            $alertes->push([
                'type' => 'error',
                'icon' => 'archive',
                'message' => "$stockFaible produits en stock faible",
                'action' => 'Voir stocks'
            ]);
        }

        return $alertes;
    }

    public function render()
    {
        return view('livewire.dashboard');
    }
}

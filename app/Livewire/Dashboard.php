<?php

namespace App\Livewire;

use App\Models\Depots;
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

   
    public function updatedSelectedPeriod()
    {
        $this->loadStats();
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
       

        return $alertes;
    }

    public function render()
    {
        return view('livewire.dashboard');
    }
}

<?php

namespace App\Livewire;

use App\Models\Compte;
use App\Models\Vente;
use App\Models\Voyage;
use App\Models\Vehicule;
use App\Models\Produit;
use App\Models\Lieu;
use App\Models\User;
use Livewire\Component;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class Dashboard extends Component
{
    public $selectedPeriod = 'today';
    public $stats = [];
    public $totalVentesToday = 0;
    public $voyagesActifs = 0;
    public $soldeTotal = 0;
    public $notifications = 0;
    public $currentTime;

    public function mount()
    {
        $this->loadStats();
        $this->currentTime = now()->format('H:i:s');
        Log::info('Dashboard mounted for user: ' . Auth::user()->name);
    }

    public function updatedSelectedPeriod()
    {
        $this->loadStats();
    }

    private function loadStats()
    {
        try {
            // Statistiques des ventes aujourd'hui
            $this->totalVentesToday = Vente::whereDate('date', Carbon::today())
                ->sum('montant_paye_mga');

            // Voyages actifs
            $this->voyagesActifs = Voyage::where('statut', 'en_cours')->count();

            // Solde total de tous les comptes
            $this->soldeTotal = Compte::where('actif', true)->sum('solde_actuel_mga');

            // Notifications (factures en retard, voyages longs, etc.)
            $this->notifications = $this->getNotificationsCount();

            Log::info('Stats loaded', [
                'ventes_today' => $this->totalVentesToday,
                'voyages_actifs' => $this->voyagesActifs,
                'solde_total' => $this->soldeTotal,
                'notifications' => $this->notifications
            ]);

        } catch (\Exception $e) {
            Log::error('Error loading dashboard stats: ' . $e->getMessage());
            // Valeurs par défaut en cas d'erreur
            $this->totalVentesToday = 0;
            $this->voyagesActifs = 0;
            $this->soldeTotal = 0;
            $this->notifications = 0;
        }
    }

    private function getNotificationsCount()
    {
        $notifications = 0;

        try {
            // Voyages en cours depuis plus de 3 jours
            $voyagesLongs = Voyage::where('statut', 'en_cours')
                ->where('created_at', '<', Carbon::now()->subDays(3))
                ->count();
            $notifications += $voyagesLongs;

            // Ventes avec montant restant (impayées partiellement)
            $ventesPartielles = Vente::where('statut_paiement', 'partiel')
                ->where('montant_restant_mga', '>', 0)
                ->count();
            $notifications += $ventesPartielles;

            // Comptes avec solde faible (moins de 100,000 Ar)
            $comptesFaibles = Compte::where('actif', true)
                ->where('solde_actuel_mga', '<', 100000)
                ->count();
            $notifications += $comptesFaibles;

        } catch (\Exception $e) {
            Log::error('Error calculating notifications: ' . $e->getMessage());
        }

        return $notifications;
    }

    public function refreshStats()
    {
        $this->loadStats();
        $this->currentTime = now()->format('H:i:s');
        session()->flash('message', 'Statistiques mises à jour !');
    }

    // Actions rapides
    public function createVente()
    {
        return redirect()->route('vente.index');
    }

    public function createVoyage()
    {
        return redirect()->route('voyages.index');
    }

    public function viewInventaire()
    {
        return redirect()->route('produits.index');
    }

    public function viewRapports()
    {
        return redirect()->route('compte.index');
    }

    // Méthodes pour obtenir des données formatées
    public function getFormattedVentesToday()
    {
        return number_format($this->totalVentesToday, 0, ',', ' ') . ' Ar';
    }

    public function getFormattedSoldeTotal()
    {
        return number_format($this->soldeTotal, 0, ',', ' ') . ' Ar';
    }

    public function getPourcentageCroissance()
    {
        // Calculer la croissance par rapport à hier
        try {
            $ventesHier = Vente::whereDate('date', Carbon::yesterday())
                ->sum('montant_paye_mga');

            if ($ventesHier > 0) {
                $croissance = (($this->totalVentesToday - $ventesHier) / $ventesHier) * 100;
                return round($croissance, 1);
            }
        } catch (\Exception $e) {
            Log::error('Error calculating growth: ' . $e->getMessage());
        }

        return 0;
    }

    public function getTendanceVentes()
    {
        $croissance = $this->getPourcentageCroissance();

        if ($croissance > 0) {
            return [
                'type' => 'positive',
                'icon' => 'up',
                'text' => '+' . $croissance . '%',
                'color' => 'green'
            ];
        } elseif ($croissance < 0) {
            return [
                'type' => 'negative',
                'icon' => 'down',
                'text' => $croissance . '%',
                'color' => 'red'
            ];
        } else {
            return [
                'type' => 'stable',
                'icon' => 'stable',
                'text' => 'Stable',
                'color' => 'gray'
            ];
        }
    }

    public function render()
    {
        return view('livewire.dashboard', [
            'formattedVentesToday' => $this->getFormattedVentesToday(),
            'formattedSoldeTotal' => $this->getFormattedSoldeTotal(),
            'tendanceVentes' => $this->getTendanceVentes(),
            'currentUser' => Auth::user()
        ]);
    }
}
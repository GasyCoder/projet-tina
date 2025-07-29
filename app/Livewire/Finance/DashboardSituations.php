<?php
// app/Livewire/Finance/DashboardSituations.php

namespace App\Livewire\Finance;

use Livewire\Component;
use App\Models\SituationFinanciere;
use Carbon\Carbon;

class DashboardSituations extends Component
{
    public $periodeSelectionnee = 'this_month';
    public $dateDebutCustom = '';
    public $dateFinCustom = '';

    public function mount()
    {
        $this->dateDebutCustom = Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->dateFinCustom = Carbon::now()->endOfMonth()->format('Y-m-d');
    }

    public function render()
    {
        [$dateDebut, $dateFin] = $this->getPeriodeDates();

        // Résumé global
        $resumeGlobal = SituationFinanciere::getResumeGlobal($dateDebut, $dateFin);

        // Totaux par lieu
        $totauxParLieu = SituationFinanciere::getLieuxAvecTotaux($dateDebut, $dateFin);

        // Dernières situations (limité à 10)
        $dernieresSituations = SituationFinanciere::whereBetween('date_situation', [$dateDebut, $dateFin])
            ->orderBy('date_situation', 'desc')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('livewire.finance.dashboard-situations', [
            'resumeGlobal' => $resumeGlobal,
            'totauxParLieu' => $totauxParLieu,
            'dernieresSituations' => $dernieresSituations,
        ]);
    }

    protected function getPeriodeDates()
    {
        switch ($this->periodeSelectionnee) {
            case 'this_month':
                return [
                    Carbon::now()->startOfMonth(),
                    Carbon::now()->endOfMonth()
                ];

            case 'last_month':
                return [
                    Carbon::now()->subMonth()->startOfMonth(),
                    Carbon::now()->subMonth()->endOfMonth()
                ];

            case 'this_year':
                return [
                    Carbon::now()->startOfYear(),
                    Carbon::now()->endOfYear()
                ];

            case 'custom':
                return [
                    $this->dateDebutCustom ? Carbon::parse($this->dateDebutCustom) : Carbon::now()->startOfMonth(),
                    $this->dateFinCustom ? Carbon::parse($this->dateFinCustom) : Carbon::now()->endOfMonth()
                ];

            default:
                return [
                    Carbon::now()->startOfMonth(),
                    Carbon::now()->endOfMonth()
                ];
        }
    }

    public function updated($propertyName)
    {
        // Réinitialise le rendu quand les filtres changent
        if (in_array($propertyName, ['periodeSelectionnee', 'dateDebutCustom', 'dateFinCustom'])) {
            $this->render();
        }
    }
}
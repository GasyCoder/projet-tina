<?php

namespace App\Livewire\Finance;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Transaction;

class Revenus extends Component
{
    use WithPagination;

    public $periodeRevenus = 'mois';
    public $dateDebutRevenus;
    public $dateFinRevenus;
    public $typeRevenu = '';
    public $statutRevenu = '';

    protected $paginationTheme = 'bootstrap';

    public function mount()
    {
        $this->dateDebutRevenus = now()->startOfMonth()->format('Y-m-d');
        $this->dateFinRevenus = now()->endOfMonth()->format('Y-m-d');
    }

    public function render()
    {
        return view('livewire.finance.tabs.revenus', [
            'voyages' => $this->getVoyages(),
            'comptes' => $this->getComptes(),
            'revenus' => Transaction::revenus()
                ->when($this->typeRevenu, fn($q) => $q->where('type', $this->typeRevenu))
                ->when($this->statutRevenu, fn($q) => $q->where('statut', $this->statutRevenu))
                ->whereBetween('date', [$this->dateDebutRevenus, $this->dateFinRevenus])
                ->latest()
                ->paginate(10),
        ]);
    }

    public function getTotalRevenusProperty()
    {
        return Transaction::revenus()
            ->whereBetween('date', [$this->dateDebutRevenus, $this->dateFinRevenus])
            ->sum('montant');
    }

    public function getRevenuMoyenProperty()
    {
        return Transaction::revenus()
            ->whereBetween('date', [$this->dateDebutRevenus, $this->dateFinRevenus])
            ->avg('montant');
    }

    public function getNombreRevenusProperty()
    {
        return Transaction::revenus()
            ->whereBetween('date', [$this->dateDebutRevenus, $this->dateFinRevenus])
            ->count();
    }

    private function getVoyages()
    {
        return collect(); // à remplacer par Voyage::all() si tu veux afficher des voyages
    }

    private function getComptes()
    {
        return collect(); // à remplacer par Compte::all()
    }

    public function editTransaction($id)
    {
        // à implémenter
    }

    public function marquerEncaisse($id)
    {
        // à implémenter
    }
}

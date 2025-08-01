<?php

namespace App\Livewire\Finance;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Transaction;
use App\Models\Compte; // Pense à importer le modèle
class Depenses extends Component
{
    use WithPagination;

    public $categorieDepense = '';
    public $periodeDepenses = 'mois';
    public $dateDebutDepenses;
    public $dateFinDepenses;
    public $statutDepense = '';
    public $showCompteModal = false;
    public $editingCompte = null; // si tu l’utilises dans le modal


    protected $paginationTheme = 'bootstrap';


    public function openCompteModal($compteId = null)
    {
        $this->editingCompte = $compteId ? Compte::find($compteId) : null;
        $this->showCompteModal = true;
    }

    public function closeCompteModal()
    {
        $this->showCompteModal = false;
        $this->editingCompte = null;
    }

    public function mount()
    {
        $this->dateDebutDepenses = now()->startOfMonth()->format('Y-m-d');
        $this->dateFinDepenses = now()->endOfMonth()->format('Y-m-d');
    }

    public function render()
    {
        $depensesQuery = Transaction::depenses()
            ->when($this->statutDepense, fn($query) => $query->where('statut', $this->statutDepense))
            ->whereBetween('date', [$this->dateDebutDepenses, $this->dateFinDepenses])
            ->latest();

        return view('livewire.finance.tabs.depenses', [
            'voyages' => $this->getVoyages(),
            'comptes' => $this->getComptes(),
            'depenses' => $depensesQuery->paginate(10),
        ]);
    }

    // Propriétés calculées pour les statistiques

    public function getTotalDepensesProperty()
    {
        return Transaction::depenses()
            ->when($this->statutDepense, fn($query) => $query->where('statut', $this->statutDepense))
            ->whereBetween('date', [$this->dateDebutDepenses, $this->dateFinDepenses])
            ->sum('montant');
    }

    public function getDepenseMoyenneProperty()
    {
        return Transaction::depenses()
            ->when($this->statutDepense, fn($query) => $query->where('statut', $this->statutDepense))
            ->whereBetween('date', [$this->dateDebutDepenses, $this->dateFinDepenses])
            ->avg('montant') ?? 0;
    }

    public function getDepensesEnAttenteProperty()
    {
        return Transaction::depenses()
            ->where('statut', 'en_attente')
            ->whereBetween('date', [$this->dateDebutDepenses, $this->dateFinDepenses])
            ->count();
    }

    public function getNombreDepensesProperty()
    {
        return Transaction::depenses()
            ->when($this->statutDepense, fn($query) => $query->where('statut', $this->statutDepense))
            ->whereBetween('date', [$this->dateDebutDepenses, $this->dateFinDepenses])
            ->count();
    }



    public function getRepartitionDepensesProperty()
    {
        $depenses = Transaction::depenses()
            ->when($this->statutDepense, fn($query) => $query->where('statut', $this->statutDepense))
            ->whereBetween('date', [$this->dateDebutDepenses, $this->dateFinDepenses])
            ->get();

        $result = [];

        foreach ($depenses->groupBy('type') as $type => $group) {
            $result[$type] = [
                'count' => $group->count(),
                'total' => $group->sum('montant'),
            ];
        }

        return $result;
    }

    // ... tes autres propriétés

    public $showTransactionModal = false;
    public $editingTransaction = null;

    // ... ton mount(), render(), etc.

    public function openTransactionModal($transactionId = null)
    {
        $this->editingTransaction = $transactionId ? Transaction::find($transactionId) : null;
        $this->showTransactionModal = true;
    }

    public function closeTransactionModal()
    {
        $this->showTransactionModal = false;
        $this->editingTransaction = null;
    }

    public function getDepensesProperty()
    {
        return Transaction::depenses()
            ->when($this->statutDepense, fn($query) => $query->where('statut', $this->statutDepense))
            ->whereBetween('date', [$this->dateDebutDepenses, $this->dateFinDepenses])
            ->latest()
            ->paginate(10);
    }


    // Méthodes pour récupérer voyages et comptes (à adapter selon ton projet)

    private function getVoyages()
    {
        return collect(); // Ou Voyage::all();
    }

    private function getComptes()
    {
        return collect(); // Ou Compte::all();
    }

    // Actions

    public function editTransaction($id)
    {
        // À implémenter
    }

    public function marquerPayee($id)
    {
        // À implémenter
    }
}

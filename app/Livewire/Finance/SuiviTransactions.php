<?php

// app/Http/Livewire/Finance/SuiviTransactions.php
namespace App\Livewire\Finance;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Transaction;


class SuiviTransactions extends Component
{
    use WithPagination;

    // Propriétés pour les filtres
    public $dateDebut;
    public $dateFin;
    public $filterType = '';
    public $filterStatut = '';
    public bool $showTransactionModal = false;
    public bool $showCompteModal = false;
    public function openCompteModal()
    {
        $this->showCompteModal = true;
    }

    public function closeCompteModal()
    {
        $this->showCompteModal = false;
    }

    public function openTransactionModal()
    {
        $this->showTransactionModal = true;
    }

    public function closeTransactionModal()
    {
        $this->showTransactionModal = false;
    }


    protected $paginationTheme = 'bootstrap';

    public function mount()
    {
        // Initialiser les dates par défaut
        $this->dateDebut = now()->startOfMonth()->format('Y-m-d');
        $this->dateFin = now()->endOfMonth()->format('Y-m-d');
    }

    public function getTransactions()
    {
        return Transaction::query()
            ->when($this->filterType, fn($q) => $q->where('type', $this->filterType))
            ->when($this->filterStatut, fn($q) => $q->where('statut', $this->filterStatut))
            ->whereBetween('date', [$this->dateDebut, $this->dateFin])
            ->latest()
            ->paginate(10); // <- important pour la pagination
    }


    public function render()
    {
        $transactions = $this->getTransactions();

        return view('livewire.finance.tabs.suivi-transactions', [
            'transactions' => $transactions,
            'voyages' => $this->getVoyages(),
            'comptes' => $this->getComptes(),
        ]);
    }

    // Propriétés calculées
    public function getTotalEntreesProperty()
    {
        // Logique pour calculer le total des entrées
        return 0; // À implémenter
    }

    public function getTotalSortiesProperty()
    {
        // Logique pour calculer le total des sorties
        return 0; // À implémenter
    }

    public function getBeneficeNetProperty()
    {
        return $this->totalEntrees - $this->totalSorties;
    }

    public function getTransactionsEnAttenteProperty()
    {
        // Logique pour compter les transactions en attente
        return 0; // À implémenter
    }

    public function getRepartitionParTypeProperty()
    {
        // Logique pour la répartition par type
        return []; // À implémenter
    }

    public function getRepartitionParStatutProperty()
    {
        // Logique pour la répartition par statut
        return []; // À implémenter
    }

 

    private function getVoyages()
    {
        // return Voyage::all();
        return collect(); // À implémenter
    }

    private function getComptes()
    {
        // return Compte::all();
        return collect(); // À implémenter
    }

    // Actions
    public function editTransaction($id)
    {
        // Logique pour éditer une transaction
    }

    public function marquerPayee($id)
    {
        // Logique pour marquer une transaction comme payée
    }
}



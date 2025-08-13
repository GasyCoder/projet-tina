<?php

namespace App\Livewire\Partenaire;

use App\Models\Partenaire;
use Livewire\Component;

class PartenaireShow extends Component
{
    public Partenaire $partenaire;
    public string $filter = 'all';
    public bool $showTransactionModal = false;
    public array $selectedTransaction = [];

    // Fake data pour les transactions
    protected array $fakeTransactions = [
        [
            'id' => 1,
            'reference' => 'ENT-2023-001',
            'date' => '15/01/2023',
            'type' => 'entree',
            'montant' => 250000,
            'description' => 'Paiement pour livraison produits'
        ],
        [
            'id' => 2,
            'reference' => 'SORT-2023-001',
            'date' => '16/01/2023',
            'type' => 'sortie',
            'montant' => 100000,
            'description' => 'Achat matières premières',
            'beneficiaire' => 'Fournisseur XYZ',
            'justificatif' => 'Facture 1234'
        ],
        [
            'id' => 3,
            'reference' => 'ENT-2023-002',
            'date' => '20/01/2023',
            'type' => 'entree',
            'montant' => 150000,
            'description' => 'Règlement facture'
        ],
        [
            'id' => 4,
            'reference' => 'SORT-2023-002',
            'date' => '25/01/2023',
            'type' => 'sortie',
            'montant' => 50000,
            'description' => 'Frais de transport',
            'beneficiaire' => 'Transporteur ABC',
            'justificatif' => 'Bon de livraison 567'
        ],
        [
            'id' => 5,
            'reference' => 'SORT-2023-003',
            'date' => '30/01/2023',
            'type' => 'sortie',
            'montant' => 150000,
            'description' => 'Achat équipement',
            'beneficiaire' => 'Fournisseur DEF',
            'justificatif' => 'Facture 8910'
        ],
    ];

    public function mount(Partenaire $partenaire)
    {
        $this->partenaire = $partenaire;
    }

    public function filterTransactions(string $type)
    {
        $this->filter = $type;
    }

    public function showTransactionDetail(int $id)
    {
        $this->selectedTransaction = collect($this->fakeTransactions)
            ->firstWhere('id', $id);

        $this->showTransactionModal = true;
    }

    public function closeTransactionModal()
    {
        $this->showTransactionModal = false;
        $this->selectedTransaction = [];
    }

    public function addSortie()
    {
        // Logique pour ajouter une sortie
        // Vous pouvez ouvrir un autre modal ici
    }

    public function editSortie(int $id)
    {
        // Logique pour éditer une sortie
        // Vous pouvez ouvrir un autre modal ici
    }

    public function getTransactionsProperty()
    {
        return collect($this->fakeTransactions)
            ->when($this->filter !== 'all', function ($collection) {
                return $collection->where('type', $this->filter);
            })
            ->sortByDesc('date')
            ->values()
            ->all();
    }
    

    public function render()
    {
        return view('livewire.partenaire.partenaire-show', [
            'transactions' => $this->transactions
        ]);
    }
}
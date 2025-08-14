<?php

namespace App\Livewire\Categorie;

use App\Models\Categorie;
use App\Models\TransactionComptable;
use App\Models\Partenaire;
use Livewire\Component;
use Livewire\WithPagination;

class CategorieShow extends Component
{
    use WithPagination;

    public Categorie $categorie;
    public string $filter = 'all';
    public string $search = '';
    public string $periode = 'all';
    public string $dateDebut = '';
    public string $dateFin = '';

    // Modal transaction
    public bool $showTransactionModal = false;
    public ?array $selectedTransaction = null;

    // Modal création transaction
    public bool $showCreateModal = false;
    public array $newTransaction = [];
    public $partenaires = [];

    protected $queryString = [
        'filter' => ['except' => 'all'],
        'search' => ['except' => ''],
        'periode' => ['except' => 'all'],
        'page' => ['except' => 1],
    ];

    public function mount(Categorie $categorie)
    {
        $this->categorie = $categorie;
        $this->partenaires = Partenaire::active()->orderBy('nom')->get();
        $this->initNewTransaction();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilter()
    {
        $this->resetPage();
    }

    public function updatingPeriode()
    {
        $this->resetPage();
        if ($this->periode === 'mois_courant') {
            $this->dateDebut = now()->startOfMonth()->format('Y-m-d');
            $this->dateFin = now()->endOfMonth()->format('Y-m-d');
        } elseif ($this->periode === 'annee_courante') {
            $this->dateDebut = now()->startOfYear()->format('Y-m-d');
            $this->dateFin = now()->endOfYear()->format('Y-m-d');
        }
    }

    private function initNewTransaction(): void
    {
        $this->newTransaction = [
            'categorie_id' => $this->categorie->id,
            'description' => '',
            'montant' => '',
            'date_transaction' => now()->format('Y-m-d'),
            'type' => $this->categorie->type,
            'partenaire_id' => '',
            'justificatif' => '',
            'notes' => '',
            'statut' => 'validee',
        ];
    }

    public function filterTransactions(string $type): void
    {
        $this->filter = $type;
        $this->resetPage();
    }

    public function showTransactionDetail(int $id): void
    {
        $transaction = TransactionComptable::with(['categorie', 'partenaire'])->findOrFail($id);

        $this->selectedTransaction = [
            'id' => $transaction->id,
            'reference' => $transaction->reference,
            'description' => $transaction->description,
            'montant' => $transaction->montant,
            'montant_formate' => $transaction->montant_formate,
            'date' => $transaction->date_formattee,
            'type' => $transaction->type,
            'statut' => $transaction->statut,
            'partenaire' => $transaction->partenaire?->nom,
            'justificatif' => $transaction->justificatif,
            'notes' => $transaction->notes,
            'categorie' => $transaction->categorie->nom,
            'created_at' => $transaction->created_at->format('d/m/Y à H:i'),
        ];

        $this->showTransactionModal = true;
    }

    public function closeTransactionModal(): void
    {
        $this->showTransactionModal = false;
        $this->selectedTransaction = null;
    }

    public function openCreateModal(): void
    {
        $this->initNewTransaction();
        $this->showCreateModal = true;
    }

    public function closeCreateModal(): void
    {
        $this->showCreateModal = false;
        $this->initNewTransaction();
    }

    public function saveTransaction(): void
    {
        $this->validate([
            'newTransaction.description' => 'required|string|min:3|max:255',
            'newTransaction.montant' => 'required|numeric|min:0.01',
            'newTransaction.date_transaction' => 'required|date',
            'newTransaction.partenaire_id' => 'nullable|exists:partenaires,id',
            'newTransaction.justificatif' => 'nullable|string|max:255',
            'newTransaction.notes' => 'nullable|string|max:500',
        ]);

        $this->newTransaction['reference'] = 'TXN-' . now()->format('YmdHis') . '-' . rand(100, 999);

        TransactionComptable::create($this->newTransaction);

        session()->flash('success', 'Transaction créée avec succès.');
        $this->closeCreateModal();
        $this->resetPage();
    }

    public function deleteTransaction(int $id): void
    {
        $transaction = TransactionComptable::findOrFail($id);
        $transaction->delete();

        session()->flash('success', 'Transaction supprimée avec succès.');
        $this->closeTransactionModal();
        $this->resetPage();
    }

    public function exportTransactions(): void
    {
        // Logique d'export (CSV, Excel, etc.)
        session()->flash('info', 'Export en cours de développement.');
    }

    public function getTransactionsProperty()
    {
        return TransactionComptable::query()
            ->where('categorie_id', $this->categorie->id)
            ->with(['partenaire'])
            ->when($this->search !== '', function ($query) {
                $query->where(function ($q) {
                    $q->where('description', 'like', "%{$this->search}%")
                        ->orWhere('reference', 'like', "%{$this->search}%")
                        ->orWhereHas('partenaire', function ($qq) {
                            $qq->where('nom', 'like', "%{$this->search}%");
                        });
                });
            })
            ->when($this->filter !== 'all', function ($query) {
                if ($this->filter === 'validees') {
                    $query->where('statut', 'validee');
                } elseif ($this->filter === 'en_attente') {
                    $query->where('statut', 'en_attente');
                }
            })
            ->when($this->periode !== 'all', function ($query) {
                if ($this->periode === 'mois_courant') {
                    $query->whereMonth('date_transaction', now()->month)
                        ->whereYear('date_transaction', now()->year);
                } elseif ($this->periode === 'annee_courante') {
                    $query->whereYear('date_transaction', now()->year);
                } elseif ($this->periode === 'personnalisee' && $this->dateDebut && $this->dateFin) {
                    $query->whereBetween('date_transaction', [$this->dateDebut, $this->dateFin]);
                }
            })
            ->orderByDesc('date_transaction')
            ->orderByDesc('id')
            ->paginate(15);
    }

    public function getStatistiquesProperty()
    {
        $baseQuery = TransactionComptable::where('categorie_id', $this->categorie->id);

        return [
            'total_transactions' => $baseQuery->count(),
            'montant_total' => $baseQuery->sum('montant'),
            'montant_mois' => $baseQuery->whereMonth('date_transaction', now()->month)
                ->whereYear('date_transaction', now()->year)
                ->sum('montant'),
            'moyenne_transaction' => $baseQuery->avg('montant') ?? 0,
            'derniere_transaction' => $baseQuery->latest('date_transaction')->first()?->date_formattee,
        ];
    }

    public function render()
    {
        return view('livewire.comptabilite.categorie-show', [
            'transactions' => $this->transactions,
            'statistiques' => $this->statistiques,
        ]);
    }
}
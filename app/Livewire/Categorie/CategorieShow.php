<?php

namespace App\Livewire\Categorie;

use App\Models\Categorie;
use App\Models\TransactionComptable;
use App\Models\Partenaire;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Validate;

class CategorieShow extends Component
{
    use WithPagination;

    public Categorie $categorie;
    public string $filter = 'all';
    public string $search = '';
    public string $periode = 'all';

    // === MODALS ===
    public bool $showFormModal = false;
    public bool $showNewTransactionModal = false;
    public bool $showTransactionDetailModal = false;
    public bool $showDetail = false;

    // === DONNÉES SÉLECTIONNÉES ===
    public ?array $detail = null;
    public array $transactionDetails = [];

    // === FORMULAIRES ===
    // Form Catégorie (création/édition)
    public ?int $editingId = null;
    
    #[Validate('required|string|max:10')]
    public string $code_comptable = '';
    
    #[Validate('required|string|max:255')]
    public string $nom = '';
    
    #[Validate('required|numeric|min:0')]
    public float $budget = 0;
    
    #[Validate('nullable|string|max:1000')]
    public string $description = '';
    
    public bool $is_active = true;

    // Form Nouvelle Transaction
    public array $newTransaction = [];

    // Données de référence
    public $partenaires = [];

    protected $queryString = [
        'filter' => ['except' => 'all'],
        'search' => ['except' => ''],
        'periode' => ['except' => 'all'],
        'page' => ['except' => 1],
    ];

    public function mount(Categorie $categorie): void
    {
        $this->categorie = $categorie;
        $this->loadData();
        $this->initNewTransaction();
    }

    public function loadData(): void
    {
        $this->partenaires = Partenaire::where('is_active', true)->orderBy('nom')->get();
    }

    private function initNewTransaction(): void
    {
        $this->newTransaction = [
            'categorie_id' => $this->categorie->id,
            'description' => '',
            'montant' => '',
            'date_transaction' => now()->format('Y-m-d'),
            'type' => $this->categorie->type ?? 'depense',
            'partenaire_id' => '',
            'justificatif' => '',
            'notes' => '',
            'statut' => 'entrer',
        ];
    }

    private function resetCategorieForm(): void
    {
        $this->editingId = null;
        $this->code_comptable = '';
        $this->nom = '';
        $this->budget = 0;
        $this->description = '';
        $this->is_active = true;
        $this->resetErrorBag();
    }

    // === LISTENERS ===
    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingFilter(): void
    {
        $this->resetPage();
    }

    public function updatingPeriode(): void
    {
        $this->resetPage();
    }

    // === FILTRES ===
    public function filterTransactions(string $type): void
    {
        $this->filter = $type;
        $this->resetPage();
    }

    // === GESTION DES CATÉGORIES ===
    public function closeModal(): void
    {
        $this->showFormModal = false;
        $this->resetCategorieForm();
    }

    public function editModal(int $id): void
    {
        $categorie = Categorie::findOrFail($id);

        $this->editingId = $categorie->id;
        $this->code_comptable = $categorie->code_comptable;
        $this->nom = $categorie->nom;
        $this->budget = (float) $categorie->budget;
        $this->description = $categorie->description ?? '';
        $this->is_active = (bool) $categorie->is_active;

        $this->showFormModal = true;
    }

    public function save(): void
    {
        // Custom validation for unique code_comptable
        $this->validate();
        
        $query = Categorie::where('code_comptable', $this->code_comptable);
        if ($this->editingId) {
            $query->where('id', '!=', $this->editingId);
        }
        if ($query->exists()) {
            $this->addError('code_comptable', 'Ce code comptable existe déjà.');
            return;
        }

        try {
            DB::transaction(function () {
                $data = [
                    'code_comptable' => $this->code_comptable,
                    'nom' => $this->nom,
                    'budget' => $this->budget,
                    'description' => $this->description,
                    'is_active' => $this->is_active,
                ];

                if ($this->editingId) {
                    Categorie::where('id', $this->editingId)->update($data);
                    session()->flash('success', 'Catégorie mise à jour avec succès !');
                } else {
                    Categorie::create($data);
                    session()->flash('success', 'Catégorie créée avec succès !');
                }
            });

            $this->closeModal();
            $this->categorie->refresh();

        } catch (\Throwable $e) {
            session()->flash('error', "Erreur lors de la sauvegarde : " . $e->getMessage());
        }
    }

    // === GESTION DES TRANSACTIONS ===
    public function afficherDetailsRapides(int $categorieId): void
    {
        $this->openNewTransactionModal();
    }

    public function openNewTransactionModal(): void
    {
        $this->resetNewTransactionForm();   
        $this->showNewTransactionModal = true;
    }

    public function closeNewTransactionModal(): void
    {
        $this->showNewTransactionModal = false;
        $this->resetNewTransactionForm();
    }

    public function resetNewTransactionForm(): void
    {
        $this->initNewTransaction();
        $this->resetErrorBag();
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

        try {
            DB::transaction(function () {
                $this->newTransaction['reference'] = 'TXN-' . now()->format('YmdHis') . '-' . rand(100, 999);
                TransactionComptable::create($this->newTransaction);
            });

            session()->flash('success', 'Transaction créée avec succès !');
            $this->closeNewTransactionModal();
            $this->resetPage();

        } catch (\Throwable $e) {
            session()->flash('error', "Erreur lors de la création de la transaction : " . $e->getMessage());
        }
    }

    // === GESTION DES DÉTAILS DE TRANSACTION ===
    public function showTransactionDetail(int $id): void
    {
        $transaction = TransactionComptable::with(['categorie', 'partenaire'])->find($id);

        if ($transaction) {
            $this->transactionDetails = [
                'id' => $transaction->id,
                'reference' => $transaction->reference,
                'description' => $transaction->description,
                'montant' => $transaction->montant,
                'montant_formate' => number_format($transaction->montant, 0, ',', ' ') . ' Ar',
                'date' => $transaction->date_transaction ? $transaction->date_transaction->format('d/m/Y') : '',
                'type' => $transaction->type,
                'statut' => $transaction->statut,
                'partenaire' => $transaction->partenaire?->nom,
                'justificatif' => $transaction->justificatif,
                'notes' => $transaction->notes,
                'categorie' => $transaction->categorie->nom,
                'created_at' => $transaction->created_at->format('d/m/Y à H:i'),
            ];
            $this->showTransactionDetailModal = true;
        }
    }

    public function closeTransactionDetailModal(): void
    {
        $this->showTransactionDetailModal = false;
        $this->transactionDetails = [];
    }

    public function deleteTransaction(int $id): void
    {
        try {
            $transaction = TransactionComptable::findOrFail($id);
            $transaction->delete();

            session()->flash('success', 'Transaction supprimée avec succès.');
            $this->closeTransactionDetailModal();
            $this->resetPage();

        } catch (\Throwable $e) {
            session()->flash('error', 'Erreur lors de la suppression : ' . $e->getMessage());
        }
    }

    // === FONCTIONS UTILITAIRES ===
    public function exportTransactions(): void
    {
        session()->flash('info', 'Export en cours de développement.');
    }

    // === PROPRIÉTÉS CALCULÉES ===
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
                if ($this->filter === 'entrer') {
                    $query->where('statut', 'entrer');
                } elseif ($this->filter === 'sortie') {
                    $query->where('statut', 'sortie');
                }
            })
            ->when($this->periode !== 'all', function ($query) {
                if ($this->periode === 'mois_courant') {
                    $query->whereMonth('date_transaction', now()->month)
                        ->whereYear('date_transaction', now()->year);
                } elseif ($this->periode === 'annee_courante') {
                    $query->whereYear('date_transaction', now()->year);
                }
            })
            ->orderByDesc('date_transaction')
            ->orderByDesc('id')
            ->paginate(15);
    }

    public function getStatistiquesProperty(): array
    {
        $baseQuery = TransactionComptable::where('categorie_id', $this->categorie->id);

        return [
            'total_transactions' => $baseQuery->count(),
            'montant_total' => $baseQuery->sum('montant'),
            'montant_mois' => $baseQuery->whereMonth('date_transaction', now()->month)
                ->whereYear('date_transaction', now()->year)
                ->sum('montant'),
            'moyenne_transaction' => $baseQuery->avg('montant') ?? 0,
            'derniere_transaction' => $baseQuery->latest('date_transaction')->first()?->date_transaction?->format('d/m/Y'),
        ];
    }

    public function render()
    {
        return view('livewire.categorie.categorieShow', [
            'transactions' => $this->transactions,
            'statistiques' => $this->statistiques,
            'partenaires' => $this->partenaires,
        ]);
    }
}
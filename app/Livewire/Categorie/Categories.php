<?php

namespace App\Livewire\Categorie;

use App\Models\Categorie;
use App\Models\TransactionComptable;
use App\Models\Partenaire;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithPagination;

class Categories extends Component
{
    use WithPagination;

    public string $search = '';
    public ?int $editingId = null;

    // Filtre sur les transactions (si tu l'utilises dans withCount)
    public string $filter = 'all';

    #[Validate('required|string|max:10')]
    public string $code_comptable = '';

    #[Validate('required|string|min:2|max:100')]
    public string $nom = '';

    #[Validate('nullable|string|max:255')]
    public ?string $description = null;

    #[Validate('required|numeric|min:0|max:99999999.99')]
    public string $budget = '0';

    #[Validate('boolean')]
    public bool $is_active = true;

    // Modales
    public bool $showDetail = false;
    public ?array $detail = null;
    public bool $showFormModal = false;
    public bool $showTransactionModal = false;
    public bool $showNewTransactionModal = false; // ADD THIS LINE
    public bool $showTransactionDetailModal = false; // ADD THIS LINE

    // Transaction data
    public array $newTransaction = [];
    public ?array $transactionDetails = []; // ADD THIS LINE
    public $partenaires = []; // ADD THIS LINE

    protected $queryString = [
        'search' => ['except' => ''],
        'page' => ['except' => 1],
    ];

    public function mount()
    {
        // Load partenaires for transaction forms
        $this->partenaires = Partenaire::where('is_active', true)->orderBy('nom')->get();
        $this->initNewTransaction();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    private function resetForm(): void
    {
        $this->editingId = null;
        $this->code_comptable = '';
        $this->nom = '';
        $this->description = null;
        $this->budget = '0';
        $this->is_active = true;
    }

    private function initNewTransaction(): void
    {
        $this->newTransaction = [
            'categorie_id' => null,
            'description' => '',
            'montant' => '',
            'date_transaction' => now()->format('Y-m-d'),
            'type' => 'depense',
            'partenaire_id' => '',
            'justificatif' => '',
            'notes' => '',
            'statut' => 'entrer',
        ];
    }

    // Form modale
    public function openModal(): void
    {
        $this->resetForm();
        $this->showFormModal = true;
    }

    public function closeModal(): void
    {
        $this->showFormModal = false;
        $this->resetForm();
    }

    public function editModal(int $id): void
    {
        $categorie = Categorie::findOrFail($id);

        $this->editingId = $categorie->id;
        $this->code_comptable = $categorie->code_comptable;
        $this->nom = $categorie->nom;
        $this->description = $categorie->description;
        $this->budget = (string) $categorie->budget;
        $this->is_active = (bool) $categorie->is_active;

        $this->showFormModal = true;
    }

    public function save(): void
    {
        $data = $this->validate();

        // unicité code_comptable
        $query = Categorie::where('code_comptable', $this->code_comptable);
        if ($this->editingId) {
            $query->where('id', '!=', $this->editingId);
        }
        if ($query->exists()) {
            $this->addError('code_comptable', 'Ce code comptable existe déjà.');
            return;
        }

        if ($this->editingId) {
            Categorie::whereKey($this->editingId)->update($data);
            session()->flash('success', 'Catégorie mise à jour avec succès.');
        } else {
            Categorie::create($data);
            session()->flash('success', 'Catégorie créée avec succès.');
        }

        $this->resetForm();
        $this->showFormModal = false;
        $this->resetPage();
    }

    public function toggle(int $id): void
    {
        $categorie = Categorie::findOrFail($id);
        $categorie->is_active = !$categorie->is_active;
        $categorie->save();

        session()->flash('success', 'Statut mis à jour avec succès.');

        if ($this->showDetail && $this->detail && $this->detail['id'] == $id) {
            $this->detail['is_active'] = (bool) $categorie->is_active;
        }
    }

    public function delete(int $id): void
    {
        $categorie = Categorie::findOrFail($id);

        if ($categorie->transactions()->count() > 0) {
            session()->flash('error', 'Impossible de supprimer cette catégorie car elle contient des transactions.');
            return;
        }

        $categorie->delete();
        session()->flash('success', 'Catégorie supprimée avec succès.');

        if ($this->editingId === $id)
            $this->resetForm();
        if ($this->showDetail && $this->detail && $this->detail['id'] == $id) {
            $this->closeDetail();
        }

        $this->resetPage();
    }

    public function show(int $id): void
    {
        redirect()->route('categorie.categories.show', ['categorie' => $id]);
    }

    public function closeDetail(): void
    {
        $this->showDetail = false;
        $this->detail = null;
    }

    // ADD THESE NEW TRANSACTION METHODS
    public function openNewTransactionModal(): void
    {
        $this->initNewTransaction();
        $this->showNewTransactionModal = true;
        $this->showDetail = false; // Close detail modal if open
    }

    public function closeNewTransactionModal(): void
    {
        $this->showNewTransactionModal = false;
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

        try {
            // Generate unique reference
            $this->newTransaction['reference'] = 'TXN-' . now()->format('YmdHis') . '-' . rand(100, 999);

            TransactionComptable::create($this->newTransaction);

            session()->flash('success', 'Transaction créée avec succès !');
            $this->closeNewTransactionModal();
            $this->resetPage();

            // Refresh detail if open
            if ($this->showDetail && $this->detail && $this->detail['id'] == $this->newTransaction['categorie_id']) {
                $this->afficherDetailsRapides($this->detail['id']);
            }

        } catch (\Throwable $e) {
            session()->flash('error', "Erreur lors de la création de la transaction : " . $e->getMessage());
        }
    }

    // ADD TRANSACTION DETAIL METHODS
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
                'date' => $transaction->date_transaction?->format('d/m/Y'),
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

    // Modale transaction rapide (le type est sur TransactionComptable, pas sur Categorie)
    public function openTransactionModal(int $categorieId): void
    {
        $this->newTransaction = [
            'categorie_id' => $categorieId,
            'description' => '',
            'montant' => '',
            'date_transaction' => now()->format('Y-m-d'),
            'type' => 'depense',
            'partenaire_id' => '',
            'justificatif' => '',
            'notes' => '',
            'statut' => 'entrer',
        ];
        $this->showTransactionModal = true;
    }

    public function closeTransactionModal(): void
    {
        $this->showTransactionModal = false;
        $this->newTransaction = [];
    }

    public function getRowsProperty()
    {
        return Categorie::query()
            ->when($this->search !== '', function ($q) {
                $q->where(function ($qq) {
                    $qq->where('nom', 'like', "%{$this->search}%")
                        ->orWhere('code_comptable', 'like', "%{$this->search}%")
                        ->orWhere('description', 'like', "%{$this->search}%");
                });
            })
            ->withCount([
                'transactions' => function ($query) {
                    if ($this->filter === 'en_attente') {
                        $query->where('statut', 'en_attente');
                    } elseif ($this->filter === 'validees') {
                        $query->where('statut', 'validee');
                    }
                }
            ])
            ->orderBy('code_comptable')
            ->paginate(12);
    }

    public function afficherDetailsRapides(int $id): void
    {
        $categorie = Categorie::with([
            'transactions' => fn($q) => $q->latest('date_transaction')->limit(5),
        ])->findOrFail($id);

        $this->detail = [
            'id' => $categorie->id,
            'code_comptable' => $categorie->code_comptable,
            'nom' => $categorie->nom,
            'description' => $categorie->description,
            'budget' => $categorie->budget,
            'is_active' => (bool) $categorie->is_active,
            'created_at' => optional($categorie->created_at)->format('d/m/Y à H:i'),
            'updated_at' => optional($categorie->updated_at)->format('d/m/Y à H:i'),
            'recent_transactions' => $categorie->transactions->map(function ($t) {
                return [
                    'id' => $t->id,
                    'reference' => $t->reference,
                    'description' => $t->description,
                    'montant' => $t->montant,
                    'date' => $t->date_formattee ?? $t->date_transaction?->format('d/m/Y'),
                    'partenaire' => $t->partenaire?->nom,
                ];
            })->toArray(),
        ];
        $this->showDetail = true;
    }

    public function render()
    {
        return view('livewire.categorie.categorie', [
            'rows' => $this->rows,
            'partenaires' => $this->partenaires,
        ]);
    }
}
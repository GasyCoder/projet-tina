<?php

namespace App\Livewire\Categorie;

use App\Models\Categorie;
use App\Models\TransactionComptable;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithPagination;

class Categories extends Component
{
    use WithPagination;

    public string $search = '';
    public string $filterType = 'all';

    public ?int $editingId = null;

    #[Validate('required|string|max:10')]
    public string $code_comptable = '';

    #[Validate('required|string|min:2|max:100')]
    public string $nom = '';

    #[Validate('nullable|string|max:255')]
    public ?string $description = null;

    #[Validate('required|in:depense,recette')]
    public string $type = 'depense';

    #[Validate('boolean')]
    public bool $is_active = true;

    // Propriétés pour les modales
    public bool $showDetail = false;
    public ?array $detail = null;
    public bool $showFormModal = false;
    public bool $showTransactionModal = false;
    public array $newTransaction = [];

    protected $queryString = [
        'search' => ['except' => ''],
        'filterType' => ['except' => 'all'],
        'page' => ['except' => 1],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterType()
    {
        $this->resetPage();
    }

    private function resetForm(): void
    {
        $this->editingId = null;
        $this->code_comptable = '';
        $this->nom = '';
        $this->description = null;
        $this->type = 'depense';
        $this->is_active = true;
    }

    // Méthodes pour la modal de formulaire
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
        $this->type = $categorie->type;
        $this->is_active = (bool) $categorie->is_active;
        $this->showFormModal = true;
    }

    public function save(): void
    {
        $data = $this->validate();

        // Vérifier l'unicité du code comptable
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

        // Mettre à jour les détails si la modal est ouverte pour cette catégorie
        if ($this->showDetail && $this->detail && $this->detail['id'] == $id) {
            $this->detail['is_active'] = (bool) $categorie->is_active;
        }
    }

    public function delete(int $id): void
    {
        $categorie = Categorie::findOrFail($id);

        // Vérifier s'il y a des transactions liées
        if ($categorie->transactions()->count() > 0) {
            session()->flash('error', 'Impossible de supprimer cette catégorie car elle contient des transactions.');
            return;
        }

        $categorie->delete();
        session()->flash('success', 'Catégorie supprimée avec succès.');

        if ($this->editingId === $id)
            $this->resetForm();

        // Fermer la modal de détails si elle était ouverte pour cette catégorie
        if ($this->showDetail && $this->detail && $this->detail['id'] == $id) {
            $this->closeDetail();
        }

        $this->resetPage();
    }

    public function show(int $id): void
    {
        redirect()->route('categorie.categories.show', ['categorie' => $id]);
    }

    public function showQuick(int $id): void
    {
        $categorie = Categorie::with([
            'transactions' => function ($query) {
                $query->latest('date_transaction')->limit(5);
            }
        ])->findOrFail($id);

        $this->detail = [
            'id' => $categorie->id,
            'code_comptable' => $categorie->code_comptable,
            'nom' => $categorie->nom,
            'description' => $categorie->description,
            'type' => $categorie->type,
            'is_active' => (bool) $categorie->is_active,
            'montant_total' => $categorie->montant_total,
            'transactions_mois' => $categorie->transactions_mois,
            'nombre_transactions' => $categorie->nombre_transactions,
            'created_at' => optional($categorie->created_at)->format('d/m/Y à H:i'),
            'updated_at' => optional($categorie->updated_at)->format('d/m/Y à H:i'),
            'recent_transactions' => $categorie->transactions->map(function ($transaction) {
                return [
                    'id' => $transaction->id,
                    'reference' => $transaction->reference,
                    'description' => $transaction->description,
                    'montant' => $transaction->montant,
                    'date' => $transaction->date_formattee,
                    'partenaire' => $transaction->partenaire?->nom,
                ];
            })->toArray()
        ];
        $this->showDetail = true;
    }

    public function closeDetail(): void
    {
        $this->showDetail = false;
        $this->detail = null;
    }

    // Méthodes pour ajouter une transaction rapide
    public function openTransactionModal(int $categorieId): void
    {
        $this->newTransaction = [
            'categorie_id' => $categorieId,
            'description' => '',
            'montant' => '',
            'date_transaction' => now()->format('Y-m-d'),
            'type' => 'depense',
        ];
        $this->showTransactionModal = true;
    }

    public function closeTransactionModal(): void
    {
        $this->showTransactionModal = false;
        $this->newTransaction = [];
    }

    public function saveTransaction(): void
    {
        $this->validate([
            'newTransaction.description' => 'required|string|min:3',
            'newTransaction.montant' => 'required|numeric|min:0.01',
            'newTransaction.date_transaction' => 'required|date',
        ]);

        $this->newTransaction['reference'] = 'TXN-' . now()->format('YmdHis');

        TransactionComptable::create($this->newTransaction);

        session()->flash('success', 'Transaction ajoutée avec succès.');
        $this->closeTransactionModal();

        // Rafraîchir les données si la modal de détail est ouverte
        if ($this->showDetail) {
            $this->showQuick($this->detail['id']);
        }
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
            ->when($this->filterType !== 'all', fn($q) => $q->where('type', $this->filterType))
            ->withCount('transactions')
            ->orderBy('code_comptable')
            ->paginate(12);
    }

    public function render()
    {
        return view('livewire.categorie.categorie', [
            'rows' => $this->rows
        ]);

    }
}
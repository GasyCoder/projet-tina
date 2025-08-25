<?php

namespace App\Livewire\Categorie;

use App\Models\Categorie;
use App\Models\TransactionComptable;
use App\Models\Partenaire;
use App\Models\Compte;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

class Categories extends Component
{
    use WithPagination;

    public string $search = '';
    public ?int $editingId = null;
    public string $filter = 'all';

    // Formulaire Catégorie
    #[Validate('required|string|max:10')]
    public string $code_comptable = '';

    #[Validate('required|string|min:2|max:100')]
    public string $nom = '';

    #[Validate('required|in:recette,depense')]
    public string $type = 'depense';

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
    public bool $showNewTransactionModal = false;
    public bool $showTransactionDetailModal = false;

    // Transaction data - Structure cohérente avec CategorieShow
    public array $newTransaction = [];
    public ?array $transactionDetails = [];

    // Options de référence cohérentes
    public array $mobileMoneyOptions = ['Mvola', 'OrangeMoney', 'AirtelMoney'];
    public array $banqueOptions = ['BNI', 'BFV', 'BOA', 'BMOI', 'SBM'];

    protected $queryString = [
        'search' => ['except' => ''],
        'page' => ['except' => 1],
    ];

    public function mount()
    {
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
        $this->type = 'depense';
        $this->description = null;
        $this->budget = '0';
        $this->is_active = true;
        $this->resetErrorBag();
    }

    private function initNewTransaction(): void
    {
        $this->newTransaction = [
            'categorie_id' => null,
            'description' => '',
            'montant' => '',
            'date_transaction' => now()->format('Y-m-d'),
            'type' => 'depense', // Sera défini par la catégorie
            'mode_paiement' => 'especes',
            'sous_type_compte' => '', // Nom cohérent
            'partenaire_nom' => '',
            'partenaire_id' => null,
            'justificatif' => '',
            'notes' => '',
            'statut' => 'entrer',
        ];
    }

    // === GESTION DES MODALES ===
    public function openModal(string $type = 'depense'): void
    {
        $this->resetForm();
        $this->type = $type; // Pré-sélectionner le type
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
        $this->type = $categorie->type ?? 'depense';
        $this->description = $categorie->description;
        $this->budget = (string) $categorie->budget;
        $this->is_active = (bool) $categorie->is_active;

        $this->showFormModal = true;
    }

    public function save(): void
    {
        $data = $this->validate();

        // Vérification unicité code_comptable
        $query = Categorie::where('code_comptable', $this->code_comptable);
        if ($this->editingId) {
            $query->where('id', '!=', $this->editingId);
        }
        if ($query->exists()) {
            $this->addError('code_comptable', 'Ce code comptable existe déjà.');
            return;
        }

        try {
            DB::transaction(function () use ($data) {
                if ($this->editingId) {
                    Categorie::whereKey($this->editingId)->update($data);
                    session()->flash('success', 'Catégorie mise à jour avec succès.');
                } else {
                    Categorie::create($data);
                    session()->flash('success', 'Catégorie créée avec succès.');
                }
            });

            $this->resetForm();
            $this->showFormModal = false;
            $this->resetPage();

        } catch (\Throwable $e) {
            session()->flash('error', "Erreur lors de la sauvegarde : " . $e->getMessage());
        }
    }

    public function toggle(int $id): void
    {
        try {
            $categorie = Categorie::findOrFail($id);
            $categorie->is_active = !$categorie->is_active;
            $categorie->save();

            session()->flash('success', 'Statut mis à jour avec succès.');

            // Mise à jour du détail si ouvert
            if ($this->showDetail && $this->detail && $this->detail['id'] == $id) {
                $this->detail['is_active'] = (bool) $categorie->is_active;
            }

        } catch (\Throwable $e) {
            session()->flash('error', 'Erreur lors de la mise à jour : ' . $e->getMessage());
        }
    }

    public function delete(int $id): void
    {
        try {
            $categorie = Categorie::findOrFail($id);

            if ($categorie->transactions()->count() > 0) {
                session()->flash('error', 'Impossible de supprimer cette catégorie car elle contient des transactions.');
                return;
            }

            $categorie->delete();
            session()->flash('success', 'Catégorie supprimée avec succès.');

            if ($this->editingId === $id) {
                $this->resetForm();
            }
            if ($this->showDetail && $this->detail && $this->detail['id'] == $id) {
                $this->closeDetail();
            }

            $this->resetPage();

        } catch (\Throwable $e) {
            session()->flash('error', 'Erreur lors de la suppression : ' . $e->getMessage());
        }
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

    // === GESTION DES TRANSACTIONS ===
    public function afficherDetailsRapides(int $categorieId): void
    {
        $categorie = Categorie::findOrFail($categorieId);

        $this->detail = array_merge(
            $categorie->toArray(),
            $categorie->getStatistiquesCompletes()
        );

        $this->showDetail = true;
    }

    public function openTransactionModal(int $categorieId): void
    {
        $categorie = Categorie::findOrFail($categorieId);

        // Initialiser avec les données de la catégorie
        $this->newTransaction['categorie_id'] = $categorieId;
        $this->newTransaction['type'] = $categorie->type; // Type automatique selon la catégorie

        $this->showNewTransactionModal = true;
        $this->showDetail = false;
    }

    public function closeNewTransactionModal(): void
    {
        $this->showNewTransactionModal = false;
        $this->initNewTransaction();
    }

    // Propriété calculée pour vérifier solde insuffisant (cohérente avec CategorieShow)
    public function getInsuffisantTransactionProperty(): bool
    {
        if (
            empty($this->newTransaction['montant']) ||
            empty($this->newTransaction['mode_paiement']) ||
            $this->newTransaction['type'] === 'recette'
        ) {
            return false; // Pas de vérification pour les recettes
        }

        $montant = (float) $this->newTransaction['montant'];
        $modePaiement = $this->newTransaction['mode_paiement'];
        $sousType = $this->newTransaction['sous_type_compte'] ?? '';

        // Vérifier le solde selon le mode de paiement pour les dépenses uniquement
        if ($modePaiement === 'especes') {
            $compte = Compte::where('type', 'principal')->first();
            return $compte ? $compte->solde < $montant : true;
        } elseif ($modePaiement === 'MobileMoney' && $sousType) {
            $compte = Compte::where('type', 'mobile_money')
                ->where('sous_type', $sousType)
                ->first();
            return $compte ? $compte->solde < $montant : true;
        } elseif ($modePaiement === 'Banque' && $sousType) {
            $compte = Compte::where('type', 'banque')
                ->where('sous_type', $sousType)
                ->first();
            return $compte ? $compte->solde < $montant : true;
        }

        return false;
    }

    public function saveTransaction(): void
    {
        $this->validate([
            'newTransaction.description' => 'required|string|min:3|max:255',
            'newTransaction.montant' => 'required|numeric|min:0.01',
            'newTransaction.date_transaction' => 'required|date',
            'newTransaction.mode_paiement' => 'required|in:especes,MobileMoney,Banque',
            'newTransaction.partenaire_nom' => 'nullable|string|max:255',
            'newTransaction.justificatif' => 'nullable|string|max:255',
            'newTransaction.notes' => 'nullable|string|max:500',
        ]);

        // Validation du sous-type selon le mode de paiement
        if ($this->newTransaction['mode_paiement'] === 'MobileMoney') {
            $this->validate([
                'newTransaction.sous_type_compte' => 'required|in:' . implode(',', $this->mobileMoneyOptions),
            ]);
        } elseif ($this->newTransaction['mode_paiement'] === 'Banque') {
            $this->validate([
                'newTransaction.sous_type_compte' => 'required|in:' . implode(',', $this->banqueOptions),
            ]);
        }

        // Vérification solde insuffisant (uniquement pour les dépenses)
        if ($this->newTransaction['type'] === 'depense' && $this->insuffisantTransaction) {
            $this->addError('newTransaction.montant', 'Solde insuffisant pour cette dépense.');
            return;
        }

        try {
            DB::transaction(function () {
                // Génération référence unique
                $this->newTransaction['reference'] = TransactionComptable::genererReference();

                // Gestion du partenaire
                if (!empty($this->newTransaction['partenaire_nom'])) {
                    $partenaire = Partenaire::firstOrCreate(
                        ['nom' => $this->newTransaction['partenaire_nom']],
                        ['is_active' => true]
                    );
                    $this->newTransaction['partenaire_id'] = $partenaire->id;
                }

                // Nettoyer les données avant création
                unset($this->newTransaction['partenaire_nom']);

                TransactionComptable::create($this->newTransaction);

                // Mise à jour du solde du compte approprié
                $this->updateCompteBalance();
            });

            session()->flash('success', 'Transaction créée avec succès !');
            $this->closeNewTransactionModal();
            $this->resetPage();

            // Refresh detail si ouvert
            if ($this->showDetail && $this->detail && $this->detail['id'] == $this->newTransaction['categorie_id']) {
                $this->afficherDetailsRapides($this->detail['id']);
            }

        } catch (\Throwable $e) {
            session()->flash('error', "Erreur lors de la création de la transaction : " . $e->getMessage());
        }
    }

    private function updateCompteBalance(): void
    {
        $montant = (float) $this->newTransaction['montant'];
        $modePaiement = $this->newTransaction['mode_paiement'];
        $sousType = $this->newTransaction['sous_type_compte'] ?? '';
        $type = $this->newTransaction['type'];

        $compte = null;

        if ($modePaiement === 'especes') {
            $compte = Compte::where('type', 'principal')->first();
        } elseif ($modePaiement === 'MobileMoney' && $sousType) {
            $compte = Compte::where('type', 'mobile_money')
                ->where('sous_type', $sousType)
                ->first();
        } elseif ($modePaiement === 'Banque' && $sousType) {
            $compte = Compte::where('type', 'banque')
                ->where('sous_type', $sousType)
                ->first();
        }

        if ($compte) {
            if ($type === 'recette') {
                $compte->increment('solde', $montant);
            } else {
                $compte->decrement('solde', $montant);
            }
        }
    }

    // === DÉTAILS DE TRANSACTION ===
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
                'mode_paiement' => $transaction->mode_paiement,
                'sous_type_compte' => $transaction->sous_type_compte,
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
            DB::transaction(function () use ($id) {
                $transaction = TransactionComptable::findOrFail($id);

                // Restaurer le solde du compte avant suppression
                $this->restoreCompteBalance($transaction);

                $transaction->delete();
            });

            session()->flash('success', 'Transaction supprimée avec succès.');
            $this->closeTransactionDetailModal();
            $this->resetPage();

            // Refresh detail si ouvert
            if ($this->showDetail && $this->detail) {
                $this->afficherDetailsRapides($this->detail['id']);
            }

        } catch (\Throwable $e) {
            session()->flash('error', 'Erreur lors de la suppression : ' . $e->getMessage());
        }
    }

    private function restoreCompteBalance(TransactionComptable $transaction): void
    {
        $montant = $transaction->montant;
        $modePaiement = $transaction->mode_paiement;
        $sousType = $transaction->sous_type_compte;
        $type = $transaction->type;

        $compte = null;

        if ($modePaiement === 'especes') {
            $compte = Compte::where('type', 'principal')->first();
        } elseif ($modePaiement === 'MobileMoney' && $sousType) {
            $compte = Compte::where('type', 'mobile_money')
                ->where('sous_type', $sousType)
                ->first();
        } elseif ($modePaiement === 'Banque' && $sousType) {
            $compte = Compte::where('type', 'banque')
                ->where('sous_type', $sousType)
                ->first();
        }

        if ($compte) {
            if ($type === 'recette') {
                $compte->decrement('solde', $montant);
            } else {
                $compte->increment('solde', $montant);
            }
        }
    }

    // === PROPRIÉTÉS CALCULÉES ===
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
                    if ($this->filter === 'entrer') {
                        $query->where('statut', 'entrer');
                    } elseif ($this->filter === 'sortie') {
                        $query->where('statut', 'sortie');
                    }
                }
            ])
            ->orderBy('code_comptable')
            ->paginate(12);
    }

    // Accesseur pour la catégorie courante dans la modale de transaction
    public function getCategorieCouranteProperty()
    {
        if ($this->newTransaction['categorie_id']) {
            return Categorie::find($this->newTransaction['categorie_id']);
        }
        return null;
    }

    public function render()
    {
        return view('livewire.categorie.categorie', [
            'rows' => $this->rows,
            'categorieCourante' => $this->categorieCourante,
        ]);
    }
}
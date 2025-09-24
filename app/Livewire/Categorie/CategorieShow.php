<?php

namespace App\Livewire\Categorie;

use App\Models\Categorie;
use App\Models\TransactionComptable;
use App\Models\Partenaire;
use App\Models\Compte;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CategorieShow extends Component
{
    use WithPagination;

    public Categorie $categorie;
    public string $filter = 'all';
    public string $search = '';
    public string $periode = 'all';
    public string $activeTab = 'dashboard'; // dashboard, transactions, analytics, budget

    // === MODALS ===
    public bool $showNewTransactionModal = false;
    public bool $showTransactionDetailModal = false;
    public bool $showBudgetModal = false;
    public bool $showRapportModal = false;

    // === TRANSACTIONS MULTIPLES ===
    public array $transactionsEnCours = [];
    public float $totalTransactions = 0;

    // === DONNÉES SÉLECTIONNÉES ===
    public array $transactionDetails = [];
    public array $dashboardData = [];

    // === FORMULAIRE TRANSACTION ===
    public array $newTransaction = [];

    // === FORMULAIRE BUDGET ===
    public string $nouveauBudget = '';
    public string $objectifMensuel = '';
    public string $seuilAlerte = '';

    // === PÉRIODE POUR RAPPORTS ===
    public string $moisRapport = '';
    public array $rapportData = [];

    // Données de référence
    public $partenaires = [];
    public $comptes = [];

    protected $queryString = [
        'filter' => ['except' => 'all'],
        'search' => ['except' => ''],
        'periode' => ['except' => 'all'],
        'activeTab' => ['except' => 'dashboard'],
        'page' => ['except' => 1],
    ];

    public function mount(Categorie $categorie): void
    {
        $this->categorie = $categorie;
        $this->loadReferenceData();
        $this->loadDashboardData();
        $this->initNewTransaction();
        $this->initBudgetForm();
    }

    private function loadReferenceData(): void
    {
        $this->partenaires = Partenaire::where('is_active', true)
            ->orderBy('nom')
            ->pluck('nom', 'id')
            ->toArray();

        $this->comptes = [
            'principal' => Compte::where('type_compte', 'Principal')->first(),
            'mobile_money' => Compte::where('type_compte', 'MobileMoney')->get(),
            'banque' => Compte::where('type_compte', 'Banque')->get(),
        ];
    }

private function loadDashboardData(): void
{
    $this->dashboardData = $this->categorie->getStatistiquesCompletes();
}

    private function initNewTransaction(): void
    {
        $type = $this->newTransaction['type'] ?? $this->categorie->type;

        $this->newTransaction = [
            'categorie_id' => $this->categorie->id,
            'description' => '',
            'montant' => '',
            'date_transaction' => now()->format('Y-m-d'),
            'type' => $type,
            'mode_paiement' => 'especes',
            'type_compte_mobilemoney_or_banque' => '',
            'partenaire_nom' => '',
            'partenaire_id' => '',
            'notes' => '',
        ];
    }

    private function initBudgetForm(): void
    {
        $this->nouveauBudget = (string) $this->categorie->budget;
        $this->objectifMensuel = (string) $this->categorie->objectif_mensuel;
        $this->seuilAlerte = (string) $this->categorie->seuil_alerte;
    }

    // === GESTION DES ONGLETS ===
    public function switchTab(string $tab): void
    {
        $this->activeTab = $tab;

        if ($tab === 'dashboard') {
            $this->loadDashboardData();
        }

        $this->resetPage();
    }

    // === GESTION MODALS ===
    public function openRecetteModal(): void
    {
        $this->newTransaction['type'] = 'recette';
        $this->showNewTransactionModal = true;
        $this->resetErrorBag();
    }

    public function openDepenseModal(): void
    {
        $this->newTransaction['type'] = 'depense';
        $this->showNewTransactionModal = true;
        $this->resetErrorBag();
    }

    public function closeNewTransactionModal(): void
    {
        $this->showNewTransactionModal = false;
        $this->initNewTransaction();
        $this->transactionsEnCours = [];
        $this->totalTransactions = 0;
        $this->resetErrorBag();
    }

    public function openBudgetModal(): void
    {
        $this->initBudgetForm();
        $this->showBudgetModal = true;
    }

    public function closeBudgetModal(): void
    {
        $this->showBudgetModal = false;
        $this->resetErrorBag();
    }

    public function openRapportModal(): void
    {
        $this->moisRapport = now()->format('Y-m');
        $this->genererRapport();
        $this->showRapportModal = true;
    }

    public function closeRapportModal(): void
    {
        $this->showRapportModal = false;
        $this->rapportData = [];
    }

    // === TRANSACTIONS MULTIPLES ===
    public function addToTransactionList(): void
    {
        $this->validate([
            'newTransaction.description' => 'required|string|min:3|max:255',
            'newTransaction.montant' => 'required|numeric|min:0.01',
            'newTransaction.date_transaction' => 'required|date',
            'newTransaction.mode_paiement' => 'required|in:especes,MobileMoney,Banque',
            'newTransaction.partenaire_nom' => 'nullable|string|max:255',
            'newTransaction.notes' => 'nullable|string|max:1000',
        ]);

        if (in_array($this->newTransaction['mode_paiement'], ['MobileMoney', 'Banque'])) {
            $this->validate([
                'newTransaction.type_compte_mobilemoney_or_banque' => 'required',
            ]);
        }

        if ($this->insuffisantTransaction) {
            $this->addError('newTransaction.montant', 'Solde insuffisant pour ce montant.');
            return;
        }

        $this->transactionsEnCours[] = $this->newTransaction;
        $this->initNewTransaction();
        $this->calculateTotal();
    }

    public function removeFromTransactionList(int $index): void
    {
        if (isset($this->transactionsEnCours[$index])) {
            unset($this->transactionsEnCours[$index]);
            $this->transactionsEnCours = array_values($this->transactionsEnCours);
            $this->calculateTotal();
        }
    }

    public function calculateTotal(): void
    {
        $this->totalTransactions = array_reduce($this->transactionsEnCours, function ($carry, $transaction) {
            return $carry + (float) $transaction['montant'];
        }, 0);
    }

    public function saveAllTransactions(): void
    {
        if (empty($this->transactionsEnCours)) {
            session()->flash('error', 'Aucune transaction à enregistrer.');
            return;
        }

        try {
            DB::transaction(function () {
                foreach ($this->transactionsEnCours as $transaction) {
                    $reference = 'TXN-' . now()->format('YmdHis') . '-' . rand(100, 999);

                    $partenaireId = null;
                    if (!empty($transaction['partenaire_nom'])) {
                        $partenaire = Partenaire::firstOrCreate(
                            ['nom' => $transaction['partenaire_nom']],
                            ['is_active' => true]
                        );
                        $partenaireId = $partenaire->id;
                    }

                    $dataToSave = [
                        'categorie_id' => $transaction['categorie_id'],
                        'description' => $transaction['description'],
                        'montant' => $transaction['montant'],
                        'date_transaction' => $transaction['date_transaction'],
                        'type' => $transaction['type'],
                        'mode_paiement' => $transaction['mode_paiement'],
                        'type_compte_mobilemoney_or_banque' => $transaction['type_compte_mobilemoney_or_banque'] ?? null,
                        'partenaire_id' => $partenaireId,
                        'notes' => $transaction['notes'] ?? null,
                        'reference' => $reference,
                    ];

                    $newTransaction = TransactionComptable::create($dataToSave);
                    $this->updateCompteBalance($newTransaction);
                }
            });

            $count = count($this->transactionsEnCours);
            session()->flash('success', "$count transaction(s) créée(s) avec succès !");

            $this->transactionsEnCours = [];
            $this->totalTransactions = 0;
            $this->closeNewTransactionModal();
            $this->resetPage();
            $this->loadDashboardData(); // Recharger le dashboard

        } catch (\Throwable $e) {
            session()->flash('error', "Erreur lors de la création : " . $e->getMessage());
        }
    }

    // === GESTION BUDGET ===
    public function updateBudget(): void
    {
        $this->validate([
            'nouveauBudget' => 'required|numeric|min:0',
            'objectifMensuel' => 'required|numeric|min:0',
            'seuilAlerte' => 'required|integer|min:1|max:100',
        ]);

        try {
            $this->categorie->update([
                'budget' => $this->nouveauBudget,
                'objectif_mensuel' => $this->objectifMensuel,
                'seuil_alerte' => $this->seuilAlerte,
            ]);

            session()->flash('success', 'Budget mis à jour avec succès !');
            $this->closeBudgetModal();
            $this->loadDashboardData();

        } catch (\Throwable $e) {
            session()->flash('error', "Erreur lors de la mise à jour : " . $e->getMessage());
        }
    }

    // === RAPPORTS ===
public function genererRapport(): void
{
    if (empty($this->moisRapport)) {
        $this->moisRapport = now()->format('Y-m');
    }

    try {
        $mois = Carbon::createFromFormat('Y-m', $this->moisRapport);
        $this->rapportData = $this->categorie->genererRapportMensuel($mois);
    } catch (\Exception $e) {
        session()->flash('error', "Erreur lors de la génération du rapport : " . $e->getMessage());
        $this->rapportData = [];
    }
}

    public function exportRapport(): void
    {
        // Ici vous pourriez implémenter l'export en PDF/Excel
        session()->flash('info', 'Fonctionnalité d\'export en cours de développement.');
    }

    // === FILTRES ===
    public function filterTransactions($filterType): void
    {
        $this->filter = $filterType;
        $this->resetPage();
    }

    // === SUGGESTIONS INTELLIGENTES ===
    public function selectPartenaire($partenairId): void
    {
        $partenaire = Partenaire::find($partenairId);
        if ($partenaire) {
            $this->newTransaction['partenaire_id'] = $partenaire->id;
            $this->newTransaction['partenaire_nom'] = $partenaire->nom;
        }
    }

    public function selectDescriptionTemplate($template): void
    {
        $this->newTransaction['description'] = $template;
    }

    // === VALIDATION SOLDE ===
    public function getInsuffisantTransactionProperty(): bool
    {
        if (empty($this->newTransaction['montant']) || $this->newTransaction['type'] === 'recette') {
            return false;
        }

        $montant = (float) $this->newTransaction['montant'];
        $modePaiement = $this->newTransaction['mode_paiement'];
        $sousType = $this->newTransaction['type_compte_mobilemoney_or_banque'] ?? '';

        $compte = $this->getCompteByMode($modePaiement, $sousType);
        return $compte ? $compte->solde_actuel_mga < $montant : true;
    }

    private function getCompteByMode(string $mode, string $sousType = ''): ?Compte
    {
        switch ($mode) {
            case 'especes':
                return Compte::where('type_compte', 'Principal')->first();
            case 'MobileMoney':
                return $sousType ? Compte::where('type_compte', 'MobileMoney')
                    ->where('type_compte_mobilemoney_or_banque', $sousType)->first() : null;
            case 'Banque':
                return $sousType ? Compte::where('type_compte', 'Banque')
                    ->where('type_compte_mobilemoney_or_banque', $sousType)->first() : null;
            default:
                return null;
        }
    }

    // === SUGGESTIONS BASÉES SUR L'HISTORIQUE ===
    public function getSuggestionsProperty(): array
    {
        $type = $this->newTransaction['type'] ?? $this->categorie->type;

        return [
            'descriptions' => $this->getDescriptionsFrequentes($type),
            'partenaires_frequents' => $this->getPartenairesFrequents($type),
        ];
    }

    private function getDescriptionsFrequentes(string $type): array
    {
        return TransactionComptable::where('type', $type)
            ->where('categorie_id', $this->categorie->id)
            ->select('description')
            ->selectRaw('count(*) as total')
            ->groupBy('description')
            ->orderByDesc('total')
            ->limit(5)
            ->pluck('description')
            ->toArray();
    }

    private function getPartenairesFrequents(string $type): array
    {
        return TransactionComptable::where('type', $type)
            ->where('categorie_id', $this->categorie->id)
            ->whereNotNull('partenaire_id')
            ->with('partenaire')
            ->select('partenaire_id', DB::raw('count(*) as total'))
            ->groupBy('partenaire_id')
            ->orderByDesc('total')
            ->limit(5)
            ->get()
            ->pluck('partenaire.nom', 'partenaire_id')
            ->toArray();
    }

    // === MISE À JOUR SOLDES ===
    private function updateCompteBalance(TransactionComptable $transaction): void
    {
        $montant = (float) $transaction->montant;
        $compte = $this->getCompteByMode(
            $transaction->mode_paiement,
            $transaction->type_compte_mobilemoney_or_banque ?? ''
        );

        if ($compte) {
            if ($transaction->type === 'recette') {
                $compte->increment('solde_actuel_mga', $montant);
            } else {
                $compte->decrement('solde_actuel_mga', $montant);
            }
        }
    }

    // === DÉTAIL TRANSACTION ===
    public function showTransactionDetail(int $id): void
    {
        $transaction = TransactionComptable::with(['categorie', 'partenaire'])->find($id);

        if ($transaction) {
            $this->transactionDetails = [
                'id' => $transaction->id,
                'reference' => $transaction->reference,
                'description' => $transaction->description,
                'montant' => $transaction->montant,
                'montant_formate' => $transaction->montant_formate,
                'date' => $transaction->date_formattee,
                'type' => $transaction->type,
                'statut' => $transaction->statut,
                'mode_paiement' => $transaction->mode_paiement,
                'type_compte_mobilemoney_or_banque' => $transaction->type_compte_mobilemoney_or_banque,
                'partenaire' => $transaction->partenaire?->nom,
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

                // Restaurer le solde
                $compte = $this->getCompteByMode(
                    $transaction->mode_paiement,
                    $transaction->type_compte_mobilemoney_or_banque
                );

                if ($compte) {
                    if ($transaction->type === 'recette') {
                        $compte->decrement('solde_actuel_mga', $transaction->montant);
                    } else {
                        $compte->increment('solde_actuel_mga', $transaction->montant);
                    }
                }

                $transaction->delete();
            });

            session()->flash('success', 'Transaction supprimée avec succès !');
            $this->closeTransactionDetailModal();
            $this->resetPage();
            $this->loadDashboardData();

        } catch (\Throwable $e) {
            session()->flash('error', "Erreur lors de la suppression : " . $e->getMessage());
        }
    }

    // === EXPORT ===
    public function exportTransactions(): void
    {
        session()->flash('info', 'Fonctionnalité d\'export en cours de développement.');
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
                if ($this->filter === 'recette') {
                    $query->where('type', 'recette');
                } elseif ($this->filter === 'depense') {
                    $query->where('type', 'depense');
                } else {
                    $query->where('statut', $this->filter);
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
    return $this->dashboardData['financier'] ?? [];
}

    public function render()
    {
        return view('livewire.categorie.categorieShow', [
            'transactions' => $this->transactions,
            'statistiques' => $this->statistiques,
            'dashboardData' => $this->dashboardData,
            'partenaires' => $this->partenaires,
            'comptes' => $this->comptes,
            'suggestions' => $this->suggestions,
        ]);
    }
}
<?php

namespace App\Livewire\Finance;

use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Log;
use App\Models\Achat;
use App\Models\Compte;

class AchatIndex extends Component
{
    use WithPagination;

    /** UI */
    public $activeTab = 'transactions';
    public $soldeInsuffisantMessage = '';

    /** Filtres */
    public $searchTerm = '';
    public $filterModePaiement = '';
    public $filterDate = '';           // Filtre par date au lieu de statut
    public $filterPersonne = '';
    public $dateDebut = '';
    public $dateFin = '';

    /** Formulaire Achat */
    public $showTransactionModal = false;
    public $editingTransaction = null;
    public $reference = '';
    public $date = '';
    public $from_nom = '';
    public $to_nom = '';
    public $montant = '';              // Unifié : montant au lieu de montant_mga
    public $objet = '';
    public $mode_paiement = 'especes';
    public $statut = true;             // ✅ BOOLEAN : true = confirmé, false = en attente
    public $observation = '';

    /** Règles de validation */
    protected $rules = [
        'reference'     => 'required|string|max:255',
        'date'          => 'required|date',
        'from_nom'      => 'nullable|string|max:255',
        'to_nom'        => 'nullable|string|max:255',
        'montant'       => 'required|numeric|min:0',
        'objet'         => 'required|string|max:255',
        'mode_paiement' => 'required|in:especes,AirtelMoney,Mvola,OrangeMoney,banque',
        'statut'        => 'required|boolean',
        'observation'   => 'nullable|string',
    ];

    /** Messages d'erreur personnalisés */
    protected $messages = [
        'objet.required' => 'L\'objet de la transaction est obligatoire.',
        'montant.required' => 'Le montant est obligatoire.',
        'montant.numeric' => 'Le montant doit être un nombre.',
        'montant.min' => 'Le montant doit être positif.',
        'date.required' => 'La date est obligatoire.',
        'mode_paiement.required' => 'Le mode de paiement est obligatoire.',
        'statut.required' => 'Le statut est obligatoire.',
        'statut.boolean' => 'Le statut doit être valide.',
    ];

    /** Mount */
    public function mount()
    {
        $this->activeTab = in_array(request()->query('tab', 'transactions'), ['transactions','rapports'])
            ? request()->query('tab', 'transactions')
            : 'transactions';

        $this->dateDebut = Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->dateFin   = Carbon::now()->endOfMonth()->format('Y-m-d');

        $this->dispatch('tab-changed', tab: $this->activeTab);
    }

    /** Tabs */
    public function setActiveTab($tab)
    {
        $validTabs = ['transactions', 'rapports'];
        if (!in_array($tab, $validTabs)) {
            Log::warning("Invalid tab requested: {$tab}");
            return;
        }
        $this->activeTab = $tab;
        $this->resetPage();
        $this->dispatch('tab-changed', tab: $tab);
        $this->js("
            const url = new URL(window.location);
            url.searchParams.set('tab', '{$tab}');
            window.history.pushState({}, '', url);
        ");
    }

    /** CRUD Methods */
    public function createTransaction()
    {
        Log::info('createTransaction called', ['activeTab' => $this->activeTab]);
        $this->resetTransactionForm();
        $this->editingTransaction = null;
        $this->reference = $this->generateTransactionReference();
        $this->date = Carbon::now()->format('Y-m-d');
        $this->statut = true;          // ✅ true par défaut = confirmé
        $this->showTransactionModal = true;
        $this->dispatch('open-transaction-modal');
    }

    public function editTransaction($achatId)
    {
        $achat = Achat::findOrFail($achatId);
        
        Log::info('=== DÉBUT ÉDITION ACHAT ===', [
            'achat_id' => $achat->id,
            'montant' => $achat->montant_mga,
            'statut' => $achat->statut
        ]);

        $this->editingTransaction = $achat;
        $this->reference     = $achat->reference;
        $this->date          = $achat->date?->format('Y-m-d');
        $this->from_nom      = $achat->from_nom;
        $this->to_nom        = $achat->to_nom;
        $this->montant       = $achat->montant_mga;  // Mapping
        $this->objet         = $achat->objet;
        $this->mode_paiement = $achat->mode_paiement;
        $this->statut        = (bool) $achat->statut; // ✅ Cast vers boolean
        $this->observation   = $achat->observation;

        $this->showTransactionModal = true;

        Log::info('=== FIN ÉDITION ACHAT ===', [
            'reference' => $this->reference,
            'date' => $this->date,
            'montant' => $this->montant,
            'statut' => $this->statut ? 'confirmé' : 'attente'
        ]);
    }


    public function saveTransaction()
    {
        Log::info('=== DÉBUT SAUVEGARDE TRANSACTION ===', [
            'montant' => $this->montant,
            'statut' => $this->statut ? 'confirmé' : 'attente',
            'objet' => $this->objet,
            'mode_paiement' => $this->mode_paiement,
            'editing' => $this->editingTransaction ? 'true' : 'false'
        ]);

        // Validation avec gestion d'erreurs détaillée
        try {
            $this->validate();
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Erreurs de validation:', [
                'errors' => $e->errors(),
                'data' => [
                    'montant' => $this->montant,
                    'objet' => $this->objet,
                    'date' => $this->date,
                    'statut' => $this->statut
                ]
            ]);
            return;
        }

        // Vérification solde uniquement si transaction confirmée (true)
        if ($this->statut === true && !$this->verifierSoldeCompte()) {
            Log::warning('Vérification solde échouée');
            return;
        }

        // Préparation des données avec mapping correct
        $data = [
            'reference'     => $this->reference,
            'date'          => $this->date,
            'from_nom'      => $this->from_nom ?: null,
            'to_nom'        => $this->to_nom   ?: null,
            'montant_mga'   => $this->montant,          // ✅ Mapping vers montant_mga
            'objet'         => $this->objet,
            'mode_paiement' => $this->mode_paiement,
            'statut'        => (bool) $this->statut,    // ✅ Cast boolean
            'observation'   => $this->observation ?: null,
        ];

        Log::info('Données à sauvegarder:', $data);

        try {
            if ($this->editingTransaction) {
                $this->editingTransaction->update($data);
                flash()->success('Transaction modifiée avec succès');
                Log::info('Transaction modifiée avec succès', ['id' => $this->editingTransaction->id]);
            } else {
                $transaction = Achat::create($data);
                flash()->success('Transaction créée avec succès');
                Log::info('Transaction créée avec succès', ['id' => $transaction->id]);
            }
        } catch (\Exception $e) {
            Log::error('Erreur lors de la sauvegarde:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            flash()->error("Erreur lors de la sauvegarde : {$e->getMessage()}");
            return;
        }

        $this->closeTransactionModal();
    }

    public function deleteTransaction($achatId)
    {
        try {
            $achat = Achat::findOrFail($achatId);
            $achat->delete();
            session()->flash('success', 'Transaction supprimée avec succès');
            Log::info('Transaction supprimée', ['id' => $achat->id]);
        } catch (\Exception $e) {
            session()->flash('error', 'Erreur lors de la suppression');
            Log::error('Erreur suppression transaction', ['error' => $e->getMessage()]);
        }
    }

    public function confirmerTransaction($achatId)
    {
        try {
            $achat = Achat::findOrFail($achatId);
            $achat->update(['statut' => true]); // ✅ Boolean true = confirmé
            session()->flash('success', 'Transaction confirmée');
            Log::info('Transaction confirmée', ['id' => $achat->id]);
        } catch (\Exception $e) {
            session()->flash('error', 'Erreur lors de la confirmation');
            Log::error('Erreur confirmation transaction', ['error' => $e->getMessage()]);
        }
    }

    /** Modal helpers */
    public function closeTransactionModal()
    {
        Log::info('closeTransactionModal called');
        $this->showTransactionModal = false;
        $this->resetTransactionForm();
        $this->editingTransaction = null;
        $this->dispatch('close-transaction-modal');
    }

    private function resetTransactionForm()
    {
        $this->reference = '';
        $this->date = '';
        $this->from_nom = '';
        $this->to_nom = '';
        $this->montant = '';
        $this->objet = '';
        $this->mode_paiement = 'especes';
        $this->statut = true;          // ✅ true par défaut = confirmé
        $this->observation = '';
        $this->soldeInsuffisantMessage = '';
        $this->resetErrorBag();
    }

    private function generateTransactionReference(): string
    {
        $count = Achat::withTrashed()
            ->whereDate('created_at', Carbon::today())
            ->count() + 1;

        $reference = 'ACH' . date('Ymd') . str_pad($count, 3, '0', STR_PAD_LEFT);

        while (Achat::withTrashed()->where('reference', $reference)->exists()) {
            $count++;
            $reference = 'ACH' . date('Ymd') . str_pad($count, 3, '0', STR_PAD_LEFT);
        }

        Log::info('Nouvelle référence ACH générée', [
            'reference' => $reference,
            'count_today' => $count - 1,
            'date' => Carbon::today()->format('Y-m-d')
        ]);

        return $reference;
    }

    /** Filtres */
    public function clearFilters()
    {
        $this->searchTerm = '';
        $this->filterModePaiement = '';
        $this->filterDate = '';
        $this->filterPersonne = '';
        $this->resetPage();
    }

    /** Vérification solde avec gestion boolean */
    private function verifierSoldeCompte(): bool
    {
        if ($this->statut === true && $this->mode_paiement !== 'especes') {
            $typeCompte = match($this->mode_paiement) {
                'AirtelMoney' => 'AirtelMoney',
                'Mvola'       => 'Mvola',
                'OrangeMoney' => 'OrangeMoney',
                'banque'      => 'banque',
                default       => null
            };

            if ($typeCompte) {
                $compte = Compte::where('type_compte', $typeCompte)
                    ->where('actif', true)
                    ->first();

                if (!$compte) {
                    flash()->error("Aucun compte actif trouvé pour le mode de paiement {$this->mode_paiement}.");
                    return false;
                }

                $solde = (float) $compte->solde_actuel_mga;
                if ($solde < (float) $this->montant) {
                    $this->soldeInsuffisantMessage =
                        "Solde insuffisant pour le compte {$typeCompte}. Solde actuel : "
                        . number_format($solde, 0, ',', ' ')
                        . " MGA, requis : "
                        . number_format((float)$this->montant, 0, ',', ' ')
                        . " MGA.";
                    flash()->error($this->soldeInsuffisantMessage);
                    return false;
                }
            }
        }
        return true;
    }

    /** Event listeners mis à jour */
    public function updatedModePaiement()
    {
        if ($this->montant && $this->mode_paiement !== 'especes' && $this->statut === true) {
            $this->verifierSoldeCompte();
        } else {
            $this->resetErrorBag('montant');
            $this->soldeInsuffisantMessage = '';
        }
    }

    public function updatedMontant()
    {
        if ($this->montant && $this->mode_paiement !== 'especes' && $this->statut === true) {
            $this->verifierSoldeCompte();
        } else {
            $this->resetErrorBag('montant');
            $this->soldeInsuffisantMessage = '';
        }
    }

    public function updatedStatut()
    {
        if ($this->montant && $this->mode_paiement !== 'especes' && $this->statut === true) {
            $this->verifierSoldeCompte();
        } else {
            $this->resetErrorBag('montant');
            $this->soldeInsuffisantMessage = '';
        }
    }

    /** Render avec filtres par date */
    public function render()
    {
        Log::info('Rendering AchatIndex', ['activeTab' => $this->activeTab]);

        $query = Achat::query()
            ->when($this->searchTerm, function ($q) {
                $q->where(function ($subQ) {
                    $subQ->where('reference', 'like', '%' . $this->searchTerm . '%')
                         ->orWhere('objet', 'like', '%' . $this->searchTerm . '%')
                         ->orWhere('from_nom', 'like', '%' . $this->searchTerm . '%')
                         ->orWhere('to_nom', 'like', '%' . $this->searchTerm . '%');
                });
            })
            ->when($this->filterModePaiement, fn($q) => $q->where('mode_paiement', $this->filterModePaiement))
            ->when($this->filterDate, function($q) {
                // Filtre par date
                switch($this->filterDate) {
                    case 'today':
                        $q->whereDate('date', Carbon::today());
                        break;
                    case 'week':
                        $q->whereBetween('date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                        break;
                    case 'month':
                        $q->whereBetween('date', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()]);
                        break;
                    case 'year':
                        $q->whereBetween('date', [Carbon::now()->startOfYear(), Carbon::now()->endOfYear()]);
                        break;
                }
            })
            ->when($this->filterPersonne, function ($q) {
                $q->where(function ($subQ) {
                    $subQ->where('from_nom', 'like', '%' . $this->filterPersonne . '%')
                         ->orWhere('to_nom', 'like', '%' . $this->filterPersonne . '%');
                });
            })
            ->when($this->dateDebut && $this->dateFin, fn($q) => $q->whereBetween('date', [$this->dateDebut, $this->dateFin]))
            ->orderBy('date', 'desc');

        $transactions = $query->paginate(15);

        // Statistiques avec boolean
        $totalSorties = Achat::query()
            ->when($this->dateDebut && $this->dateFin, fn($q) => $q->whereBetween('date', [$this->dateDebut, $this->dateFin]))
            ->where('statut', true)  // ✅ true = confirmé
            ->sum('montant_mga');

        $transactionsEnAttente = Achat::query()
            ->when($this->dateDebut && $this->dateFin, fn($q) => $q->whereBetween('date', [$this->dateDebut, $this->dateFin]))
            ->where('statut', false)  // ✅ false = en attente
            ->count();

        // Statistiques pour compatibilité avec la vue
        $totalEntrees = 0;
        $beneficeNet = 0 - $totalSorties;
        $revenusEnAttente = 0;
        
        $repartitionParStatut = Achat::select('statut')
            ->selectRaw('COUNT(*) as count')
            ->when($this->dateDebut && $this->dateFin, fn($q) => $q->whereBetween('date', [$this->dateDebut, $this->dateFin]))
            ->groupBy('statut')
            ->pluck('count', 'statut');

        $repartitionParType = Achat::select('mode_paiement as type')
            ->selectRaw('COUNT(*) as count, SUM(montant_mga) as total')
            ->when($this->dateDebut && $this->dateFin, fn($q) => $q->whereBetween('date', [$this->dateDebut, $this->dateFin]))
            ->groupBy('mode_paiement')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->type => ['count' => (int)$item->count, 'total' => (float)$item->total]];
            });

        $comptes = Compte::where('actif', true)->get();

        // Variables de compatibilité (pour anciennes vues)
        $voyagesDisponibles = collect();
        $dechargementsDisponibles = collect();
        $produitsDisponibles = collect();
        $produitSelectionne = null;
        $transactionsVoyage = collect();
        $transactionsAutre = collect();
        $revenus = collect();
        $depenses = $transactions;
        $totalRevenus = 0;
        $revenuMoyen = 0;
        $nombreRevenus = 0;
        $totalDepenses = $totalSorties;
        $depenseMoyenne = ($transactions->total() > 0) ? (float) $totalSorties / $transactions->total() : 0;
        $depensesEnAttente = Achat::where('statut', false)->sum('montant_mga');  // ✅ false = attente
        $nombreDepenses = $transactions->total();
        $repartitionDepenses = $repartitionParType;
        $repartitionRevenus = collect();

        return view('livewire.finance.achat-index', compact(
            'transactions',
            'comptes',
            'voyagesDisponibles',
            'dechargementsDisponibles',
            'produitsDisponibles',
            'produitSelectionne',
            'totalEntrees',
            'totalSorties',
            'beneficeNet',
            'transactionsEnAttente',
            'repartitionParType',
            'repartitionParStatut',
            'transactionsVoyage',
            'transactionsAutre',
            'revenus',
            'depenses',
            'totalRevenus',
            'revenuMoyen',
            'nombreRevenus',
            'revenusEnAttente',
            'totalDepenses',
            'depenseMoyenne',
            'depensesEnAttente',
            'nombreDepenses',
            'repartitionDepenses',
            'repartitionRevenus'
        ));
    }
}
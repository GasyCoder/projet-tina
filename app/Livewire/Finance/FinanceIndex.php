<?php

namespace App\Livewire\Finance;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Transaction;
use App\Models\Compte;
use App\Models\Voyage;
use App\Models\User;
use App\Models\Produit;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class FinanceIndex extends Component
{
    use WithPagination;

    public $activeTab = 'suivi';

    // Filtres existants
    public $searchTerm = '';
    public $filterType = '';
    public $filterStatut = '';
    public $dateDebut = '';
    public $dateFin = '';
    public $filterPersonne = '';

    // ✅ NOUVEAUX FILTRES POUR LES FONCTIONNALITÉS AJOUTÉES
    public $typeSuivi = 'tous'; // tous, voyage, autre
    
    // Filtres revenus
    public $periodeRevenus = 'mois';
    public $dateDebutRevenus = '';
    public $dateFinRevenus = '';
    
    // Filtres dépenses
    public $categorieDepense = '';
    public $periodeDepenses = 'mois';
    public $dateDebutDepenses = '';
    public $dateFinDepenses = '';
    
    // Transaction form - VOTRE CODE EXISTANT
    public $showTransactionModal = false;
    public $editingTransaction = null;
    public $reference = '';
    public $date = '';
    public $type = '';
    public $from_nom = '';
    public $from_compte = '';
    public $to_nom = '';
    public $to_compte = '';
    public $montant_mga = '';
    public $objet = '';
    public $voyage_id = '';
    public $mode_paiement = 'especes';
    public $statut = 'confirme';
    public $observation = '';
    
    // ✅ AJOUT DU CHAMP RESTE_À_PAYER
    public $reste_a_payer = '';

    // Compte form - VOTRE CODE EXISTANT
    public $showCompteModal = false;
    public $editingCompte = null;
    public $nom_proprietaire = '';
    public $type_compte = 'principal';
    public $nom_compte = '';
    public $numero_compte = '';
    public $solde_actuel_mga = 0;
    public $compte_actif = true;

    protected $rules = [
        // Transaction rules - ✅ SELON VOS VRAIES TABLES
        'reference' => 'required|string|max:255',
        'date' => 'required|date',
        'montant_mga' => 'required|numeric|min:0',
        'objet' => 'required|string',
        'mode_paiement' => 'required|in:especes,mobile_money,banque,credit',
        'statut' => 'required|in:attente,confirme,annule,payee,partiellement_payee',

        // Compte rules - ✅ SELON VOS VRAIES TABLES
        'type_compte' => 'required|in:principal,mobile_money,banque,credit',
        'nom_compte' => 'required|string|max:255',
        'solde_actuel_mga' => 'required|numeric',

        'type' => 'required|in:achat,vente,transfert,frais,commission,paiement,avance,depot,retrait,Autre',
        'voyage_id' => 'nullable|exists:voyages,id',
        'reste_a_payer' => 'required_if:statut,partiellement_payee|numeric|min:0',
    ];

    public function mount()
    {
        $this->dateDebut = Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->dateFin = Carbon::now()->endOfMonth()->format('Y-m-d');
    }
public function setActiveTab($tab)
{
    $this->activeTab = $tab;
    $this->resetPage();
    
    // Réinitialiser les filtres de période
    $this->periodeRevenus = 'mois';
    $this->periodeDepenses = 'mois';
    $this->dateDebutRevenus = '';
    $this->dateFinRevenus = '';
    $this->dateDebutDepenses = '';
    $this->dateFinDepenses = '';
}

public function updatedPeriodeRevenus()
{
    $this->resetPage();
}

public function updatedPeriodeDepenses()
{
    $this->resetPage();
}

public function updatedCategorieDepense()
{
    $this->resetPage();
}

    // =====================================================
    // ✅ NOUVELLES PROPRIÉTÉS CALCULÉES POUR LES STATISTIQUES
    // =====================================================

    public function getTotalEntreesProperty()
    {
        // ✅ UTILISATION DIRECTE DES TYPES AU LIEU DES SCOPES
        return Transaction::whereIn('type', ['vente', 'depot', 'commission'])
            ->where('statut', '!=', 'annule')
            ->whereBetween('date', [$this->dateDebut, $this->dateFin])
            ->sum('montant_mga');
    }

    public function getTotalSortiesProperty()
    {
        // ✅ UTILISATION DIRECTE DES TYPES AU LIEU DES SCOPES
        return Transaction::whereIn('type', ['achat', 'frais', 'avance', 'paiement', 'retrait', 'transfert'])
            ->where('statut', '!=', 'annule')
            ->whereBetween('date', [$this->dateDebut, $this->dateFin])
            ->sum('montant_mga');
    }

    public function getBeneficeNetProperty()
    {
        return $this->totalEntrees - $this->totalSorties;
    }

    public function getTransactionsEnAttenteProperty()
    {
        return Transaction::where('statut', 'attente')->count();
    }

    // =====================================================
    // ✅ NOUVELLES MÉTHODES POUR SUIVI CONDITIONNEL
    // =====================================================

    public function getTransactionsVoyageProperty()
    {
        return Transaction::with(['voyage'])
            ->whereNotNull('voyage_id')
            ->whereBetween('date', [$this->dateDebut, $this->dateFin])
            ->where('statut', '!=', 'annule')
            ->orderBy('date', 'desc')
            ->get();
    }

    public function getTransactionsAutreProperty()
    {
        return Transaction::whereNull('voyage_id')
            ->whereBetween('date', [$this->dateDebut, $this->dateFin])
            ->where('statut', '!=', 'annule')
            ->orderBy('date', 'desc')
            ->get();
    }

    public function getRepartitionParTypeProperty()
    {
        return Transaction::whereBetween('date', [$this->dateDebut, $this->dateFin])
            ->where('statut', '!=', 'annule')
            ->select('type', DB::raw('COUNT(*) as count'), DB::raw('SUM(montant_mga) as total'))
            ->groupBy('type')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->type => ['count' => $item->count, 'total' => $item->total]];
            })
            ->toArray();
    }

    public function getRepartitionParStatutProperty()
    {
        return Transaction::whereBetween('date', [$this->dateDebut, $this->dateFin])
            ->select('statut', DB::raw('COUNT(*) as count'))
            ->groupBy('statut')
            ->pluck('count', 'statut')
            ->toArray();
    }

    // =====================================================
    // ✅ NOUVELLES MÉTHODES POUR REVENUS
    // =====================================================

    public function getRevenusProperty()
    {
        $dates = $this->getDateRangeRevenus();
        
        return Transaction::with(['voyage'])
            ->whereIn('type', ['vente', 'depot', 'commission']) // Types de revenus
            ->whereBetween('date', [$dates['debut'], $dates['fin']])
            ->where('statut', '!=', 'annule')
            ->orderBy('date', 'desc')
            ->paginate(10);
    }

    public function getTotalRevenusProperty()
    {
        $dates = $this->getDateRangeRevenus();
        
        return Transaction::whereIn('type', ['vente', 'depot', 'commission'])
            ->whereBetween('date', [$dates['debut'], $dates['fin']])
            ->where('statut', '!=', 'annule')
            ->sum('montant_mga');
    }

    public function getRevenuMoyenProperty()
    {
        $total = $this->totalRevenus;
        $count = $this->nombreRevenus;
        
        return $count > 0 ? $total / $count : 0;
    }

    public function getNombreRevenusProperty()
    {
        $dates = $this->getDateRangeRevenus();
        
        return Transaction::whereIn('type', ['vente', 'depot', 'commission'])
            ->whereBetween('date', [$dates['debut'], $dates['fin']])
            ->where('statut', '!=', 'annule')
            ->count();
    }

    private function getDateRangeRevenus()
    {
          if ($this->periodeRevenus === 'personnalise' && $this->dateDebutRevenus && $this->dateFinRevenus) {
        return [
            'debut' => $this->dateDebutRevenus,
            'fin' => $this->dateFinRevenus
        ];
    }
        switch ($this->periodeRevenus) {
            case 'semaine':
                return [
                    'debut' => Carbon::now()->startOfWeek()->format('Y-m-d'),
                    'fin' => Carbon::now()->endOfWeek()->format('Y-m-d')
                ];
            case 'trimestre':
                return [
                    'debut' => Carbon::now()->startOfQuarter()->format('Y-m-d'),
                    'fin' => Carbon::now()->endOfQuarter()->format('Y-m-d')
                ];
            case 'annee':
                return [
                    'debut' => Carbon::now()->startOfYear()->format('Y-m-d'),
                    'fin' => Carbon::now()->endOfYear()->format('Y-m-d')
                ];
            default: // mois
                return [
                    'debut' => Carbon::now()->startOfMonth()->format('Y-m-d'),
                    'fin' => Carbon::now()->endOfMonth()->format('Y-m-d')
                ];
        }
    }

    // =====================================================
    // ✅ NOUVELLES MÉTHODES POUR DÉPENSES
    // =====================================================

    public function getDepensesProperty()
    {
        $dates = $this->getDateRangeDepenses();
        
        $query = Transaction::with(['voyage'])
            ->whereIn('type', ['achat', 'frais', 'avance', 'paiement', 'retrait', 'transfert']) // Types de dépenses
            ->whereBetween('date', [$dates['debut'], $dates['fin']])
            ->where('statut', '!=', 'annule');

        if ($this->categorieDepense) {
            $query->where('type', $this->categorieDepense);
        }

        return $query->orderBy('date', 'desc')->paginate(10);
    }

    public function getTotalDepensesProperty()
    {
        $dates = $this->getDateRangeDepenses();
        
        $query = Transaction::whereIn('type', ['achat', 'frais', 'avance', 'paiement', 'retrait', 'transfert'])
            ->whereBetween('date', [$dates['debut'], $dates['fin']])
            ->where('statut', '!=', 'annule');

        if ($this->categorieDepense) {
            $query->where('type', $this->categorieDepense);
        }

        return $query->sum('montant_mga');
    }

    public function getDepenseMoyenneProperty()
    {
        $total = $this->totalDepenses;
        $count = $this->nombreDepenses;
        
        return $count > 0 ? $total / $count : 0;
    }

    public function getDepensesEnAttenteProperty()
    {
        $dates = $this->getDateRangeDepenses();
        
        return Transaction::whereIn('type', ['achat', 'frais', 'avance', 'paiement', 'retrait', 'transfert'])
            ->whereBetween('date', [$dates['debut'], $dates['fin']])
            ->where('statut', 'attente')
            ->sum('montant_mga');
    }

    public function getNombreDepensesProperty()
    {
        $dates = $this->getDateRangeDepenses();
        
        $query = Transaction::whereIn('type', ['achat', 'frais', 'avance', 'paiement', 'retrait', 'transfert'])
            ->whereBetween('date', [$dates['debut'], $dates['fin']])
            ->where('statut', '!=', 'annule');

        if ($this->categorieDepense) {
            $query->where('type', $this->categorieDepense);
        }

        return $query->count();
    }

    public function getRepartitionDepensesProperty()
    {
        $dates = $this->getDateRangeDepenses();
        
        return Transaction::whereIn('type', ['achat', 'frais', 'avance', 'paiement', 'retrait', 'transfert'])
            ->whereBetween('date', [$dates['debut'], $dates['fin']])
            ->where('statut', '!=', 'annule')
            ->select('type', DB::raw('COUNT(*) as count'), DB::raw('SUM(montant_mga) as total'))
            ->groupBy('type')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->type => ['count' => $item->count, 'total' => $item->total]];
            })
            ->toArray();
    }

    private function getDateRangeDepenses()
    {
        if ($this->periodeDepenses === 'personnalise') {
            return [
                'debut' => $this->dateDebutDepenses ?: Carbon::now()->startOfMonth()->format('Y-m-d'),
                'fin' => $this->dateFinDepenses ?: Carbon::now()->endOfMonth()->format('Y-m-d')
            ];
        }

        switch ($this->periodeDepenses) {
            case 'semaine':
                return [
                    'debut' => Carbon::now()->startOfWeek()->format('Y-m-d'),
                    'fin' => Carbon::now()->endOfWeek()->format('Y-m-d')
                ];
            case 'trimestre':
                return [
                    'debut' => Carbon::now()->startOfQuarter()->format('Y-m-d'),
                    'fin' => Carbon::now()->endOfQuarter()->format('Y-m-d')
                ];
            case 'annee':
                return [
                    'debut' => Carbon::now()->startOfYear()->format('Y-m-d'),
                    'fin' => Carbon::now()->endOfYear()->format('Y-m-d')
                ];
            default: // mois
                return [
                    'debut' => Carbon::now()->startOfMonth()->format('Y-m-d'),
                    'fin' => Carbon::now()->endOfMonth()->format('Y-m-d')
                ];
        }
    }

    // =====================================================
    // TRANSACTIONS - VOTRE CODE EXISTANT AMÉLIORÉ
    // =====================================================

    public function createTransaction()
    {
        $this->resetTransactionForm();
        $this->editingTransaction = null;
        $this->reference = $this->generateTransactionReference();
        $this->date = Carbon::now()->format('Y-m-d');
        $this->showTransactionModal = true;
    }

    public function editTransaction(Transaction $transaction)
    {
        $this->editingTransaction = $transaction;
        $this->reference = $transaction->reference;
        $this->date = $transaction->date->format('Y-m-d');
        $this->type = $transaction->type;
        $this->from_nom = $transaction->from_nom;
        $this->from_compte = $transaction->from_compte;
        $this->to_nom = $transaction->to_nom;
        $this->to_compte = $transaction->to_compte;
        $this->montant_mga = $transaction->montant_mga;
        $this->objet = $transaction->objet;
        $this->voyage_id = $transaction->voyage_id;
        $this->mode_paiement = $transaction->mode_paiement;
        $this->statut = $transaction->statut;
        $this->reste_a_payer = $transaction->reste_a_payer; // ✅ Ajouté
        $this->observation = $transaction->observation;
        $this->showTransactionModal = true;
    }

    public function saveTransaction()
    {
        $rules = [
            'reference' => 'required|string|max:255',
            'date' => 'required|date',
            'type' => 'required|in:achat,vente,transfert,frais,commission,paiement,avance,depot,retrait,Autre',
            'montant_mga' => 'required|numeric|min:0',
            'objet' => 'required|string',
            'mode_paiement' => 'required|in:especes,mobile_money,banque,credit',
            'statut' => 'required|in:attente,confirme,annule,payee,partiellement_payee',
        ];

        // ✅ VALIDATION CONDITIONNELLE POUR RESTE À PAYER
        if ($this->statut === 'partiellement_payee') {
            $rules['reste_a_payer'] = 'required|numeric|min:0';
        }

        $this->validate($rules);

        $data = [
            'reference' => $this->reference,
            'date' => $this->date,
            'type' => $this->type,
            'from_nom' => $this->from_nom ?: null,
            'from_compte' => $this->from_compte ?: null,
            'to_nom' => $this->to_nom ?: null,
            'to_compte' => $this->to_compte ?: null,
            'montant_mga' => $this->montant_mga,
            'objet' => $this->objet,
            'voyage_id' => $this->voyage_id ?: null,
            'mode_paiement' => $this->mode_paiement,
            'statut' => $this->statut,
            'reste_a_payer' => $this->statut === 'partiellement_payee' ? $this->reste_a_payer : null, // ✅ Ajouté
            'observation' => $this->observation ?: null,
        ];

        if ($this->editingTransaction) {
            $this->editingTransaction->update($data);
            session()->flash('success', 'Transaction modifiée avec succès');
        } else {
            Transaction::create($data);
            session()->flash('success', 'Transaction ajoutée avec succès');
        }

        $this->closeTransactionModal();
    }

    public function deleteTransaction(Transaction $transaction)
    {
        $transaction->delete();
        session()->flash('success', 'Transaction supprimée avec succès');
    }

    public function confirmerTransaction(Transaction $transaction)
    {
        $transaction->update(['statut' => 'confirme']);
        session()->flash('success', 'Transaction confirmée');
    }

    // ✅ NOUVELLE MÉTHODE POUR MARQUER COMME PAYÉE
    public function marquerPayee($id)
    {
        $transaction = Transaction::findOrFail($id);
        $transaction->update(['statut' => 'payee']);
        
        session()->flash('success', 'Transaction marquée comme payée !');
    }

    // =====================================================
    // COMPTES - VOTRE CODE EXISTANT
    // =====================================================

    public function createCompte()
    {
        $this->resetCompteForm();
        $this->editingCompte = null;
        $this->showCompteModal = true;
    }

    public function editCompte(Compte $compte)
    {
        $this->editingCompte = $compte;
        $this->nom_proprietaire = $compte->nom_proprietaire;
        $this->type_compte = $compte->type_compte;
        $this->nom_compte = $compte->nom_compte;
        $this->numero_compte = $compte->numero_compte;
        $this->solde_actuel_mga = $compte->solde_actuel_mga;
        $this->compte_actif = $compte->actif;
        $this->showCompteModal = true;
    }

    public function saveCompte()
    {
        $this->validate([
            'type_compte' => 'required|in:principal,mobile_money,banque,credit',
            'nom_compte' => 'required|string|max:255',
            'solde_actuel_mga' => 'required|numeric',
        ]);

        if ($this->editingCompte) {
            $this->editingCompte->update([
                'nom_proprietaire' => $this->nom_proprietaire ?: null,
                'type_compte' => $this->type_compte,
                'nom_compte' => $this->nom_compte,
                'numero_compte' => $this->numero_compte ?: null,
                'solde_actuel_mga' => $this->solde_actuel_mga,
                'actif' => $this->compte_actif,
            ]);
            session()->flash('success', 'Compte modifié avec succès');
        } else {
            Compte::create([
                'nom_proprietaire' => $this->nom_proprietaire ?: null,
                'type_compte' => $this->type_compte,
                'nom_compte' => $this->nom_compte,
                'numero_compte' => $this->numero_compte ?: null,
                'solde_actuel_mga' => $this->solde_actuel_mga,
                'actif' => $this->compte_actif,
            ]);
            session()->flash('success', 'Compte ajouté avec succès');
        }

        $this->closeCompteModal();
    }

    public function deleteCompte(Compte $compte)
    {
        $compte->delete();
        session()->flash('success', 'Compte supprimé avec succès');
    }

    // =====================================================
    // MODAL MANAGEMENT - VOTRE CODE EXISTANT AMÉLIORÉ
    // =====================================================

    public function closeTransactionModal()
    {
        $this->showTransactionModal = false;
        $this->resetTransactionForm();
        $this->editingTransaction = null;
    }

    public function closeCompteModal()
    {
        $this->showCompteModal = false;
        $this->resetCompteForm();
        $this->editingCompte = null;
    }

    private function resetTransactionForm()
    {
        $this->reference = '';
        $this->date = '';
        $this->type = '';
        $this->from_nom = '';
        $this->from_compte = '';
        $this->to_nom = '';
        $this->to_compte = '';
        $this->montant_mga = '';
        $this->objet = '';
        $this->voyage_id = '';
        $this->mode_paiement = 'especes';
        $this->statut = 'confirme';
        $this->reste_a_payer = ''; // ✅ Ajouté
        $this->observation = '';
        $this->resetErrorBag();
    }

    private function resetCompteForm()
    {
        $this->nom_proprietaire = '';
        $this->type_compte = 'principal';
        $this->nom_compte = '';
        $this->numero_compte = '';
        $this->solde_actuel_mga = 0;
        $this->compte_actif = true;
        $this->resetErrorBag();
    }

    private function generateTransactionReference()
    {
        $count = Transaction::whereDate('created_at', Carbon::today())->count() + 1;
        return 'TXN' . date('Ymd') . str_pad($count, 3, '0', STR_PAD_LEFT);
    }

    // =====================================================
    // ✅ MÉTHODES EXISTANTES QUE VOUS AVIEZ PEUT-ÊTRE
    // =====================================================

    public function updatedSearchTerm()
    {
        $this->resetPage();
    }

    public function updatedFilterType()
    {
        $this->resetPage();
    }

    public function updatedFilterStatut()
    {
        $this->resetPage();
    }

    public function clearFilters()
    {
        $this->searchTerm = '';
        $this->filterType = '';
        $this->filterStatut = '';
        $this->filterPersonne = '';
        $this->resetPage();
    }

    // =====================================================
    // ✅ MÉTHODE DE DEBUG (TEMPORAIRE)
    // =====================================================
    
    public function debugData()
    {
        $revenus = $this->revenus;
        $depenses = $this->depenses;
        
        session()->flash('debug', [
            'revenus_count' => $revenus->total(),
            'depenses_count' => $depenses->total(),
            'total_revenus' => $this->totalRevenus,
            'total_depenses' => $this->totalDepenses,
        ]);
    }

    public function render()
    {
        // ✅ STATISTIQUES SELON VOS VRAIS TYPES ET SCOPES
        $totalEntrees = $this->totalEntrees;
        $totalSorties = $this->totalSorties;
        $beneficeNet = $this->beneficeNet;
        $transactionsEnAttente = $this->transactionsEnAttente;

        // Transactions paginées avec filtres - VOTRE CODE EXISTANT
        $query = Transaction::with(['voyage'])
            ->when($this->searchTerm, function ($q) {
                $q->where(function ($subQ) {
                    $subQ->where('reference', 'like', '%' . $this->searchTerm . '%')
                        ->orWhere('objet', 'like', '%' . $this->searchTerm . '%');
                });
            })
            ->when($this->filterType, fn($q) => $q->where('type', $this->filterType))
            ->when($this->filterStatut, fn($q) => $q->where('statut', $this->filterStatut))
            ->when($this->filterPersonne, function($q) {
                $q->where(function($subQ) {
                    $subQ->where('from_nom', 'like', '%' . $this->filterPersonne . '%')
                        ->orWhere('to_nom', 'like', '%' . $this->filterPersonne . '%');
                });
            })
            ->when($this->dateDebut && $this->dateFin, fn($q) => $q->whereBetween('date', [$this->dateDebut, $this->dateFin]))
            ->orderBy('date', 'desc');

        $transactions = $query->paginate(15);

        // Comptes actifs - VOTRE CODE EXISTANT
        $comptes = Compte::where('actif', true)->get();

        // Données pour les selects - VOTRE CODE EXISTANT
        $voyages = Voyage::select('id', 'reference')->latest()->limit(50)->get();
        $users = collect(); // Collection vide pour compatibilité

        // ✅ AJOUT DES VARIABLES MANQUANTES POUR LES VUES
        $repartitionParType = $this->repartitionParType;
        $repartitionParStatut = $this->repartitionParStatut;
        $transactionsVoyage = $this->transactionsVoyage;
        $transactionsAutre = $this->transactionsAutre;
        $revenus = $this->revenus;
        $depenses = $this->depenses;
        $totalRevenus = $this->totalRevenus;
        $revenuMoyen = $this->revenuMoyen;
        $nombreRevenus = $this->nombreRevenus;
        $totalDepenses = $this->totalDepenses;
        $depenseMoyenne = $this->depenseMoyenne;
        $depensesEnAttente = $this->depensesEnAttente;
        $nombreDepenses = $this->nombreDepenses;
        $repartitionDepenses = $this->repartitionDepenses;

        return view('livewire.finance.finance-index', compact(
            'transactions',
            'comptes',
            'voyages',
            'users',
            'totalEntrees',
            'totalSorties',
            'beneficeNet',
            'transactionsEnAttente',
            // ✅ NOUVELLES VARIABLES POUR LES VUES
            'repartitionParType',
            'repartitionParStatut',
            'transactionsVoyage',
            'transactionsAutre',
            'revenus',
            'depenses',
            'totalRevenus',
            'revenuMoyen',
            'nombreRevenus',
            'totalDepenses',
            'depenseMoyenne',
            'depensesEnAttente',
            'nombreDepenses',
            'repartitionDepenses'
        ));
    }
}
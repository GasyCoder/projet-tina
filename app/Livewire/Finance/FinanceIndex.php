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

class FinanceIndex extends Component
{
    use WithPagination;

    public $activeTab = 'dashboard';
    
    // Filtres
    public $searchTerm = '';
    public $filterType = '';
    public $filterStatut = '';
    public $dateDebut = '';
    public $dateFin = '';
    public $filterPersonne = '';
    
    // Transaction form
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

    // Compte form
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
        'statut' => 'required|in:attente,confirme,annule',

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
    }

    // TRANSACTIONS
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
        $this->observation = $transaction->observation;
        $this->showTransactionModal = true;
    }

    public function saveTransaction()
    {
        $this->validate([
            'reference' => 'required|string|max:255',
            'date' => 'required|date',
            'type' => 'required|in:achat,vente,transfert,frais,commission,paiement,avance,depot,retrait',
            'montant_mga' => 'required|numeric|min:0',
            'objet' => 'required|string',
            'mode_paiement' => 'required|in:especes,mobile_money,banque,credit',
            'statut' => 'required|in:attente,confirme,annule',
        ]);

        if ($this->editingTransaction) {
            $this->editingTransaction->update([
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
                'observation' => $this->observation ?: null,
            ]);
            session()->flash('success', 'Transaction modifiée avec succès');
        } else {
            Transaction::create([
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
                'observation' => $this->observation ?: null,
            ]);
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

    // COMPTES
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

    // MODAL MANAGEMENT
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

    public function render()
    {
        // ✅ STATISTIQUES SELON VOS VRAIS TYPES
        $totalEntrees = Transaction::confirme()
            ->entrees() // vente, depot, transfert
            ->periode($this->dateDebut, $this->dateFin)
            ->sum('montant_mga');

        $totalSorties = Transaction::confirme()
            ->sorties() // achat, frais, commission, paiement, avance, retrait
            ->periode($this->dateDebut, $this->dateFin)
            ->sum('montant_mga');

        $beneficeNet = $totalEntrees - $totalSorties;

        $transactionsEnAttente = Transaction::where('statut', 'attente')->count();

        // Transactions paginées avec filtres
        $query = Transaction::with(['fromUser', 'toUser', 'voyage'])
            ->when($this->searchTerm, function ($q) {
                $q->where(function ($subQ) {
                    $subQ->where('reference', 'like', '%' . $this->searchTerm . '%')
                        ->orWhere('objet', 'like', '%' . $this->searchTerm . '%');
                });
            })
            ->when($this->filterType, fn($q) => $q->where('type', $this->filterType))
            ->when($this->filterStatut, fn($q) => $q->where('statut', $this->filterStatut))
            ->when($this->filterPersonne, fn($q) => $q->parPersonne($this->filterPersonne))
            ->when($this->dateDebut && $this->dateFin, fn($q) => $q->periode($this->dateDebut, $this->dateFin))
            ->orderBy('date', 'desc');

        $transactions = $query->paginate(15);

        // Comptes actifs
        $comptes = Compte::actif()->get();

        // Données pour les selects
        $voyages = Voyage::select('id', 'reference')->latest()->limit(50)->get();
        
        // ✅ AJOUTER CETTE LIGNE pour éviter l'erreur (même si on n'utilise plus les users)
        $users = collect(); // Collection vide pour compatibilité

        return view('livewire.finance.finance-index', compact(
            'transactions',
            'comptes',
            'voyages',
            'users',  // ✅ Ajouté pour éviter l'erreur
            'totalEntrees',
            'totalSorties',
            'beneficeNet',
            'transactionsEnAttente'
        ));
    }
}
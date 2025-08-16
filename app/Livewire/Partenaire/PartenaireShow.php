<?php
namespace App\Livewire\Partenaire;

use App\Models\Partenaire;
use App\Models\PartenaireTransaction;
use App\Models\PartenaireTransactionDetail;
use App\Models\Produit;
use App\Models\Compte;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class PartenaireShow extends Component
{
    public Partenaire $partenaire;
    public string $filter = 'all';

    // Modal states
    public bool $showTransactionModal = false;
    public bool $showNewEntreeModal = false;
    public bool $showNewSortieModal = false;
    public bool $showTransactionDetailModal = false;

    // Selected transaction
    public ?PartenaireTransaction $selectedTransaction = null;
    public array $transactionDetails = [];

    // Formulaire Nouvelle Entrée
    public array $entreeForm = [
        'montant_mga' => '',
        'motif' => '',
        'mode_paiement' => 'especes',
        'compte_source_id' => '',
        'observation' => ''
    ];

    // Formulaire Nouvelle Sortie avec détails
    public array $sortieForm = [
        'montant_total' => '',
        'motif' => '',
        'observation' => ''
    ];

    public array $sortieDetails = [];
    public array $newDetail = [
        'type_detail' => 'achat_produit',
        'produit_id' => '',
        'description' => '',
        'quantite' => '',
        'prix_unitaire_mga' => '',
        'montant_mga' => ''
    ];

    // Données
    public $produits = [];
    public $comptes = [];

    protected $rules = [
        'entreeForm.montant_mga' => 'required|numeric|min:0',
        'entreeForm.motif' => 'required|string|max:255',
        'entreeForm.mode_paiement' => 'required|in:especes,AirtelMoney,OrangeMoney,Mvola,banque',
        'entreeForm.compte_source_id' => 'required|exists:comptes,id',

        'sortieForm.montant_total' => 'required|numeric|min:0',
        'sortieForm.motif' => 'required|string|max:255',

        'newDetail.type_detail' => 'required|in:achat_produit,credit,frais,autre',
        'newDetail.description' => 'required|string|max:255',
        'newDetail.montant_mga' => 'required|numeric|min:0',
    ];

    public function mount(Partenaire $partenaire)
    {
        $this->partenaire = $partenaire;
        $this->loadData();
    }

    public function loadData()
    {
        $this->produits = Produit::actif()->get();
        $this->comptes = Compte::actif()->get();
    }

    // Gestion des filtres
    public function filterTransactions(string $type)
    {
        $this->filter = $type;
    }

    // Nouvelle Entrée
    public function openNewEntreeModal()
    {
        $this->resetEntreeForm();
        $this->showNewEntreeModal = true;
    }

    public function closeNewEntreeModal()
    {
        $this->showNewEntreeModal = false;
        $this->resetEntreeForm();
    }

    public function resetEntreeForm()
    {
        $this->entreeForm = [
            'montant_mga' => '',
            'motif' => '',
            'mode_paiement' => 'especes',
            'compte_source_id' => '',
            'observation' => ''
        ];
        $this->resetErrorBag();
    }

    public function creerEntree()
    {
        $this->validate([
            'entreeForm.montant_mga' => 'required|numeric|min:0',
            'entreeForm.motif' => 'required|string|max:255',
            'entreeForm.mode_paiement' => 'required|in:especes,AirtelMoney,OrangeMoney,Mvola,banque',
            'entreeForm.compte_source_id' => 'required|exists:comptes,id',
        ]);

        try {
            DB::transaction(function () {
                $compteSource = Compte::find($this->entreeForm['compte_source_id']);

                // Vérifier si le compte source a assez de fonds
                if ($compteSource->solde_actuel_mga < $this->entreeForm['montant_mga']) {
                    throw new \Exception('Solde insuffisant dans le compte source.');
                }

                // Créer la transaction
                $transaction = PartenaireTransaction::create([
                    'partenaire_id' => $this->partenaire->id,
                    'reference' => PartenaireTransaction::genererReference('entree'),
                    'type' => 'entree',
                    'montant_mga' => $this->entreeForm['montant_mga'],
                    'motif' => $this->entreeForm['motif'],
                    'mode_paiement' => $this->entreeForm['mode_paiement'],
                    'compte_source_id' => $this->entreeForm['compte_source_id'],
                    'compte_destination_id' => $this->partenaire->compte_id,
                    'statut' => true,
                    'date_transaction' => now(),
                    'observation' => $this->entreeForm['observation']
                ]);

                // Débiter le compte source (Mme Tina)
                $compteSource->decrement('solde_actuel_mga', $this->entreeForm['montant_mga']);

                // Créditer le partenaire
                $this->partenaire->ajouterEntree($this->entreeForm['montant_mga']);

                // Créditer le compte du partenaire si existe
                if ($this->partenaire->compte_id) {
                    $compteDestination = Compte::find($this->partenaire->compte_id);
                    $compteDestination->increment('solde_actuel_mga', $this->entreeForm['montant_mga']);
                }
            });

            session()->flash('success', 'Entrée créée avec succès !');
            $this->closeNewEntreeModal();
            $this->partenaire->refresh();

        } catch (\Exception $e) {
            session()->flash('error', 'Erreur lors de la création de l\'entrée : ' . $e->getMessage());
        }
    }

    // Nouvelle Sortie
    public function openNewSortieModal()
    {
        $this->resetSortieForm();
        $this->showNewSortieModal = true;
    }

    public function closeNewSortieModal()
    {
        $this->showNewSortieModal = false;
        $this->resetSortieForm();
    }

    public function resetSortieForm()
    {
        $this->sortieForm = [
            'montant_total' => '',
            'motif' => '',
            'observation' => ''
        ];
        $this->sortieDetails = [];
        $this->resetNewDetail();
        $this->resetErrorBag();
    }

    public function resetNewDetail()
    {
        $this->newDetail = [
            'type_detail' => 'achat_produit',
            'produit_id' => '',
            'description' => '',
            'quantite' => '',
            'prix_unitaire_mga' => '',
            'montant_mga' => ''
        ];
    }

    public function calculerMontantDetail()
    {
        if ($this->newDetail['quantite'] && $this->newDetail['prix_unitaire_mga']) {
            $this->newDetail['montant_mga'] = $this->newDetail['quantite'] * $this->newDetail['prix_unitaire_mga'];
        }
    }

    public function ajouterDetail()
    {
        $this->validate([
            'newDetail.type_detail' => 'required|in:achat_produit,credit,frais,autre',
            'newDetail.description' => 'required|string|max:255',
            'newDetail.montant_mga' => 'required|numeric|min:0',
        ]);

        $detail = $this->newDetail;
        $detail['id'] = uniqid(); // ID temporaire pour le frontend
        $this->sortieDetails[] = $detail;

        $this->resetNewDetail();
        $this->calculerMontantTotal();
    }

    public function supprimerDetail($index)
    {
        unset($this->sortieDetails[$index]);
        $this->sortieDetails = array_values($this->sortieDetails);
        $this->calculerMontantTotal();
    }

    public function calculerMontantTotal()
    {
        $total = array_sum(array_column($this->sortieDetails, 'montant_mga'));
        $this->sortieForm['montant_total'] = $total;
    }

    public function creerSortie()
    {
        $this->validate([
            'sortieForm.montant_total' => 'required|numeric|min:0',
            'sortieForm.motif' => 'required|string|max:255',
        ]);

        if (empty($this->sortieDetails)) {
            session()->flash('error', 'Veuillez ajouter au moins un détail à la sortie.');
            return;
        }

        try {
            DB::transaction(function () {
                // Vérifier si le partenaire a assez de fonds
                if ($this->partenaire->solde_actuel_mga < $this->sortieForm['montant_total']) {
                    throw new \Exception('Solde insuffisant pour effectuer cette sortie.');
                }

                // Créer la transaction
                $transaction = PartenaireTransaction::create([
                    'partenaire_id' => $this->partenaire->id,
                    'reference' => PartenaireTransaction::genererReference('sortie'),
                    'type' => 'sortie',
                    'montant_mga' => $this->sortieForm['montant_total'],
                    'motif' => $this->sortieForm['motif'],
                    'statut' => true,
                    'date_transaction' => now(),
                    'observation' => $this->sortieForm['observation']
                ]);

                // Créer les détails
                foreach ($this->sortieDetails as $detail) {
                    PartenaireTransactionDetail::create([
                        'transaction_id' => $transaction->id,
                        'produit_id' => $detail['produit_id'] ?: null,
                        'description' => $detail['description'],
                        'quantite' => $detail['quantite'] ?: 0,
                        'prix_unitaire_mga' => $detail['prix_unitaire_mga'] ?: 0,
                        'montant_mga' => $detail['montant_mga'],
                        'type_detail' => $detail['type_detail']
                    ]);
                }

                // Débiter le partenaire
                $this->partenaire->retirerSortie($this->sortieForm['montant_total']);

                // Débiter le compte du partenaire si existe
                if ($this->partenaire->compte_id) {
                    $comptePartenaire = Compte::find($this->partenaire->compte_id);
                    $comptePartenaire->decrement('solde_actuel_mga', $this->sortieForm['montant_total']);
                }
            });

            session()->flash('success', 'Sortie créée avec succès !');
            $this->closeNewSortieModal();
            $this->partenaire->refresh();

        } catch (\Exception $e) {
            session()->flash('error', 'Erreur lors de la création de la sortie : ' . $e->getMessage());
        }
    }

    // Affichage des détails de transaction
    public function showTransactionDetail($transactionId)
    {
        $this->selectedTransaction = PartenaireTransaction::with(['details.produit', 'compteSource', 'compteDestination'])
            ->find($transactionId);

        if ($this->selectedTransaction) {
            $this->transactionDetails = $this->selectedTransaction->details->toArray();
            $this->showTransactionDetailModal = true;
        }
    }

    public function closeTransactionDetailModal()
    {
        $this->showTransactionDetailModal = false;
        $this->selectedTransaction = null;
        $this->transactionDetails = [];
    }

    // Propriétés calculées
    public function getTransactionsProperty()
    {
        $query = $this->partenaire->transactions()
            ->with(['details.produit'])
            ->latest('date_transaction');

        if ($this->filter !== 'all') {
            $query->where('type', $this->filter);
        }

        return $query->get();
    }

    public function getStatistiquesProperty()
    {
        return [
            'total_entrees' => $this->partenaire->total_entrees_mois,
            'total_sorties' => $this->partenaire->total_sorties_mois,
            'solde_actuel' => $this->partenaire->solde_actuel_mga,
        ];
    }

    public function render()
    {
        return view('livewire.partenaire.partenaire-show', [
            'transactions' => $this->transactions,
            'statistiques' => $this->statistiques,
            'produits' => $this->produits,
            'comptes' => $this->comptes,
        ]);
    }
}
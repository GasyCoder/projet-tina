<?php

// Composant 1: Vente Enhanced
// app/Livewire/Stocks/VenteEnhanced.php

namespace App\Livewire\Stocks;

use App\Models\Vente;
use App\Models\Produit;
use App\Models\Lieu;
use App\Models\Chargement;
use App\Models\AlerteStock;
use App\Models\Depots;
use App\Models\Retour;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;


class RetourEnhanced extends Component
{
    use WithPagination, WithFileUploads;

    // Propriétés de filtrage
    public $search = '';
    public $filterStatut = '';
    public $filterMotif = '';
    public $filterEtat = '';
    public $perPage = 25;
    public $sortField = 'date_retour';
    public $sortDirection = 'desc';

    // Modal de gestion
    public $showRetourModal = false;
    public $showDetailsModal = false;
    public $showTraitementModal = false;
    public $editingRetour = null;
    public $retourDetails = null;

    // Formulaire de retour
    public $numero_retour = '';
    public $date_retour = '';
    public $vente_id = '';
    public $produit_id = '';
    public $lieu_stockage_id = '';
    public $client_nom = '';
    public $client_contact = '';
    public $quantite_retour_kg = '';
    public $sacs_pleins_retour = 0;
    public $sacs_demi_retour = 0;
    public $motif_retour = '';
    public $description_motif = '';
    public $responsabilite = 'client';
    public $etat_produit = '';
    public $produit_revendable = true;
    public $valeur_recuperable_mga = 0;
    public $perte_estimee_mga = 0;
    public $statut_retour = 'en_attente';
    public $observations = '';

    // Traitement du retour
    public $action_prise = '';
    public $montant_rembourse_mga = 0;
    public $transporteur_retour = '';
    public $frais_retour_mga = 0;
    public $prise_charge_frais = 'client';

    // Photos et documents
    public $photos_produit = [];
    public $documents_justificatifs = [];

    // Statistiques
    public $stats = [];

    protected $listeners = [
        'refreshRetours' => '$refresh',
        'retourCreated' => 'handleRetourCreated'
    ];

    protected $rules = [
        'date_retour' => 'required|date',
        'produit_id' => 'required|exists:produits,id',
        'lieu_stockage_id' => 'required|exists:lieux,id',
        'client_nom' => 'required|string|max:255',
        'quantite_retour_kg' => 'required|numeric|min:0.01',
        'motif_retour' => 'required|in:defaut_qualite,erreur_livraison,annulation_client,surplus,autre',
        'description_motif' => 'required|string|min:10',
        'etat_produit' => 'required|in:excellent,bon,moyen,mauvais,inutilisable',
        'responsabilite' => 'required|in:client,transporteur,vendeur,produit'
    ];

    public function mount()
    {
        $this->date_retour = today()->format('Y-m-d');
        $this->calculateStats();
    }

    // Calculs automatiques
    public function updatedQuantiteRetourKg()
    {
        $this->calculateValeurs();
    }

    public function updatedEtatProduit()
    {
        $this->produit_revendable = in_array($this->etat_produit, ['excellent', 'bon']);
        $this->calculateValeurs();
    }

    public function updatedMotifRetour()
    {
        if ($this->motif_retour === 'defaut_qualite') {
            $this->responsabilite = 'vendeur';
        } elseif ($this->motif_retour === 'erreur_livraison') {
            $this->responsabilite = 'transporteur';
        }
    }

    private function calculateValeurs()
    {
        if ($this->vente_id && $this->quantite_retour_kg) {
            $vente = Vente::find($this->vente_id);
            if ($vente) {
                $valeurOriginale = $this->quantite_retour_kg * $vente->prix_unitaire_mga;

                if ($this->produit_revendable) {
                    $tauxRecuperation = match ($this->etat_produit) {
                        'excellent' => 0.95,
                        'bon' => 0.85,
                        'moyen' => 0.70,
                        default => 0.50
                    };
                    $this->valeur_recuperable_mga = $valeurOriginale * $tauxRecuperation;
                    $this->perte_estimee_mga = $valeurOriginale - $this->valeur_recuperable_mga;
                } else {
                    $this->valeur_recuperable_mga = 0;
                    $this->perte_estimee_mga = $valeurOriginale;
                }
            }
        }
    }

    // Gestion des modals
    public function openRetourModal($retourId = null)
    {
        if ($retourId) {
            $this->editingRetour = Retour::findOrFail($retourId);
            $this->fillForm();
        } else {
            $this->resetForm();
            $this->generateNumeroRetour();
        }
        $this->showRetourModal = true;
    }

    public function closeRetourModal()
    {
        $this->showRetourModal = false;
        $this->resetForm();
    }

    public function showDetails($retourId)
    {
        $this->retourDetails = Retour::with(['vente', 'produit', 'lieuStockage'])
            ->findOrFail($retourId);
        $this->showDetailsModal = true;
    }

    public function closeDetailsModal()
    {
        $this->showDetailsModal = false;
        $this->retourDetails = null;
    }

    public function openTraitementModal($retourId)
    {
        $this->editingRetour = Retour::findOrFail($retourId);
        $this->showTraitementModal = true;
    }

    public function closeTraitementModal()
    {
        $this->showTraitementModal = false;
        $this->reset(['action_prise', 'montant_rembourse_mga']);
    }

    // Sauvegarde
    public function saveRetour()
    {
        $this->validate();

        DB::beginTransaction();
        try {
            $this->calculateValeurs();

            $data = [
                'numero_retour' => $this->numero_retour,
                'date_retour' => $this->date_retour,
                'vente_id' => $this->vente_id ?: null,
                'produit_id' => $this->produit_id,
                'lieu_stockage_id' => $this->lieu_stockage_id,
                'client_nom' => $this->client_nom,
                'client_contact' => $this->client_contact,
                'quantite_retour_kg' => $this->quantite_retour_kg,
                'sacs_pleins_retour' => $this->sacs_pleins_retour,
                'sacs_demi_retour' => $this->sacs_demi_retour,
                'motif_retour' => $this->motif_retour,
                'description_motif' => $this->description_motif,
                'responsabilite' => $this->responsabilite,
                'etat_produit' => $this->etat_produit,
                'produit_revendable' => $this->produit_revendable,
                'valeur_recuperable_mga' => $this->valeur_recuperable_mga,
                'perte_estimee_mga' => $this->perte_estimee_mga,
                'statut_retour' => $this->statut_retour,
                'transporteur_retour' => $this->transporteur_retour,
                'frais_retour_mga' => $this->frais_retour_mga,
                'prise_charge_frais' => $this->prise_charge_frais,
                'observations' => $this->observations,
                'user_reception_id' => Auth::id(),
            ];

            if ($this->editingRetour) {
                $this->editingRetour->update($data);
                $retour = $this->editingRetour;
                $action = 'modifié';
            } else {
                $retour = Retour::create($data);
                $action = 'créé';
            }

            DB::commit();
            session()->flash('success', "↩️ Retour {$action} avec succès !");
            $this->dispatch('retourCreated', $retour->id);
            $this->closeRetourModal();
            $this->calculateStats();

        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Erreur lors de la sauvegarde : ' . $e->getMessage());
        }
    }

    // Actions sur les retours
    public function accepterRetour($retourId)
    {
        $retour = Retour::findOrFail($retourId);
        $retour->accepter();

        session()->flash('success', '✅ Retour accepté avec succès !');
        $this->calculateStats();
    }

    public function refuserRetour($retourId, $motif)
    {
        $retour = Retour::findOrFail($retourId);
        $retour->refuser($motif);

        session()->flash('success', '❌ Retour refusé !');
        $this->calculateStats();
    }

    public function traiterRetour()
    {
        $this->validate([
            'action_prise' => 'required|in:remboursement,echange,avoir,destruction,revente_reduite',
            'montant_rembourse_mga' => 'nullable|numeric|min:0'
        ]);

        $this->editingRetour->traiter($this->action_prise, $this->montant_rembourse_mga);

        session()->flash('success', '✅ Retour traité avec succès !');
        $this->closeTraitementModal();
        $this->calculateStats();
    }

    // Méthodes utilitaires
    private function fillForm()
    {
        $r = $this->editingRetour;
        $this->numero_retour = $r->numero_retour;
        $this->date_retour = $r->date_retour->format('Y-m-d');
        $this->vente_id = $r->vente_id;
        $this->produit_id = $r->produit_id;
        $this->lieu_stockage_id = $r->lieu_stockage_id;
        $this->client_nom = $r->client_nom;
        $this->client_contact = $r->client_contact;
        $this->quantite_retour_kg = $r->quantite_retour_kg;
        $this->sacs_pleins_retour = $r->sacs_pleins_retour;
        $this->sacs_demi_retour = $r->sacs_demi_retour;
        $this->motif_retour = $r->motif_retour;
        $this->description_motif = $r->description_motif;
        $this->responsabilite = $r->responsabilite;
        $this->etat_produit = $r->etat_produit;
        $this->produit_revendable = $r->produit_revendable;
        $this->valeur_recuperable_mga = $r->valeur_recuperable_mga;
        $this->perte_estimee_mga = $r->perte_estimee_mga;
        $this->statut_retour = $r->statut_retour;
        $this->observations = $r->observations;
    }

    private function resetForm()
    {
        $this->reset([
            'numero_retour',
            'vente_id',
            'produit_id',
            'lieu_stockage_id',
            'client_nom',
            'client_contact',
            'quantite_retour_kg',
            'sacs_pleins_retour',
            'sacs_demi_retour',
            'motif_retour',
            'description_motif',
            'etat_produit',
            'valeur_recuperable_mga',
            'perte_estimee_mga',
            'observations',
            'transporteur_retour',
            'frais_retour_mga'
        ]);

        $this->responsabilite = 'client';
        $this->statut_retour = 'en_attente';
        $this->prise_charge_frais = 'client';
        $this->produit_revendable = true;
        $this->editingRetour = null;
    }

    private function generateNumeroRetour()
    {
        $count = Retour::whereDate('date_retour', $this->date_retour ?: today())->count() + 1;
        $this->numero_retour = 'RET' . date('Ymd', strtotime($this->date_retour ?: today())) . str_pad($count, 4, '0', STR_PAD_LEFT);
    }

    private function calculateStats()
    {
        $thisMonth = Carbon::now()->startOfMonth();

        $this->stats = [
            'retours_mois' => Retour::where('date_retour', '>=', $thisMonth)->count(),
            'retours_en_attente' => Retour::where('statut_retour', 'en_attente')->count(),
            'retours_acceptes' => Retour::where('statut_retour', 'accepte')->count(),
            'valeur_recuperable' => Retour::where('produit_revendable', true)->sum('valeur_recuperable_mga'),
            'perte_totale' => Retour::sum('perte_estimee_mga'),
            'retours_par_motif' => Retour::selectRaw('motif_retour, COUNT(*) as count')
                ->groupBy('motif_retour')
                ->pluck('count', 'motif_retour')
                ->toArray()
        ];
    }

    // Propriétés calculées
    public function getRetoursProperty()
    {
        return Retour::with(['vente', 'produit', 'lieuStockage'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('numero_retour', 'like', '%' . $this->search . '%')
                        ->orWhere('client_nom', 'like', '%' . $this->search . '%')
                        ->orWhereHas('produit', function ($subQ) {
                            $subQ->where('nom', 'like', '%' . $this->search . '%');
                        });
                });
            })
            ->when($this->filterStatut, fn($q) => $q->where('statut_retour', $this->filterStatut))
            ->when($this->filterMotif, fn($q) => $q->where('motif_retour', $this->filterMotif))
            ->when($this->filterEtat, fn($q) => $q->where('etat_produit', $this->filterEtat))
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);
    }

    public function getProduitsProperty()
    {
        return Produit::orderBy('nom')->get();
    }

    public function getLieuxProperty()
    {
        return Lieu::where('type', 'depot')->orderBy('nom')->get();
    }

    public function getVentesProperty()
    {
        return Vente::where('statut_vente', 'livree')
            ->orderBy('date_vente', 'desc')
            ->limit(100)
            ->get();
    }

    public function render()
    {
        return view('livewire.stocks.retour-enhanced', [
            'retours' => $this->retours,
            'produits' => $this->produits,
            'lieux' => $this->lieux,
            'ventes' => $this->ventes,
            'stats' => $this->stats
        ]);
    }
}
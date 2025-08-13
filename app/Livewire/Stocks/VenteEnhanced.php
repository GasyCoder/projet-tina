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
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class VenteEnhanced extends Component
{
    use WithPagination, WithFileUploads;

    // Propriétés de filtrage et recherche
    public $search = '';
    public $filterStatut = '';
    public $filterPeriod = '';
    public $filterPaiement = '';
    public $filterClient = '';
    public $perPage = 25;
    public $sortField = 'date_vente';
    public $sortDirection = 'desc';

    // Propriétés du modal de vente
    public $showVenteModal = false;
    public $editingVente = null;
    public $showDetailsModal = false;
    public $venteDetails = null;

    // Formulaire de vente
    public $numero_vente = '';
    public $date_vente = '';
    public $chargement_id = '';
    public $produit_id = '';
    public $lieu_livraison_id = '';
    public $client_nom = '';
    public $client_contact = '';
    public $client_adresse = '';
    public $client_type = 'particulier';
    public $quantite_kg = '';
    public $sacs_pleins = 0;
    public $sacs_demi = 0;
    public $prix_unitaire_mga = '';
    public $prix_total_mga = '';
    public $montant_paye_mga = 0;
    public $montant_restant_mga = '';
    public $statut_paiement = 'impaye';
    public $mode_paiement = '';
    public $transporteur_nom = '';
    public $vehicule_immatriculation = '';
    public $frais_transport_mga = 0;
    public $date_livraison_prevue = '';
    public $statut_vente = 'brouillon';
    public $qualite_produit = 'bon';
    public $observations = '';
    public $remarques_client = '';
    public $tva_taux = 0;

    // Export et impression
    public $showExportModal = false;
    public $exportFormat = 'excel';
    public $exportDateDebut = '';
    public $exportDateFin = '';

    // Statistiques
    public $stats = [];
    public $alertes = [];

    protected $listeners = [
        'refreshVentes' => '$refresh',
        'venteCreated' => 'handleVenteCreated',
        'showVenteDetails' => 'showDetails'
    ];

    protected $rules = [
        'date_vente' => 'required|date',
        'produit_id' => 'required|exists:produits,id',
        'lieu_livraison_id' => 'required|exists:lieux,id',
        'client_nom' => 'required|string|max:255',
        'client_contact' => 'nullable|string|max:20',
        'quantite_kg' => 'required|numeric|min:0.01',
        'prix_unitaire_mga' => 'required|numeric|min:0',
        'montant_paye_mga' => 'nullable|numeric|min:0'
    ];

    public function mount()
    {
        $this->date_vente = today()->format('Y-m-d');
        $this->exportDateDebut = now()->startOfMonth()->format('Y-m-d');
        $this->exportDateFin = now()->endOfMonth()->format('Y-m-d');
        $this->calculateStats();
        $this->loadAlertes();
    }

    // Méthodes de calcul automatique
    public function updatedQuantiteKg()
    {
        $this->calculateTotal();
    }

    public function updatedPrixUnitaireMga()
    {
        $this->calculateTotal();
    }

    public function updatedMontantPayeMga()
    {
        $this->calculateRestant();
    }

    public function updatedTvaTaux()
    {
        $this->calculateTotal();
    }

    private function calculateTotal()
    {
        if ($this->quantite_kg && $this->prix_unitaire_mga) {
            $sousTotal = $this->quantite_kg * $this->prix_unitaire_mga;
            $montantTva = $sousTotal * ($this->tva_taux / 100);
            $this->prix_total_mga = $sousTotal + $montantTva;
            $this->calculateRestant();
        }
    }

    private function calculateRestant()
    {
        $this->montant_restant_mga = $this->prix_total_mga - $this->montant_paye_mga;

        if ($this->montant_restant_mga <= 0) {
            $this->statut_paiement = 'paye';
        } elseif ($this->montant_paye_mga > 0) {
            $this->statut_paiement = 'partiel';
        } else {
            $this->statut_paiement = 'impaye';
        }
    }

    // Gestion des modals
    public function openVenteModal($venteId = null)
    {
        if ($venteId) {
            $this->editingVente = Vente::findOrFail($venteId);
            $this->fillForm();
        } else {
            $this->resetForm();
            $this->generateNumeroVente();
        }
        $this->showVenteModal = true;
    }

    public function closeVenteModal()
    {
        $this->showVenteModal = false;
        $this->resetForm();
    }

    public function showDetails($venteId)
    {
        $this->venteDetails = Vente::with(['produit', 'lieuLivraison', 'chargement', 'retours', 'paiements'])
            ->findOrFail($venteId);
        $this->showDetailsModal = true;
    }

    public function closeDetailsModal()
    {
        $this->showDetailsModal = false;
        $this->venteDetails = null;
    }

    // Sauvegarde et validation
    public function saveVente()
    {
        $this->validate();

        DB::beginTransaction();
        try {
            $this->calculateTotal();

            $data = [
                'numero_vente' => $this->numero_vente,
                'date_vente' => $this->date_vente,
                'chargement_id' => $this->chargement_id ?: null,
                'produit_id' => $this->produit_id,
                'lieu_livraison_id' => $this->lieu_livraison_id,
                'client_nom' => $this->client_nom,
                'client_contact' => $this->client_contact,
                'client_adresse' => $this->client_adresse,
                'client_type' => $this->client_type,
                'quantite_kg' => $this->quantite_kg,
                'sacs_pleins' => $this->sacs_pleins,
                'sacs_demi' => $this->sacs_demi,
                'prix_unitaire_mga' => $this->prix_unitaire_mga,
                'prix_total_mga' => $this->prix_total_mga,
                'montant_paye_mga' => $this->montant_paye_mga,
                'montant_restant_mga' => $this->montant_restant_mga,
                'statut_paiement' => $this->statut_paiement,
                'mode_paiement' => $this->mode_paiement,
                'transporteur_nom' => $this->transporteur_nom,
                'vehicule_immatriculation' => $this->vehicule_immatriculation,
                'frais_transport_mga' => $this->frais_transport_mga,
                'date_livraison_prevue' => $this->date_livraison_prevue ?: null,
                'statut_vente' => $this->statut_vente,
                'qualite_produit' => $this->qualite_produit,
                'observations' => $this->observations,
                'remarques_client' => $this->remarques_client,
                'tva_taux' => $this->tva_taux,
                'tva_montant_mga' => ($this->prix_total_mga - ($this->prix_total_mga / (1 + $this->tva_taux / 100))),
                'user_creation_id' => Auth::id(),
            ];

            if ($this->editingVente) {
                $this->editingVente->update($data);
                $vente = $this->editingVente;
                $action = 'modifiée';
            } else {
                $vente = Vente::create($data);
                $action = 'créée';
            }

            // Vérifier les alertes de stock
            $this->verifierAlerteStock($vente);

            DB::commit();
            session()->flash('success', "💰 Vente {$action} avec succès !");
            $this->dispatch('venteCreated', $vente->id);
            $this->closeVenteModal();
            $this->calculateStats();

        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Erreur lors de la sauvegarde : ' . $e->getMessage());
        }
    }

    public function confirmerVente($venteId)
    {
        $vente = Vente::findOrFail($venteId);
        $vente->confirmer();

        session()->flash('success', '✅ Vente confirmée avec succès !');
        $this->calculateStats();
    }

    public function annulerVente($venteId, $motif = 'Annulation demandée')
    {
        $vente = Vente::findOrFail($venteId);
        $vente->annuler($motif);

        session()->flash('success', '❌ Vente annulée avec succès !');
        $this->calculateStats();
    }

    // Gestion des paiements
    public function ajouterPaiement($venteId, $montant, $mode = 'especes')
    {
        $vente = Vente::findOrFail($venteId);
        $vente->ajouterPaiement($montant, $mode);

        session()->flash('success', '💵 Paiement ajouté avec succès !');
        $this->calculateStats();
    }

    // Export et impression
    public function openExportModal()
    {
        $this->showExportModal = true;
    }

    public function closeExportModal()
    {
        $this->showExportModal = false;
    }

    public function exportVentes()
    {
        $this->validate([
            'exportDateDebut' => 'required|date',
            'exportDateFin' => 'required|date|after_or_equal:exportDateDebut',
            'exportFormat' => 'required|in:excel,pdf,csv'
        ]);

        $ventes = Vente::with(['produit', 'lieuLivraison'])
            ->whereBetween('date_vente', [$this->exportDateDebut, $this->exportDateFin])
            ->when($this->filterStatut, fn($q) => $q->where('statut_vente', $this->filterStatut))
            ->orderBy('date_vente', 'desc')
            ->get();

        // Logique d'export selon le format
        switch ($this->exportFormat) {
            case 'excel':
                return $this->exportToExcel($ventes);
            case 'pdf':
                return $this->exportToPdf($ventes);
            case 'csv':
                return $this->exportToCsv($ventes);
        }
    }

    public function imprimerVente($venteId)
    {
        $vente = Vente::with(['produit', 'lieuLivraison', 'chargement'])->findOrFail($venteId);

        // Génération du PDF de la vente
        session()->flash('success', '🖨️ Impression en cours...');
    }

    // Méthodes utilitaires
    private function fillForm()
    {
        $v = $this->editingVente;
        $this->numero_vente = $v->numero_vente;
        $this->date_vente = $v->date_vente->format('Y-m-d');
        $this->chargement_id = $v->chargement_id;
        $this->produit_id = $v->produit_id;
        $this->lieu_livraison_id = $v->lieu_livraison_id;
        $this->client_nom = $v->client_nom;
        $this->client_contact = $v->client_contact;
        $this->client_adresse = $v->client_adresse;
        $this->client_type = $v->client_type;
        $this->quantite_kg = $v->quantite_kg;
        $this->sacs_pleins = $v->sacs_pleins;
        $this->sacs_demi = $v->sacs_demi;
        $this->prix_unitaire_mga = $v->prix_unitaire_mga;
        $this->prix_total_mga = $v->prix_total_mga;
        $this->montant_paye_mga = $v->montant_paye_mga;
        $this->montant_restant_mga = $v->montant_restant_mga;
        $this->statut_paiement = $v->statut_paiement;
        $this->mode_paiement = $v->mode_paiement;
        $this->transporteur_nom = $v->transporteur_nom;
        $this->vehicule_immatriculation = $v->vehicule_immatriculation;
        $this->frais_transport_mga = $v->frais_transport_mga;
        $this->date_livraison_prevue = $v->date_livraison_prevue?->format('Y-m-d');
        $this->statut_vente = $v->statut_vente;
        $this->qualite_produit = $v->qualite_produit;
        $this->observations = $v->observations;
        $this->remarques_client = $v->remarques_client;
        $this->tva_taux = $v->tva_taux;
    }

    private function resetForm()
    {
        $this->reset([
            'numero_vente',
            'chargement_id',
            'produit_id',
            'lieu_livraison_id',
            'client_nom',
            'client_contact',
            'client_adresse',
            'quantite_kg',
            'sacs_pleins',
            'sacs_demi',
            'prix_unitaire_mga',
            'prix_total_mga',
            'montant_paye_mga',
            'montant_restant_mga',
            'mode_paiement',
            'transporteur_nom',
            'vehicule_immatriculation',
            'frais_transport_mga',
            'date_livraison_prevue',
            'observations',
            'remarques_client'
        ]);

        $this->client_type = 'particulier';
        $this->statut_vente = 'brouillon';
        $this->statut_paiement = 'impaye';
        $this->qualite_produit = 'bon';
        $this->tva_taux = 0;
        $this->editingVente = null;
    }

    private function generateNumeroVente()
    {
        $count = Vente::whereDate('date_vente', $this->date_vente ?: today())->count() + 1;
        $this->numero_vente = 'VTE' . date('Ymd', strtotime($this->date_vente ?: today())) . str_pad($count, 4, '0', STR_PAD_LEFT);
    }

    private function calculateStats()
    {
        $today = Carbon::today();
        $thisMonth = Carbon::now()->startOfMonth();

        $this->stats = [
            'ventes_jour' => Vente::whereDate('date_vente', $today)->count(),
            'ca_journalier' => Vente::whereDate('date_vente', $today)->sum('prix_total_mga'),
            'ventes_mois' => Vente::where('date_vente', '>=', $thisMonth)->count(),
            'ca_mensuel' => Vente::where('date_vente', '>=', $thisMonth)->sum('prix_total_mga'),
            'ventes_en_attente' => Vente::where('statut_vente', 'brouillon')->count(),
            'montant_impaye' => Vente::where('statut_paiement', 'impaye')->sum('montant_restant_mga'),
            'ventes_en_retard' => Vente::enRetard()->count()
        ];
    }

    private function loadAlertes()
    {
        $this->alertes = AlerteStock::actives()
            ->where('user_destinataire_id', Auth::id())
            ->where('type_alerte', 'stock_bas')
            ->with(['produit', 'depot'])
            ->limit(5)
            ->get();
    }

    private function verifierAlerteStock($vente)
    {
        // Vérifier si la vente réduit le stock en dessous du seuil
        $stockActuel = Depots::getStockActuel($vente->produit_id, $vente->lieu_livraison_id);
        $nouveauStock = $stockActuel - $vente->quantite_kg;

        if ($nouveauStock <= 0) {
            AlerteStock::creerAlerte('stock_zero', $vente, $nouveauStock, 0);
        }
    }

    // Tri et filtres
    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
        $this->resetPage();
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedFilterStatut()
    {
        $this->resetPage();
    }

    public function updatedFilterPeriod()
    {
        $this->resetPage();
    }

    // Propriétés calculées
    public function getVentesProperty()
    {
        return Vente::with(['produit', 'lieuLivraison', 'chargement'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('numero_vente', 'like', '%' . $this->search . '%')
                        ->orWhere('client_nom', 'like', '%' . $this->search . '%')
                        ->orWhereHas('produit', function ($subQ) {
                            $subQ->where('nom', 'like', '%' . $this->search . '%');
                        });
                });
            })
            ->when($this->filterStatut, fn($q) => $q->where('statut_vente', $this->filterStatut))
            ->when($this->filterPaiement, fn($q) => $q->where('statut_paiement', $this->filterPaiement))
            ->when($this->filterClient, fn($q) => $q->where('client_nom', 'like', '%' . $this->filterClient . '%'))
            ->when($this->filterPeriod, function ($query) {
                switch ($this->filterPeriod) {
                    case 'today':
                        $query->whereDate('date_vente', Carbon::today());
                        break;
                    case 'week':
                        $query->whereBetween('date_vente', [
                            Carbon::now()->startOfWeek(),
                            Carbon::now()->endOfWeek()
                        ]);
                        break;
                    case 'month':
                        $query->whereMonth('date_vente', Carbon::now()->month)
                            ->whereYear('date_vente', Carbon::now()->year);
                        break;
                }
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);
    }

    public function getProduitsProperty()
    {
        return Produit::orderBy('nom')->get();
    }

    public function getDestinationsProperty()
    {
        return Lieu::where('type', 'depot')->orderBy('nom')->get();
    }

    public function getChargementsProperty()
    {
        return Chargement::with('produit')->where('statut', 'livre')->latest()->limit(50)->get();
    }

    public function render()
    {
        return view('livewire.stocks.vente-enhanced', [
            'ventes' => $this->ventes,
            'produits' => $this->produits,
            'destinations' => $this->destinations,
            'chargements' => $this->chargements,
            'stats' => $this->stats,
            'alertes' => $this->alertes
        ]);
    }
}
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
use App\Models\SeuilAlerte;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;



class TransfertEnhanced extends Component
{
    use WithPagination;

    // Propriétés de filtrage
    public $search = '';
    public $filterStatut = '';
    public $filterPriorite = '';
    public $filterDepotOrigine = '';
    public $filterDepotDestination = '';
    public $perPage = 25;
    public $sortField = 'date_creation';
    public $sortDirection = 'desc';

    // Modals
    public $showTransfertModal = false;
    public $showDetailsModal = false;
    public $showSuiviModal = false;
    public $showPlanificationModal = false;
    
    public $editingTransfert = null;
    public $transfertDetails = null;

    // Formulaire de transfert
    public $transfert = [
        'numero_transfert' => '',
        'date_creation' => '',
        'date_prevue_expedition' => '',
        'date_prevue_reception' => '',
        'depot_origine_id' => '',
        'depot_destination_id' => '',
        'responsable_origine_id' => '',
        'responsable_destination_id' => '',
        'vehicule_id' => '',
        'chauffeur_nom' => '',
        'chauffeur_contact' => '',
        'distance_km' => 0,
        'cout_transport_prevu_mga' => 0,
        'priorite' => 'normale',
        'motif_transfert' => '',
        'description_motif' => '',
        'conditions_transport' => '',
        'temperature_controlee' => false,
        'temperature_min' => '',
        'temperature_max' => '',
        'assurance_souscrite' => false,
        'numero_police_assurance' => '',
        'valeur_assuree_mga' => 0
    ];

    // Produits à transférer
    public $produitsTransfert = [];

    // Planification automatique
    public $planification = [
        'critere_optimisation' => 'cout', // cout, temps, distance
        'jours_prevision' => 7,
        'depot_source' => '',
        'depot_cible' => '',
        'produit_prioritaire' => ''
    ];

    // Statistiques et métriques
    public $stats = [];
    public $transfertsEnCours = [];
    public $alertesUrgentes = [];

    protected $listeners = [
        'refreshTransferts' => '$refresh',
        'transfertUpdated' => 'handleTransfertUpdated'
    ];

    public function mount()
    {
        $this->transfert['date_creation'] = today()->format('Y-m-d');
        $this->transfert['date_prevue_expedition'] = today()->addDay()->format('Y-m-d');
        $this->transfert['date_prevue_reception'] = today()->addDays(2)->format('Y-m-d');
        $this->calculateStats();
        $this->loadTransfertsEnCours();
        $this->loadAlertesUrgentes();
    }

    // Gestion des transferts
    public function openTransfertModal($transfertId = null)
    {
        if ($transfertId) {
            $this->editingTransfert = Transfert::with('details')->findOrFail($transfertId);
            $this->fillTransfertForm();
        } else {
            $this->resetTransfertForm();
            $this->generateNumeroTransfert();
        }
        $this->showTransfertModal = true;
    }

    public function closeTransfertModal()
    {
        $this->showTransfertModal = false;
        $this->resetTransfertForm();
    }

    public function ajouterProduit()
    {
        $this->produitsTransfert[] = [
            'produit_id' => '',
            'stock_origine_id' => '',
            'quantite_prevue_kg' => '',
            'sacs_pleins_prevus' => 0,
            'sacs_demi_prevus' => 0,
            'prix_unitaire_mga' => 0,
            'observations_qualite' => ''
        ];
    }

    public function retirerProduit($index)
    {
        unset($this->produitsTransfert[$index]);
        $this->produitsTransfert = array_values($this->produitsTransfert);
    }

    public function saveTransfert()
    {
        $this->validate([
            'transfert.depot_origine_id' => 'required|exists:lieux,id',
            'transfert.depot_destination_id' => 'required|exists:lieux,id|different:transfert.depot_origine_id',
            'transfert.date_prevue_expedition' => 'required|date|after_or_equal:today',
            'transfert.date_prevue_reception' => 'required|date|after:transfert.date_prevue_expedition',
            'transfert.motif_transfert' => 'required|in:reequilibrage_stock,commande_client,optimisation,urgence,autre',
            'produitsTransfert' => 'required|array|min:1',
            'produitsTransfert.*.produit_id' => 'required|exists:produits,id',
            'produitsTransfert.*.quantite_prevue_kg' => 'required|numeric|min:0.01'
        ]);

        DB::beginTransaction();
        try {
            $transfertData = array_merge($this->transfert, [
                'statut_transfert' => 'planifie',
                'progression_pourcent' => 0,
                'user_creation_id' => Auth::id()
            ]);

            if ($this->editingTransfert) {
                $this->editingTransfert->update($transfertData);
                $transfert = $this->editingTransfert;
                
                // Supprimer les anciens détails
                $transfert->details()->delete();
                $action = 'modifié';
            } else {
                $transfert = Transfert::create($transfertData);
                $action = 'créé';
            }

            // Créer les détails du transfert
            foreach ($this->produitsTransfert as $produit) {
                $transfert->details()->create(array_merge($produit, [
                    'valeur_prevue_mga' => $produit['quantite_prevue_kg'] * $produit['prix_unitaire_mga'],
                    'statut_detail' => 'en_attente'
                ]));
            }

            DB::commit();
            session()->flash('success', "🔄 Transfert {$action} avec succès !");
            $this->closeTransfertModal();
            $this->calculateStats();
            $this->dispatch('transfertUpdated');

        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Erreur lors de la sauvegarde : ' . $e->getMessage());
        }
    }

    // Actions sur les transferts
    public function demarrerTransfert($transfertId)
    {
        $transfert = Transfert::findOrFail($transfertId);
        
        // Vérifier la disponibilité des stocks
        foreach ($transfert->details as $detail) {
            $stockDisponible = DepotStock::getStockActuel($detail->produit_id, $transfert->depot_origine_id);
            if ($stockDisponible < $detail->quantite_prevue_kg) {
                session()->flash('error', 'Stock insuffisant pour ' . $detail->produit->nom);
                return;
            }
        }

        $transfert->demarrer();
        session()->flash('success', '🚛 Transfert démarré avec succès !');
        $this->calculateStats();
    }

    public function livrerTransfert($transfertId)
    {
        $transfert = Transfert::findOrFail($transfertId);
        $transfert->livrer();
        session()->flash('success', '📦 Transfert livré avec succès !');
        $this->calculateStats();
    }

    public function receptionnerTransfert($transfertId)
    {
        $transfert = Transfert::findOrFail($transfertId);
        $transfert->receptionner();
        session()->flash('success', '✅ Transfert réceptionné avec succès !');
        $this->calculateStats();
    }

    public function annuler
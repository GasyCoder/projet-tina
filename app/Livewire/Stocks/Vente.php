<?php
// app/Livewire/Stocks/Vente.php

namespace App\Livewire\Stocks;

use Carbon\Carbon;
use App\Models\Lieu;
use App\Models\Voyage;
use App\Models\Produit;
use Livewire\Component;
use App\Models\Dechargement;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class Vente extends Component
{
    use WithPagination;

    // Propriétés de filtrage et recherche
    public $search = '';
    public $filterStatus = '';
    public $filterPeriod = '';
    public $perPage = 25;
    public $sortField = 'date';
    public $sortDirection = 'desc';

    // Propriétés du modal de vente (hérite du système dynamique)
    public $showDechargementModal = false;
    public $type_dechargement = 'vente';
    public $editingDechargement = null;

    // Formulaire de vente
    public $dechargement_reference = '';
    public $chargement_id = '';
    public $client_nom = '';
    public $client_contact = '';
    public $pointeur_nom = '';
    public $lieu_livraison_id = '';
    public $poids_arrivee_kg = '';
    public $sacs_pleins_arrivee = '';
    public $sacs_demi_arrivee = '';
    public $prix_unitaire_mga = '';
    public $montant_total_mga = '';
    public $paiement_mga = '';
    public $reste_mga = '';
    public $statut_commercial = 'vendu';
    public $observation = '';

    // Propriétés calculées pour les statistiques
    public $ventesJour;
    public $caJournalier;
    public $commandesAttente;
    public $caMensuel;

    protected $listeners = [
        'dechargement-saved' => 'refreshData',
        'vente-deleted' => 'refreshData',
    ];

    protected $rules = [
        'dechargement_reference' => 'required|string|max:255',
        'chargement_id' => 'required|exists:chargements,id',
        'client_nom' => 'required|string|max:255',
        'client_contact' => 'nullable|string|max:20',
        'poids_arrivee_kg' => 'required|numeric|min:0',
        'prix_unitaire_mga' => 'required|numeric|min:0',
        'paiement_mga' => 'nullable|numeric|min:0',
    ];

    public function mount()
    {
        $this->calculateStats();
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedFilterStatus()
    {
        $this->resetPage();
    }

    public function updatedFilterPeriod()
    {
        $this->resetPage();
    }

    // Méthodes de tri
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

    // Calculs automatiques financiers
    public function updatedPrixUnitaireMga()
    {
        $this->calculateFinancials();
    }

    public function updatedPoidsArriveeKg()
    {
        $this->calculateFinancials();
    }

    public function updatedPaiementMga()
    {
        $this->calculateFinancials();
    }

    private function calculateFinancials()
    {
        if ($this->prix_unitaire_mga && $this->poids_arrivee_kg) {
            $this->montant_total_mga = $this->prix_unitaire_mga * $this->poids_arrivee_kg;
            $this->reste_mga = $this->montant_total_mga - ($this->paiement_mga ?: 0);
        }
    }

    public function setFullPayment()
    {
        if ($this->montant_total_mga) {
            $this->paiement_mga = $this->montant_total_mga;
            $this->reste_mga = 0;
        }
    }

    // Gestion du modal de vente
    public function openVenteModal($dechargementId = null)
    {
        if ($dechargementId) {
            $this->editingDechargement = Dechargement::findOrFail($dechargementId);
            $this->fillForm();
        } else {
            $this->resetForm();
            $this->generateReference();
        }
        
        $this->showDechargementModal = true;
    }

    public function closeDechargementModal()
    {
        $this->showDechargementModal = false;
        $this->resetForm();
    }

    // Actions sur les ventes
    public function viewVente($venteId)
    {
        $this->dispatch('show-vente-details', ['venteId' => $venteId]);
    }

    public function editVente($venteId)
    {
        $this->openVenteModal($venteId);
    }

    public function deleteVente($venteId)
    {
        $vente = Dechargement::findOrFail($venteId);
        $vente->delete();
        
        session()->flash('success', '🗑️ Vente supprimée avec succès !');
        $this->dispatch('vente-deleted');
    }

    // Sauvegarde de la vente
    public function saveDechargement()
    {
        $this->validate();

        DB::beginTransaction();
        try {
            $this->calculateFinancials();

            $data = [
                'reference' => $this->dechargement_reference,
                'type' => 'vente',
                'chargement_id' => $this->chargement_id,
                'interlocuteur_nom' => $this->client_nom,
                'interlocuteur_contact' => $this->client_contact,
                'pointeur_nom' => $this->pointeur_nom,
                'lieu_livraison_id' => $this->lieu_livraison_id,
                'poids_arrivee_kg' => $this->poids_arrivee_kg,
                'sacs_pleins_arrivee' => $this->sacs_pleins_arrivee,
                'sacs_demi_arrivee' => $this->sacs_demi_arrivee,
                'prix_unitaire_mga' => $this->prix_unitaire_mga,
                'montant_total_mga' => $this->montant_total_mga,
                'paiement_mga' => $this->paiement_mga ?: 0,
                'reste_mga' => $this->reste_mga ?: 0,
                'statut_commercial' => $this->statut_commercial,
                'observation' => $this->observation,
                'date' => now(),
                'user_id' => Auth::id(),
            ];

            if ($this->editingDechargement) {
                $this->editingDechargement->update($data);
                $action = 'modifiée';
            } else {
                $voyage = $this->getVoyageForChargement();
                if ($voyage) {
                    $data['voyage_id'] = $voyage->id;
                }
                
                Dechargement::create($data);
                $action = 'créée';
            }

            DB::commit();
            session()->flash('success', "💰 Vente {$action} avec succès !");
            $this->dispatch('dechargement-saved');
            $this->closeDechargementModal();

        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Erreur lors de la sauvegarde : ' . $e->getMessage());
        }
    }

    // Export des ventes
    public function exportVentes()
    {
        session()->flash('success', '📊 Export en cours de préparation...');
    }

    // Méthodes utilitaires
    private function fillForm()
    {
        $d = $this->editingDechargement;
        $this->dechargement_reference = $d->reference;
        $this->chargement_id = $d->chargement_id;
        $this->client_nom = $d->interlocuteur_nom;
        $this->client_contact = $d->interlocuteur_contact;
        $this->pointeur_nom = $d->pointeur_nom;
        $this->lieu_livraison_id = $d->lieu_livraison_id;
        $this->poids_arrivee_kg = $d->poids_arrivee_kg;
        $this->sacs_pleins_arrivee = $d->sacs_pleins_arrivee;
        $this->sacs_demi_arrivee = $d->sacs_demi_arrivee;
        $this->prix_unitaire_mga = $d->prix_unitaire_mga;
        $this->montant_total_mga = $d->montant_total_mga;
        $this->paiement_mga = $d->paiement_mga;
        $this->reste_mga = $d->reste_mga;
        $this->statut_commercial = $d->statut_commercial;
        $this->observation = $d->observation;
    }

    private function resetForm()
    {
        $this->reset([
            'dechargement_reference', 'chargement_id', 'client_nom', 'client_contact',
            'pointeur_nom', 'lieu_livraison_id', 'poids_arrivee_kg', 'sacs_pleins_arrivee',
            'sacs_demi_arrivee', 'prix_unitaire_mga', 'montant_total_mga', 'paiement_mga',
            'reste_mga', 'observation'
        ]);
        $this->statut_commercial = 'vendu';
        $this->editingDechargement = null;
    }

    private function generateReference()
    {
        $count = Dechargement::where('type', 'vente')
            ->whereDate('created_at', today())
            ->count() + 1;
        $this->dechargement_reference = 'VENTE-' . date('Ymd') . '-' . str_pad($count, 3, '0', STR_PAD_LEFT);
    }

    private function getVoyageForChargement()
    {
        if ($this->chargement_id) {
            return Voyage::whereHas('chargements', function ($query) {
                $query->where('chargements.id', $this->chargement_id);
            })->first();
        }
        return null;
    }

    // Calcul des statistiques
    private function calculateStats()
    {
        $today = Carbon::today();
        $thisMonth = Carbon::now()->startOfMonth();

        $this->ventesJour = Dechargement::where('type', 'vente')
            ->whereDate('date', $today)
            ->count();

        $this->caJournalier = Dechargement::where('type', 'vente')
            ->whereDate('date', $today)
            ->sum('montant_total_mga');

        $this->commandesAttente = Dechargement::where('type', 'vente')
            ->where('statut_commercial', 'en_attente')
            ->count();

        $this->caMensuel = Dechargement::where('type', 'vente')
            ->where('date', '>=', $thisMonth)
            ->sum('montant_total_mga');
    }

    public function refreshData()
    {
        $this->calculateStats();
        $this->resetPage();
    }

    public function updatedChargementId()
    {
        // Logique pour mettre à jour les informations du chargement sélectionné
    }

    public function getVoyageProperty()
    {
        return $this->getVoyageForChargement();
    }

    public function getDestinationsProperty()
    {
        return Lieu::orderBy('nom')->get();
    }

    public function getVentesProperty()
    {
        $query = Dechargement::where('type', 'vente')
            ->with(['chargement.produit', 'lieuLivraison']); // Correction ici

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('reference', 'like', '%' . $this->search . '%')
                  ->orWhere('interlocuteur_nom', 'like', '%' . $this->search . '%')
                  ->orWhereHas('chargement.produit', function ($subQ) {
                      $subQ->where('nom', 'like', '%' . $this->search . '%');
                  });
            });
        }

        if ($this->filterStatus) {
            $query->where('statut_commercial', $this->filterStatus);
        }

        if ($this->filterPeriod) {
            switch ($this->filterPeriod) {
                case 'today':
                    $query->whereDate('date', Carbon::today());
                    break;
                case 'week':
                    $query->whereBetween('date', [
                        Carbon::now()->startOfWeek(),
                        Carbon::now()->endOfWeek()
                    ]);
                    break;
                case 'month':
                    $query->whereMonth('date', Carbon::now()->month)
                          ->whereYear('date', Carbon::now()->year);
                    break;
            }
        }

        $query->orderBy($this->sortField, $this->sortDirection);

        return $query->paginate($this->perPage);
    }

    public function render()
    {
        return view('livewire.stocks.vente', [
            'ventes' => $this->ventes,
            'destinations' => $this->destinations,
            'voyage' => $this->voyage,
        ]);
    }
}

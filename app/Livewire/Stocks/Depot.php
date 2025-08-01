<?php

namespace App\Livewire\Stocks;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Depots;
use App\Models\Dechargement;
use App\Models\Produit;
use App\Models\Lieu;
use App\Models\User;
use App\Models\Vehicule;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class Depot extends Component
{
    use WithPagination;
    public $showDetailsModal = false;
    public $detailsDepot = null;
    public $search = '';
    public $filterCategorie = '';
    public $filterStatut = '';
    public $sortField = 'date';
    public $sortDirection = 'desc';

    public $showEntreeModal = false;
    public $showAjustementModal = false;
    public $showSortieModal = false;
    public $showHistoriqueModal = false;
    public $historiqueSelectionne = null;
    public $selectedDepot = null;

    public $formEntree = [
        'date_entree' => '',
        'produit_id' => '',
        'origine' => '',
        'depot_id' => '',
        'proprietaire_id' => '',
        'sacs_pleins' => '',
        'sacs_demi' => '',
        'poids_entree_kg' => '',
        'prix_marche_actuel_mga' => '',
        'observation' => '',
    ];

    public $formSortie = [
        'poids_sortie_kg' => '',
        'date_sortie' => '',
        'vehicule_sortie_id' => '',
        'observation' => '',
    ];

    public $formAjustement = [
        'produit_id' => '',
        'nouveau_stock' => '',
        'motif' => '',
        'commentaire' => '',
    ];

    // Ajout du listener pour debug
    protected $listeners = [
        '$refresh',
        'depotAction' => 'handleDepotAction'
    ];

    public function handleDepotAction($action, $id)
    {
        \Log::info("Action reçue: $action pour ID: $id");
        
        switch ($action) {
            case 'details':
                $this->showDetails($id);
                break;
            case 'sortie':
                $this->prepareSortie($id);
                break;
            default:
                session()->flash('error', 'Action non reconnue.');
        }
    }


    public function mount()
    {
        $this->formEntree['date_entree'] = now()->format('Y-m-d');
        $this->formSortie['date_sortie'] = now()->format('Y-m-d');
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterCategorie()
    {
        $this->resetPage();
    }

    public function updatingFilterStatut()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function openEntreeModal()
    {
        $this->showEntreeModal = true;
        $this->resetFormEntree();
    }

    public function closeEntreeModal()
    {
        $this->showEntreeModal = false;
        $this->resetFormEntree();
    }

    public function resetFormEntree()
    {
        $this->formEntree = [
            'date_entree' => now()->format('Y-m-d'),
            'produit_id' => '',
            'origine' => '',
            'depot_id' => '',
            'proprietaire_id' => '',
            'sacs_pleins' => '',
            'sacs_demi' => '',
            'poids_entree_kg' => '',
            'prix_marche_actuel_mga' => '',
            'observation' => '',
        ];
    }

    public function openAjustementModal()
    {
        $this->showAjustementModal = true;
        $this->resetFormAjustement();
    }

    public function closeAjustementModal()
    {
        $this->showAjustementModal = false;
        $this->resetFormAjustement();
    }

    public function resetFormAjustement()
    {
        $this->formAjustement = [
            'produit_id' => '',
            'nouveau_stock' => '',
            'motif' => '',
            'commentaire' => '',
        ];
    }

    public function saveEntree()
    {
        $this->validate([
            'formEntree.date_entree' => 'required|date',
            'formEntree.produit_id' => 'required|exists:produits,id',
            'formEntree.origine' => 'required|string|max:255',
            'formEntree.depot_id' => 'required|exists:lieux,id',
            'formEntree.proprietaire_id' => 'required|exists:users,id',
            'formEntree.poids_entree_kg' => 'required|numeric|min:0',
            'formEntree.prix_marche_actuel_mga' => 'required|numeric|min:0',
        ]);

        // Logique pour créer l'entrée de stock
        // À adapter selon votre modèle de données

        session()->flash('message', 'Entrée de stock enregistrée avec succès !');
        $this->closeEntreeModal();
        $this->dispatch('$refresh');
    }

    public function saveAjustement()
    {
        $this->validate([
            'formAjustement.produit_id' => 'required',
            'formAjustement.nouveau_stock' => 'required|numeric|min:0',
            'formAjustement.motif' => 'required',
            'formAjustement.commentaire' => 'required|min:5',
        ]);

        session()->flash('message', 'Ajustement de stock effectué avec succès !');
        $this->closeAjustementModal();
        $this->dispatch('$refresh');
    }

    // Correction de la méthode prepareSortie
    public function prepareSortie($id)
    {
        try {
            $this->selectedDepot = $this->getStockById($id);

            if ($this->selectedDepot) {
                // Vérifier si le stock peut être sorti
                if (($this->selectedDepot->reste_kg ?? 0) <= 0) {
                    session()->flash('error', 'Ce stock ne peut pas être sorti (quantité insuffisante).');
                    return;
                }

                // Préparer les données pour le modal
                $this->selectedDepot->produit = $this->getProduitFromChargement($this->selectedDepot->chargement_id);
                $this->selectedDepot->proprietaire = $this->getProprietaireFromDechargement($this->selectedDepot);

                $this->showSortieModal = true;
                $this->formSortie['date_sortie'] = now()->format('Y-m-d');
                $this->formSortie['poids_sortie_kg'] = '';
                $this->formSortie['vehicule_sortie_id'] = '';
                $this->formSortie['observation'] = '';
            } else {
                session()->flash('error', 'Stock introuvable.');
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Erreur lors de la préparation de la sortie : ' . $e->getMessage());
        }
    }


    public function saveSortie()
    {
        $this->validate([
            'formSortie.poids_sortie_kg' => 'required|numeric|min:0.01|max:' . $this->selectedDepot->reste_kg,
            'formSortie.date_sortie' => 'required|date',
        ]);

        // Logique pour enregistrer la sortie de stock
        // À adapter selon votre modèle de données

        session()->flash('message', 'Sortie de stock enregistrée avec succès !');
        $this->showSortieModal = false;
        $this->selectedDepot = null;
        $this->dispatch('$refresh');
    }

    // Correction de la méthode showDetails
    public function showDetails($id)
    {
        try {
            // Récupérer les détails du dépôt
            $this->detailsDepot = Dechargement::with(['chargement.produit'])
                ->where('type', 'depot')
                ->find($id);

            if ($this->detailsDepot) {
                // Préparer les données pour l'affichage
                $this->detailsDepot->produit = $this->getProduitFromChargement($this->detailsDepot->chargement_id);
                $this->detailsDepot->proprietaire = $this->getProprietaireFromDechargement($this->detailsDepot);
                $this->detailsDepot->date_entree = $this->detailsDepot->date;
                $this->detailsDepot->origine = 'Transport';

                $this->showDetailsModal = true;
            } else {
                session()->flash('error', 'Dépôt introuvable.');
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Erreur lors de la récupération des détails : ' . $e->getMessage());
        }
    }


    public function closeDetailsModal()
    {
        $this->showDetailsModal = false;
        $this->detailsDepot = null;
    }

    public function ajusterStock($id)
    {
        $this->formAjustement['produit_id'] = $id;
        $this->showAjustementModal = true;
    }

    public function voirHistorique($id)
    {
        $dechargement = Dechargement::where('type', 'depot')->find($id);

        if ($dechargement) {
            $this->historiqueSelectionne = [
                'type' => 'dechargement',
                'id' => $dechargement->id,
                'nom' => $dechargement->produit?->nom ?? 'Produit inconnu',
                'date' => $dechargement->date?->format('d/m/Y'),
                'quantite' => $dechargement->poids_arrivee_kg,
                'proprietaire' => $dechargement->proprietaire_nom ?? 'N/A',
            ];
            $this->showHistoriqueModal = true;
            return;
        }

        session()->flash('error', 'Stock introuvable.');
    }

    // Correction de la méthode getStockById avec gestion d'erreur
    private function getStockById($id)
    {
        try {
            return Dechargement::with(['chargement.produit'])
                ->where('type', 'depot')
                ->find($id);
        } catch (\Exception $e) {
            \Log::error('Erreur getStockById: ' . $e->getMessage());
            return null;
        }
    }


    public function getStocksFusionnes()
    {
        $query = Dechargement::where('type', 'depot')
            ->with(['chargement.produit']) // Charger les relations nécessaires
            ->select([
                'id',
                'date',
                'poids_arrivee_kg as poids_entree_kg',
                DB::raw("0 as poids_sortie_kg"),
                'poids_arrivee_kg as reste_kg',
                'statut',
                DB::raw("'dechargement' as source"),
                'voyage_id',
                'chargement_id',
                'reference',
                'sacs_pleins_arrivee as sacs_pleins',
                'sacs_demi_arrivee as sacs_demi',
                'pointeur_nom',
                'interlocuteur_nom'
                // Retirer 'proprietaire_nom' car ce n'est pas une vraie colonne
            ]);

        // Appliquer les filtres
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('reference', 'like', '%' . $this->search . '%')
                    ->orWhere('pointeur_nom', 'like', '%' . $this->search . '%')
                    ->orWhere('interlocuteur_nom', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->filterStatut) {
            $query->where('statut', $this->filterStatut);
        }

        // Utiliser paginate() au lieu de get()
        return $query->orderBy($this->sortField, $this->sortDirection)
            ->paginate(15)
            ->through(function ($item) {
                // Ajouter les relations et propriétés manuellement
                $item->produit = $this->getProduitFromChargement($item->chargement_id);
                $item->proprietaire = $this->getProprietaireFromDechargement($item);
                $item->depot = (object) ['nom' => 'Dépôt principal']; // À adapter selon vos besoins
                $item->date_entree = $item->date;
                $item->origine = $someSource->lieu->nom ?? null;

                $item->prix_marche_actuel_mga = 0; // À adapter selon vos besoins
    
                // Définir les propriétés manquantes pour éviter les erreurs
                $item->produit_id = $item->chargement ? $item->chargement->produit_id : null;
                $item->proprietaire_id = null; // À adapter si vous avez cette information
    
                return $item;
            });
    }


    // Méthode alternative avec jointure si vous préférez récupérer proprietaire_nom directement
    public function getStocksFusionnesAvecJointure()
    {
        $query = Dechargement::where('dechargements.type', 'depot')
            ->leftJoin('chargements', 'dechargements.chargement_id', '=', 'chargements.id')
            ->leftJoin('produits', 'chargements.produit_id', '=', 'produits.id')
            ->select([
                'dechargements.id',
                'dechargements.date',
                'dechargements.poids_arrivee_kg as poids_entree_kg',
                DB::raw("0 as poids_sortie_kg"),
                'dechargements.poids_arrivee_kg as reste_kg',
                'dechargements.statut',
                DB::raw("'dechargement' as source"),
                'dechargements.voyage_id',
                'dechargements.chargement_id',
                'dechargements.reference',
                'dechargements.sacs_pleins_arrivee as sacs_pleins',
                'dechargements.sacs_demi_arrivee as sacs_demi',
                'chargements.proprietaire_nom',
                'dechargements.pointeur_nom',
                'dechargements.interlocuteur_nom',
                'produits.nom as produit_nom',
                'chargements.produit_id'
            ]);

        // Appliquer les filtres
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('dechargements.reference', 'like', '%' . $this->search . '%')
                    ->orWhere('chargements.proprietaire_nom', 'like', '%' . $this->search . '%')
                    ->orWhere('dechargements.pointeur_nom', 'like', '%' . $this->search . '%')
                    ->orWhere('produits.nom', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->filterStatut) {
            $query->where('dechargements.statut', $this->filterStatut);
        }

        return $query->orderBy('dechargements.' . $this->sortField, $this->sortDirection)
            ->paginate(15)
            ->through(function ($item) {
                // Créer les objets relations pour la compatibilité avec la vue
                $item->produit = (object) ['nom' => $item->produit_nom ?? 'Produit inconnu'];
                $item->proprietaire = (object) ['name' => $item->proprietaire_nom ?? 'Inconnu'];
                $item->depot = (object) ['nom' => 'Dépôt principal'];
                $item->date_entree = $item->date;
                $item->origine = 'Transport';
                $item->prix_marche_actuel_mga = 0;

                return $item;
            });
    }



    // Mise à jour des méthodes helper pour gérer les cas où les relations sont null

    // Mise à jour des méthodes helper pour gérer les cas où les relations sont null
    protected function getProduitFromChargement($chargementId)
    {
        if (!$chargementId) {
            return (object) ['nom' => 'Produit inconnu'];
        }

        try {
            $chargement = \App\Models\Chargement::with('produit')->find($chargementId);
            return $chargement && $chargement->produit
                ? $chargement->produit
                : (object) ['nom' => 'Produit inconnu'];
        } catch (\Exception $e) {
            return (object) ['nom' => 'Produit inconnu'];
        }
    }



    protected function getProprietaireFromDechargement($dechargement)
    {
        // Utiliser l'accesseur du modèle si disponible
        $nom = $dechargement->proprietaire_nom ??
            $dechargement->pointeur_nom ??
            $dechargement->interlocuteur_nom ??
            'Inconnu';

        return (object) ['name' => $nom];
    }
    // Méthodes pour récupérer les données des selects
    public function getProduits()
    {
        return Produit::orderBy('nom')->get();
    }

    public function getLieux()
    {
        return Lieu::where('type', 'depot')->orderBy('nom')->get();
    }

    public function getProprietaires()
    {
        return User::orderBy('name')->get();
    }

    public function getVehicules()
    {
        return Vehicule::orderBy('immatriculation')->get();
    }

    public function render()
    {
        $stocksFusionnes = $this->getStocksFusionnes();

        return view('livewire.stocks.depot', [
            'depots' => $stocksFusionnes, // Maintenant c'est un objet paginé
            'stocksFusionnes' => $stocksFusionnes,
            'produits' => $this->getProduits(),
            'lieux' => $this->getLieux(),
            'proprietaires' => $this->getProprietaires(),
            'vehicules' => $this->getVehicules(),
        ]);
    }
}
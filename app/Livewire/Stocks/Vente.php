<?php

namespace App\Livewire\Stocks;

use Livewire\Component;
use App\Models\Vente as VenteModel;
use App\Models\Produit;
use App\Models\Vehicule;
use Carbon\Carbon;
use Livewire\WithPagination;

class Vente extends Component
{
    use WithPagination;

    // Propriétés pour les statistiques
    public $ventesJour;
    public $caJour;
    public $ventesEnAttente;
    public $caMensuel;

    // Propriétés pour le filtrage
    public $search = '';
    public $filterProduit = '';
    public $filterStatut = '';
    public $filterDate = '';

    // Propriétés pour le tri
    public $sortField = 'date';
    public $sortDirection = 'desc';

    // Propriétés pour les modales
    public $showCreateModal = false;
    public $showEditModal = false;

    // Propriétés pour le formulaire
    public $form = [
        'produit_id' => '',
        'client' => '',
        'poids' => '',
        'prix_unitaire' => '',
        'date_livraison' => '',
        'vehicule_id' => '',
        'observations' => '',
        'statut' => 'attente'
    ];

    public $venteId;

    // Initialisation
    public function mount()
    {
        $this->calculerStatistiques();
    }

    // Calcul des statistiques
    protected function calculerStatistiques()
    {
        $this->ventesJour = Vente::whereDate('date', Carbon::today())
                               ->where('statut', 'valide')
                               ->count();

        $this->caJour = Vente::whereDate('date', Carbon::today())
                           ->where('statut', 'valide')
                           ->sum('prix');

        $this->ventesEnAttente = Vente::where('statut', 'attente')
                                    ->count();

        $this->caMensuel = Vente::whereMonth('date', Carbon::now()->month)
                               ->whereYear('date', Carbon::now()->year)
                               ->where('statut', 'valide')
                               ->sum('prix');
    }

    // Tri des colonnes
    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    // Réinitialiser le formulaire
    public function resetForm()
    {
        $this->reset('form', 'showCreateModal', 'showEditModal', 'venteId');
    }

    // Créer une vente
    public function createVente()
    {
        $validated = $this->validate([
            'form.produit_id' => 'required',
            'form.client' => 'required',
            'form.poids' => 'required|numeric',
            'form.prix_unitaire' => 'required|numeric',
            'form.date_livraison' => 'required|date',
        ]);

        $vente = Vente::create([
            'produit_id' => $this->form['produit_id'],
            'client' => $this->form['client'],
            'poids' => $this->form['poids'],
            'prix_unitaire' => $this->form['prix_unitaire'],
            'prix' => $this->form['poids'] * $this->form['prix_unitaire'],
            'date' => now(),
            'date_livraison' => $this->form['date_livraison'],
            'vehicule_id' => $this->form['vehicule_id'],
            'observations' => $this->form['observations'],
            'statut' => $this->form['statut']
        ]);

        $this->resetForm();
        $this->calculerStatistiques();
    }

    // Afficher le formulaire d'édition
    public function editVente($id)
    {
        $this->venteId = $id;
        $vente = Vente::find($id);
        $this->form = $vente->toArray();
        $this->showEditModal = true;
    }

    // Mettre à jour une vente
    public function updateVente()
    {
        $validated = $this->validate([
            'form.produit_id' => 'required',
            'form.client' => 'required',
            'form.poids' => 'required|numeric',
            'form.prix_unitaire' => 'required|numeric',
            'form.date_livraison' => 'required|date',
        ]);

        $vente = Vente::find($this->venteId);
        $vente->update([
            'produit_id' => $this->form['produit_id'],
            'client' => $this->form['client'],
            'poids' => $this->form['poids'],
            'prix_unitaire' => $this->form['prix_unitaire'],
            'prix' => $this->form['poids'] * $this->form['prix_unitaire'],
            'date_livraison' => $this->form['date_livraison'],
            'vehicule_id' => $this->form['vehicule_id'],
            'observations' => $this->form['observations'],
            'statut' => $this->form['statut']
        ]);

        $this->resetForm();
        $this->calculerStatistiques();
    }

    // Supprimer une vente
    public function deleteVente($id)
    {
        Vente::find($id)->delete();
        $this->calculerStatistiques();
    }

    // Exporter les ventes
    public function exportVentes()
    {
        // Implémentez votre logique d'export ici
    }

    // Rendu de la vue
    public function render()
    {
        $ventes = Vente::query()
            ->when($this->search, function ($query) {
                $query->where('client', 'like', '%'.$this->search.'%');
            })
            ->when($this->filterProduit, function ($query) {
                $query->whereHas('produit', function ($q) {
                    $q->where('nom', $this->filterProduit);
                });
            })
            ->when($this->filterStatut, function ($query) {
                $query->where('statut', $this->filterStatut);
            })
            ->when($this->filterDate, function ($query) {
                $query->whereDate('date', $this->filterDate);
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

        return view('livewire.stocks.vente', [
            'ventes' => $ventes,
            'produits' => Produit::all(),
            'vehicules' => Vehicule::all()
        ]);
    }
}
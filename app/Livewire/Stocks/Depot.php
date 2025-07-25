<?php

namespace App\Livewire\Stocks;

use Livewire\Component;
use Livewire\WithPagination;

class Depot extends Component
{
    use WithPagination;

    // Propriétés pour la recherche et les filtres
    public $search = '';
    public $filterCategorie = '';
    
    // Propriétés pour le tri
    public $sortField = 'nom';
    public $sortDirection = 'asc';
    
    // Propriétés pour les modales
    public $showEntreeModal = false;
    public $showAjustementModal = false;
    
    // Propriétés pour le formulaire d'entrée
    public $formEntree = [
        'produit_id' => '',
        'quantite' => '',
        'prix_unitaire' => '',
        'fournisseur' => '',
        'notes' => '',
    ];
    
    // Propriétés pour le formulaire d'ajustement
    public $formAjustement = [
        'produit_id' => '',
        'nouveau_stock' => '',
        'motif' => '',
        'commentaire' => '',
    ];
    
    // Statistiques (données factices)
    public $stockTotal = 15420;
    public $nombreProduits = 8;
    public $stockCritique = 2;
    public $valeurStock = 8547200;

    // Listeners Livewire
    protected $listeners = ['$refresh'];

    // Méthodes pour réinitialiser la pagination lors des recherches
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterCategorie()
    {
        $this->resetPage();
    }

    // Méthode pour le tri
    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    // Méthodes pour les modales d'entrée de stock
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
            'produit_id' => '',
            'quantite' => '',
            'prix_unitaire' => '',
            'fournisseur' => '',
            'notes' => '',
        ];
    }

    // Méthodes pour les modales d'ajustement
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

    // Méthode pour sauvegarder une entrée de stock
    public function saveEntree()
    {
        $this->validate([
            'formEntree.produit_id' => 'required',
            'formEntree.quantite' => 'required|numeric|min:0',
            'formEntree.prix_unitaire' => 'required|numeric|min:0',
            'formEntree.fournisseur' => 'required',
        ], [
            'formEntree.produit_id.required' => 'Veuillez sélectionner un produit.',
            'formEntree.quantite.required' => 'La quantité est obligatoire.',
            'formEntree.quantite.numeric' => 'La quantité doit être un nombre.',
            'formEntree.prix_unitaire.required' => 'Le prix unitaire est obligatoire.',
            'formEntree.prix_unitaire.numeric' => 'Le prix unitaire doit être un nombre.',
            'formEntree.fournisseur.required' => 'Le fournisseur est obligatoire.',
        ]);

        // Ici vous pourrez sauvegarder en base de données
        // MouvementStock::create([
        //     'produit_id' => $this->formEntree['produit_id'],
        //     'type' => 'entree',
        //     'quantite' => $this->formEntree['quantite'],
        //     'prix_unitaire' => $this->formEntree['prix_unitaire'],
        //     'fournisseur' => $this->formEntree['fournisseur'],
        //     'notes' => $this->formEntree['notes'],
        // ]);

        session()->flash('message', 'Entrée de stock enregistrée avec succès !');
        $this->closeEntreeModal();
        $this->dispatch('$refresh');
    }

    // Méthode pour sauvegarder un ajustement
    public function saveAjustement()
    {
        $this->validate([
            'formAjustement.produit_id' => 'required',
            'formAjustement.nouveau_stock' => 'required|numeric|min:0',
            'formAjustement.motif' => 'required',
            'formAjustement.commentaire' => 'required|min:5',
        ], [
            'formAjustement.produit_id.required' => 'Veuillez sélectionner un produit.',
            'formAjustement.nouveau_stock.required' => 'Le nouveau stock est obligatoire.',
            'formAjustement.nouveau_stock.numeric' => 'Le nouveau stock doit être un nombre.',
            'formAjustement.motif.required' => 'Le motif est obligatoire.',
            'formAjustement.commentaire.required' => 'Le commentaire est obligatoire.',
            'formAjustement.commentaire.min' => 'Le commentaire doit contenir au moins 5 caractères.',
        ]);

        // Ici vous pourrez sauvegarder en base de données
        // AjustementStock::create($this->formAjustement);

        session()->flash('message', 'Ajustement de stock effectué avec succès !');
        $this->closeAjustementModal();
        $this->dispatch('$refresh');
    }

    // Méthodes pour les actions sur les stocks
    public function ajusterStock($id)
    {
        $this->formAjustement['produit_id'] = $id;
        $this->showAjustementModal = true;
    }

    public function voirHistorique($id)
    {
        // Rediriger vers la page d'historique ou ouvrir une modale
        // return redirect()->route('stocks.historique', $id);
    }

    // Méthode pour obtenir des données factices de stock
    public function getDummyStocks()
    {
        return collect([
            ['id' => 1, 'nom' => 'Riz local', 'categorie' => 'cereales', 'stock_actuel' => 2450, 'seuil_min' => 1000, 'prix_unitaire' => 3500],
            ['id' => 2, 'nom' => 'Maïs jaune', 'categorie' => 'cereales', 'stock_actuel' => 850, 'seuil_min' => 800, 'prix_unitaire' => 2800],
            ['id' => 3, 'nom' => 'Haricot rouge', 'categorie' => 'legumineuses', 'stock_actuel' => 320, 'seuil_min' => 500, 'prix_unitaire' => 4200],
            ['id' => 4, 'nom' => 'Patate douce', 'categorie' => 'tubercules', 'stock_actuel' => 1200, 'seuil_min' => 600, 'prix_unitaire' => 1800],
            ['id' => 5, 'nom' => 'Haricot blanc', 'categorie' => 'legumineuses', 'stock_actuel' => 180, 'seuil_min' => 400, 'prix_unitaire' => 3800],
        ]);
    }

    // Méthode pour obtenir des données factices d'historique
    public function getDummyHistorique()
    {
        return collect([
            ['produit' => 'Riz local', 'type' => 'entree', 'quantite' => 500, 'date' => '2024-01-15 14:30:00'],
            ['produit' => 'Maïs jaune', 'type' => 'sortie', 'quantite' => 150, 'date' => '2024-01-15 11:20:00'],
            ['produit' => 'Haricot rouge', 'type' => 'sortie', 'quantite' => 80, 'date' => '2024-01-14 16:45:00'],
            ['produit' => 'Patate douce', 'type' => 'entree', 'quantite' => 300, 'date' => '2024-01-14 09:15:00'],
            ['produit' => 'Riz local', 'type' => 'sortie', 'quantite' => 200, 'date' => '2024-01-13 15:00:00'],
        ]);
    }

    public function render()
    {
        // Pour l'instant on retourne des données factices
        // Plus tard vous pourrez faire une vraie requête :
        // $stocks = Stock::query()
        //     ->when($this->search, fn($query) => $query->where('nom', 'like', '%'.$this->search.'%'))
        //     ->when($this->filterCategorie, fn($query) => $query->where('categorie', $this->filterCategorie))
        //     ->orderBy($this->sortField, $this->sortDirection)
        //     ->paginate(10);

        return view('livewire.stocks.depot', [
            'stocks' => $this->getDummyStocks(),
            'historique' => $this->getDummyHistorique(),
        ]);
    }
}
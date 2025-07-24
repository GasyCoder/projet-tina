<?php

namespace App\Livewire\Products;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Produit;

class ProductIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $sortField = 'nom';
    public $sortDirection = 'asc';
    public $showModal = false;
    public $editingProduit = null;

    // Form fields
    public $nom = '';
    public $variete = '';
    public $unite = 'sacs';
    public $poids_moyen_sac_kg = 120;
    public $prix_reference_mga = '';
    public $description = '';
    public $actif = true;

    protected $rules = [
        'nom' => 'required|string|max:255',
        'variete' => 'nullable|string|max:255',
        'unite' => 'required|in:sacs,kg,tonnes,boites,cartons',
        'poids_moyen_sac_kg' => 'required|numeric|min:0',
        'prix_reference_mga' => 'nullable|numeric|min:0',
        'description' => 'nullable|string',
        'actif' => 'boolean'
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }
        $this->sortField = $field;
    }

    public function create()
    {
        $this->resetForm();
        $this->editingProduit = null;
        $this->showModal = true;
    }

    public function edit(Produit $produit)
    {
        $this->editingProduit = $produit;
        $this->nom = $produit->nom;
        $this->variete = $produit->variete;
        $this->unite = $produit->unite;
        $this->poids_moyen_sac_kg = $produit->poids_moyen_sac_kg;
        $this->prix_reference_mga = $produit->prix_reference_mga;
        $this->description = $produit->description;
        $this->actif = $produit->actif;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        if ($this->editingProduit) {
            $this->editingProduit->update([
                'nom' => $this->nom,
                'variete' => $this->variete,
                'unite' => $this->unite,
                'poids_moyen_sac_kg' => $this->poids_moyen_sac_kg,
                'prix_reference_mga' => $this->prix_reference_mga,
                'description' => $this->description,
                'actif' => $this->actif,
            ]);
            session()->flash('success', 'Produit modifié avec succès');
        } else {
            Produit::create([
                'nom' => $this->nom,
                'variete' => $this->variete,
                'unite' => $this->unite,
                'poids_moyen_sac_kg' => $this->poids_moyen_sac_kg,
                'prix_reference_mga' => $this->prix_reference_mga,
                'description' => $this->description,
                'actif' => $this->actif,
            ]);
            session()->flash('success', 'Produit créé avec succès');
        }

        $this->closeModal();
    }

    public function delete(Produit $produit)
    {
        $produit->delete();
        session()->flash('success', 'Produit supprimé avec succès');
    }

    public function toggleActif(Produit $produit)
    {
        $produit->update(['actif' => !$produit->actif]);
        session()->flash('success', 'Statut mis à jour');
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
        $this->editingProduit = null;
    }

    private function resetForm()
    {
        $this->nom = '';
        $this->variete = '';
        $this->unite = 'sacs';
        $this->poids_moyen_sac_kg = 120;
        $this->prix_reference_mga = '';
        $this->description = '';
        $this->actif = true;
        $this->resetErrorBag();
    }

    public function render()
    {
        $produits = Produit::query()
            ->when($this->search, function ($query) {
                $query->where('nom', 'like', '%' . $this->search . '%')
                      ->orWhere('variete', 'like', '%' . $this->search . '%');
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

        return view('livewire.products.product-index', compact('produits'));
    }
}
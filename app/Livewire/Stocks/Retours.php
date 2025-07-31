<?php

namespace App\Livewire\Stocks;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Retour;
use App\Models\Produit;
use App\Models\Lieu;

class Retours extends Component
{
    use WithPagination;

    // Filtrage et tri
    public $search = '';
    public $filterStatut = '';
    public $perPage = 25;
    public $sortField = 'created_at';
    public $sortDirection = 'desc';

    // Modal de gestion
    public $showModal = false;
    public $editingRetour = null;

    // Champs du formulaire
    public $reference = '';
    public $produit_id = '';
    public $lieu_stockage_id = '';
    public $quantite_kg = 0;
    public $quantite_sacs_pleins = 0;
    public $quantite_sacs_demi = 0;
    public $prix_unitaire_mga = 0;
    public $valeur_totale_mga = 0;
    public $statut = 'en_stock';
    public $motif_retour = '';
    public $observation = '';

    // Statistiques
    public $stats = [];

    protected $listeners = ['refreshRetours' => '$refresh'];

    protected $rules = [
        'reference' => 'required|string|max:255',
        'produit_id' => 'required|exists:produits,id',
        'lieu_stockage_id' => 'required|exists:lieux,id',
        'quantite_kg' => 'required|numeric|min:0',
        'prix_unitaire_mga' => 'required|numeric|min:0',
        'statut' => 'required|in:en_stock,vendu,perdu',
    ];

    public function mount()
    {
        $this->calculateStats();
    }

    public function updated($property)
    {
        if (in_array($property, ['quantite_kg', 'prix_unitaire_mga'])) {
            $this->valeur_totale_mga = $this->quantite_kg * $this->prix_unitaire_mga;
        }
    }

    public function openModal($id = null)
    {
        $this->editingRetour = $id ? Retour::findOrFail($id) : null;
        $this->editingRetour ? $this->fillForm() : $this->resetForm();
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function saveRetour()
    {
        $this->validate();

        DB::transaction(function () {
            $data = [
                'reference' => $this->reference,
                'produit_id' => $this->produit_id,
                'lieu_stockage_id' => $this->lieu_stockage_id,
                'quantite_kg' => $this->quantite_kg,
                'quantite_sacs_pleins' => $this->quantite_sacs_pleins,
                'quantite_sacs_demi' => $this->quantite_sacs_demi,
                'prix_unitaire_mga' => $this->prix_unitaire_mga,
                'valeur_totale_mga' => $this->valeur_totale_mga,
                'statut' => $this->statut,
                'motif_retour' => $this->motif_retour,
                'observation' => $this->observation,
            ];

            if ($this->editingRetour) {
                $this->editingRetour->update($data);
            } else {
                Retour::create($data);
            }

            $this->closeModal();
            $this->calculateStats();
            session()->flash('success', 'Retour enregistré avec succès!');
        });
    }

    public function markAsSold($id)
    {
        Retour::findOrFail($id)->update(['statut' => 'vendu']);
        $this->calculateStats();
        session()->flash('success', 'Retour marqué comme vendu!');
    }

    public function deleteRetour($id)
    {
        Retour::findOrFail($id)->delete();
        $this->calculateStats();
        session()->flash('success', 'Retour supprimé avec succès!');
    }

    private function calculateStats()
    {
        $this->stats = [
            'total_stock' => Retour::where('statut', 'en_stock')->count(),
            'valeur_stock' => Retour::where('statut', 'en_stock')->sum('montant_total_mga'),
            'vendus_mois' => Retour::where('statut', 'vendu')
                ->whereMonth('created_at', now()->month)->count(),
            'ca_retours' => Retour::where('statut', 'vendu')
                ->whereMonth('created_at', now()->month)->sum('montant_total_mga'),
        ];
    }


    public function getRetoursProperty()
    {
        return Retour::with(['produit', 'lieuStockage'])
            ->when($this->search, fn($q) => $q->where('reference', 'like', "%{$this->search}%")
                ->orWhereHas('produit', fn($q) => $q->where('nom', 'like', "%{$this->search}%")))
            ->when($this->filterStatut, fn($q) => $q->where('statut', $this->filterStatut))
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);
    }

    public function render()
    {
        return view('livewire.stocks.retour', [
            'retours' => $this->retours,
            'produits' => Produit::orderBy('nom')->get(),
            'lieux' => Lieu::orderBy('nom')->get(),
            
        ]);
    }
}
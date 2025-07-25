<?php

namespace App\Livewire\Stocks;

use Livewire\Component;
use Livewire\WithPagination;

class Transfert extends Component
{
    use WithPagination;

    // Propriétés pour la recherche et les filtres
    public $search = '';
    public $filterStatus = '';
    public $filterDepot = '';
    
    // Propriétés pour le tri
    public $sortField = 'date';
    public $sortDirection = 'desc';
    
    // Propriétés pour les modales
    public $showModal = false;
    public $showDetailsModal = false;
    public $editingId = null;
    
    // Propriétés pour le formulaire
    public $form = [
        'depot_origine' => '',
        'depot_destination' => '',
        'date_prevue' => '',
        'vehicule' => '',
        'chauffeur' => '',
        'priorite' => 'normale',
        'notes' => '',
        'produits' => [
            ['id' => '', 'quantite' => ''],
            ['id' => '', 'quantite' => ''],
            ['id' => '', 'quantite' => ''],
        ],
    ];
    
    // Statistiques (données factices)
    public $transfertsMois = 15;
    public $transfertsPreparation = 4;
    public $transfertsTransit = 6;
    public $transfertsRecus = 5;
    public $totalTransferts = 18;

    // Listeners Livewire
    protected $listeners = ['$refresh'];

    // Méthodes pour réinitialiser la pagination lors des recherches
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterStatus()
    {
        $this->resetPage();
    }

    public function updatingFilterDepot()
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

    // Méthodes pour les modales
    public function openModal()
    {
        $this->showModal = true;
        $this->resetForm();
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->editingId = null;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->form = [
            'depot_origine' => '',
            'depot_destination' => '',
            'date_prevue' => '',
            'vehicule' => '',
            'chauffeur' => '',
            'priorite' => 'normale',
            'notes' => '',
            'produits' => [
                ['id' => '', 'quantite' => ''],
                ['id' => '', 'quantite' => ''],
                ['id' => '', 'quantite' => ''],
            ],
        ];
    }

    // Méthodes pour gérer les produits dans le formulaire
    public function addProduit()
    {
        $this->form['produits'][] = ['id' => '', 'quantite' => ''];
    }

    public function removeProduit($index)
    {
        if (count($this->form['produits']) > 1) {
            unset($this->form['produits'][$index]);
            $this->form['produits'] = array_values($this->form['produits']);
        }
    }

    // Méthode pour sauvegarder
    public function save()
    {
        $this->validate([
            'form.depot_origine' => 'required',
            'form.depot_destination' => 'required|different:form.depot_origine',
            'form.date_prevue' => 'required|date|after:now',
            'form.vehicule' => 'required',
            'form.chauffeur' => 'required',
            'form.produits.*.id' => 'required_with:form.produits.*.quantite',
            'form.produits.*.quantite' => 'required_with:form.produits.*.id|numeric|min:0',
        ], [
            'form.depot_origine.required' => 'Le dépôt d\'origine est obligatoire.',
            'form.depot_destination.required' => 'Le dépôt de destination est obligatoire.',
            'form.depot_destination.different' => 'Le dépôt de destination doit être différent de l\'origine.',
            'form.date_prevue.required' => 'La date prévue est obligatoire.',
            'form.date_prevue.after' => 'La date prévue doit être dans le futur.',
            'form.vehicule.required' => 'Le véhicule est obligatoire.',
            'form.chauffeur.required' => 'Le chauffeur est obligatoire.',
            'form.produits.*.id.required_with' => 'Veuillez sélectionner un produit.',
            'form.produits.*.quantite.required_with' => 'La quantité est obligatoire.',
            'form.produits.*.quantite.numeric' => 'La quantité doit être un nombre.',
        ]);

        // Filtrer les produits vides
        $this->form['produits'] = array_filter($this->form['produits'], function($produit) {
            return !empty($produit['id']) && !empty($produit['quantite']);
        });

        if (empty($this->form['produits'])) {
            $this->addError('form.produits', 'Veuillez ajouter au moins un produit.');
            return;
        }

        // Ici vous pourrez sauvegarder en base de données
        // Transfert::create(array_merge($this->form, ['statut' => 'en_preparation']));

        session()->flash('message', 'Transfert créé avec succès !');
        $this->closeModal();
        $this->dispatch('$refresh');
    }

    // Méthodes pour les actions sur les transferts
    public function demarrerTransfert($id)
    {
        // Mettre à jour le statut en base
        // Transfert::find($id)->update(['statut' => 'en_transit', 'date_depart' => now()]);
        
        session()->flash('message', 'Transfert démarré avec succès !');
        $this->dispatch('$refresh');
    }

    public function confirmerReception($id)
    {
        // Mettre à jour le statut en base
        // Transfert::find($id)->update(['statut' => 'recu', 'date_reception' => now()]);
        
        session()->flash('message', 'Réception confirmée avec succès !');
        $this->dispatch('$refresh');
    }

    public function annulerTransfert($id)
    {
        // Mettre à jour le statut en base
        // Transfert::find($id)->update(['statut' => 'annule']);
        
        session()->flash('message', 'Transfert annulé.');
        $this->dispatch('$refresh');
    }

    public function voirDetails($id)
    {
        $this->showDetailsModal = true;
        // Charger les détails du transfert
        // $this->selectedTransfert = Transfert::with('produits')->find($id);
    }

    // Méthode pour obtenir des données factices de transferts
    public function getDummyTransferts()
    {
        return collect([
            [
                'id' => 1, 
                'numero' => 'T001', 
                'date' => '2024-01-15 08:30:00',
                'depot_origine' => 'Dépôt Central',
                'depot_destination' => 'Dépôt Nord',
                'produits' => ['Riz: 500kg', 'Maïs: 200kg'],
                'vehicule' => 'Camion ABC-123',
                'statut' => 'en_transit'
            ],
            [
                'id' => 2,
                'numero' => 'T002',
                'date' => '2024-01-14 14:15:00',
                'depot_origine' => 'Dépôt Nord',
                'depot_destination' => 'Dépôt Sud',
                'produits' => ['Haricot: 150kg', 'Patate: 300kg'],
                'vehicule' => 'Fourgon DEF-456',
                'statut' => 'recu'
            ],
            [
                'id' => 3,
                'numero' => 'T003',
                'date' => '2024-01-14 09:00:00',
                'depot_origine' => 'Dépôt Est',
                'depot_destination' => 'Dépôt Central',
                'produits' => ['Riz: 800kg'],
                'vehicule' => 'Camion GHI-789',
                'statut' => 'en_preparation'
            ],
            [
                'id' => 4,
                'numero' => 'T004',
                'date' => '2024-01-13 16:30:00',
                'depot_origine' => 'Dépôt Sud',
                'depot_destination' => 'Dépôt Est',
                'produits' => ['Maïs: 400kg', 'Haricot: 250kg', 'Riz: 600kg'],
                'vehicule' => 'Camion JKL-012',
                'statut' => 'en_transit'
            ],
            [
                'id' => 5,
                'numero' => 'T005',
                'date' => '2024-01-12 11:45:00',
                'depot_origine' => 'Dépôt Central',
                'depot_destination' => 'Dépôt Sud',
                'produits' => ['Patate: 450kg'],
                'vehicule' => 'Fourgon MNO-345',
                'statut' => 'recu'
            ]
        ]);
    }

    public function render()
    {
        // Pour l'instant on retourne des données factices
        // Plus tard vous pourrez faire une vraie requête :
        // $transferts = Transfert::query()
        //     ->when($this->search, fn($query) => $query->where('numero', 'like', '%'.$this->search.'%'))
        //     ->when($this->filterStatus, fn($query) => $query->where('statut', $this->filterStatus))
        //     ->when($this->filterDepot, fn($query) => $query->where('depot_origine', $this->filterDepot)->orWhere('depot_destination', $this->filterDepot))
        //     ->orderBy($this->sortField, $this->sortDirection)
        //     ->paginate(10);

        return view('livewire.stocks.transfert', [
            'transferts' => $this->getDummyTransferts(),
        ]);
    }
}
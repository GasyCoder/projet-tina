<?php

namespace App\Livewire\Partenaire;

use App\Models\Partenaire;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithPagination;

class Partenaires extends Component
{
    use WithPagination;

    public string $search = '';
    public string $filterType = 'all';

    public ?int $editingId = null;

    #[Validate('required|string|min:2')]
    public string $nom = '';

    #[Validate('nullable|string|max:30')]
    public ?string $telephone = null;

    #[Validate('nullable|string|max:255')]
    public ?string $adresse = null;

    #[Validate('required|in:fournisseur,client')]
    public string $type = 'fournisseur';

    #[Validate('boolean')]
    public bool $is_active = true;

    // Propriétés pour les modales
    public bool $showDetail = false;
    public ?array $detail = null;
    public bool $showFormModal = false;

    protected $queryString = [
        'search' => ['except' => ''],
        'filterType' => ['except' => 'all'],
        'page' => ['except' => 1],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }
    public function updatingFilterType()
    {
        $this->resetPage();
    }

    private function resetForm(): void
    {
        $this->editingId = null;
        $this->nom = '';
        $this->telephone = null;
        $this->adresse = null;
        $this->type = 'fournisseur';
        $this->is_active = true;
    }

    // Méthodes pour la modal de formulaire
    public function openModal(): void
    {
        $this->resetForm();
        $this->showFormModal = true;
    }

    public function closeModal(): void
    {
        $this->showFormModal = false;
        $this->resetForm();
    }

    // Ancienne méthode create() renommée pour éviter la confusion
    public function create(): void
    {
        $this->resetForm();
    }

    // Nouvelle méthode pour édition en modal
    public function editModal(int $id): void
    {
        $p = Partenaire::findOrFail($id);
        $this->editingId = $p->id;
        $this->nom = $p->nom;
        $this->telephone = $p->telephone;
        $this->adresse = $p->adresse;
        $this->type = $p->type;
        $this->is_active = (bool) $p->is_active;
        $this->showFormModal = true;
    }

    // Ancienne méthode edit() pour compatibilité
    public function edit(int $id): void
    {
        $p = Partenaire::findOrFail($id);
        $this->editingId = $p->id;
        $this->nom = $p->nom;
        $this->telephone = $p->telephone;
        $this->adresse = $p->adresse;
        $this->type = $p->type;
        $this->is_active = (bool) $p->is_active;

        // Fermer la modal de détails si ouverte
        $this->closeDetail();
        // Ouvrir la modal de formulaire
        $this->showFormModal = true;
    }

    public function save(): void
    {
        $data = $this->validate();

        if ($this->editingId) {
            Partenaire::whereKey($this->editingId)->update($data);
            session()->flash('success', 'Partenaire mis à jour avec succès.');
        } else {
            Partenaire::create($data);
            session()->flash('success', 'Partenaire créé avec succès.');
        }

        $this->resetForm();
        $this->showFormModal = false;
        $this->resetPage();
    }

    public function toggle(int $id): void
    {
        $p = Partenaire::findOrFail($id);
        $p->is_active = !$p->is_active;
        $p->save();

        session()->flash('success', 'Statut mis à jour avec succès.');

        // Mettre à jour les détails si la modal est ouverte pour ce partenaire
        if ($this->showDetail && $this->detail && $this->detail['id'] == $id) {
            $this->detail['is_active'] = (bool) $p->is_active;
        }
    }

    public function delete(int $id): void
    {
        Partenaire::findOrFail($id)->delete();
        session()->flash('success', 'Partenaire supprimé avec succès.');

        if ($this->editingId === $id)
            $this->resetForm();

        // Fermer la modal de détails si elle était ouverte pour ce partenaire
        if ($this->showDetail && $this->detail && $this->detail['id'] == $id) {
            $this->closeDetail();
        }

        $this->resetPage();
    }

    public function show(int $id): void
    {
        redirect()->route('partenaire.show', ['partenaire' => $id]);
        $p = Partenaire::findOrFail($id);
        $this->detail = [
            'id' => $p->id,
            'nom' => $p->nom,
            'telephone' => $p->telephone,
            'adresse' => $p->adresse,
            'type' => $p->type,
            'is_active' => (bool) $p->is_active,
            'created_at' => optional($p->created_at)->format('d/m/Y à H:i'),
            'updated_at' => optional($p->updated_at)->format('d/m/Y à H:i'),
            
        ];
        $this->showDetail = true;
        
    }

    public function closeDetail(): void
    {
        $this->showDetail = false;
        $this->detail = null;
    }

    public function getRowsProperty()
    {
        return Partenaire::query()
            ->when($this->search !== '', function ($q) {
                $q->where(function ($qq) {
                    $qq->where('nom', 'like', "%{$this->search}%")
                        ->orWhere('telephone', 'like', "%{$this->search}%")
                        ->orWhere('adresse', 'like', "%{$this->search}%");
                });
            })
            ->when($this->filterType !== 'all', fn($q) => $q->where('type', $this->filterType))
            ->orderByDesc('id')
            ->paginate(10);
    }

    public function render()
    {
        return view('livewire.partenaire.partenaires', [
            'rows' => $this->rows
        ]);
    }
}
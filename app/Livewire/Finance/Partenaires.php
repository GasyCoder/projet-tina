<?php

namespace App\Livewire\Finance;

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

    public bool $showDetail = false;
    public ?array $detail = null;

    protected $queryString = [
        'search'     => ['except' => ''],
        'filterType' => ['except' => 'all'],
        'page'       => ['except' => 1],
    ];

    public function updatingSearch()     { $this->resetPage(); }
    public function updatingFilterType() { $this->resetPage(); }

    private function resetForm(): void
    {
        $this->editingId = null;
        $this->nom = '';
        $this->telephone = null;
        $this->adresse = null;
        $this->type = 'fournisseur';
        $this->is_active = true;
    }

    public function create(): void
    {
        $this->resetForm();
    }

    public function edit(int $id): void
    {
        $p = Partenaire::findOrFail($id);
        $this->editingId = $p->id;
        $this->nom = $p->nom;
        $this->telephone = $p->telephone;
        $this->adresse = $p->adresse;
        $this->type = $p->type;
        $this->is_active = (bool) $p->is_active;
    }

    public function save(): void
    {
        $data = $this->validate();

        if ($this->editingId) {
            Partenaire::whereKey($this->editingId)->update($data);
            session()->flash('success', 'Partenaire mis à jour.');
        } else {
            Partenaire::create($data);
            session()->flash('success', 'Partenaire créé.');
        }

        $this->resetForm();
        $this->resetPage();
    }

    public function toggle(int $id): void
    {
        $p = Partenaire::findOrFail($id);
        $p->is_active = ! $p->is_active;
        $p->save();

        session()->flash('success', 'Statut mis à jour.');
    }

    public function delete(int $id): void
    {
        Partenaire::findOrFail($id)->delete();
        session()->flash('success', 'Partenaire supprimé.');
        if ($this->editingId === $id) $this->resetForm();
        $this->resetPage();
    }

    public function show(int $id): void
    {
        $p = Partenaire::findOrFail($id);
        $this->detail = [
            'id'        => $p->id,
            'nom'       => $p->nom,
            'telephone' => $p->telephone,
            'adresse'   => $p->adresse,
            'type'      => $p->type,
            'is_active' => (bool) $p->is_active,
            'created_at'=> optional($p->created_at)->format('Y-m-d H:i'),
            'updated_at'=> optional($p->updated_at)->format('Y-m-d H:i'),
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
        return view('livewire.finance.partenaires', [
            'rows' => $this->rows
        ]);
    }
}

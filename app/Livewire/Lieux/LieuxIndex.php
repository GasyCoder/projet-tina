<?php

namespace App\Livewire\Lieux;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Lieu;

class LieuxIndex extends Component
{
    use WithPagination;

    /** Filtres & tri */
    public string $search = '';
    public string $filterType = '';
    public string $sortField = 'nom';
    public string $sortDirection = 'asc';

    /** Modal & édition */
    public bool $showModal = false;
    public ?Lieu $editingLieu = null;

    /** Form */
    public string $nom = '';
    public string $type = 'origine'; // défaut aligné
    public string $region = '';
    public string $telephone = '';
    public string $adresse = '';
    public bool $actif = true;

    /** Tri autorisé pour éviter les erreurs SQL */
    protected array $sortable = ['nom', 'type', 'region', 'telephone', 'actif', 'created_at'];

    protected function rules(): array
    {
        return [
            'nom'       => ['required','string','max:255'],
            'type'      => ['required','in:'.implode(',', Lieu::types())], // origine|depot|magasin|boutique
            'region'    => ['nullable','string','max:255'],
            'telephone' => ['nullable','string','max:20'],
            'adresse'   => ['nullable','string'],
            'actif'     => ['boolean'],
        ];
    }

    /* ==== Hooks filtres/recherche ==== */
    public function updatingSearch(): void   { $this->resetPage(); }
    public function updatedFilterType(): void{ $this->resetPage(); }

    public function sortBy(string $field): void
    {
        if (!in_array($field, $this->sortable, true)) return;

        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }

        $this->resetPage();
    }

    /* ==== CRUD ==== */
    public function create(): void
    {
        $this->resetForm();
        $this->editingLieu = null;
        $this->showModal = true;
    }

    public function edit(Lieu $lieu): void
    {
        $this->editingLieu = $lieu;
        $this->nom       = (string) $lieu->nom;
        $this->type      = (string) $lieu->type;
        $this->region    = (string) ($lieu->region ?? '');
        $this->telephone = (string) ($lieu->telephone ?? '');
        $this->adresse   = (string) ($lieu->adresse ?? '');
        $this->actif     = (bool) $lieu->actif;

        $this->showModal = true;
    }

    public function save(): void
    {
        $this->validate();

        $payload = [
            'nom'       => $this->nom,
            'type'      => $this->type,
            'region'    => $this->region ?: null,
            'telephone' => $this->telephone ?: null,
            'adresse'   => $this->adresse ?: null,
            'actif'     => $this->actif,
        ];

        if ($this->editingLieu) {
            $this->editingLieu->update($payload);
            session()->flash('success', 'Lieu modifié avec succès.');
        } else {
            Lieu::create($payload);
            session()->flash('success', 'Lieu créé avec succès.');
        }

        $this->closeModal();
    }

    public function delete(Lieu $lieu): void
    {
        $lieu->delete();
        session()->flash('success', 'Lieu supprimé avec succès.');
        $this->resetPage();
    }

    public function toggleActif(Lieu $lieu): void
    {
        $lieu->update(['actif' => !$lieu->actif]);
        session()->flash('success', 'Statut mis à jour.');
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->resetForm();
        $this->editingLieu = null;
    }

    private function resetForm(): void
    {
        $this->nom       = '';
        $this->type      = 'origine';
        $this->region    = '';
        $this->telephone = '';
        $this->adresse   = '';
        $this->actif     = true;
        $this->resetErrorBag();
    }

    public function render()
    {
        $lieux = Lieu::query()
            ->when($this->search, function ($q) {
                $term = '%'.$this->search.'%';
                $q->where(function ($sub) use ($term) {
                    $sub->where('nom', 'like', $term)
                        ->orWhere('region', 'like', $term)
                        ->orWhere('telephone', 'like', $term);
                });
            })
            ->when($this->filterType, fn($q) => $q->where('type', $this->filterType))
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

        $stats = [
            'total'      => Lieu::count(),
            'origines'   => Lieu::where('type', Lieu::TYPE_ORIGINE)->count(),
            'depots'     => Lieu::where('type', Lieu::TYPE_DEPOT)->count(),
            'magasins'   => Lieu::where('type', Lieu::TYPE_MAGASIN)->count(),
            'boutiques'  => Lieu::where('type', Lieu::TYPE_BOUTIQUE)->count(),
        ];

        return view('livewire.lieux.lieux-index', compact('lieux', 'stats'));
    }
}

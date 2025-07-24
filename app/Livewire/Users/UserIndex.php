<?php

namespace App\Livewire\Users;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserIndex extends Component
{

    use WithPagination;

    public $search = '';
    public $filterType = '';
    public $filterActif = '';
    public $sortField = 'name';
    public $sortDirection = 'asc';
    public $showModal = false;
    public $editingUser = null;

    // Form fields
    public $name = '';
    public $email = '';
    public $password = '';
    public $password_confirmation = '';
    public $type = 'chauffeur';
    public $code = '';
    public $contact = '';
    public $adresse = '';
    public $actif = true;

    protected function rules()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255|unique:users,email',
            'type' => 'required|in:chargeur,chauffeur,pointeur,client,proprietaire,admin',
            'code' => 'nullable|string|max:255',
            'contact' => 'nullable|string|max:255',
            'adresse' => 'nullable|string',
            'actif' => 'boolean'
        ];

        if ($this->editingUser) {
            $rules['email'] = 'nullable|email|max:255|unique:users,email,' . $this->editingUser->id;
            $rules['password'] = 'nullable|string|min:8|confirmed';
        } else {
            $rules['password'] = 'required|string|min:8|confirmed';
        }

        return $rules;
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatedFilterType()
    {
        $this->resetPage();
    }

    public function updatedFilterActif()
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
        $this->editingUser = null;
        $this->showModal = true;
    }

    public function edit(User $user)
    {
        $this->editingUser = $user;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->password = '';
        $this->password_confirmation = '';
        $this->type = $user->type;
        $this->code = $user->code;
        $this->contact = $user->contact;
        $this->adresse = $user->adresse;
        $this->actif = $user->actif;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        $userData = [
            'name' => $this->name,
            'email' => $this->email ?: null,
            'type' => $this->type,
            'code' => $this->code,
            'contact' => $this->contact,
            'adresse' => $this->adresse,
            'actif' => $this->actif,
        ];

        if ($this->password) {
            $userData['password'] = Hash::make($this->password);
        }

        if ($this->editingUser) {
            $this->editingUser->update($userData);
            session()->flash('success', 'Utilisateur modifié avec succès');
        } else {
            User::create($userData);
            session()->flash('success', 'Utilisateur créé avec succès');
        }

        $this->closeModal();
    }

    public function delete(User $user)
    {
        if ($user->id === Auth::id()) {
            session()->flash('error', 'Vous ne pouvez pas supprimer votre propre compte');
            return;
        }

        $user->delete();
        session()->flash('success', 'Utilisateur supprimé avec succès');
    }

    public function toggleActif(User $user)
    {
        if ($user->id === Auth::id()) {
            session()->flash('error', 'Vous ne pouvez pas désactiver votre propre compte');
            return;
        }

        $user->update(['actif' => !$user->actif]);
        session()->flash('success', 'Statut mis à jour');
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
        $this->editingUser = null;
    }

    private function resetForm()
    {
        $this->name = '';
        $this->email = '';
        $this->password = '';
        $this->password_confirmation = '';
        $this->type = 'chauffeur';
        $this->code = '';
        $this->contact = '';
        $this->adresse = '';
        $this->actif = true;
        $this->resetErrorBag();
    }

    public function render()
    {
        $users = User::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%')
                      ->orWhere('code', 'like', '%' . $this->search . '%')
                      ->orWhere('contact', 'like', '%' . $this->search . '%');
            })
            ->when($this->filterType, function ($query) {
                $query->where('type', $this->filterType);
            })
            ->when($this->filterActif !== '', function ($query) {
                $query->where('actif', $this->filterActif);
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

        $stats = [
            'total' => User::count(),
            'chauffeurs' => User::where('type', 'chauffeur')->count(),
            'proprietaires' => User::where('type', 'proprietaire')->count(),
            'pointeurs' => User::where('type', 'pointeur')->count(),
            'clients' => User::where('type', 'client')->count(),
            'chargeurs' => User::where('type', 'chargeur')->count(),
            'admins' => User::where('type', 'admin')->count(),
            'actifs' => User::where('actif', true)->count(),
        ];

        return view('livewire.users.user-index', compact('users', 'stats'));
    }
}

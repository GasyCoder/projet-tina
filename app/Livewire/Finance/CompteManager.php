<?php

namespace App\Livewire\Finance;

use App\Models\Compte;
use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Log;

class CompteManager extends Component
{
    public $showCompteModal = false;
    public $editingCompte = null;
    public $nom_proprietaire = 'Mme TINAH';
    public $type_compte = 'principal';
    public $numero_compte = '';
    public $solde_actuel_mga = 0;
    public $compte_actif = true;

    protected $rules = [
        'type_compte' => 'required|in:principal,OrangeMoney,AirtelMoney,Mvola,banque,credit',
        'solde_actuel_mga' => 'nullable|numeric',
        'nom_proprietaire' => 'nullable|string|max:255',
        'numero_compte' => 'nullable|string|max:255',
        'compte_actif' => 'boolean',
    ];

    public function mount()
    {
        $this->resetCompteForm();
    }


    // ✅ SIMPLE : Créer un compte
    public function createCompte()
    {
        Log::info('createCompte called');
        $this->resetCompteForm();
        $this->editingCompte = null;
        $this->showCompteModal = true;
    }

    // ✅ SIMPLE : Éditer un compte
    public function editCompte($id)
    {
        Log::info('editCompte called', ['id' => $id]);
        $compte = Compte::findOrFail($id);
        $this->editingCompte = $compte;
        $this->nom_proprietaire = $compte->nom_proprietaire ?: 'Mme TINAH';
        $this->type_compte = $compte->type_compte;
        $this->numero_compte = $compte->numero_compte;
        $this->solde_actuel_mga = $compte->solde_actuel_mga;
        $this->compte_actif = $compte->actif;
        $this->showCompteModal = true;
    }

    // ✅ SIMPLE : Sauvegarder un compte
    public function saveCompte()
    {
        Log::info('saveCompte called');
        $this->validate();

        $data = [
            'nom_proprietaire' => $this->nom_proprietaire ?: 'Mme TINAH',
            'type_compte' => $this->type_compte,
            'numero_compte' => $this->numero_compte ?: null,
            'solde_actuel_mga' => $this->solde_actuel_mga,
            'actif' => $this->compte_actif,
        ];

        if ($this->editingCompte) {
            $this->editingCompte->update($data);
            session()->flash('success', 'Compte modifié avec succès');
        } else {
            Compte::create($data);
            session()->flash('success', 'Compte ajouté avec succès');
        }
        
        $this->closeCompteModal();
    }

    // ✅ SIMPLE : Supprimer un compte
    public function deleteCompte($id)
    {
        Log::info('deleteCompte called', ['id' => $id]);
        try {
            $compte = Compte::findOrFail($id);
            $compte->delete();
            session()->flash('success', 'Compte supprimé avec succès');
        } catch (\Exception $e) {
            session()->flash('error', 'Erreur lors de la suppression: ' . $e->getMessage());
        }
    }

    // ✅ SIMPLE : Fermer la modal
    public function closeCompteModal()
    {
        Log::info('closeCompteModal called');
        $this->showCompteModal = false;
        $this->resetCompteForm();
        $this->editingCompte = null;
    }
    
    private function resetCompteForm()
    {
        $this->nom_proprietaire = 'Mme TINAH';
        $this->type_compte = 'principal';
        $this->numero_compte = '';
        $this->solde_actuel_mga = 0;
        $this->compte_actif = true;
        $this->resetErrorBag();
    }

    // ✅ ÉCOUTER les événements du composant parent
    #[On('create-compte')]
    public function handleCreateCompte()
    {
        $this->createCompte();
    }

    public function render()
    {
        $comptes = Compte::where('actif', true)->orderBy('type_compte')->get();
        return view('livewire.finance.compte-manager', compact('comptes'));
    }
}
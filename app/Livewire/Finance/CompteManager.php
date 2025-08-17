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

    // Champs formulaire
    public $nom_proprietaire = 'Mme TINAH';
    public $type_compte = 'Principal'; // Principal | MobileMoney | Banque
    public $type_compte_mobilemoney_or_banque = null; // sous-type
    public $numero_compte = '';
    public $solde_actuel_mga = 0;
    public $compte_actif = true;

    // Listes (tu peux déplacer ça en constantes du modèle si tu préfères)
    public array $MM_SOUS_TYPES   = ['Mvola', 'OrangeMoney', 'AirtelMoney'];
    public array $BANK_SOUS_TYPES = ['BNI', 'BFV', 'BOA', 'BMOI']; // adapte à ton contexte

    protected function rules(): array
    {
        return [
            'type_compte' => 'required|in:Principal,MobileMoney,Banque',
            'type_compte_mobilemoney_or_banque' => 'nullable|required_if:type_compte,MobileMoney,Banque|string|max:100',
            'solde_actuel_mga' => 'nullable|numeric',
            'nom_proprietaire' => 'nullable|string|max:255',
            'numero_compte' => 'nullable|string|max:255',
            'compte_actif' => 'boolean',
    ];}

    public function mount()
    {
        $this->resetCompteForm();
    }

    public function updatedTypeCompte($value)
    {
        // Quand on change de type, on reset le sous-type
        $this->type_compte_mobilemoney_or_banque = null;

        // Si Principal, on vide le numéro (optionnel)
        if ($value === 'Principal') {
            $this->numero_compte = '';
        }
    }

    // ✅ Créer
    public function createCompte()
    {
        Log::info('createCompte called');
        $this->resetCompteForm();
        $this->editingCompte = null;
        $this->showCompteModal = true;
    }

    // ✅ Éditer
    public function editCompte($id)
    {
        Log::info('editCompte called', ['id' => $id]);
        $compte = Compte::findOrFail($id);
        $this->editingCompte = $compte;

        $this->nom_proprietaire = $compte->nom_proprietaire ?: 'Mme TINAH';
        $this->type_compte = $compte->type_compte; // Principal/MobileMoney/Banque
        $this->type_compte_mobilemoney_or_banque = $compte->type_compte_mobilemoney_or_banque; // sous-type
        $this->numero_compte = $compte->numero_compte;
        $this->solde_actuel_mga = $compte->solde_actuel_mga;
        $this->compte_actif = $compte->actif;

        $this->showCompteModal = true;
    }

    // ✅ Sauvegarder (avec déduplication par type + sous-type + propriétaire)
    public function saveCompte()
    {
        Log::info('saveCompte called');
        $this->validate();

        $data = [
            'nom_proprietaire' => $this->nom_proprietaire ?: 'Mme TINAH',
            'type_compte' => $this->type_compte,
            'type_compte_mobilemoney_or_banque' => in_array($this->type_compte, ['MobileMoney','Banque']) ? $this->type_compte_mobilemoney_or_banque : null,
            'numero_compte' => $this->type_compte === 'Principal' ? null : ($this->numero_compte ?: null),
            'solde_actuel_mga' => $this->solde_actuel_mga ?: 0,
            'actif' => $this->compte_actif,
        ];

        if ($this->editingCompte) {
            $this->editingCompte->update($data);
            session()->flash('success', 'Compte modifié avec succès');
        } else {
            $compteExistant = Compte::where('type_compte', $this->type_compte)
                ->when(in_array($this->type_compte, ['MobileMoney','Banque']), function ($q) {
                    $q->where('type_compte_mobilemoney_or_banque', $this->type_compte_mobilemoney_or_banque);
                }, function ($q) {
                    $q->whereNull('type_compte_mobilemoney_or_banque');
                })
                ->where('nom_proprietaire', $this->nom_proprietaire ?: 'Mme TINAH')
                ->first();

            if ($compteExistant) {
                $compteExistant->update($data);
                session()->flash('success', 'Compte existant mis à jour avec succès');
                Log::info('Compte existant mis à jour', ['compte_id' => $compteExistant->id]);
            } else {
                $nouveauCompte = Compte::create($data);
                session()->flash('success', 'Nouveau compte ajouté avec succès');
                Log::info('Nouveau compte créé', ['compte_id' => $nouveauCompte->id]);
            }
        }

        $this->closeCompteModal();
    }

    // ✅ Supprimer
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

    // ✅ Fermer modal
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
        $this->type_compte = 'Principal';
        $this->type_compte_mobilemoney_or_banque = null;
        $this->numero_compte = '';
        $this->solde_actuel_mga = 0;
        $this->compte_actif = true;
        $this->resetErrorBag();
    }

    #[On('create-compte')]
    public function handleCreateCompte()
    {
        $this->createCompte();
    }

    public function render()
    {
        $comptes = Compte::where('actif', true)
            ->orderBy('type_compte')
            ->orderBy('type_compte_mobilemoney_or_banque')
            ->orderBy('created_at')
            ->get();

        return view('livewire.finance.compte-manager', [
            'comptes' => $comptes,
            // Stats rapides (alignées avec la nouvelle structure)
            'stats' => [
                'principal_total'  => $comptes->where('type_compte','Principal')->sum('solde_actuel_mga'),
                'mobile_total'     => $comptes->where('type_compte','MobileMoney')->sum('solde_actuel_mga'),
                'banque_total'     => $comptes->where('type_compte','Banque')->sum('solde_actuel_mga'),
                'principal_count'  => $comptes->where('type_compte','Principal')->count(),
                'mobile_count'     => $comptes->where('type_compte','MobileMoney')->count(),
                'banque_count'     => $comptes->where('type_compte','Banque')->count(),
            ],
            'mmSousTypes'   => $this->MM_SOUS_TYPES,
            'bankSousTypes' => $this->BANK_SOUS_TYPES,
        ]);
    }
}

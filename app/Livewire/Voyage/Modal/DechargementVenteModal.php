<?php
// app/Livewire/Voyage/Modal/DechargementVenteModal.php

namespace App\Livewire\Voyage\Modal;

use Livewire\Component;
use App\Models\Voyage;
use App\Models\Dechargement;
use Illuminate\Support\Facades\DB;

class DechargementVenteModal extends Component
{
    public Voyage $voyage;
    public $showModal = false;
    public $editingDechargement = null;

    // Form fields spécifiques à la vente
    public $dechargement_reference = '';
    public $chargement_id = '';
    public $client_nom = '';
    public $client_contact = '';
    public $pointeur_nom = '';
    public $lieu_livraison_id = '';
    public $poids_arrivee_kg = '';
    public $prix_unitaire_mga = '';
    public $montant_total_mga = '';
    public $paiement_mga = '';
    public $reste_mga = '';
    public $statut_commercial = 'vendu';
    public $observation = '';

    protected $listeners = [
        'openVenteModal' => 'openModal',
        'closeVenteModal' => 'closeModal',
    ];

    public function mount(Voyage $voyage)
    {
        $this->voyage = $voyage;
    }

    public function openModal($dechargementId = null)
    {
        if ($dechargementId) {
            $this->editingDechargement = Dechargement::findOrFail($dechargementId);
            $this->fillForm();
        } else {
            $this->resetForm();
            $this->generateReference();
        }
        
        $this->showModal = true;
        $this->dispatch('show-dechargement-modal', [
            'type' => 'vente',
            'data' => $this->getFormData()
        ]);
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
        $this->dispatch('hide-dechargement-modal');
    }

    // Calculs automatiques
    public function updatedPrixUnitaireMga() { $this->calculateFinancials(); }
    public function updatedPoidsArriveeKg() { $this->calculateFinancials(); }
    public function updatedPaiementMga() { $this->calculateFinancials(); }

    private function calculateFinancials()
    {
        if ($this->prix_unitaire_mga && $this->poids_arrivee_kg) {
            $this->montant_total_mga = $this->prix_unitaire_mga * $this->poids_arrivee_kg;
            $this->reste_mga = $this->montant_total_mga - ($this->paiement_mga ?: 0);
        }
    }

    public function saveVente()
    {
        $this->validate([
            'chargement_id' => 'required|exists:chargements,id',
            'client_nom' => 'required|string|max:255',
            'poids_arrivee_kg' => 'required|numeric|min:0',
            'prix_unitaire_mga' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            $this->calculateFinancials();

            $data = [
                'voyage_id' => $this->voyage->id,
                'reference' => $this->dechargement_reference,
                'chargement_id' => $this->chargement_id,
                'type' => 'vente',
                'interlocuteur_nom' => $this->client_nom,
                'interlocuteur_contact' => $this->client_contact,
                'pointeur_nom' => $this->pointeur_nom,
                'lieu_livraison_id' => $this->lieu_livraison_id,
                'poids_arrivee_kg' => $this->poids_arrivee_kg,
                'prix_unitaire_mga' => $this->prix_unitaire_mga,
                'montant_total_mga' => $this->montant_total_mga,
                'paiement_mga' => $this->paiement_mga ?: 0,
                'reste_mga' => $this->reste_mga ?: 0,
                'statut_commercial' => $this->statut_commercial,
                'observation' => $this->observation,
                'date_dechargement' => now(),
                'user_id' => auth()->id(),
            ];

            if ($this->editingDechargement) {
                $this->editingDechargement->update($data);
                $action = 'modifiée';
            } else {
                Dechargement::create($data);
                $action = 'créée';
            }

            DB::commit();
            session()->flash('success', "💰 Vente {$action} avec succès !");
            $this->dispatch('dechargement-saved');
            $this->closeModal();

        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Erreur : ' . $e->getMessage());
        }
    }

    private function fillForm()
    {
        $d = $this->editingDechargement;
        $this->dechargement_reference = $d->reference;
        $this->chargement_id = $d->chargement_id;
        $this->client_nom = $d->interlocuteur_nom;
        $this->prix_unitaire_mga = $d->prix_unitaire_mga;
        $this->poids_arrivee_kg = $d->poids_arrivee_kg;
        $this->montant_total_mga = $d->montant_total_mga;
        $this->paiement_mga = $d->paiement_mga;
        $this->reste_mga = $d->reste_mga;
        $this->observation = $d->observation;
    }

    private function resetForm()
    {
        $this->reset(['dechargement_reference', 'chargement_id', 'client_nom', 'prix_unitaire_mga', 'poids_arrivee_kg', 'observation']);
        $this->statut_commercial = 'vendu';
    }

    private function generateReference()
    {
        $count = $this->voyage->dechargements()->where('type', 'vente')->count() + 1;
        $this->dechargement_reference = 'VENTE-' . str_pad($count, 3, '0', STR_PAD_LEFT);
    }

    public function getFormData()
    {
        return [
            'dechargement_reference' => $this->dechargement_reference,
            'chargement_id' => $this->chargement_id,
            'client_nom' => $this->client_nom,
            'prix_unitaire_mga' => $this->prix_unitaire_mga,
            'poids_arrivee_kg' => $this->poids_arrivee_kg,
            'montant_total_mga' => $this->montant_total_mga,
            'observation' => $this->observation,
        ];
    }

    public function render()
    {
        return '<div></div>';
    }
}

// ===============================================

// app/Livewire/Voyage/Modal/DechargementTransfertModal.php

namespace App\Livewire\Voyage\Modal;

use Livewire\Component;
use App\Models\Voyage;
use App\Models\Dechargement;
use Illuminate\Support\Facades\DB;

class DechargementTransfertModal extends Component
{
    public Voyage $voyage;
    public $showModal = false;
    public $editingDechargement = null;

    // Form fields spécifiques au transfert
    public $dechargement_reference = '';
    public $chargement_id = '';
    public $destination_transfert_id = '';
    public $responsable_destination = '';
    public $pointeur_nom = '';
    public $poids_arrivee_kg = '';
    public $motif_transfert = '';
    public $numero_bon_transfert = '';
    public $statut_commercial = 'transfere';
    public $observation = '';

    protected $listeners = [
        'openTransfertModal' => 'openModal',
        'closeTransfertModal' => 'closeModal',
    ];

    public function mount(Voyage $voyage)
    {
        $this->voyage = $voyage;
    }

    public function openModal($dechargementId = null)
    {
        if ($dechargementId) {
            $this->editingDechargement = Dechargement::findOrFail($dechargementId);
            $this->fillForm();
        } else {
            $this->resetForm();
            $this->generateReference();
        }
        
        $this->showModal = true;
        $this->dispatch('show-dechargement-modal', [
            'type' => 'transfert',
            'data' => $this->getFormData()
        ]);
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
        $this->dispatch('hide-dechargement-modal');
    }

    public function saveTransfert()
    {
        $this->validate([
            'chargement_id' => 'required|exists:chargements,id',
            'destination_transfert_id' => 'required|exists:lieux,id',
            'poids_arrivee_kg' => 'required|numeric|min:0',
            'motif_transfert' => 'required|string',
        ]);

        DB::beginTransaction();
        try {
            $data = [
                'voyage_id' => $this->voyage->id,
                'reference' => $this->dechargement_reference,
                'chargement_id' => $this->chargement_id,
                'type' => 'transfert',
                'lieu_livraison_id' => $this->destination_transfert_id,
                'interlocuteur_nom' => $this->responsable_destination,
                'pointeur_nom' => $this->pointeur_nom,
                'poids_arrivee_kg' => $this->poids_arrivee_kg,
                'motif_transfert' => $this->motif_transfert,
                'numero_bon_transfert' => $this->numero_bon_transfert ?: 'BT-' . time(),
                'statut_commercial' => $this->statut_commercial,
                'observation' => $this->observation,
                'date_dechargement' => now(),
                'user_id' => auth()->id(),
            ];

            if ($this->editingDechargement) {
                $this->editingDechargement->update($data);
                $action = 'modifié';
            } else {
                Dechargement::create($data);
                $action = 'créé';
            }

            // Ici vous pourriez ajouter la logique de transfert entre stocks
            $this->traiterTransfert($data);

            DB::commit();
            session()->flash('success', "🔁 Transfert {$action} avec succès !");
            $this->dispatch('dechargement-saved');
            $this->closeModal();

        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Erreur : ' . $e->getMessage());
        }
    }

    private function traiterTransfert($data)
    {
        // Logique spécifique aux transferts
        // Par exemple : mise à jour des stocks source et destination
    }

    private function fillForm()
    {
        $d = $this->editingDechargement;
        $this->dechargement_reference = $d->reference;
        $this->chargement_id = $d->chargement_id;
        $this->destination_transfert_id = $d->lieu_livraison_id;
        $this->responsable_destination = $d->interlocuteur_nom;
        $this->poids_arrivee_kg = $d->poids_arrivee_kg;
        $this->motif_transfert = $d->motif_transfert ?? '';
        $this->observation = $d->observation;
    }

    private function resetForm()
    {
        $this->reset(['dechargement_reference', 'chargement_id', 'destination_transfert_id', 'responsable_destination', 'poids_arrivee_kg', 'motif_transfert', 'observation']);
        $this->statut_commercial = 'transfere';
    }

    private function generateReference()
    {
        $count = $this->voyage->dechargements()->where('type', 'transfert')->count() + 1;
        $this->dechargement_reference = 'TRANSF-' . str_pad($count, 3, '0', STR_PAD_LEFT);
    }

    public function getFormData()
    {
        return [
            'dechargement_reference' => $this->dechargement_reference,
            'chargement_id' => $this->chargement_id,
            'destination_transfert_id' => $this->destination_transfert_id,
            'responsable_destination' => $this->responsable_destination,
            'poids_arrivee_kg' => $this->poids_arrivee_kg,
            'motif_transfert' => $this->motif_transfert,
            'observation' => $this->observation,
        ];
    }

    public function render()
    {
        return '<div></div>';
    }
}

// ===============================================

// app/Livewire/Voyage/Modal/DechargementRetourModal.php

namespace App\Livewire\Voyage\Modal;

use Livewire\Component;
use App\Models\Voyage;
use App\Models\Dechargement;
use Illuminate\Support\Facades\DB;

class DechargementRetourModal extends Component
{
    public Voyage $voyage;
    public $showModal = false;
    public $editingDechargement = null;

    // Form fields spécifiques au retour
    public $dechargement_reference = '';
    public $chargement_id = '';
    public $motif_retour = '';
    public $responsable_retour = '';
    public $pointeur_nom = '';
    public $poids_arrivee_kg = '';
    public $etat_marchandise = '';
    public $decision_retour = '';
    public $remboursement_mga = '';
    public $statut_commercial = 'retourne';
    public $observation = '';

    protected $listeners = [
        'openRetourModal' => 'openModal',
        'closeRetourModal' => 'closeModal',
    ];

    public function mount(Voyage $voyage)
    {
        $this->voyage = $voyage;
    }

    public function openModal($dechargementId = null)
    {
        if ($dechargementId) {
            $this->editingDechargement = Dechargement::findOrFail($dechargementId);
            $this->fillForm();
        } else {
            $this->resetForm();
            $this->generateReference();
        }
        
        $this->showModal = true;
        $this->dispatch('show-dechargement-modal', [
            'type' => 'retour',
            'data' => $this->getFormData()
        ]);
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
        $this->dispatch('hide-dechargement-modal');
    }

    public function saveRetour()
    {
        $this->validate([
            'chargement_id' => 'required|exists:chargements,id',
            'motif_retour' => 'required|string',
            'poids_arrivee_kg' => 'required|numeric|min:0',
            'etat_marchandise' => 'required|in:bon,deteriore,impropre',
        ]);

        DB::beginTransaction();
        try {
            $data = [
                'voyage_id' => $this->voyage->id,
                'reference' => $this->dechargement_reference,
                'chargement_id' => $this->chargement_id,
                'type' => 'retour',
                'interlocuteur_nom' => $this->responsable_retour,
                'pointeur_nom' => $this->pointeur_nom,
                'poids_arrivee_kg' => $this->poids_arrivee_kg,
                'motif_retour' => $this->motif_retour,
                'etat_marchandise' => $this->etat_marchandise,
                'decision_retour' => $this->decision_retour,
                'remboursement_mga' => $this->remboursement_mga ?: 0,
                'statut_commercial' => $this->statut_commercial,
                'observation' => $this->observation,
                'date_dechargement' => now(),
                'user_id' => auth()->id(),
            ];

            if ($this->editingDechargement) {
                $this->editingDechargement->update($data);
                $action = 'modifié';
            } else {
                Dechargement::create($data);
                $action = 'créé';
            }

            // Logique spécifique aux retours
            $this->traiterRetour($data);

            DB::commit();
            session()->flash('success', "↩️ Retour {$action} avec succès !");
            $this->dispatch('dechargement-saved');
            $this->closeModal();

        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Erreur : ' . $e->getMessage());
        }
    }

    private function traiterRetour($data)
    {
        // Logique spécifique aux retours
        // Par exemple : gestion des remboursements, re-stockage, etc.
    }

    private function fillForm()
    {
        $d = $this->editingDechargement;
        $this->dechargement_reference = $d->reference;
        $this->chargement_id = $d->chargement_id;
        $this->motif_retour = $d->motif_retour ?? '';
        $this->responsable_retour = $d->interlocuteur_nom;
        $this->poids_arrivee_kg = $d->poids_arrivee_kg;
        $this->etat_marchandise = $d->etat_marchandise ?? '';
        $this->remboursement_mga = $d->remboursement_mga ?? '';
        $this->observation = $d->observation;
    }

    private function resetForm()
    {
        $this->reset(['dechargement_reference', 'chargement_id', 'motif_retour', 'responsable_retour', 'poids_arrivee_kg', 'etat_marchandise', 'remboursement_mga', 'observation']);
        $this->statut_commercial = 'retourne';
    }

    private function generateReference()
    {
        $count = $this->voyage->dechargements()->where('type', 'retour')->count() + 1;
        $this->dechargement_reference = 'RET-' . str_pad($count, 3, '0', STR_PAD_LEFT);
    }

    public function getFormData()
    {
        return [
            'dechargement_reference' => $this->dechargement_reference,
            'chargement_id' => $this->chargement_id,
            'motif_retour' => $this->motif_retour,
            'responsable_retour' => $this->responsable_retour,
            'poids_arrivee_kg' => $this->poids_arrivee_kg,
            'etat_marchandise' => $this->etat_marchandise,
            'observation' => $this->observation,
        ];
    }

    public function render()
    {
        return '<div></div>';
    }
}
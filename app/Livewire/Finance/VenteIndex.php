<?php

namespace App\Livewire\Finance;

use App\Models\Lieu;
use App\Models\Vente;
use App\Models\Compte;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class VenteIndex extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    /** UI / Modale */
    public bool $showModal = false;
    public ?Vente $editingVente = null;
    public ?string $soldeMessage = null;
    public string $activeTab = 'ventes';

    /** Formulaire */
    public array $form = [
        'reference'             => null,
        'date'                  => null,
        'objet'                 => null,
        'depot_id'              => null,
        'vendeur_nom'           => null,

        'montant_paye_mga'      => null,
        'montant_restant_mga'   => 0,
        'statut_paiement'       => 'paye', // paye | partiel

        'type_paiement'         => 'Principal', // Principal | MobileMoney | Banque
        'sous_type_paiement'    => null,        // Mvola | OrangeMoney | AirtelMoney | BNI | BFV | ...

        'observation'           => null,
    ];

    /** Filtres */
    public ?string $searchTerm = null;
    public ?string $filterDate = null;           // today|week|month|year|null
    public ?string $filterStatutPaiement = null; // paye|partiel|null
    public ?int    $filterDepot = null;
    public ?string $filterTypePaiement = null;   // Principal|MobileMoney|Banque|null
    public ?string $filterSousType = null;       // Mvola|BNI|...|null

    /** UI : lignes développées */
    public array $expandedVentes = [];

    /** Data pour selects */
    public $depots = [];

    /* =========================
     |   Validation
     ========================= */
    protected function rules(): array
    {
        return [
            'form.reference'            => 'nullable|string|max:50',
            'form.date'                 => 'required|date',
            'form.objet'                => 'nullable|string',
            'form.depot_id'             => 'nullable|exists:lieux,id',
            'form.vendeur_nom'          => 'nullable|string|max:255',

            'form.montant_paye_mga'     => 'required|numeric|min:0.01',
            'form.statut_paiement'      => 'required|in:paye,partiel',
            'form.montant_restant_mga'  => 'nullable|numeric|min:0|required_if:form.statut_paiement,partiel',

            'form.type_paiement'        => 'required|in:Principal,MobileMoney,Banque',
            'form.sous_type_paiement'   => 'nullable|string|required_if:form.type_paiement,MobileMoney,Banque|max:50',

            'form.observation'          => 'nullable|string',
        ];
    }

    /* =========================
     |   Hooks & Helpers
     ========================= */
    public function mount(): void
    {
        Log::info('🚀 VenteIndex::mount() - Initialisation du composant PURE LIVEWIRE');

        $this->depots = Lieu::query()
            ->when(true, fn($q) => $q->whereIn('type', ['depot', 'boutique', 'magasin'])->orWhereNull('type'))
            ->orderBy('nom')
            ->get();
    }

    public function updatedFormTypePaiement(): void
    {
        // Reset du sous-type quand on change le type
        $this->form['sous_type_paiement'] = null;
        $this->updateSoldeMessage();
    }

    public function updatedFormStatutPaiement($value): void
    {
        if ($value === 'paye') {
            $this->form['montant_restant_mga'] = 0;
        }
        $this->updateSoldeMessage();
    }

    public function updatedFormMontantPayeMga(): void
    {
        $this->updateSoldeMessage();
    }

    protected function updateSoldeMessage(): void
    {
        $this->soldeMessage = null;
        if (($this->form['statut_paiement'] ?? 'paye') === 'partiel') {
            $this->soldeMessage = "Paiement partiel sélectionné : pensez à renseigner le montant restant.";
        }
    }

    /* =========================
     |   Actions Modale
     ========================= */
    public function createVente(): void
    {
        Log::info('➕ VenteIndex::createVente() - Création nouvelle vente');

        $this->form = [
            'reference'             => $this->genererReference(),
            'date'                  => now()->toDateString(),
            'objet'                 => null,
            'depot_id'              => null,
            'vendeur_nom'           => null,

            'montant_paye_mga'      => null,
            'montant_restant_mga'   => 0,
            'statut_paiement'       => 'paye',

            'type_paiement'         => 'Principal',
            'sous_type_paiement'    => null,

            'observation'           => null,
        ];

        $this->editingVente = null;
        $this->resetErrorBag();
        $this->updateSoldeMessage();

        $this->showModal = true;

        Log::info('✅ Modal création ouvert', ['reference' => $this->form['reference']]);
    }

    public function editVente(int $id): void
    {
        $vente = Vente::findOrFail($id);

        $this->form = [
            'reference'             => $vente->reference,
            'date'                  => optional($vente->date)->toDateString(),
            'objet'                 => $vente->objet,
            'depot_id'              => $vente->depot_id,
            'vendeur_nom'           => $vente->vendeur_nom,

            'montant_paye_mga'      => $vente->montant_paye_mga,
            'montant_restant_mga'   => $vente->montant_restant_mga,
            'statut_paiement'       => $vente->statut_paiement,

            'type_paiement'         => $vente->type_paiement,
            'sous_type_paiement'    => $vente->sous_type_paiement,

            'observation'           => $vente->observation,
        ];

        $this->editingVente = $vente;
        $this->resetErrorBag();
        $this->updateSoldeMessage();

        $this->showModal = true;
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->editingVente = null;
        $this->resetErrorBag();
    }

    public function saveVente(): void
    {
        Log::info('💾 VenteIndex::saveVente() - Début sauvegarde');

        $this->validate();

        // Normalisation si payé complet
        if (($this->form['statut_paiement'] ?? 'paye') === 'paye') {
            $this->form['montant_restant_mga'] = 0;
        }

        $data = [
            'reference'             => $this->form['reference'] ?: $this->genererReference(),
            'date'                  => $this->form['date'],
            'objet'                 => $this->form['objet'],
            'depot_id'              => $this->form['depot_id'],
            'vendeur_nom'           => $this->form['vendeur_nom'],

            'montant_paye_mga'      => (float) ($this->form['montant_paye_mga'] ?? 0),
            'montant_restant_mga'   => (float) ($this->form['montant_restant_mga'] ?? 0),
            'statut_paiement'       => $this->form['statut_paiement'],

            'type_paiement'         => $this->form['type_paiement'],
            'sous_type_paiement'    => $this->form['sous_type_paiement'],

            'observation'           => $this->form['observation'],
        ];

        DB::transaction(function () use ($data) {
            // 1) Trouver (ou créer) le compte cible selon type/sous-type
            $compteCible = $this->resolveCompteFromForm($data['type_paiement'], $data['sous_type_paiement']);
            $data['compte_id'] = $compteCible->id;

            if ($this->editingVente) {
                // 2a) EDIT : annuler l'effet de l'ancienne vente sur l'ancien compte
                $venteOld = $this->editingVente->fresh();

                if ($venteOld->compte_id) {
                    $compteAncien = Compte::find($venteOld->compte_id);
                    if ($compteAncien) {
                        // on retire l'ancien montant payé de l'ancien compte
                        $compteAncien->decrement('solde_actuel_mga', (float) $venteOld->montant_paye_mga);
                    }
                }

                // 2b) Mettre à jour la vente avec le nouveau compte
                $this->editingVente->update($data);

                // 2c) Créditer le compte cible du nouveau montant
                $compteCible->increment('solde_actuel_mga', (float) $data['montant_paye_mga']);
            } else {
                // 2a) CREATE : créer la vente
                $vente = Vente::create($data);

                // 2b) Créditer le compte cible
                $compteCible->increment('solde_actuel_mga', (float) $data['montant_paye_mga']);
            }
        });

        session()->flash('success', $this->editingVente ? 'Vente modifiée avec succès' : 'Vente créée avec succès');
        $this->closeModal();
    }

    public function deleteVente(int $id): void
    {
        DB::transaction(function () use ($id) {
            $vente = Vente::findOrFail($id);

            // Annuler l'effet sur le compte (décréditer du montant payé)
            if ($vente->compte_id) {
                $compte = Compte::find($vente->compte_id);
                if ($compte) {
                    $compte->decrement('solde_actuel_mga', (float) $vente->montant_paye_mga);
                }
            }

            $vente->delete();
        });

        session()->flash('success', 'Vente supprimée avec succès');
    }

    public function marquerPaye(int $id): void
    {
        // Ici on ne touche pas aux montants, on ferme juste le restant
        $vente = Vente::findOrFail($id);
        $vente->update([
            'statut_paiement'      => 'paye',
            'montant_restant_mga'  => 0,
        ]);
        session()->flash('success', 'Vente marquée comme payée.');
    }

    public function toggleDetails(int $id): void
    {
        if (in_array($id, $this->expandedVentes, true)) {
            $this->expandedVentes = array_values(array_diff($this->expandedVentes, [$id]));
        } else {
            $this->expandedVentes[] = $id;
        }
    }

    public function clearFilters(): void
    {
        $this->reset([
            'searchTerm',
            'filterDate',
            'filterStatutPaiement',
            'filterDepot',
            'filterTypePaiement',
            'filterSousType',
        ]);
        $this->resetPage();
    }

    /* =========================
     |   Render / Query
     ========================= */
    public function render()
    {
         $query = Vente::query()->with(['depot', 'compte']);

        // Recherche
        if ($this->searchTerm) {
            $t = trim($this->searchTerm);
            $query->where(function ($q) use ($t) {
                $q->where('reference', 'like', "%{$t}%")
                  ->orWhere('objet', 'like', "%{$t}%")
                  ->orWhere('vendeur_nom', 'like', "%{$t}%");
            });
        }

        // Filtres
        if ($this->filterDepot) {
            $query->where('depot_id', $this->filterDepot);
        }
        if ($this->filterStatutPaiement) {
            $query->where('statut_paiement', $this->filterStatutPaiement);
        }
        if ($this->filterTypePaiement) {
            $query->where('type_paiement', $this->filterTypePaiement);
        }
        if ($this->filterSousType) {
            $query->where('sous_type_paiement', $this->filterSousType);
        }

        // Date
        if ($this->filterDate) {
            match ($this->filterDate) {
                'today' => $query->whereDate('date', now()->toDateString()),
                'week'  => $query->whereBetween('date', [now()->startOfWeek(), now()->endOfWeek()]),
                'month' => $query->whereBetween('date', [now()->startOfMonth(), now()->endOfMonth()]),
                'year'  => $query->whereBetween('date', [now()->startOfYear(), now()->endOfYear()]),
                default => null,
            };
        }

        $ventes = $query->latest('date')->latest('id')->paginate(10);

        return view('livewire.finance.vente-index', [
            'ventes'     => $ventes,
            'depots'     => $this->depots,
            'activeTab'  => $this->activeTab,
        ]);
    }

    /* =========================
     |   Utils
     ========================= */
    protected function genererReference(): string
    {
        // Ex: VTE20250816001
        $prefix = 'VTE' . now()->format('Ymd');
        $last   = Vente::withTrashed()
                    ->where('reference', 'like', $prefix . '%')
                    ->orderBy('reference', 'desc')
                    ->value('reference');

        $nextNumber = 1;
        if ($last && preg_match('/(\d{3})$/', $last, $m)) {
            $nextNumber = (int)$m[1] + 1;
        }

        return $prefix . str_pad((string)$nextNumber, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Trouve ou crée le compte cible à partir du type/sous-type de paiement.
     * - Principal  -> type_compte = "Principal"
     * - MobileMoney -> type_compte = "MobileMoney", sous-type = opérateur
     * - Banque      -> type_compte = "Banque", sous-type = banque
     */
    protected function resolveCompteFromForm(string $type, ?string $sousType): Compte
    {
        $query = Compte::query()->actif();

        if ($type === Compte::TYPE_PRINCIPAL) {
            $query->where('type_compte', Compte::TYPE_PRINCIPAL);
        } elseif ($type === Compte::TYPE_MOBILEMONEY) {
            $query->where('type_compte', Compte::TYPE_MOBILEMONEY)
                  ->where('type_compte_mobilemoney_or_banque', $sousType);
        } elseif ($type === Compte::TYPE_BANQUE) {
            $query->where('type_compte', Compte::TYPE_BANQUE)
                  ->where('type_compte_mobilemoney_or_banque', $sousType);
        }

        $compte = $query->first();

        if (!$compte) {
            // Création automatique d’un compte actif si inexistant
            $compte = Compte::create([
                'user_id'                          => null,
                'nom_proprietaire'                 => 'Mme TINAH', // adapte si nécessaire
                'type_compte'                      => $type,
                'type_compte_mobilemoney_or_banque'=> $type === Compte::TYPE_PRINCIPAL ? null : $sousType,
                'numero_compte'                    => null,
                'solde_actuel_mga'                 => 0,
                'actif'                            => true,
            ]);
        }

        return $compte;
    }
}

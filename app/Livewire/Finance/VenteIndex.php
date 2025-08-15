<?php

namespace App\Livewire\Finance;

use App\Models\Vente;
use App\Models\Lieu;
use App\Models\Compte;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithPagination;

class VenteIndex extends Component
{
    use WithPagination;

    /** Onglets */
    public string $activeTab = 'ventes';

    /** Filtres */
    public string $searchTerm = '';
    public string $filterModePaiement = '';
    public string $filterStatutPaiement = '';
    public string $filterDepot = '';
    public string $filterDate = '';
    public string $dateDebut = '';
    public string $dateFin = '';

    /** Modal & form */
    public bool $showModal = false;
    public ?Vente $editingVente = null;
    public string $soldeMessage = '';

    /** Gestion des détails expandables - SANS ALPINE */
    public array $expandedVentes = [];

    /** Form groupé */
    public array $form = [
        'reference'              => '',
        'date'                   => '',
        'objet'                  => '',
        'depot_id'               => '',
        'vendeur_nom'            => '',
        'montant_paye'           => '',
        'montant_restant'        => '',
        'statut_paiement'        => 'paye',
        'mode_paiement'          => 'especes',
        'observation'            => '',
    ];

    /** Règles de validation */
    protected function rules(): array
    {
        return [
            'form.reference'         => ['required','string','max:255'],
            'form.date'              => ['required','date'],
            'form.objet'             => ['nullable','string'],
            'form.depot_id'          => ['nullable','exists:lieux,id'],
            'form.vendeur_nom'       => ['nullable','string','max:255'],
            'form.montant_paye'      => ['required','numeric','min:0'],
            'form.montant_restant'   => ['nullable','numeric','min:0'],
            'form.statut_paiement'   => ['required','in:paye,partiel'],
            'form.mode_paiement'     => ['required','in:especes,AirtelMoney,OrangeMoney,Mvola,banque'],
            'form.observation'       => ['nullable','string'],
        ];
    }

    protected array $messages = [
        'form.reference.required'       => 'La référence est obligatoire.',
        'form.date.required'            => 'La date est obligatoire.',
        'form.montant_paye.required'    => 'Le montant payé est obligatoire.',
        'form.montant_paye.numeric'     => 'Le montant payé doit être un nombre.',
        'form.montant_paye.min'         => 'Le montant payé doit être positif.',
        'form.depot_id.exists'          => 'Le dépôt sélectionné n\'existe pas.',
        'form.statut_paiement.required' => 'Le statut de paiement est obligatoire.',
        'form.mode_paiement.required'   => 'Le mode de paiement est obligatoire.',
    ];

    public function mount(): void
    {
        Log::info('🚀 VenteIndex::mount() - Initialisation du composant PURE LIVEWIRE');
        $this->activeTab = request()->query('tab', 'ventes');
        $this->dateDebut = Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->dateFin   = Carbon::now()->endOfMonth()->format('Y-m-d');
    }

    /** Toggle des détails - REMPLACE ALPINE.JS */
    public function toggleDetails($venteId): void
    {
        Log::info("🔄 Toggle détails pour vente ID: {$venteId}");
        
        if (in_array($venteId, $this->expandedVentes)) {
            $this->expandedVentes = array_diff($this->expandedVentes, [$venteId]);
            Log::info("➖ Détails fermés pour vente ID: {$venteId}");
        } else {
            $this->expandedVentes[] = $venteId;
            Log::info("➕ Détails ouverts pour vente ID: {$venteId}");
        }
    }

    /** Navigation entre onglets */
    public function setActiveTab(string $tab): void
    {
        Log::info("📋 Changement d'onglet vers: {$tab}");
        
        if (!in_array($tab, ['ventes', 'rapports'], true)) {
            Log::warning("⚠️ Onglet invalide: {$tab}");
            return;
        }

        $this->activeTab = $tab;
        $this->resetPage();
        $this->dispatch('tab-changed', tab: $tab);
        
        Log::info("✅ Onglet changé: {$tab}");
    }

    /** CRUD OPERATIONS */
    
    public function createVente(): void
    {
        Log::info('➕ VenteIndex::createVente() - Création nouvelle vente');
        
        $this->resetFormAndModal();
        $this->form['reference'] = $this->generateReference();
        $this->form['date'] = Carbon::now()->format('Y-m-d');
        $this->showModal = true;
        
        Log::info('✅ Modal création ouvert', ['reference' => $this->form['reference']]);
    }

    public function editVente($id): void
    {
        Log::info("✏️ VenteIndex::editVente() - Edition vente ID: {$id}");
        
        try {
            $this->editingVente = Vente::findOrFail($id);
            
            Log::info('📄 Vente trouvée pour édition', [
                'id' => $this->editingVente->id,
                'reference' => $this->editingVente->reference
            ]);

            $this->form = [
                'reference'         => $this->editingVente->reference,
                'date'              => optional($this->editingVente->date)->format('Y-m-d'),
                'objet'             => $this->editingVente->objet ?? '',
                'depot_id'          => $this->editingVente->depot_id ?? '',
                'vendeur_nom'       => $this->editingVente->vendeur_nom ?? '',
                'montant_paye'      => $this->editingVente->montant_paye_mga,
                'montant_restant'   => $this->editingVente->montant_restant_mga ?? 0,
                'statut_paiement'   => $this->editingVente->statut_paiement,
                'mode_paiement'     => $this->editingVente->mode_paiement,
                'observation'       => $this->editingVente->observation ?? '',
            ];

            $this->showModal = true;
            $this->soldeMessage = '';
            
            Log::info('✅ Modal édition ouvert', ['vente_id' => $id]);
            
        } catch (\Throwable $e) {
            Log::error("❌ Erreur édition vente ID: {$id}", [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            session()->flash('error', 'Erreur lors du chargement de la vente.');
        }
    }

    private function mettreAJourCompte(string $modePaiement, float $montant, string $action = 'ajouter'): void
    {
        // Mapping mode_paiement -> type_compte
        $typeCompte = match ($modePaiement) {
            'especes' => 'principal',
            'AirtelMoney' => 'AirtelMoney',
            'OrangeMoney' => 'OrangeMoney',
            'Mvola' => 'Mvola',
            'banque' => 'banque',
            default => null
        };

        if (!$typeCompte) {
            Log::warning("Mode de paiement non reconnu: {$modePaiement}");
            return;
        }

        // Trouver ou créer le compte
        $compte = Compte::where('type_compte', $typeCompte)
                       ->where('actif', true)
                       ->first();

        if (!$compte) {
            // Créer le compte automatiquement
            $compte = Compte::create([
                'nom_proprietaire' => 'Mme TINAH',
                'type_compte' => $typeCompte,
                'numero_compte' => $typeCompte === 'principal' ? null : date('Ymd') . '-' . strtoupper(substr($typeCompte, 0, 2)),
                'solde_actuel_mga' => 0,
                'actif' => true
            ]);
            Log::info("Compte créé automatiquement: {$typeCompte}");
        }

        // Mettre à jour le solde
        $nouveauSolde = $action === 'ajouter' 
            ? $compte->solde_actuel_mga + $montant
            : $compte->solde_actuel_mga - $montant;

        $compte->update(['solde_actuel_mga' => $nouveauSolde]);
        
        Log::info("Compte {$typeCompte} mis à jour: {$action} {$montant} MGA, nouveau solde: {$nouveauSolde} MGA");
    }

// ✅ CORRRIGÉE : saveVente avec gestion des champs vides
    public function saveVente(): void
    {
        Log::info('💾 VenteIndex::saveVente() - Début sauvegarde');
        
        try {
            $this->validate();
            
            $data = [
                'reference'              => $this->form['reference'],
                'date'                   => $this->form['date'],
                'objet'                  => $this->form['objet'] ?: null,
                'depot_id'               => $this->form['depot_id'] ?: null,
                'vendeur_nom'            => $this->form['vendeur_nom'] ?: null,
                'montant_paye_mga'       => $this->form['montant_paye'],
                'montant_restant_mga'    => empty($this->form['montant_restant']) ? 0 : $this->form['montant_restant'], // ✅ FIX ICI
                'statut_paiement'        => $this->form['statut_paiement'],
                'mode_paiement'          => $this->form['mode_paiement'],
                'observation'            => $this->form['observation'] ?: null,
            ];

            if ($this->editingVente) {
                // Mode édition : retirer l'ancien montant puis ajouter le nouveau
                $this->mettreAJourCompte($this->editingVente->mode_paiement, $this->editingVente->montant_paye_mga, 'retirer');
                $this->mettreAJourCompte($data['mode_paiement'], (float) $data['montant_paye_mga'], 'ajouter');
                
                $this->editingVente->update($data);
                session()->flash('success', 'Vente modifiée et compte mis à jour avec succès.');
                Log::info("✅ Vente modifiée ID: {$this->editingVente->id}");
            } else {
                // Mode création : ajouter au compte
                $this->mettreAJourCompte($data['mode_paiement'], (float) $data['montant_paye_mga'], 'ajouter');
                
                $newVente = Vente::create($data);
                session()->flash('success', 'Vente créée et compte mis à jour avec succès.');
                Log::info("✅ Nouvelle vente créée ID: {$newVente->id}");
            }

            $this->closeModal();

        } catch (\Throwable $e) {
            Log::error('❌ Erreur sauvegarde vente', [
                'message' => $e->getMessage(),
                'form' => $this->form
            ]);
            session()->flash('error', 'Erreur lors de la sauvegarde.');
        }
    }


    public function deleteVente($id): void
    {
        Log::info("🗑️ VenteIndex::deleteVente() - Suppression vente ID: {$id}");
        
        try {
            $vente = Vente::findOrFail($id);
            
            // Retirer le montant du compte correspondant
            $this->mettreAJourCompte($vente->mode_paiement, $vente->montant_paye_mga, 'retirer');
            
            Log::info('📄 Vente trouvée pour suppression', [
                'id' => $vente->id,
                'reference' => $vente->reference
            ]);
            
            $vente->delete();
            
            // Retirer de la liste des expanded si présent
            $this->expandedVentes = array_diff($this->expandedVentes, [$id]);
            
            session()->flash('success', 'Vente supprimée et compte mis à jour avec succès.');
            Log::info("✅ Vente supprimée ID: {$id}");
            
        } catch (\Throwable $e) {
            Log::error("❌ Erreur suppression vente ID: {$id}", [
                'message' => $e->getMessage()
            ]);
            session()->flash('error', 'Erreur lors de la suppression.');
        }
    }

    public function marquerPaye($id): void
    {
        Log::info("✅ VenteIndex::marquerPaye() - Marquer payé vente ID: {$id}");
        
        try {
            $vente = Vente::findOrFail($id);
            
            Log::info('📄 Vente trouvée pour marquer payée', [
                'id' => $vente->id,
                'reference' => $vente->reference,
                'statut_actuel' => $vente->statut_paiement
            ]);

            $vente->update([
                'statut_paiement' => 'paye',
                'montant_restant_mga' => 0
            ]);
            
            session()->flash('success', 'Vente marquée comme payée.');
            Log::info("✅ Vente marquée payée ID: {$id}");
            
        } catch (\Throwable $e) {
            Log::error("❌ Erreur marquer payé vente ID: {$id}", [
                'message' => $e->getMessage()
            ]);
            session()->flash('error', 'Erreur lors de la mise à jour.');
        }
    }

    /** MODAL MANAGEMENT */
    
    public function closeModal(): void
    {
        Log::info('❌ Fermeture modal');
        
        $this->showModal = false;
        $this->resetFormAndModal();
        
        Log::info('✅ Modal fermé');
    }

    private function resetFormAndModal(): void
    {
        $this->form = [
            'reference'         => '',
            'date'              => '',
            'objet'             => '',
            'depot_id'          => '',
            'vendeur_nom'       => '',
            'montant_paye'      => '',
            'montant_restant'   => '',
            'statut_paiement'   => 'paye',
            'mode_paiement'     => 'especes',
            'observation'       => '',
        ];
        
        $this->editingVente = null;
        $this->soldeMessage = '';
        $this->resetErrorBag();
    }

    /** HELPER METHODS */
    
    private function generateReference(): string
    {
        $count = Vente::withTrashed()
            ->whereDate('created_at', Carbon::today())
            ->count() + 1;

        do {
            $reference = 'VTE' . date('Ymd') . str_pad($count, 3, '0', STR_PAD_LEFT);
            $count++;
        } while (Vente::withTrashed()->where('reference', $reference)->exists());

        Log::info("🔢 Référence générée: {$reference}");
        return $reference;
    }

    private function verifierCapaciteEncaissement(string $modePaiement, float $montant): array
    {
        Log::info("💰 Vérification capacité encaissement", [
            'mode_paiement' => $modePaiement,
            'montant' => $montant
        ]);
        
        $typeCompte = match ($modePaiement) {
            'AirtelMoney'  => 'AirtelMoney',
            'Mvola'        => 'Mvola',
            'OrangeMoney'  => 'OrangeMoney',
            'banque'       => 'banque',
            default        => null,
        };

        if (!$typeCompte) {
            return ['success' => false, 'message' => 'Mode de paiement invalide.'];
        }

        $compte = Compte::where('type_compte', $typeCompte)
            ->where('actif', true)
            ->first();

        if (!$compte) {
            // Pour les ventes, on peut accepter même sans compte configuré
            Log::info("⚠️ Aucun compte pour {$modePaiement}, mais vente acceptée");
            return ['success' => true, 'message' => "Attention: Aucun compte configuré pour {$modePaiement}"];
        }

        Log::info("✅ Compte trouvé pour encaissement");
        return ['success' => true];
    }

    /** FILTERS */
    
    public function clearFilters(): void
    {
        Log::info('🧹 Effacement des filtres');
        
        $this->searchTerm = '';
        $this->filterModePaiement = '';
        $this->filterStatutPaiement = '';
        $this->filterDepot = '';
        $this->filterDate = '';
        $this->resetPage();
        
        Log::info('✅ Filtres effacés');
    }

    /** REAL-TIME VALIDATION */
    
    public function updatedForm($field): void
    {
        if (in_array($field, ['form.montant_paye', 'form.mode_paiement'], true)) {
            if ($this->form['montant_paye'] !== '' && $this->form['mode_paiement'] !== 'especes') {
                $check = $this->verifierCapaciteEncaissement($this->form['mode_paiement'], (float) $this->form['montant_paye']);
                $this->soldeMessage = !$check['success'] ? $check['message'] : '';
            } else {
                $this->soldeMessage = '';
            }
        }
    }

    /** RENDER */
    
    public function render()
    {
        Log::info('🎨 Rendu du composant VenteIndex PURE LIVEWIRE');
        
        $query = Vente::with('depot')
            ->when($this->searchTerm, function ($q) {
                $q->where(function ($sub) {
                    $term = '%' . $this->searchTerm . '%';
                    $sub->where('reference', 'like', $term)
                        ->orWhere('objet', 'like', $term)
                        ->orWhere('vendeur_nom', 'like', $term);
                });
            })
            ->when($this->filterModePaiement, fn ($q) => $q->where('mode_paiement', $this->filterModePaiement))
            ->when($this->filterStatutPaiement, fn ($q) => $q->where('statut_paiement', $this->filterStatutPaiement))
            ->when($this->filterDepot, fn ($q) => $q->where('depot_id', $this->filterDepot))
            ->when($this->filterDate, function ($q) {
                match ($this->filterDate) {
                    'today' => $q->whereDate('date', Carbon::today()),
                    'week'  => $q->whereBetween('date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]),
                    'month' => $q->whereBetween('date', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()]),
                    'year'  => $q->whereBetween('date', [Carbon::now()->startOfYear(), Carbon::now()->endOfYear()]),
                    default => null,
                };
            })
            ->when($this->dateDebut && $this->dateFin, fn ($q) => $q->whereBetween('date', [$this->dateDebut, $this->dateFin]))
            ->orderByDesc('date');

        $ventes = $query->paginate(15);

        // Récupérer les dépôts pour les filtres
        $depots = Lieu::whereIn('type', ['depot', 'magasin', 'boutique'])
            ->where('actif', true)
            ->orderBy('nom')
            ->get();

        $statistiques = [
            'totalVentes'           => (float) Vente::sum('montant_paye_mga'),
            'ventesPartielles'      => (int) Vente::where('statut_paiement', 'partiel')->count(),
            'nombreVentes'          => (int) Vente::count(),
            'montantRestant'        => (float) Vente::sum('montant_restant_mga'),
        ];

        Log::info('✅ Rendu terminé VenteIndex PURE LIVEWIRE', [
            'nombre_ventes' => $ventes->count(),
            'total_pages' => $ventes->lastPage(),
            'expanded_count' => count($this->expandedVentes)
        ]);

        return view('livewire.finance.vente-index', compact('ventes', 'depots', 'statistiques'));
    }

    // Méthode de test pour déboguer
    public function testMethod(): void
    {
        Log::info('🧪 TEST METHOD VENTES APPELÉE AVEC SUCCÈS !!!! (PURE LIVEWIRE)');
        session()->flash('success', 'Test méthode ventes fonctionne parfaitement ! ✅');
    }
}
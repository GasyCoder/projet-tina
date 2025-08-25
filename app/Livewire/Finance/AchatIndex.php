<?php

namespace App\Livewire\Finance;

use App\Models\Achat;
use App\Models\Compte;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithPagination;

class AchatIndex extends Component
{
    use WithPagination;

    /** Onglets */
    public string $activeTab = 'achats';

    /** Filtres */
    public string $searchTerm = '';
    public string $filterModePaiement = '';
    public string $filterDate = '';
    public string $dateDebut = '';
    public string $dateFin = '';

    /** Modal & form */
    public bool $showModal = false;
    public ?Achat $editingAchat = null;
    public string $soldeMessage = '';

    /** Gestion des détails expandables - SANS ALPINE */
    public array $expandedAchats = [];

    /** Form groupé */
    public array $form = [
        'reference'      => '',
        'date'           => '',
        'from_nom'       => '',
        'to_nom'         => '',
        'to_compte'      => '',
        'montant'        => '',
        'objet'          => '',
        'mode_paiement'  => 'especes',
        'statut'         => true,
        'observation'    => '',
    ];

    /** Règles de validation */
    protected function rules(): array
    {
        return [
            'form.reference'     => ['required','string','max:255'],
            'form.date'          => ['required','date'],
            'form.from_nom'      => ['nullable','string','max:255'],
            'form.to_nom'        => ['nullable','string','max:255'],
            'form.to_compte'     => ['nullable','string','max:255'],
            'form.montant'       => ['required','numeric','min:0'],
            'form.objet'         => ['required','string','max:255'],
            'form.mode_paiement' => ['required','in:'.implode(',', Achat::modesPaiement())],
            'form.statut'        => ['required','boolean'],
            'form.observation'   => ['nullable','string'],
        ];
    }

    protected array $messages = [
        'form.objet.required'         => "L'objet est obligatoire.",
        'form.montant.required'       => 'Le montant est obligatoire.',
        'form.montant.numeric'        => 'Le montant doit être un nombre.',
        'form.montant.min'            => 'Le montant doit être positif.',
        'form.date.required'          => 'La date est obligatoire.',
        'form.mode_paiement.required' => 'Le mode de paiement est obligatoire.',
        'form.mode_paiement.in'       => 'Mode de paiement inconnu.',
    ];

    public function mount(): void
    {
        Log::info('🚀 AchatIndex::mount() - Initialisation du composant PURE LIVEWIRE');
        $this->activeTab = request()->query('tab', 'achats');
        $this->dateDebut = Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->dateFin   = Carbon::now()->endOfMonth()->format('Y-m-d');
    }

    /** Toggle des détails - REMPLACE ALPINE.JS */
    public function toggleDetails($achatId): void
    {
        Log::info("🔄 Toggle détails pour achat ID: {$achatId}");
        
        if (in_array($achatId, $this->expandedAchats)) {
            // Fermer
            $this->expandedAchats = array_diff($this->expandedAchats, [$achatId]);
            Log::info("➖ Détails fermés pour achat ID: {$achatId}");
        } else {
            // Ouvrir
            $this->expandedAchats[] = $achatId;
            Log::info("➕ Détails ouverts pour achat ID: {$achatId}");
        }
    }

    /** Navigation entre onglets */
    public function setActiveTab(string $tab): void
    {
        Log::info("📋 Changement d'onglet vers: {$tab}");
        
        if (!in_array($tab, ['achats', 'rapports'], true)) {
            Log::warning("⚠️ Onglet invalide: {$tab}");
            return;
        }

        $this->activeTab = $tab;
        $this->resetPage();
        $this->dispatch('tab-changed', tab: $tab);
        
        Log::info("✅ Onglet changé: {$tab}");
    }

    /** CRUD OPERATIONS */
    
    public function createAchat(): void
    {
        Log::info('➕ AchatIndex::createAchat() - Création nouvel achat');
        
        $this->resetFormAndModal();
        $this->form['reference'] = $this->generateReference();
        $this->form['date'] = Carbon::now()->format('Y-m-d');
        $this->showModal = true;
        
        Log::info('✅ Modal création ouvert', ['reference' => $this->form['reference']]);
    }

    public function editAchat($id): void
    {
        Log::info("✏️ AchatIndex::editAchat() - Edition achat ID: {$id}");
        
        try {
            $this->editingAchat = Achat::findOrFail($id);
            
            Log::info('📄 Achat trouvé pour édition', [
                'id' => $this->editingAchat->id,
                'reference' => $this->editingAchat->reference
            ]);

            $this->form = [
                'reference'      => $this->editingAchat->reference,
                'date'           => optional($this->editingAchat->date)->format('Y-m-d'),
                'from_nom'       => $this->editingAchat->from_nom ?? '',
                'to_nom'         => $this->editingAchat->to_nom ?? '',
                'to_compte'      => $this->editingAchat->to_compte ?? '',
                'montant'        => $this->editingAchat->montant_mga,
                'objet'          => $this->editingAchat->objet,
                'mode_paiement'  => $this->editingAchat->mode_paiement,
                'statut'         => (bool) $this->editingAchat->statut,
                'observation'    => $this->editingAchat->observation ?? '',
            ];

            $this->showModal = true;
            $this->soldeMessage = '';
            
            Log::info('✅ Modal édition ouvert', ['achat_id' => $id]);
            
        } catch (\Throwable $e) {
            Log::error("❌ Erreur édition achat ID: {$id}", [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            session()->flash('error', 'Erreur lors du chargement de l\'achat.');
        }
    }

    public function saveAchat(): void
    {
        Log::info('💾 AchatIndex::saveAchat() - Début sauvegarde');
        
        try {
            $this->validate();
            
            $data = [
                'reference'      => $this->form['reference'],
                'date'           => $this->form['date'],
                'from_nom'       => $this->form['from_nom'] ?: null,
                'to_nom'         => $this->form['to_nom'] ?: null,
                'to_compte'      => $this->form['to_compte'] ?: null,
                'montant_mga'    => $this->form['montant'],
                'objet'          => $this->form['objet'],
                'mode_paiement'  => $this->form['mode_paiement'],
                'statut'         => $this->form['statut'],
                'observation'    => $this->form['observation'] ?: null,
            ];

            // Vérification du solde si nécessaire
            if ($data['statut'] && $data['mode_paiement'] !== Achat::MODE_ESPECES) {
                $check = $this->verifierSolde($data['mode_paiement'], (float) $data['montant_mga']);
                if (!$check['success']) {
                    $this->soldeMessage = $check['message'];
                    Log::warning('⚠️ Solde insuffisant', ['message' => $check['message']]);
                    return;
                }
            }

            if ($this->editingAchat) {
                $this->editingAchat->update($data);
                session()->flash('success', 'Achat modifié avec succès.');
                Log::info("✅ Achat modifié ID: {$this->editingAchat->id}");
            } else {
                $newAchat = Achat::create($data);
                session()->flash('success', 'Achat créé avec succès.');
                Log::info("✅ Nouvel achat créé ID: {$newAchat->id}");
            }

            $this->closeModal();

        } catch (\Throwable $e) {
            Log::error('❌ Erreur sauvegarde achat', [
                'message' => $e->getMessage(),
                'form' => $this->form
            ]);
            session()->flash('error', 'Erreur lors de la sauvegarde.');
        }
    }

    public function deleteAchat($id): void
    {
        Log::info("🗑️ AchatIndex::deleteAchat() - Suppression achat ID: {$id}");
        
        try {
            $achat = Achat::findOrFail($id);
            
            Log::info('📄 Achat trouvé pour suppression', [
                'id' => $achat->id,
                'reference' => $achat->reference
            ]);
            
            $achat->delete();
            
            // Retirer de la liste des expanded si présent
            $this->expandedAchats = array_diff($this->expandedAchats, [$id]);
            
            session()->flash('success', 'Achat supprimé avec succès.');
            Log::info("✅ Achat supprimé ID: {$id}");
            
        } catch (\Throwable $e) {
            Log::error("❌ Erreur suppression achat ID: {$id}", [
                'message' => $e->getMessage()
            ]);
            session()->flash('error', 'Erreur lors de la suppression.');
        }
    }

    public function confirmerAchat($id): void
    {
        Log::info("✅ AchatIndex::confirmerAchat() - Confirmation achat ID: {$id}");
        
        try {
            $achat = Achat::findOrFail($id);
            
            Log::info('📄 Achat trouvé pour confirmation', [
                'id' => $achat->id,
                'reference' => $achat->reference,
                'mode_paiement' => $achat->mode_paiement
            ]);

            // Vérifier le solde si nécessaire
            if ($achat->mode_paiement !== Achat::MODE_ESPECES) {
                $check = $this->verifierSolde($achat->mode_paiement, (float) $achat->montant_mga);
                if (!$check['success']) {
                    session()->flash('error', $check['message']);
                    Log::warning('⚠️ Solde insuffisant pour confirmation', ['message' => $check['message']]);
                    return;
                }
            }

            $achat->update(['statut' => true]);
            
            session()->flash('success', 'Achat confirmé avec succès.');
            Log::info("✅ Achat confirmé ID: {$id}");
            
        } catch (\Throwable $e) {
            Log::error("❌ Erreur confirmation achat ID: {$id}", [
                'message' => $e->getMessage()
            ]);
            session()->flash('error', 'Erreur lors de la confirmation.');
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
            'reference'      => '',
            'date'           => '',
            'from_nom'       => '',
            'to_nom'         => '',
            'to_compte'      => '',
            'montant'        => '',
            'objet'          => '',
            'mode_paiement'  => Achat::MODE_ESPECES,
            'statut'         => true,
            'observation'    => '',
        ];
        
        $this->editingAchat = null;
        $this->soldeMessage = '';
        $this->resetErrorBag();
    }

    /** HELPER METHODS */
    
    private function generateReference(): string
    {
        $count = Achat::withTrashed()
            ->whereDate('created_at', Carbon::today())
            ->count() + 1;

        do {
            $reference = 'ACH' . date('Ymd') . str_pad($count, 3, '0', STR_PAD_LEFT);
            $count++;
        } while (Achat::withTrashed()->where('reference', $reference)->exists());

        Log::info("🔢 Référence générée: {$reference}");
        return $reference;
    }

    private function verifierSolde(string $modePaiement, float $montant): array
    {
        Log::info("💰 Vérification solde", [
            'mode_paiement' => $modePaiement,
            'montant' => $montant
        ]);
        
        $typeCompte = match ($modePaiement) {
            Achat::MODE_AIRTEL => 'AirtelMoney',
            Achat::MODE_MVOLA  => 'Mvola',
            Achat::MODE_ORANGE => 'OrangeMoney',
            Achat::MODE_BANQUE => 'banque',
            default            => null,
        };

        if (!$typeCompte) {
            return ['success' => false, 'message' => 'Mode de paiement invalide.'];
        }

        $compte = Compte::where('type_compte', $typeCompte)
            ->where('actif', true)
            ->first();

        if (!$compte) {
            return ['success' => false, 'message' => "Aucun compte actif pour {$modePaiement}"];
        }

        $solde = (float) $compte->solde_actuel_mga;

        if ($solde < $montant) {
            return [
                'success' => false,
                'message' => "Solde insuffisant. Disponible : " . number_format($solde, 0, ',', ' ') . " MGA",
            ];
        }

        return ['success' => true];
    }

    /** FILTERS */
    
    public function clearFilters(): void
    {
        Log::info('🧹 Effacement des filtres');
        
        $this->searchTerm = '';
        $this->filterModePaiement = '';
        $this->filterDate = '';
        $this->resetPage();
        
        Log::info('✅ Filtres effacés');
    }

    /** REAL-TIME VALIDATION */
    
    public function updatedForm($field): void
    {
        if (in_array($field, ['form.montant', 'form.mode_paiement', 'form.statut'], true)) {
            if ($this->form['statut'] && 
                $this->form['montant'] !== '' && 
                $this->form['mode_paiement'] !== Achat::MODE_ESPECES) {
                
                $check = $this->verifierSolde($this->form['mode_paiement'], (float) $this->form['montant']);
                $this->soldeMessage = $check['success'] ? '' : $check['message'];
            } else {
                $this->soldeMessage = '';
            }
        }
    }

    /** RENDER */
    
    public function render()
    {
        Log::info('🎨 Rendu du composant PURE LIVEWIRE');
        
        $query = Achat::query()
            ->when($this->searchTerm, function ($q) {
                $q->where(function ($sub) {
                    $term = '%' . $this->searchTerm . '%';
                    $sub->where('reference', 'like', $term)
                        ->orWhere('objet', 'like', $term)
                        ->orWhere('from_nom', 'like', $term)
                        ->orWhere('to_nom', 'like', $term);
                });
            })
            ->when($this->filterModePaiement, fn ($q) => $q->where('mode_paiement', $this->filterModePaiement))
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

        $achats = $query->paginate(15);

        $statistiques = [
            'totalSorties'        => (float) Achat::where('statut', true)->sum('montant_mga'),
            'achatsEnAttente'     => (int) Achat::where('statut', false)->count(),
            'nombreAchats'        => (int) Achat::count(),
        ];

        Log::info('✅ Rendu terminé PURE LIVEWIRE', [
            'nombre_achats' => $achats->count(),
            'total_pages' => $achats->lastPage(),
            'expanded_count' => count($this->expandedAchats)
        ]);

        return view('livewire.finance.achat-index', compact('achats', 'statistiques'));
    }

    // Méthode de test pour déboguer
    public function testMethod(): void
    {
        Log::info('🧪 TEST METHOD APPELÉE AVEC SUCCÈS !!!! (PURE LIVEWIRE)');
        session()->flash('success', 'Test méthode fonctionne parfaitement ! ✅');
    }
}
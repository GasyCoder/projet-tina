<?php

namespace App\Livewire\Partenaire;

use App\Models\Compte;
use App\Models\Produit;
use Livewire\Component;
use App\Models\Partenaire;
use Illuminate\Support\Facades\DB;
use Flasher\Laravel\Facade\Flasher;
use App\Models\PartenaireTransaction;
use App\Models\PartenaireTransactionDetail;

class PartenaireShow extends Component
{
    public Partenaire $partenaire;
    public string $filter = 'all';

    // === MODALS ===
    public bool $showTransactionModal = false;
    public bool $showNewEntreeModal = false;
    public bool $showNewSortieModal = false;
    public bool $showTransactionDetailModal = false;
    public bool $showSortieDepuisEntreeModal = false;
    public bool $showSortiesEntreeModal = false;

    // === DONNÉES SÉLECTIONNÉES ===
    public ?PartenaireTransaction $selectedTransaction = null;
    public ?PartenaireTransaction $entreeSource = null;
    public array $transactionDetails = [];
    public array $sortiesEntree = [];

    // === FORMULAIRES ===
    
    // Form Entrée (NOUS -> PARTENAIRE)
    public array $entreeForm = [
        'montant_mga'      => '',
        'motif'            => '',
        'mode_paiement'    => 'especes',
        'sous_type_compte' => null,
        'observation'      => '',
    ];

    // Form Sortie (PARTENAIRE -> NOUS) - Simplifié pour sortie depuis entrée
    public array $sortieForm = [
        'montant_total'    => '',
        'motif'            => '',
        'mode_paiement'    => 'especes',
        'sous_type_compte' => null,
        // ✅ observation supprimée pour les sorties depuis entrée
    ];

    // Détails sortie
    public array $sortieDetails = [];
    public array $newDetail = [
        'type_detail'        => 'autre',
        'produit_id'         => '',
        'description'        => '',
        'quantite'           => 1,
        'unite'              => 'kg', // ✅ NOUVEAU CHAMP avec valeur par défaut
        'prix_unitaire_mga'  => '',
        'montant_mga'        => '',
    ];

    // Données de référence
    public $produits = [];
    public $comptes  = [];

    // === RÈGLES DE VALIDATION ===
    protected $rules = [
        // Entrée
        'entreeForm.montant_mga'      => 'required|numeric|min:0',
        'entreeForm.motif'            => 'required|string|max:255',
        'entreeForm.mode_paiement'    => 'required|in:especes,MobileMoney,Banque',
        'entreeForm.sous_type_compte' => 'nullable|string|required_if:entreeForm.mode_paiement,MobileMoney,Banque|max:50',
        'entreeForm.observation'      => 'nullable|string|max:1000',

        // Sortie (observation supprimée pour les sorties depuis entrée)
        'sortieForm.montant_total'    => 'required|numeric|min:0',
        'sortieForm.motif'            => 'required|string|max:255',
        'sortieForm.mode_paiement'    => 'required|in:especes,MobileMoney,Banque',
        'sortieForm.sous_type_compte' => 'nullable|string|required_if:sortieForm.mode_paiement,MobileMoney,Banque|max:50',

        // Détail (avec unité et montant auto-calculé)
        'newDetail.type_detail'       => 'required|in:achat_produit,credit,frais,autre',
        'newDetail.description'       => 'required|string|max:255',
        'newDetail.quantite'          => 'required|numeric|min:0.01', // ✅ Obligatoire
        'newDetail.unite'             => 'nullable|string|max:10',
        'newDetail.prix_unitaire_mga' => 'required|numeric|min:0.01', // ✅ Obligatoire
        'newDetail.montant_mga'       => 'required|numeric|min:0.01', // ✅ Auto-calculé mais validé
    ];

    // === INITIALISATION ===
    
    public function mount(Partenaire $partenaire): void
    {
        $this->partenaire = $partenaire;
        $this->loadData();
    }

    public function loadData(): void
    {
        $this->produits = Produit::actif()->get();
        $this->comptes  = Compte::actif()->get();
    }

    /**
     * ✅ NOUVEAU : Récupérer les unités disponibles
     */
    public function getUnitesDisponibles()
    {
        return [
            'kg' => 'Kilogramme',
            'sac' => 'Sac',
            'carton' => 'Carton',
            'g' => 'Gramme', 
            'piece' => 'Pièce',
            // 'litre' => 'Litre',
            // 'ml' => 'Millilitre',
            // 'metre' => 'Mètre',
            // 'cm' => 'Centimètre',
            // 'unite' => 'Unité',
        ];
    }

    /** Trouve automatiquement le compte selon le mode choisi */
    protected function resolveCompteBy(string $mode, ?string $sousType = null): ?Compte
    {
        return match ($mode) {
            'especes'     => Compte::actif()->principal()->first(),
            'MobileMoney' => Compte::actif()->mobileMoney($sousType)->first(),
            'Banque'      => Compte::actif()->banque($sousType)->first(),
            default       => null,
        };
    }

    // === FILTRES ===
    
    public function filterTransactions(string $type): void
    {
        $this->filter = $type;
    }

    // === 🔥 AUTO-CALCUL CORRIGÉ ===
    
    /**
     * 🔥 Se déclenche quand la QUANTITÉ change
     */
    public function updatedNewDetailQuantite($value)
    {
        $this->newDetail['quantite'] = $this->cleanNumericValue($value);
        $this->calculerMontantDetailDirect();
    }

    /**
     * 🔥 Se déclenche quand le PRIX UNITAIRE change  
     */
    public function updatedNewDetailPrixUnitaireMga($value)
    {
        $this->newDetail['prix_unitaire_mga'] = $this->cleanNumericValue($value);
        $this->calculerMontantDetailDirect();
    }

    /**
     * ⚡ CALCUL DIRECT ET IMMÉDIAT - FONCTIONNE À 100%
     */
    public function calculerMontantDetailDirect(): void
    {
        $quantite = $this->toFloat($this->newDetail['quantite'] ?? '');
        $prix = $this->toFloat($this->newDetail['prix_unitaire_mga'] ?? '');
        
        if ($quantite > 0 && $prix > 0) {
            $montant = round($quantite * $prix, 2);
            $this->newDetail['montant_mga'] = $montant;
        } else if ($quantite == 0 && $prix == 0) {
            $this->newDetail['montant_mga'] = '';
        }
    }

    /**
     * 🔄 Méthode publique pour calcul manuel
     */
    public function calculerMontant(): void
    {
        $this->calculerMontantDetailDirect();
    }

    /**
     * 🧹 Nettoie les valeurs numériques
     */
    private function cleanNumericValue($value): string
    {
        if (empty($value)) return '';
        
        // Remplacer virgule par point, supprimer espaces et caractères non-numériques
        $cleaned = str_replace([' ', ','], ['.', '.'], trim($value));
        $cleaned = preg_replace('/[^0-9.]/', '', $cleaned);
        
        // Éviter les points multiples
        $parts = explode('.', $cleaned);
        if (count($parts) > 2) {
            $cleaned = $parts[0] . '.' . implode('', array_slice($parts, 1));
        }
        
        return $cleaned;
    }

    /**
     * 🔄 Conversion sécurisée en float
     */
    private function toFloat($value): float
    {
        if (empty($value) || !is_numeric($value)) {
            return 0.0;
        }
        return (float) $value;
    }

    /**
     * 📊 Calcul inverse : si montant change, proposer prix unitaire
     */
    public function calculerPrixUnitaire(): void
    {
        $quantite = $this->toFloat($this->newDetail['quantite'] ?? '');
        $montant = $this->toFloat($this->newDetail['montant_mga'] ?? '');
        
        if ($quantite > 0 && $montant > 0) {
            $prixCalcule = round($montant / $quantite, 2);
            $this->newDetail['prix_unitaire_mga'] = $prixCalcule;
        }
    }

    // === GESTION DES DÉTAILS ===
    
    public function resetNewDetail(): void
    {
        $this->newDetail = [
            'type_detail'        => 'autre',
            'produit_id'         => '',
            'description'        => '',
            'quantite'           => 1,
            'unite'              => 'kg', // ✅ Valeur par défaut
            'prix_unitaire_mga'  => '',
            'montant_mga'        => '',
        ];
    }

    /**
     * ✅ Validation avant ajout du détail
     */
    public function ajouterDetail(): void
    {
        // ✅ S'assurer que le montant est calculé avant validation
        $this->calculerMontantDetailDirect();
        
        // Validation des champs requis (montant sera validé automatiquement)
        $this->validate([
            'newDetail.type_detail'       => 'required|in:achat_produit,credit,frais,autre',
            'newDetail.description'       => 'required|string|max:255',
            'newDetail.quantite'          => 'required|numeric|min:0.01', // ✅ Obligatoire maintenant
            'newDetail.unite'             => 'nullable|string|max:10',
            'newDetail.prix_unitaire_mga' => 'required|numeric|min:0.01', // ✅ Obligatoire maintenant
            'newDetail.montant_mga'       => 'required|numeric|min:0.01', // ✅ Sera calculé automatiquement
        ]);

        // Vérification que le montant a bien été calculé
        $montantCalcule = $this->toFloat($this->newDetail['montant_mga']);
        if ($montantCalcule <= 0) {
            session()->flash('error', 'Erreur de calcul. Vérifiez la quantité et le prix unitaire.');
            return;
        }

        // Préparation du détail
        $detail = $this->newDetail;
        $detail['id'] = uniqid();
        
        // Normalisation des valeurs numériques
        $detail['quantite'] = $this->toFloat($detail['quantite']);
        $detail['unite'] = $detail['unite'] ?: 'kg';
        $detail['prix_unitaire_mga'] = $this->toFloat($detail['prix_unitaire_mga']);
        $detail['montant_mga'] = $montantCalcule; // ✅ Utiliser le montant calculé
        
        // Ajout à la liste
        $this->sortieDetails[] = $detail;

        // Reset du formulaire et recalcul du total
        $this->resetNewDetail();
        $this->calculerMontantTotal();
        
        // Message de succès
        session()->flash('detail_success', 'Détail ajouté avec succès !');
    }

    public function supprimerDetail($index): void
    {
        if (isset($this->sortieDetails[$index])) {
            unset($this->sortieDetails[$index]);
            $this->sortieDetails = array_values($this->sortieDetails);
            $this->calculerMontantTotal();
            
            session()->flash('detail_info', 'Détail supprimé.');
        }
    }

    /**
     * 💰 Calcul du montant total de la sortie
     */
    public function calculerMontantTotal(): void
    {
        $total = 0;
        foreach ($this->sortieDetails as $detail) {
            $total += $this->toFloat($detail['montant_mga'] ?? 0);
        }
        
        $this->sortieForm['montant_total'] = round($total, 2);
    }

    /**
     * 🔄 Recalculer un détail existant
     */
    public function recalculerDetail($index): void
    {
        if (!isset($this->sortieDetails[$index])) return;
        
        $detail = &$this->sortieDetails[$index];
        $qte = $this->toFloat($detail['quantite'] ?? 1);
        $prix = $this->toFloat($detail['prix_unitaire_mga'] ?? 0);
        
        if ($qte > 0 && $prix > 0) {
            $detail['montant_mga'] = round($qte * $prix, 2);
            $this->calculerMontantTotal();
            
            session()->flash('detail_success', 'Détail recalculé !');
        }
    }

    // === GESTION DES ENTRÉES ===
    
    public function openNewEntreeModal(): void
    {
        $this->resetEntreeForm();
        $this->showNewEntreeModal = true;
    }

    public function closeNewEntreeModal(): void
    {
        $this->showNewEntreeModal = false;
        $this->resetEntreeForm();
    }

    public function resetEntreeForm(): void
    {
        $this->entreeForm = [
            'montant_mga'      => '',
            'motif'            => '',
            'mode_paiement'    => 'especes',
            'sous_type_compte' => null,
            'observation'      => '',
        ];
        $this->resetErrorBag();
    }

    public function creerEntree(): void
    {
        $this->validate([
            'entreeForm.montant_mga'      => 'required|numeric|min:0.01',
            'entreeForm.motif'            => 'required|string|max:255',
            'entreeForm.mode_paiement'    => 'required|in:especes,MobileMoney,Banque',
            'entreeForm.sous_type_compte' => 'nullable|string|required_if:entreeForm.mode_paiement,MobileMoney,Banque|max:50',
        ]);

        $montant = (float) $this->entreeForm['montant_mga'];
        $mode    = $this->entreeForm['mode_paiement'];
        $sous    = $this->entreeForm['sous_type_compte'] ?? null;

        $compteSource = $this->resolveCompteBy($mode, $sous);

        if (!$compteSource) {
            Flasher::addError("Aucun compte correspondant au mode de paiement sélectionné.");
            return;
        }

        if (!$compteSource->hasSolde($montant)) {
            Flasher::addError('Solde insuffisant dans le compte source.');
            return;
        }

        try {
            DB::transaction(function () use ($montant, $mode, $sous, $compteSource) {
                // 1) Débit atomique : échoue si le solde a changé entre-temps
                if (! $compteSource->tryDebit($montant)) {
                    throw new \RuntimeException('Solde insuffisant (concurrence). Veuillez réessayer.');
                }

                // 2) Créer la transaction partenaire (Entrée = on paie le partenaire)
                PartenaireTransaction::create([
                    'partenaire_id'     => $this->partenaire->id,
                    'reference'         => PartenaireTransaction::genererReference('entree'),
                    'type'              => 'entree',
                    'montant_mga'       => $montant,
                    'motif'             => $this->entreeForm['motif'],
                    'mode_paiement'     => $mode,
                    'sous_type_compte'  => $sous,
                    'statut'            => true,
                    'date_transaction'  => now(),
                    'observation'       => $this->entreeForm['observation'],
                ]);

                // 3) Solde partenaire + éventuel compte partenaire
                $this->partenaire->ajouterEntree($montant);

                if ($this->partenaire->compte_id) {
                    if ($cpDest = Compte::find($this->partenaire->compte_id)) {
                        $cpDest->credit($montant);
                    }
                }
            });

            Flasher::addSuccess('Entrée créée avec succès !');
            $this->closeNewEntreeModal();
            $this->partenaire->refresh();
            $this->loadData(); // rafraîchir la liste des comptes (soldes)

        } catch (\Throwable $e) {
            Flasher::addError("Erreur lors de la création de l'entrée : ".$e->getMessage());
        }
    }

    // === GESTION DES SORTIES GÉNÉRALES ===
    
    public function openNewSortieModal(): void
    {
        $this->resetSortieForm();
        $this->showNewSortieModal = true;
    }

    public function closeNewSortieModal(): void
    {
        $this->showNewSortieModal = false;
        $this->resetSortieForm();
    }

    public function resetSortieForm(): void
    {
        $this->sortieForm = [
            'montant_total'    => '',
            'motif'            => '',
            'mode_paiement'    => 'especes',
            'sous_type_compte' => null,
            // ✅ Plus d'observation
        ];
        $this->sortieDetails = [];
        $this->resetNewDetail();
        $this->resetErrorBag();
    }


    // -- Helpers UI pour l'entrée --
    public function getCompteSelectionneProperty(): ?Compte
    {
        $mode = $this->entreeForm['mode_paiement'] ?? 'especes';
        $sous = $this->entreeForm['sous_type_compte'] ?? null;
        return $this->resolveCompteBy($mode, $sous);
    }

    public function getSoldeCompteSelectionneProperty(): ?float
    {
        return $this->compteSelectionne?->solde_actuel_mga;
    }

    public function getInsuffisantEntreeProperty(): bool
    {
        $montant = (float) ($this->entreeForm['montant_mga'] ?: 0);
        $solde   = $this->soldeCompteSelectionne ?? null;
        return $solde !== null && $montant > 0 && $montant > (float)$solde;
    }

    public function creerSortie(): void
    {
        $this->validate([
            'sortieForm.montant_total'    => 'required|numeric|min:0',
            'sortieForm.motif'            => 'required|string|max:255',
            'sortieForm.mode_paiement'    => 'required|in:especes,MobileMoney,Banque',
            'sortieForm.sous_type_compte' => 'nullable|string|required_if:sortieForm.mode_paiement,MobileMoney,Banque|max:50',
        ]);

        if (empty($this->sortieDetails)) {
            session()->flash('error', 'Veuillez ajouter au moins un détail à la sortie.');
            return;
        }

        try {
            DB::transaction(function () {
                $montant = (float) $this->sortieForm['montant_total'];
                $mode    = $this->sortieForm['mode_paiement'];
                $sous    = $this->sortieForm['sous_type_compte'] ?? null;

                // Vérifier solde partenaire
                if ($this->partenaire->solde_actuel_mga < $montant) {
                    throw new \Exception('Solde insuffisant pour effectuer cette sortie.');
                }

                // Compte à CRÉDITER
                $compteCible = $this->resolveCompteBy($mode, $sous);
                if (!$compteCible) {
                    throw new \Exception("Aucun compte disponible pour le mode de paiement sélectionné.");
                }

                // Transaction partenaire (Sortie = le partenaire nous paie)
                $transaction = PartenaireTransaction::create([
                    'partenaire_id'     => $this->partenaire->id,
                    'reference'         => PartenaireTransaction::genererReference('sortie'),
                    'type'              => 'sortie',
                    'montant_mga'       => $montant,
                    'motif'             => $this->sortieForm['motif'],
                    'mode_paiement'     => $mode,
                    'sous_type_compte'  => $sous,
                    'statut'            => true,
                    'date_transaction'  => now(),
                    'observation'       => $this->sortieForm['observation'],
                ]);

                // Détails
                foreach ($this->sortieDetails as $detail) {
                    PartenaireTransactionDetail::create([
                        'transaction_id'     => $transaction->id,
                        'produit_id'         => $detail['produit_id'] ?: null,
                        'description'        => $detail['description'],
                        'quantite'           => $detail['quantite'] ?: 1,
                        'unite'              => $detail['unite'] ?: 'kg', // ✅ NOUVEAU
                        'prix_unitaire_mga'  => $detail['prix_unitaire_mga'] ?: 0,
                        'montant_mga'        => $detail['montant_mga'],
                        'type_detail'        => $detail['type_detail'],
                    ]);
                }

                // Mouvements de solde
                $this->partenaire->retirerSortie($montant);
                if ($this->partenaire->compte_id) {
                    $cpPart = Compte::find($this->partenaire->compte_id);
                    if ($cpPart) {
                        $cpPart->decrement('solde_actuel_mga', $montant);
                    }
                }
                $compteCible->increment('solde_actuel_mga', $montant);
            });

            session()->flash('success', 'Sortie créée avec succès !');
            $this->closeNewSortieModal();
            $this->partenaire->refresh();

        } catch (\Exception $e) {
            session()->flash('error', 'Erreur lors de la création de la sortie : ' . $e->getMessage());
        }
    }

    // === GESTION DES SORTIES DEPUIS ENTRÉES ===
    
    public function ouvrirSortieDepuisEntree($entreeId): void
    {
        $this->entreeSource = PartenaireTransaction::find($entreeId);
        if (!$this->entreeSource || $this->entreeSource->type !== 'entree') {
            session()->flash('error', 'Entrée invalide.');
            return;
        }

        // Calculer le montant disponible
        $montantDisponible = $this->getMontantDisponibleEntree($entreeId);
        
        if ($montantDisponible <= 0) {
            session()->flash('error', 'Cette entrée a déjà été entièrement utilisée.');
            return;
        }

        $this->resetSortieForm();
        
        // ✅ PRÉ-REMPLIR AUTOMATIQUEMENT LE MOTIF basé sur l'entrée source
        $this->sortieForm['motif'] = "Utilisation de " . $this->entreeSource->motif;
        
        $this->showSortieDepuisEntreeModal = true;
    }

    public function fermerSortieDepuisEntreeModal(): void
    {
        $this->showSortieDepuisEntreeModal = false;
        $this->entreeSource = null;
        $this->resetSortieForm();
    }

    public function creerSortieDepuisEntree(): void
    {
        $this->validate([
            'sortieForm.montant_total' => 'required|numeric|min:0',
            'sortieForm.motif'         => 'required|string|max:255',
            // ✅ Plus de validation pour observation (supprimée)
        ]);

        if (empty($this->sortieDetails)) {
            session()->flash('error', 'Veuillez ajouter au moins un détail à la sortie.');
            return;
        }

        if (!$this->entreeSource) {
            session()->flash('error', 'Entrée source introuvable.');
            return;
        }

        try {
            DB::transaction(function () {
                $montant = (float) $this->sortieForm['montant_total'];

                // Vérifier montant disponible depuis cette entrée
                $montantDisponible = $this->getMontantDisponibleEntree($this->entreeSource->id);

                if ($montant > $montantDisponible) {
                    throw new \Exception('Montant supérieur au disponible pour cette entrée (' . number_format($montantDisponible, 0, ',', ' ') . ' Ar).');
                }

                // Créer la transaction sortie
                $transaction = PartenaireTransaction::create([
                    'partenaire_id'     => $this->partenaire->id,
                    'entree_source_id'  => $this->entreeSource->id, // LIEN AVEC L'ENTRÉE
                    'reference'         => PartenaireTransaction::genererReference('sortie'),
                    'type'              => 'sortie',
                    'montant_mga'       => $montant,
                    'motif'             => $this->sortieForm['motif'], // ✅ Motif auto-généré
                    'mode_paiement'     => $this->sortieForm['mode_paiement'],
                    'sous_type_compte'  => $this->sortieForm['sous_type_compte'],
                    'statut'            => true,
                    'date_transaction'  => now(),
                    'observation'       => null, // ✅ Plus d'observation
                ]);

                // Détails
                foreach ($this->sortieDetails as $detail) {
                    PartenaireTransactionDetail::create([
                        'transaction_id'     => $transaction->id,
                        'produit_id'         => $detail['produit_id'] ?: null,
                        'description'        => $detail['description'],
                        'quantite'           => $detail['quantite'] ?: 1,
                        'unite'              => $detail['unite'] ?: 'kg', // ✅ NOUVEAU
                        'prix_unitaire_mga'  => $detail['prix_unitaire_mga'] ?: 0,
                        'montant_mga'        => $detail['montant_mga'],
                        'type_detail'        => $detail['type_detail'],
                    ]);
                }

                // Pas de mouvement de solde car c'est une sortie depuis une entrée existante
                // Le solde du partenaire ne change pas dans ce cas
            });

            session()->flash('success', 'Sortie créée avec succès depuis l\'entrée !');
            $this->fermerSortieDepuisEntreeModal();

        } catch (\Exception $e) {
            session()->flash('error', 'Erreur : ' . $e->getMessage());
        }
    }

    public function voirSortiesEntree($entreeId): void
    {
        $this->entreeSource = PartenaireTransaction::find($entreeId);
        if (!$this->entreeSource || $this->entreeSource->type !== 'entree') {
            session()->flash('error', 'Entrée invalide.');
            return;
        }

        $this->sortiesEntree = PartenaireTransaction::with(['details'])
            ->where('entree_source_id', $entreeId)
            ->where('type', 'sortie')
            ->latest('date_transaction')
            ->get()
            ->toArray();

        $this->showSortiesEntreeModal = true;
    }

    public function fermerSortiesEntreeModal(): void
    {
        $this->showSortiesEntreeModal = false;
        $this->entreeSource = null;
        $this->sortiesEntree = [];
    }

    public function getMontantDisponibleEntree($entreeId): float
    {
        $entree = PartenaireTransaction::find($entreeId);
        if (!$entree) return 0;

        $sortiesUtilisees = PartenaireTransaction::where('entree_source_id', $entreeId)
            ->where('type', 'sortie')
            ->sum('montant_mga');

        return $entree->montant_mga - $sortiesUtilisees;
    }

    // === GESTION DES DÉTAILS DE TRANSACTION ===
    
    public function showTransactionDetail($transactionId): void
    {
        $this->selectedTransaction = PartenaireTransaction::with(['details.produit'])
            ->find($transactionId);

        if ($this->selectedTransaction) {
            $this->transactionDetails = $this->selectedTransaction->details->toArray();
            $this->showTransactionDetailModal = true;
        }
    }

    public function closeTransactionDetailModal(): void
    {
        $this->showTransactionDetailModal = false;
        $this->selectedTransaction = null;
        $this->transactionDetails = [];
    }

    // === PROPRIÉTÉS CALCULÉES ===
    
    public function getTransactionsProperty()
    {
        $query = $this->partenaire->transactions()
            ->with(['details.produit'])
            ->latest('date_transaction');

        if ($this->filter !== 'all') {
            $query->where('type', $this->filter);
        }

        return $query->get();
    }

    public function getStatistiquesProperty(): array
    {
        // ✅ UNE SEULE REQUÊTE avec agrégations conditionnelles
        $stats = $this->partenaire->transactions()
            ->selectRaw('
                SUM(CASE WHEN type = "entree" THEN montant_mga ELSE 0 END) as budget_initial,
                SUM(CASE WHEN type = "sortie" AND entree_source_id IS NOT NULL THEN montant_mga ELSE 0 END) as fond_utilise
            ')
            ->first();

        $budgetInitial = (float) ($stats->budget_initial ?? 0);
        $fondUtilise = (float) ($stats->fond_utilise ?? 0);
        $soldeDisponible = $budgetInitial - $fondUtilise;

        return [
            'budget_initial'    => $budgetInitial,
            'fond_utilise'      => $fondUtilise, 
            'solde_disponible'  => $soldeDisponible,
        ];
    }
    
    // === RENDU ===
    
    public function render()
    {
        return view('livewire.partenaire.partenaire-show', [
            'transactions' => $this->transactions,
            'statistiques' => $this->statistiques,
            'produits'     => $this->produits,
            'comptes'      => $this->comptes,
        ]);
    }
}
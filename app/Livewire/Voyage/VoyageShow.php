<?php

namespace App\Livewire\Voyage;

use App\Models\Lieu;
use App\Models\User;
use App\Models\Voyage;
use App\Models\Produit;
use Livewire\Component;
use App\Models\Chargement;
use App\Models\Dechargement;
use Illuminate\Support\Facades\Log;

class VoyageShow extends Component
{
    // ==============================================
    // PROPRIÉTÉS PRINCIPALES
    // ==============================================
    
    public Voyage $voyage;
    public $activeTab = 'chargements';
    
    // Modales
    public $showChargementModal = false;
    public $showDechargementModal = false;
    public $showPreviewModal = false;
    
    // États d'édition
    public $editingChargement = null;
    public $editingDechargement = null;
    
    // ==============================================
    // NOUVELLE LOGIQUE DÉCHARGEMENT (3 ÉTAPES)
    // ==============================================
    
    public $dechargement_step = 1; // 1: Voyage, 2: Chargement, 3: Détails
    public $selected_voyage_id = null;
    public $selected_voyage = null;
    public $available_voyages = [];
    public $available_chargements = [];
    
    // Aperçu et données de prévisualisation
    public $previewData = [];
    
    // ==============================================
    // CHAMPS DE FORMULAIRE - CHARGEMENT
    // ==============================================
    
    public $date = '';
    public $chargement_reference = '';
    public $chargeur_nom = '';
    public $chargeur_contact = '';
    public $depart_id = '';
    public $type_proprietaire = 'defaut'; // 'defaut' ou 'autre'
    public $proprietaire_nom = 'Mme TINAH';
    public $proprietaire_contact = '0349045769';
    public $produit_id = '';
    public $sacs_pleins_depart = '';
    public $sacs_demi_depart = 0;
    public $poids_depart_kg = '';
    public $chargement_observation = '';

    // ==============================================
    // CHAMPS DE FORMULAIRE - DÉCHARGEMENT
    // ==============================================
    
    public $dechargement_reference = '';
    public $chargement_id = ''; 
    public $type_dechargement = 'vente';
    public $interlocuteur_nom = '';
    public $interlocuteur_contact = '';
    public $pointeur_nom = '';
    public $pointeur_contact = '';
    public $lieu_livraison_id = '';
    public $sacs_pleins_arrivee = '';
    public $sacs_demi_arrivee = 0;
    public $poids_arrivee_kg = '';
    public $prix_unitaire_mga = '';
    public $montant_total_mga = '';
    public $paiement_mga = '';
    public $reste_mga = '';
    public $statut_commercial = 'en_attente';
    public $dechargement_observation = '';

    // ✅ NOUVEAU : Informations du produit sélectionné
    public $selected_product = null;
    public $product_unite = 'kg'; // kg, sacs, tonnes, etc.
    public $product_poids_moyen_sac = 120;
    public $product_prix_reference = 0;
    public $quantite_vendue = 0; // Quantité dans l'unité du produit

    
    // ==============================================
    // RÈGLES DE VALIDATION
    // ==============================================
    
    protected $rules = [
        // Chargement
        'date' => 'required|date',
        'chargement_reference' => 'required|string|max:255',
        'chargeur_nom' => 'required|string|max:255',
        'depart_id' => 'required|exists:lieux,id',
        'proprietaire_nom' => 'required|string|max:255',
        'produit_id' => 'required|exists:produits,id',
        'sacs_pleins_depart' => 'required|integer|min:0',
        'poids_depart_kg' => 'required|numeric|min:0',
        
        // Déchargement
        'dechargement_reference' => 'required|string|max:255',
        'chargement_id' => 'required|exists:chargements,id',
        'type_dechargement' => 'required|in:vente,retour,depot,transfert',
        'pointeur_nom' => 'nullable|string|max:255',
        'pointeur_contact' => 'nullable|string|max:255',
        'interlocuteur_nom' => 'nullable|string|max:255',
        'interlocuteur_contact' => 'nullable|string|max:255',
        'lieu_livraison_id' => 'nullable|exists:lieux,id',
        'sacs_pleins_arrivee' => 'nullable|integer|min:0',
        'sacs_demi_arrivee' => 'nullable|integer|min:0',
        'poids_arrivee_kg' => 'nullable|numeric|min:0',
        'prix_unitaire_mga' => 'nullable|numeric|min:0',
        'montant_total_mga' => 'nullable|numeric|min:0',
        'paiement_mga' => 'nullable|numeric|min:0',
        'statut_commercial' => 'required|in:en_attente,vendu,retourne,transfere',
        'dechargement_observation' => 'nullable|string',
    ];

    // ==============================================
    // INITIALISATION
    // ==============================================
    
    public function mount(Voyage $voyage)
    {
        $this->voyage = $voyage;
        $this->date = now()->format('Y-m-d');
        
        // ✅ NOUVEAU : Lire le tab depuis l'URL avec validation
        $validTabs = ['chargements', 'dechargements', 'synthese'];
        $requestedTab = request()->query('tab', 'chargements');
        $this->activeTab = in_array($requestedTab, $validTabs) ? $requestedTab : 'chargements';
        
        // Pré-configuration pour le déchargement
        $this->selected_voyage_id = $voyage->id;
        $this->selected_voyage = $voyage;
        $this->loadAvailableChargements();
    }

    // ==============================================
    // MÉTHODES UTILITAIRES ET FORMATAGE - ✅ CORRIGÉ
    // ==============================================
    
    /**
     * Méthode pour formater les nombres de façon sécurisée
     */
    public function formatNumber($value, $decimals = 0)
    {
        if (is_null($value) || $value === '') {
            return 0;
        }
        
        return number_format(floatval($value), $decimals);
    }

    /**
     * Méthode pour s'assurer que les valeurs sont numériques
     */
    public function ensureNumeric($value)
    {
        if (is_null($value) || $value === '') {
            return 0;
        }
        
        return floatval($value);
    }


    /**
     * Validation simple des sacs pleins en temps réel
     */
    public function updatedSacsPleinsDepart()
    {
        $this->resetErrorBag(['sacs_pleins_depart']);
        $this->validateStockSimple();
    }

    /**
     * Validation simple des sacs demi en temps réel
     */
    public function updatedSacsDemiDepart()
    {
        $this->resetErrorBag(['sacs_demi_depart']);
        $this->validateStockSimple();
    }


    /**
     * ✅ Validation immédiate et précise du stock pour les sacs
     */
    private function validateStockSimple()
    {
        if (!$this->produit_id) return;

        $produit = Produit::find($this->produit_id);
        if (!$produit || $produit->qte_variable <= 0) {
            $this->addError('sacs_pleins_depart', "❌ Stock épuisé pour ce produit !");
            return;
        }

        $sacsPleinsSaisis = floatval($this->sacs_pleins_depart ?: 0);
        $sacsDemiSaisis = floatval($this->sacs_demi_depart ?: 0);
        $totalSacs = $sacsPleinsSaisis + ($sacsDemiSaisis * 0.5);
        
        if ($totalSacs <= 0) return; // Pas de validation si rien de saisi

        $stockDisponible = floatval($produit->qte_variable);
        $depassement = false;

        // ✅ LOGIQUE SIMPLE selon l'unité
        if ($produit->unite === 'kg') {
            // Si unité = kg → comparer le poids calculé avec le stock
            $poidsMoyenSac = $produit->poids_moyen_sac_kg_max ?: 120;
            $poidsCalcule = $totalSacs * $poidsMoyenSac;
            
            if ($poidsCalcule > $stockDisponible) {
                $limiteSacs = $stockDisponible / $poidsMoyenSac;
                $this->addError('sacs_pleins_depart', 
                    "❌ Dépassement de stock !\n" .
                    "• Demandé : " . number_format($totalSacs, 1) . " sacs ≈ " . number_format($poidsCalcule, 2) . " kg\n" .
                    "• Disponible : " . number_format($stockDisponible, 2) . " kg\n" .
                    "• Maximum : " . number_format($limiteSacs, 1) . " sacs"
                );
            }
        } 
        elseif ($produit->unite === 'sacs') {
            // Si unité = sacs → comparer directement les sacs
            if ($totalSacs > $stockDisponible) {
                $this->addError('sacs_pleins_depart', 
                    "❌ Dépassement de stock !\n" .
                    "• Demandé : " . number_format($totalSacs, 1) . " sacs\n" .
                    "• Disponible : " . number_format($stockDisponible, 1) . " sacs\n" .
                    "• Maximum : " . number_format($stockDisponible, 1) . " sacs"
                );
            }
        }
        else {
            // Autres unités (tonnes, etc.) → traiter comme kg
            $poidsMoyenSac = $produit->poids_moyen_sac_kg_max ?: 120;
            $poidsCalcule = $totalSacs * $poidsMoyenSac;
            $stockEnKg = $produit->unite === 'tonnes' ? $stockDisponible * 1000 : $stockDisponible;
            
            if ($poidsCalcule > $stockEnKg) {
                $limiteSacs = $stockEnKg / $poidsMoyenSac;
                $this->addError('sacs_pleins_depart', 
                    "❌ Dépassement de stock !\n" .
                    "• Demandé : " . number_format($totalSacs, 1) . " sacs\n" .
                    "• Disponible : " . number_format($stockDisponible, 2) . " " . $produit->unite . "\n" .
                    "• Maximum : " . number_format($limiteSacs, 1) . " sacs"
                );
            }
        }
    }



    /**
     * Calculer les limites de sacs selon le stock disponible
    */
    public function getLimiteSacsAttribute()
    {
        if (!$this->produit_id) {
            return null;
        }

        $produit = Produit::find($this->produit_id);
        if (!$produit) {
            return null;
        }

        $stockDisponible = floatval($produit->qte_variable);
        
        // Si pas de stock, retourner des limites à zéro
        if ($stockDisponible <= 0) {
            return [
                'max_sacs' => 0,
                'max_sacs_pleins' => 0,
                'max_sacs_demi' => 0,
                'unite_affichage' => 'stock épuisé',
                'stock_formate' => '0 ' . ($produit->unite ?: 'kg') . ' (épuisé)',
                'poids_moyen_sac' => $produit->poids_moyen_sac_kg_max ?: 120,
                'stock_disponible' => 0
            ];
        }

        $poidsMoyenSac = ($produit->poids_moyen_sac_kg_max > 0) ? $produit->poids_moyen_sac_kg_max : 120;
        
        switch ($produit->unite) {
            case 'sacs':
                $maxSacs = $stockDisponible;
                $uniteAffichage = 'sacs';
                $stockFormate = number_format($stockDisponible, 1) . ' sacs';
                break;
                
            case 'kg':
                $maxSacs = $stockDisponible / $poidsMoyenSac;
                $uniteAffichage = 'sacs équivalents';
                $stockFormate = number_format($maxSacs, 1) . ' sacs max (' . number_format($stockDisponible, 2) . ' kg)';
                break;
                
            case 'tonnes':
                $maxSacs = ($stockDisponible * 1000) / $poidsMoyenSac;
                $uniteAffichage = 'sacs équivalents';
                $stockFormate = number_format($maxSacs, 1) . ' sacs max (' . number_format($stockDisponible, 3) . ' tonnes)';
                break;
                
            default:
                $maxSacs = $stockDisponible / $poidsMoyenSac;
                $uniteAffichage = 'sacs équivalents';
                $stockFormate = number_format($maxSacs, 1) . ' sacs max (' . number_format($stockDisponible, 2) . ' ' . $produit->unite . ')';
                break;
        }

        return [
            'max_sacs' => $maxSacs,
            'max_sacs_pleins' => floor($maxSacs), // Sacs pleins possibles
            'max_sacs_demi' => ($maxSacs - floor($maxSacs)) >= 0.5 ? 1 : 0, // Sac demi possible
            'unite_affichage' => $uniteAffichage,
            'stock_formate' => $stockFormate,
            'poids_moyen_sac' => $poidsMoyenSac,
            'stock_disponible' => $stockDisponible,
            'produit_unite' => $produit->unite
        ];
    }


    /**
     * Analyse le type d'écart et retourne les informations appropriées
     */
    public function analyzeEcartType($chargementOrPourcentage)
    {
        // Si on reçoit un objet Chargement, calculer le pourcentage d'écart
        if (is_object($chargementOrPourcentage)) {
            $chargement = $chargementOrPourcentage;
            $poidsDepart = $this->ensureNumeric($chargement->poids_depart_kg);
            $poidsArrivee = $this->ensureNumeric($this->poids_arrivee_kg);
            $pourcentage_ecart = $poidsDepart > 0 ? (($poidsDepart - $poidsArrivee) / $poidsDepart) * 100 : 0;
        } else {
            // Si on reçoit directement un pourcentage
            $pourcentage_ecart = $chargementOrPourcentage;
        }
        
        $pourcentage_ecart = abs($pourcentage_ecart);
        
        if ($pourcentage_ecart > 10) {
            return [
                'type' => 'significatif',
                'color' => 'orange',
                'icon' => '⚠️',
                'title' => "Écart significatif (" . number_format($pourcentage_ecart, 1) . "%)",
                'description' => 'Cet écart important peut être dû à : qualité du produit, conditions d\'humidité, tassement durant le transport, différences de pesée, ou conditions de stockage. Il est recommandé de vérifier les conditions de transport et de documenter les observations.',
                'css_classes' => 'bg-orange-100 border border-orange-200 text-orange-800'
            ];
        } elseif ($pourcentage_ecart > 5) {
            return [
                'type' => 'modere',
                'color' => 'yellow',
                'icon' => 'ℹ️',
                'title' => "Écart modéré (" . number_format($pourcentage_ecart, 1) . "%)",
                'description' => 'Variation normale pouvant être due aux conditions de transport, à la qualité du produit, à l\'humidité, ou aux différences de méthodes de pesée. Cet écart reste dans une fourchette acceptable.',
                'css_classes' => 'bg-yellow-100 border border-yellow-200 text-yellow-800'
            ];
        } else {
            return [
                'type' => 'faible',
                'color' => 'green',
                'icon' => '✅',
                'title' => "Écart faible (" . number_format($pourcentage_ecart, 1) . "%)",
                'description' => 'Variation minime et tout à fait normale dans les opérations de transport. Cet écart peut s\'expliquer par les tolérances de pesée et les conditions normales de manutention.',
                'css_classes' => 'bg-green-100 border border-green-200 text-green-800'
            ];
        }
    }

    /**
     * Calcule les écarts entre le chargement et le déchargement
     */
    public function calculateEcarts($chargementData, $dechargementData)
    {
        $sacsPleinsDep = $this->ensureNumeric($chargementData['sacs_pleins_depart'] ?? 0);
        $sacsDemiDep = $this->ensureNumeric($chargementData['sacs_demi_depart'] ?? 0);
        $poidsDep = $this->ensureNumeric($chargementData['poids_depart_kg'] ?? 0);
        
        $sacsPleinArr = $this->ensureNumeric($dechargementData['sacs_pleins_arrivee'] ?? 0);
        $sacsDemiArr = $this->ensureNumeric($dechargementData['sacs_demi_arrivee'] ?? 0);
        $poidsArr = $this->ensureNumeric($dechargementData['poids_arrivee_kg'] ?? 0);
        
        $ecartSacsPleins = $sacsPleinsDep - $sacsPleinArr;
        $ecartSacsDemi = $sacsDemiDep - $sacsDemiArr;
        $ecartPoids = $poidsDep - $poidsArr;
        
        $pourcentageEcart = $poidsDep > 0 ? (($ecartPoids / $poidsDep) * 100) : 0;
        
        return [
            'ecart_sacs_pleins' => $ecartSacsPleins,
            'ecart_sacs_demi' => $ecartSacsDemi,
            'ecart_poids_kg' => $ecartPoids,
            'pourcentage_ecart' => $pourcentageEcart,
            'analyse' => $this->analyzeEcartType($pourcentageEcart)
        ];
    }

    // ==============================================
    // MÉTHODES UTILITAIRES ET NAVIGATION
    // ==============================================
    
    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
        
        // ✅ NOUVEAU : Mettre à jour l'URL avec le paramètre tab
        $this->js("
            const url = new URL(window.location);
            url.searchParams.set('tab', '{$tab}');
            window.history.pushState({}, '', url);
        ");
    }


    public function setTypeProprietaire($type)
    {
        $this->type_proprietaire = $type;
        
        if ($type === 'defaut') {
            $this->proprietaire_nom = 'Mme TINAH';
            $this->proprietaire_contact = '0349045769';
        } else {
            $this->proprietaire_nom = '';
            $this->proprietaire_contact = '';
        }
    }

    // ==============================================
    // LOGIQUE DÉCHARGEMENT - ÉTAPES ET NAVIGATION
    // ==============================================
    
    /**
     * Charger les voyages disponibles pour le déchargement
     */
    public function loadAvailableVoyages()
    {
        $this->available_voyages = Voyage::with(['chargements.produit', 'vehicule'])
            ->whereHas('chargements', function($query) {
                // Seulement les voyages qui ont des chargements sans déchargement complet
                $query->whereDoesntHave('dechargements')
                      ->orWhereHas('dechargements', function($subQuery) {
                          $subQuery->whereColumn('poids_arrivee_kg', '<', 'chargements.poids_depart_kg');
                      });
            })
            ->orderBy('date', 'desc')
            ->limit(20) // Limiter pour performance
            ->get();
    }

    /**
     * Charger les chargements disponibles du voyage sélectionné
     */
    public function loadAvailableChargements()
    {
        if (!$this->selected_voyage) {
            $this->available_chargements = collect();
            return;
        }

        // Récupérer tous les chargements du voyage
        $allChargements = $this->selected_voyage->chargements()
            ->with(['produit', 'depart'])
            ->get();

        // Filtrer manuellement pour éviter les problèmes de relations
        $this->available_chargements = $allChargements->filter(function ($chargement) {
            // Vérifier directement en base si le chargement a des déchargements
            $hasDecharement = Dechargement::where('chargement_id', $chargement->id)->exists();
            
            // Si on est en mode édition, permettre le chargement en cours d'édition
            if ($this->editingDechargement && $this->editingDechargement->chargement_id == $chargement->id) {
                return true;
            }
            
            // Sinon, masquer les chargements qui ont déjà un déchargement
            return !$hasDecharement;
        });

        // Log pour debug
        Log::info("Chargements disponibles: " . count($this->available_chargements) . " sur " . $allChargements->count());
    }

    /**
     * Permet d'afficher le chargement en cours d'édition même s'il a déjà un déchargement
     */

    public function loadAvailableChargementsForEdit()
    {
        if (!$this->selected_voyage) return;

        $this->available_chargements = $this->selected_voyage->chargements()
            ->with(['produit', 'depart', 'dechargements'])
            ->get()
            ->filter(function ($chargement) {
                // En mode édition, afficher seulement le chargement en cours d'édition
                if ($this->editingDechargement) {
                    return $this->editingDechargement->chargement_id == $chargement->id;
                }
                
                // Sinon, afficher seulement les chargements sans déchargement
                return $chargement->dechargements->count() === 0;
            });
    }


    /**
     * Navigation entre les étapes du déchargement
     */
    public function goToStep($step)
    {
        $this->dechargement_step = $step;
        
        if ($step == 1) {
            $this->loadAvailableVoyages();
        } elseif ($step == 2 && $this->selected_voyage_id) {
            // ✅ CORRIGÉ : Choisir la bonne méthode selon le mode
            if ($this->editingDechargement) {
                $this->loadAvailableChargementsForEdit();
            } else {
                $this->loadAvailableChargements();
            }
        }
    }

    /**
     * Quand on sélectionne un voyage
     */
    public function updatedSelectedVoyageId()
    {
        if ($this->selected_voyage_id) {
            $this->selected_voyage = Voyage::with(['chargements.produit', 'chargements.depart', 'vehicule'])
                ->find($this->selected_voyage_id);
            
            $this->loadAvailableChargements();
            $this->dechargement_step = 2;
        }
    }

    /**
     * Quand on sélectionne un chargement
     */
    public function updatedChargementId()
    {
        if ($this->chargement_id && $this->selected_voyage) {
            $this->dechargement_step = 3;
            
            // Générer automatiquement une référence
            $this->dechargement_reference = $this->generateDechargementReference();
            
            // Récupérer le chargement avec son produit
            $chargement = collect($this->available_chargements)->firstWhere('id', $this->chargement_id);
            if ($chargement && $chargement->produit) {
                
                // ✅ NOUVEAU : Charger les informations du produit
                $this->selected_product = $chargement->produit;
                $this->product_unite = $chargement->produit->unite;
                $this->product_poids_moyen_sac = $chargement->produit->poids_moyen_sac_kg;
                $this->product_prix_reference = $chargement->produit->prix_reference_mga;
                
                // Pré-remplir avec les valeurs du chargement
                $this->sacs_pleins_arrivee = $chargement->sacs_pleins_depart;
                $this->sacs_demi_arrivee = $chargement->sacs_demi_depart;
                $this->poids_arrivee_kg = $chargement->poids_depart_kg;
                
                // ✅ NOUVEAU : Calcul intelligent du prix unitaire selon l'unité
                $this->calculateUnitPrice();
                
                // ✅ NOUVEAU : Calcul de la quantité vendue selon l'unité
                $this->calculateQuantiteSold();
            }
        }
    }

    // ==============================================
    // GESTION DES CHARGEMENTS
    // ==============================================
    
    public function createChargement()
    {
        $this->resetChargementForm();
        $this->editingChargement = null;
        $this->chargement_reference = $this->generateChargementReference();
        $this->showChargementModal = true;
    }

    public function editChargement(Chargement $chargement)
    {
        $this->editingChargement = $chargement;
        $this->date = $chargement->date ? $chargement->date->format('Y-m-d') : now()->format('Y-m-d');
        $this->chargement_reference = $chargement->reference;
        $this->chargeur_nom = $chargement->chargeur_nom;
        $this->chargeur_contact = $chargement->chargeur_contact;
        $this->depart_id = $chargement->depart_id;
        $this->proprietaire_nom = $chargement->proprietaire_nom;
        $this->proprietaire_contact = $chargement->proprietaire_contact;
        $this->produit_id = $chargement->produit_id;
        $this->sacs_pleins_depart = $chargement->sacs_pleins_depart;
        $this->sacs_demi_depart = $chargement->sacs_demi_depart;
        $this->poids_depart_kg = $chargement->poids_depart_kg;
        $this->chargement_observation = $chargement->observation;
        $this->showChargementModal = true;
    }



    public function saveChargement()
    {
        $this->validate([
            'date' => 'required|date',
            'chargement_reference' => 'required|string|max:255',
            'chargeur_nom' => 'required|string|max:255',
            'depart_id' => 'required|exists:lieux,id',
            'proprietaire_nom' => 'required|string|max:255',
            'produit_id' => 'required|exists:produits,id',
            'sacs_pleins_depart' => 'required|integer|min:0',
            'poids_depart_kg' => 'required|numeric|min:0',
        ]);

        // Validation finale du stock
        $produit = Produit::find($this->produit_id);
        if (!$produit) {
            $this->addError('produit_id', 'Produit introuvable.');
            return;
        }

        $poidsACharger = floatval($this->poids_depart_kg);
        $stockDisponible = floatval($produit->qte_variable);

        // Conversion selon l'unité du produit
        $quantiteACharger = $poidsACharger;
        
        if ($produit->unite === 'sacs') {
            $poidsMoyenSac = $produit->poids_moyen_sac_kg_max > 0 ? $produit->poids_moyen_sac_kg_max : 120;
            $quantiteACharger = $poidsACharger / $poidsMoyenSac;
        } elseif ($produit->unite === 'tonnes') {
            $quantiteACharger = $poidsACharger / 1000;
        }

        // Vérification finale du stock (sauf en édition d'un chargement existant)
        if (!$this->editingChargement && $quantiteACharger > $stockDisponible) {
            session()->flash('error', 'Stock insuffisant pour ce chargement. Stock disponible: ' . 
                number_format($stockDisponible, 2, ',', ' ') . ' ' . $produit->unite);
            return;
        }

        try {
            $data = [
                'voyage_id' => $this->voyage->id,
                'date' => $this->date,
                'reference' => $this->chargement_reference,
                'chargeur_nom' => $this->chargeur_nom,
                'chargeur_contact' => $this->chargeur_contact,
                'depart_id' => $this->depart_id,
                'proprietaire_nom' => $this->proprietaire_nom,
                'proprietaire_contact' => $this->proprietaire_contact,
                'produit_id' => $this->produit_id,
                'sacs_pleins_depart' => $this->sacs_pleins_depart,
                'sacs_demi_depart' => $this->sacs_demi_depart ?: 0,
                'poids_depart_kg' => $this->poids_depart_kg,
                'observation' => $this->chargement_observation,
            ];

            if ($this->editingChargement) {
                // Pour la modification, calculer la différence de stock
                $ancienPoids = floatval($this->editingChargement->poids_depart_kg);
                $ancienProduitId = $this->editingChargement->produit_id;
                
                // Remettre l'ancien stock si le produit a changé
                if ($ancienProduitId != $this->produit_id) {
                    $ancienProduit = Produit::find($ancienProduitId);
                    if ($ancienProduit) {
                        $ancienneQuantite = $ancienPoids;
                        if ($ancienProduit->unite === 'sacs') {
                            $ancienneQuantite = $ancienPoids / ($ancienProduit->poids_moyen_sac_kg_max ?: 120);
                        } elseif ($ancienProduit->unite === 'tonnes') {
                            $ancienneQuantite = $ancienPoids / 1000;
                        }
                        $ancienProduit->qte_variable = $ancienProduit->qte_variable + $ancienneQuantite;
                        $ancienProduit->save();
                    }
                } else {
                    // Même produit, ajuster seulement la différence
                    $ancienneQuantiteChargee = $ancienPoids;
                    if ($produit->unite === 'sacs') {
                        $ancienneQuantiteChargee = $ancienPoids / ($produit->poids_moyen_sac_kg_max ?: 120);
                    } elseif ($produit->unite === 'tonnes') {
                        $ancienneQuantiteChargee = $ancienPoids / 1000;
                    }
                    
                    // Remettre l'ancienne quantité en stock avant de décrémenter la nouvelle
                    $produit->qte_variable = $produit->qte_variable + $ancienneQuantiteChargee;
                }

                $this->editingChargement->update($data);
                session()->flash('success', 'Chargement modifié avec succès');
                
                Log::info('Chargement modifié', [
                    'chargement_id' => $this->editingChargement->id,
                    'ancien_produit' => $ancienProduitId,
                    'nouveau_produit' => $this->produit_id,
                    'ancien_poids' => $ancienPoids,
                    'nouveau_poids' => $this->poids_depart_kg
                ]);
            } else {
                $chargement = Chargement::create($data);
                session()->flash('success', 'Chargement ajouté avec succès');
                
                Log::info('Chargement créé', [
                    'chargement_id' => $chargement->id,
                    'produit_id' => $this->produit_id,
                    'poids' => $this->poids_depart_kg
                ]);
            }

            // ✅ DÉCRÉMENTER LE STOCK après création/modification réussie
            $produit->qte_variable = $produit->qte_variable - $quantiteACharger;
            $produit->save();
            
            Log::info('Stock décrémenté pour chargement', [
                'produit_id' => $produit->id,
                'quantite_deduite' => $quantiteACharger,
                'nouveau_stock' => $produit->qte_variable,
                'unite' => $produit->unite
            ]);

            $this->closeChargementModal();
            $this->voyage->refresh();
            
        } catch (\Exception $e) {
            Log::error('Erreur lors de la sauvegarde du chargement', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            session()->flash('error', 'Erreur lors de la sauvegarde: ' . $e->getMessage());
        }
    }



    public function deleteChargement(Chargement $chargement)
    {
        try {
            // Vérifier s'il y a des déchargements liés
            if ($chargement->dechargements()->count() > 0) {
                session()->flash('error', 'Impossible de supprimer ce chargement car il a des déchargements associés.');
                return;
            }
            
            // ✅ RESTAURER LE STOCK avant suppression
            $produit = $chargement->produit;
            if ($produit) {
                $poidsCharge = floatval($chargement->poids_depart_kg);
                
                // Convertir selon l'unité du produit
                $quantiteARestaurer = $poidsCharge;
                
                if ($produit->unite === 'sacs') {
                    $poidsMoyenSac = $produit->poids_moyen_sac_kg_max > 0 ? $produit->poids_moyen_sac_kg_max : 120;
                    $quantiteARestaurer = $poidsCharge / $poidsMoyenSac;
                } elseif ($produit->unite === 'tonnes') {
                    $quantiteARestaurer = $poidsCharge / 1000;
                }
                
                // Remettre en stock
                $produit->qte_variable = $produit->qte_variable + $quantiteARestaurer;
                $produit->save();
                
                Log::info('Stock restauré lors de la suppression du chargement', [
                    'chargement_id' => $chargement->id,
                    'produit_id' => $produit->id,
                    'quantite_restauree' => $quantiteARestaurer,
                    'nouveau_stock' => $produit->qte_variable,
                    'unite' => $produit->unite
                ]);
            }
            
            $chargement->delete();
            session()->flash('success', 'Chargement supprimé avec succès et stock restauré');
            $this->voyage->refresh();
            
        } catch (\Exception $e) {
            Log::error('Erreur lors de la suppression du chargement', [
                'message' => $e->getMessage(),
                'chargement_id' => $chargement->id ?? 'N/A'
            ]);
            session()->flash('error', 'Erreur lors de la suppression: ' . $e->getMessage());
        }
    }

    // ==============================================
    // GESTION DES DÉCHARGEMENTS
    // ==============================================
    
    public function createDechargement()
    {
        $this->resetDechargementForm();
        $this->editingDechargement = null;
        $this->dechargement_step = 1;
        
        // ✅ IMPORTANT : S'assurer que la date est définie
        $this->date = now()->format('Y-m-d');
        
        // Toujours charger les voyages disponibles
        $this->loadAvailableVoyages();
        
        // Si on est sur une page de voyage, pré-sélectionner
        if ($this->voyage) {
            $this->selected_voyage_id = $this->voyage->id;
            $this->selected_voyage = $this->voyage;
            $this->loadAvailableChargements();
            $this->dechargement_step = 2; // Aller directement aux chargements
        }
        
        $this->showDechargementModal = true;
    }


    public function editDechargement(Dechargement $dechargement)
    {
        $this->editingDechargement = $dechargement;
        $this->dechargement_step = 3; // Aller directement aux détails pour l'édition
        
        // Charger les données du voyage et chargement
        $this->selected_voyage_id = $dechargement->voyage_id;
        $this->selected_voyage = $dechargement->voyage;
        
        // ✅ CORRIGÉ : Utiliser la méthode spéciale pour l'édition
        $this->loadAvailableChargementsForEdit();
        
        // ✅ NOUVEAU : Charger les informations du produit
        $chargement = $dechargement->chargement;
        if ($chargement && $chargement->produit) {
            $this->selected_product = $chargement->produit;
            $this->product_unite = $chargement->produit->unite;
            $this->product_poids_moyen_sac = $chargement->produit->poids_moyen_sac_kg;
            $this->product_prix_reference = $chargement->produit->prix_reference_mga;
        }
        
        // Remplir le formulaire avec TOUTES les données, y compris la date
        $this->date = $dechargement->date ? $dechargement->date->format('Y-m-d') : now()->format('Y-m-d'); // ✅ AJOUT DATE
        $this->dechargement_reference = $dechargement->reference;
        $this->chargement_id = $dechargement->chargement_id;
        $this->type_dechargement = $dechargement->type;
        $this->interlocuteur_nom = $dechargement->interlocuteur_nom;
        $this->interlocuteur_contact = $dechargement->interlocuteur_contact;
        $this->pointeur_nom = $dechargement->pointeur_nom;
        $this->pointeur_contact = $dechargement->pointeur_contact;
        $this->lieu_livraison_id = $dechargement->lieu_livraison_id;
        $this->sacs_pleins_arrivee = $dechargement->sacs_pleins_arrivee;
        $this->sacs_demi_arrivee = $dechargement->sacs_demi_arrivee;
        $this->poids_arrivee_kg = $dechargement->poids_arrivee_kg;
        $this->prix_unitaire_mga = $dechargement->prix_unitaire_mga;
        $this->montant_total_mga = $dechargement->montant_total_mga;
        $this->paiement_mga = $dechargement->paiement_mga;
        $this->reste_mga = $dechargement->reste_mga;
        $this->statut_commercial = $dechargement->statut_commercial;
        $this->dechargement_observation = $dechargement->observation;
        
        // ✅ NOUVEAU : Recalculer la quantité vendue selon l'unité
        $this->calculateQuantiteSold();
        
        $this->showDechargementModal = true;
    }



    public function saveDechargement()
    {
        // ✅ VALIDATION ADAPTÉE selon l'unité du produit
        $rules = [
            'dechargement_reference' => 'required|string|max:255',
            'chargement_id' => 'required|exists:chargements,id',
            'type_dechargement' => 'required',
        ];

        // ✅ NOUVEAU : Validation conditionnelle selon l'unité du produit
        if ($this->selected_product) {
            if ($this->product_unite === 'kg') {
                $rules['poids_arrivee_kg'] = 'required|numeric|min:0';
            } elseif ($this->product_unite === 'sacs') {
                $rules['sacs_pleins_arrivee'] = 'required|integer|min:0';
            } elseif ($this->product_unite === 'tonnes') {
                $rules['poids_arrivee_kg'] = 'required|numeric|min:0';
            }
        }

        $this->validate($rules);

        // Vérifications de logique métier
        if (!$this->selected_voyage_id || !$this->chargement_id) {
            session()->flash('error', 'Veuillez sélectionner un voyage et un chargement.');
            return;
        }

        // Vérifier si un déchargement existe déjà (sauf en édition)
        if (!$this->editingDechargement) {
            $existingDechargement = Dechargement::where('chargement_id', $this->chargement_id)->first();
            if ($existingDechargement) {
                session()->flash('error', 'Un déchargement existe déjà pour ce chargement.');
                return;
            }
        }

        // ✅ NOUVEAU : Validation spécifique aux quantités selon l'unité
        if ($this->selected_product && $this->quantite_vendue <= 0) {
            $message = "Veuillez renseigner ";
            if ($this->product_unite === 'kg') {
                $message .= "le poids total reçu (kg)";
            } elseif ($this->product_unite === 'sacs') {
                $message .= "au moins les sacs pleins reçus";
            } else {
                $message .= "les quantités reçues";
            }
            session()->flash('error', $message . ' pour calculer le montant de la vente.');
            return;
        }

        $this->showPreview();
    }


    public function viewDechargementDetail($dechargementId)
    {
        $dechargement = Dechargement::with(['chargement.produit', 'chargement.depart', 'voyage.vehicule', 'lieuLivraison'])
            ->find($dechargementId);
        
        if (!$dechargement) {
            session()->flash('error', 'Déchargement introuvable');
            return;
        }

        $chargement = $dechargement->chargement;

        // Construire previewData exactement comme dans showPreview()
        $this->previewData = [
            'voyage' => [
                'reference' => $dechargement->voyage->reference,
                'date' => $dechargement->voyage->date->format('d/m/Y'),
                'vehicule' => $dechargement->voyage->vehicule->immatriculation ?? 'N/A',
            ],
            'chargement' => [
                'reference' => $chargement->reference,
                'proprietaire_nom' => $chargement->proprietaire_nom,
                'produit' => $chargement->produit->nom ?? 'N/A',
                'depart' => $chargement->depart->nom ?? 'N/A',
                'sacs_pleins_depart' => $this->ensureNumeric($chargement->sacs_pleins_depart),
                'sacs_demi_depart' => $this->ensureNumeric($chargement->sacs_demi_depart),
                'poids_depart_kg' => $this->ensureNumeric($chargement->poids_depart_kg),
            ],
            'dechargement' => [
                'reference' => $dechargement->reference,
                'type' => $dechargement->type,
                'interlocuteur_nom' => $dechargement->interlocuteur_nom ?: 'Non renseigné',
                'pointeur_nom' => $dechargement->pointeur_nom ?: 'Non renseigné',
                'lieu_livraison' => $dechargement->lieuLivraison->nom ?? 'Non renseigné',
                'sacs_pleins_arrivee' => $this->ensureNumeric($dechargement->sacs_pleins_arrivee),
                'sacs_demi_arrivee' => $this->ensureNumeric($dechargement->sacs_demi_arrivee),
                'poids_arrivee_kg' => $this->ensureNumeric($dechargement->poids_arrivee_kg),
                'product_unite' => $chargement->produit->unite ?? 'kg',
                'quantite_vendue' => $this->ensureNumeric($dechargement->poids_arrivee_kg), // Simple pour l'instant
                'prix_unitaire_mga' => $this->ensureNumeric($dechargement->prix_unitaire_mga),
                'montant_total_mga' => $this->ensureNumeric($dechargement->montant_total_mga),
                'paiement_mga' => $this->ensureNumeric($dechargement->paiement_mga),
                'reste_mga' => $this->ensureNumeric($dechargement->reste_mga),
                'statut_commercial' => $dechargement->statut_commercial,
            ],
            'calculs' => [
                'ecart_sacs_pleins' => $chargement->sacs_pleins_depart - ($dechargement->sacs_pleins_arrivee ?: 0),
                'ecart_sacs_demi' => $chargement->sacs_demi_depart - ($dechargement->sacs_demi_arrivee ?: 0),
                'ecart_poids_kg' => $chargement->poids_depart_kg - ($dechargement->poids_arrivee_kg ?: 0),
                'pourcentage_ecart' => $chargement->poids_depart_kg > 0 ? 
                    (($chargement->poids_depart_kg - ($dechargement->poids_arrivee_kg ?: 0)) / $chargement->poids_depart_kg) * 100 : 0,
            ]
        ];

        $this->showPreviewModal = true;
    }

    public function showPreview()
    {
        $this->calculateFinancials();

        // ✅ CORRECTION : Convertir en Collection d'abord si c'est un array
        $chargement = collect($this->available_chargements)->firstWhere('id', $this->chargement_id);
        
        if (!$chargement) {
            session()->flash('error', 'Chargement introuvable');
            return;
        }

        // Le reste de votre code reste identique
        $chargementData = [
            'reference' => $chargement->reference,
            'proprietaire_nom' => $chargement->proprietaire_nom,
            'produit' => $chargement->produit->nom ?? 'N/A',
            'depart' => $chargement->depart->nom ?? 'N/A',
            'sacs_pleins_depart' => $this->ensureNumeric($chargement->sacs_pleins_depart),
            'sacs_demi_depart' => $this->ensureNumeric($chargement->sacs_demi_depart),
            'poids_depart_kg' => $this->ensureNumeric($chargement->poids_depart_kg),
        ];

        $dechargementData = [
            'reference' => $this->dechargement_reference,
            'type' => $this->type_dechargement,
            'interlocuteur_nom' => $this->interlocuteur_nom ?: 'Non renseigné',
            'pointeur_nom' => $this->pointeur_nom ?: 'Non renseigné',
            'lieu_livraison' => $this->lieu_livraison_id ? 
                Lieu::find($this->lieu_livraison_id)->nom : 'Non renseigné',
            'sacs_pleins_arrivee' => $this->ensureNumeric($this->sacs_pleins_arrivee),
            'sacs_demi_arrivee' => $this->ensureNumeric($this->sacs_demi_arrivee),
            'poids_arrivee_kg' => $this->ensureNumeric($this->poids_arrivee_kg),
            'product_unite' => $this->product_unite,
            'quantite_vendue' => $this->ensureNumeric($this->quantite_vendue),
            'prix_unitaire_mga' => $this->ensureNumeric($this->prix_unitaire_mga),
            'montant_total_mga' => $this->ensureNumeric($this->montant_total_mga),
            'paiement_mga' => $this->ensureNumeric($this->paiement_mga),
            'reste_mga' => $this->ensureNumeric($this->reste_mga),
            'statut_commercial' => $this->statut_commercial,
        ];

        $calculs = $this->calculateEcarts($chargementData, $dechargementData);

        $this->previewData = [
            'voyage' => [
                'reference' => $this->selected_voyage->reference,
                'date' => $this->selected_voyage->date->format('d/m/Y'),
                'vehicule' => $this->selected_voyage->vehicule->immatriculation ?? 'N/A',
            ],
            'chargement' => $chargementData,
            'dechargement' => $dechargementData,
            'calculs' => $calculs
        ];

        $this->showPreviewModal = true;
    }

    public function confirmSaveDechargement()
    {
        // Fermer le modal de prévisualisation
        $this->showPreviewModal = false;
        
        // Utiliser la méthode existante pour sauvegarder
        $this->proceedWithSave();
        
        // Nettoyer seulement si la sauvegarde a réussi (pas d'exception lancée)
        $this->showDechargementModal = false;
        $this->resetDechargementForm();
        $this->editingDechargement = null;
        $this->voyage->refresh();
        
        // Changer vers le tab déchargements pour voir le résultat
        $this->activeTab = 'dechargements';
    }


    private function proceedWithSave()
    {
        try {
            // ✅ FORCER une valeur de date si elle est vide
            if (empty($this->date)) {
                $this->date = now()->format('Y-m-d');
            }
            
            // ✅ DEBUG: Vérifier les données avant sauvegarde
            Log::info('proceedWithSave - Début sauvegarde déchargement', [
                'voyage_id' => $this->selected_voyage_id,
                'chargement_id' => $this->chargement_id,
                'reference' => $this->dechargement_reference,
                'date' => $this->date,
                'editing' => $this->editingDechargement ? $this->editingDechargement->id : null
            ]);
            
            $this->calculateFinancials();

            $data = [
                'voyage_id' => $this->selected_voyage_id,
                'reference' => $this->dechargement_reference,
                'chargement_id' => $this->chargement_id,
                'type' => $this->type_dechargement,
                'date' => $this->date, // ✅ AJOUT DU CHAMP DATE MANQUANT
                'interlocuteur_nom' => $this->interlocuteur_nom,
                'interlocuteur_contact' => $this->interlocuteur_contact,
                'pointeur_nom' => $this->pointeur_nom,
                'pointeur_contact' => $this->pointeur_contact,
                'lieu_livraison_id' => $this->lieu_livraison_id ?: null,
                'sacs_pleins_arrivee' => $this->sacs_pleins_arrivee ?: null,
                'sacs_demi_arrivee' => $this->sacs_demi_arrivee ?: 0,
                'poids_arrivee_kg' => $this->poids_arrivee_kg ?: null,
                'prix_unitaire_mga' => $this->prix_unitaire_mga ?: null,
                'montant_total_mga' => $this->montant_total_mga ?: null,
                'paiement_mga' => $this->paiement_mga ?: null,
                'reste_mga' => $this->reste_mga ?: null,
                'statut_commercial' => $this->statut_commercial,
                'observation' => $this->dechargement_observation,
            ];

            // ✅ DEBUG: Vérifier que date est bien dans le tableau
            Log::info('proceedWithSave - Données à sauvegarder', [
                'date_in_data' => isset($data['date']),
                'date_value' => $data['date'],
                'all_data' => $data
            ]);

            if ($this->editingDechargement) {
                $this->editingDechargement->update($data);
                Log::info('Déchargement modifié', ['id' => $this->editingDechargement->id]);
                session()->flash('success', 'Déchargement modifié avec succès');
            } else {
                $dechargement = Dechargement::create($data);
                Log::info('Déchargement créé', ['id' => $dechargement->id]);
                session()->flash('success', 'Déchargement ajouté avec succès');
            }

        } catch (\Exception $e) {
            Log::error('Erreur sauvegarde déchargement dans proceedWithSave', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'data' => $data ?? [],
                'date_value' => $this->date ?? 'NULL'
            ]);
            session()->flash('error', 'Erreur lors de la sauvegarde: ' . $e->getMessage());
        }
    }


    public function deleteDechargement(Dechargement $dechargement)
    {
        try {
            $dechargement->delete();
            session()->flash('success', 'Déchargement supprimé avec succès');
            $this->voyage->refresh();
            
        } catch (\Exception $e) {
            session()->flash('error', 'Erreur lors de la suppression: ' . $e->getMessage());
        }
    }

    // ==============================================
    // CALCULS AUTOMATIQUES - ✅ CORRIGÉ
    // ==============================================
    
    /**
     * ✅ NOUVEAU : Calcul du prix unitaire selon l'unité du produit
     */
    public function calculateUnitPrice()
    {
        if (!$this->selected_product) return;
        
        // Utiliser le prix de référence du produit comme base
        $this->prix_unitaire_mga = $this->product_prix_reference;
    }
    
    /**
     * ✅ NOUVEAU : Calcul de la quantité vendue selon l'unité du produit - CORRIGÉ
     */
    public function calculateQuantiteSold()
    {
        if (!$this->selected_product) return;
        
        switch ($this->product_unite) {
            case 'kg':
                // ✅ CORRIGÉ : Pour les produits vendus au kg, utiliser le POIDS reçu
                $this->quantite_vendue = $this->ensureNumeric($this->poids_arrivee_kg);
                break;
                
            case 'sacs':
                // ✅ CORRIGÉ : Pour les produits vendus au sac, utiliser les SACS reçus
                $sacs_pleins = $this->ensureNumeric($this->sacs_pleins_arrivee);
                $sacs_demi = $this->ensureNumeric($this->sacs_demi_arrivee);
                $this->quantite_vendue = $sacs_pleins + ($sacs_demi * 0.5);
                break;
                
            case 'tonnes':
                // ✅ CORRIGÉ : Pour les produits vendus à la tonne, convertir le poids reçu
                $poids_kg = $this->ensureNumeric($this->poids_arrivee_kg);
                $this->quantite_vendue = $poids_kg / 1000;
                break;
                
            default:
                // Par défaut, utiliser le poids en kg
                $this->quantite_vendue = $this->ensureNumeric($this->poids_arrivee_kg);
                break;
        }
    }
    
    public function calculateFinancials()
    {
        // ✅ NOUVEAU : Calcul adaptatif selon l'unité du produit - CORRIGÉ
        if ($this->selected_product) {
            // Recalculer la quantité vendue
            $this->calculateQuantiteSold();
            
            // Calcul du montant total selon l'unité
            $prix = $this->ensureNumeric($this->prix_unitaire_mga);
            $this->montant_total_mga = $prix * $this->quantite_vendue;
            
        } else {
            // ✅ FALLBACK : Ancien calcul si pas de produit sélectionné
            $prix = $this->ensureNumeric($this->prix_unitaire_mga);
            $poids = $this->ensureNumeric($this->poids_arrivee_kg);
            $this->montant_total_mga = $prix * $poids;
        }
        
        // Calcul du reste (avec protection contre les erreurs)
        $paiement = $this->ensureNumeric($this->paiement_mga);
        $this->reste_mga = $this->montant_total_mga - $paiement;
    }

    public function setFullPayment()
    {
        if ($this->montant_total_mga) {
            $this->paiement_mga = $this->montant_total_mga;
            $this->calculateFinancials();
        }
    }

    // ✅ NOUVEAUX : Listeners pour recalculer selon l'unité
    public function updatedPrixUnitaireMga() { $this->calculateFinancials(); }
    public function updatedPoidsArriveeKg() { 
        $this->calculateQuantiteSold();
        $this->calculateFinancials(); 
    }
    public function updatedPaiementMga() { $this->calculateFinancials(); }
    public function updatedSacsPleinsArrivee() { 
        $this->calculateQuantiteSold();
        $this->calculateFinancials(); 
    }
    public function updatedSacsDemiArrivee() { 
        $this->calculateQuantiteSold();
        $this->calculateFinancials(); 
    }

    // ==============================================
    // GESTION DES MODALES
    // ==============================================
    
    public function closeChargementModal()
    {
        $this->showChargementModal = false;
        $this->resetChargementForm();
        $this->editingChargement = null;
    }

    public function closeDechargementModal()
    {
        $this->showDechargementModal = false;
        $this->resetDechargementForm();
        $this->editingDechargement = null;
    }

    public function closePreviewModal()
    {
        $this->showPreviewModal = false;
    }

    // ==============================================
    // RÉINITIALISATION DES FORMULAIRES
    // ==============================================
    
    private function resetChargementForm()
    {
        $this->chargement_reference = '';
        $this->date = now()->format('Y-m-d');
        $this->chargeur_nom = '';
        $this->chargeur_contact = '';
        $this->depart_id = '';
        $this->proprietaire_nom = 'Mme TINAH';
        $this->proprietaire_contact = '0349045769';
        $this->produit_id = '';
        $this->sacs_pleins_depart = '';
        $this->sacs_demi_depart = 0;
        $this->poids_depart_kg = '';
        $this->chargement_observation = '';
        $this->resetErrorBag();
    }


    private function resetDechargementForm()
    {
        $this->dechargement_step = 1;
        $this->selected_voyage_id = $this->voyage->id ?? null;
        $this->selected_voyage = $this->voyage ?? null;
        $this->available_voyages = [];
        $this->available_chargements = [];
        
        // ✅ NOUVEAU : Réinitialiser les informations du produit
        $this->selected_product = null;
        $this->product_unite = 'kg';
        $this->product_poids_moyen_sac = 120;
        $this->product_prix_reference = 0;
        $this->quantite_vendue = 0;
        
        // ✅ IMPORTANT : Garder une date valide au lieu de la vider
        $this->date = now()->format('Y-m-d'); // ✅ TOUJOURS AVOIR UNE DATE
        
        $this->dechargement_reference = '';
        $this->chargement_id = '';
        $this->type_dechargement = 'vente';
        $this->interlocuteur_nom = '';
        $this->interlocuteur_contact = '';
        $this->pointeur_nom = '';
        $this->pointeur_contact = '';
        $this->lieu_livraison_id = '';
        $this->sacs_pleins_arrivee = '';
        $this->sacs_demi_arrivee = 0;
        $this->poids_arrivee_kg = '';
        $this->prix_unitaire_mga = '';
        $this->montant_total_mga = '';
        $this->paiement_mga = '';
        $this->reste_mga = '';
        $this->statut_commercial = 'en_attente';
        $this->dechargement_observation = '';
        $this->resetErrorBag();
    }

    // ==============================================
    // GÉNÉRATEURS DE RÉFÉRENCES
    // ==============================================
    
    private function generateChargementReference()
    {
        $count = $this->voyage->chargements()->count() + 1;
        return 'CH' . str_pad($count, 3, '0', STR_PAD_LEFT);
    }

    private function generateDechargementReference()
    {
        if (!$this->selected_voyage) return 'OP001';
        
        $count = $this->selected_voyage->dechargements()->count() + 1;
        return 'OP' . str_pad($count, 3, '0', STR_PAD_LEFT);
    }


        /**
     * Récupère seulement les produits qui ont du stock disponible pour le chargement
     */
    public function getProduitsDisponiblesProperty()
    {
        return Produit::where('actif', true)
            ->where('qte_variable', '>', 0) // Seulement les produits avec du stock
            ->with(['transactions' => function($query) {
                $query->where('type', 'achat')
                      ->where('statut', '!=', 'annule');
            }])
            ->orderBy('nom')
            ->get()
            ->map(function($produit) {
                // Ajouter des informations utiles sur le stock
                $produit->stock_disponible = $produit->qte_variable;
                $produit->stock_formate = number_format($produit->qte_variable, 2, ',', ' ') . ' ' . $produit->unite;
                return $produit;
            });
    }

    /**
     * Récupère le produit sélectionné avec ses informations de stock
     */
    public function getProduitSelectionneProperty()
    {
        if (!$this->produit_id) {
            return null;
        }

        $produit = Produit::find($this->produit_id);
        if ($produit) {
            // Vérifier que le produit a bien du stock
            if ($produit->qte_variable <= 0) {
                $this->addError('produit_id', 'Ce produit n\'a plus de stock disponible.');
                return null;
            }
            
            $produit->stock_disponible = $produit->qte_variable;
            $produit->stock_formate = number_format($produit->qte_variable, 2, ',', ' ') . ' ' . $produit->unite;
        }

        return $produit;
    }


    /**
     * Validation du stock disponible lors de la sélection du produit
     */
    public function updatedProduitId()
    {
        $this->resetErrorBag(['produit_id', 'poids_depart_kg']);
        
        if ($this->produit_id) {
            $produit = Produit::find($this->produit_id);
            
            if (!$produit) {
                $this->addError('produit_id', 'Produit introuvable.');
                return;
            }
            
            if ($produit->qte_variable <= 0) {
                $this->addError('produit_id', 'Ce produit n\'a plus de stock disponible pour le chargement.');
                $this->produit_id = '';
                return;
            }
            
            Log::info('Produit sélectionné pour chargement', [
                'produit_id' => $this->produit_id,
                'nom' => $produit->nom_complet,
                'stock_disponible' => $produit->qte_variable,
                'unite' => $produit->unite
            ]);
        }
    }


        /**
     * Validation du poids/quantité à charger
     */
    public function updatedPoidsDepartKg()
    {
        $this->validateStockForChargement();
    }

    /**
     * Validation du stock pour le chargement
     */
    private function validateStockForChargement()
    {
        if (!$this->produit_id || !$this->poids_depart_kg) {
            return;
        }

        $produit = Produit::find($this->produit_id);
        if (!$produit) {
            return;
        }

        $poidsACharger = floatval($this->poids_depart_kg);
        $stockDisponible = floatval($produit->qte_variable);

        // Conversion selon l'unité du produit
        $quantiteACharger = $poidsACharger; // Par défaut en kg
        
        if ($produit->unite === 'sacs') {
            // Si le produit est géré en sacs, convertir le poids en sacs équivalents
            $poidsMoyenSac = $produit->poids_moyen_sac_kg_max > 0 ? $produit->poids_moyen_sac_kg_max : 120;
            $quantiteACharger = $poidsACharger / $poidsMoyenSac;
        } elseif ($produit->unite === 'tonnes') {
            // Si le produit est géré en tonnes, convertir
            $quantiteACharger = $poidsACharger / 1000;
        }

        Log::info('Validation stock pour chargement', [
            'produit_unite' => $produit->unite,
            'poids_a_charger' => $poidsACharger,
            'quantite_equivalent' => $quantiteACharger,
            'stock_disponible' => $stockDisponible
        ]);

        if ($quantiteACharger > $stockDisponible) {
            $message = 'Stock insuffisant. ';
            $message .= 'Disponible: ' . number_format($stockDisponible, 2, ',', ' ') . ' ' . $produit->unite . ', ';
            $message .= 'Demandé: ' . number_format($quantiteACharger, 2, ',', ' ') . ' ' . $produit->unite . ' équivalent';
            
            $this->addError('poids_depart_kg', $message);
        } else {
            $this->resetErrorBag('poids_depart_kg');
            
            // Calculer le stock restant après chargement (informatif)
            $stockRestant = $stockDisponible - $quantiteACharger;
            Log::info('Stock après chargement', [
                'stock_restant' => $stockRestant,
                'produit' => $produit->nom_complet
            ]);
        }
    }

    // ==============================================
    // RENDU DE LA VUE
    // ==============================================
    
public function render()
    {
        // Charger le voyage avec toutes ses relations
        $voyage = $this->voyage->load([
            'vehicule', 
            'chargements.produit',
            'chargements.depart',
            'dechargements.chargement.produit',
            'dechargements.lieuLivraison'
        ]);

        // ✅ CORRECTION : Récupérer les produits disponibles avec stock
        $produits = $this->produitsDisponibles;
        
        $departs = Lieu::where('type', 'depart')->where('actif', true)->orderBy('nom')->get();
        $destinations = Lieu::whereIn('type', ['destination', 'depot'])->where('actif', true)->orderBy('nom')->get();

        // ==============================================
        // CALCULS STATISTIQUES AVANCÉS
        // ==============================================
        
        // CHARGEMENTS
        $totalPoidsCharge = $voyage->chargements->sum('poids_depart_kg');
        $totalSacsChargesPleins = $voyage->chargements->sum('sacs_pleins_depart');
        $totalSacsChargesDemi = $voyage->chargements->sum('sacs_demi_depart');
        $totalSacsCharges = $totalSacsChargesPleins + ($totalSacsChargesDemi / 2);
        
        // DÉCHARGEMENTS
        $totalPoidsDecharge = $voyage->dechargements->sum('poids_arrivee_kg');
        $totalSacsDechargesPleins = $voyage->dechargements->sum('sacs_pleins_arrivee');
        $totalSacsDechargesDemi = $voyage->dechargements->sum('sacs_demi_arrivee');
        $totalSacsDecharges = $totalSacsDechargesPleins + ($totalSacsDechargesDemi / 2);
        
        // ÉCARTS ET ANALYSES
        $ecartPoids = $totalPoidsCharge - $totalPoidsDecharge;
        $ecartSacs = $totalSacsCharges - $totalSacsDecharges;
        $pourcentageCompletion = $totalPoidsCharge > 0 ? ($totalPoidsDecharge / $totalPoidsCharge) * 100 : 0;
        $pourcentagePerte = $totalPoidsCharge > 0 ? ($ecartPoids / $totalPoidsCharge) * 100 : 0;
        
        // STATISTIQUES SUPPLÉMENTAIRES
        $nombreChargements = $voyage->chargements->count();
        $nombreDechargements = $voyage->dechargements->count();
        $nombreChargementsRestants = $nombreChargements - $nombreDechargements;
        
        // DONNÉES FINANCIÈRES
        $dechargements_ventes = $voyage->dechargements->where('type', 'vente');
        $totalVentes = $dechargements_ventes->sum('montant_total_mga');
        $totalPaiements = $dechargements_ventes->sum('paiement_mga');
        $totalReste = $dechargements_ventes->sum('reste_mga');
        $pourcentagePaiement = $totalVentes > 0 ? ($totalPaiements / $totalVentes) * 100 : 0;
        
        // ANALYSE PAR PRODUIT
        $analyseParProduit = $voyage->chargements->groupBy('produit.nom')->map(function ($chargements, $produit) {
            $totalCharge = $chargements->sum('poids_depart_kg');
            $dechargements = $chargements->flatMap(function ($ch) {
                return $ch->dechargements;
            });
            $totalDecharge = $dechargements->sum('poids_arrivee_kg');
            
            return [
                'produit' => $produit ?: 'N/A',
                'poids_charge' => $totalCharge,
                'poids_decharge' => $totalDecharge,
                'ecart' => $totalCharge - $totalDecharge,
                'pourcentage_completion' => $totalCharge > 0 ? ($totalDecharge / $totalCharge) * 100 : 0,
                'chargements_count' => $chargements->count(),
                'dechargements_count' => $dechargements->count(),
            ];
        });

        return view('livewire.voyage.voyage-show', compact(
            'voyage', 
            'departs',
            'produits', // ✅ CORRECTION : Repasser $produits pour les modals
            'destinations',
            
            // Statistiques principales
            'totalPoidsCharge',
            'totalPoidsDecharge', 
            'totalSacsCharges',
            'totalSacsDecharges',
            'ecartPoids',
            'ecartSacs',
            'nombreChargements',
            'nombreDechargements',
            'nombreChargementsRestants',
            'pourcentageCompletion',
            'pourcentagePerte',
            
            // Données financières
            'totalVentes',
            'totalPaiements',
            'totalReste',
            'pourcentagePaiement',
            
            // Analyses avancées
            'analyseParProduit'
        ));
    }
    
}
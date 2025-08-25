<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class Categorie extends Model
{
    use HasFactory;

    protected $table = 'categories';

    protected $fillable = [
        'code_comptable',
        'nom',
        'type',
        'budget',
        'description',
        'is_active',
        'type_compte',
        'objectif_mensuel',
        'seuil_alerte',
    ];

    protected $attributes = [
        'budget' => 0,
        'is_active' => true,
        'type' => 'depense',
        'type_compte' => 'Principal',
        'objectif_mensuel' => 0,
        'seuil_alerte' => 80, // 80% du budget
    ];

    protected $casts = [
        'budget' => 'decimal:2',
        'is_active' => 'boolean',
        'objectif_mensuel' => 'decimal:2',
        'seuil_alerte' => 'integer',
    ];

    /**
     * Relation avec les transactions comptables
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(TransactionComptable::class, 'categorie_id');
    }

    /**
     * Transactions de recettes uniquement
     */
    public function recettes(): HasMany
    {
        return $this->transactions()->where('type', 'recette');
    }

    /**
     * Transactions de dépenses uniquement
     */
    public function depenses(): HasMany
    {
        return $this->transactions()->where('type', 'depense');
    }

    /**
     * Partenaires associés à cette catégorie
     */
    public function getPartenairesAssociesAttribute()
    {
        return Partenaire::whereHas('transactions', function ($query) {
            $query->where('categorie_id', $this->id);
        })->withCount([
                    'transactions' => function ($q) {
                        $q->where('categorie_id', $this->id);
                    }
                ])->get();
    }

    // ========== CALCULS FINANCIERS ==========

    /**
     * Montant total des transactions
     */
    public function getMontantTotalAttribute(): float
    {
        return (float) $this->transactions()->sum('montant');
    }

    /**
     * Chiffre d'affaires (recettes uniquement)
     */
    public function getChiffreAffairesAttribute(): float
    {
        return (float) $this->recettes()->sum('montant');
    }

    /**
     * Total des dépenses
     */
    public function getTotalDepensesAttribute(): float
    {
        return (float) $this->depenses()->sum('montant');
    }

    /**
     * Bénéfice/Perte de la catégorie
     */
    public function getBeneficePerteAttribute(): float
    {
        if ($this->type === 'recette') {
            return $this->chiffre_affaires; // Recettes = bénéfice
        } else {
            return -$this->total_depenses; // Dépenses = perte
        }
    }

    /**
     * Marge bénéficiaire (pour les catégories mixtes)
     */
    public function getMargeAttribute(): float
    {
        $recettes = $this->chiffre_affaires;
        $depenses = $this->total_depenses;

        if ($recettes > 0) {
            return (($recettes - $depenses) / $recettes) * 100;
        }
        return 0;
    }

    // ========== STATISTIQUES TEMPORELLES ==========

    /**
     * Transactions du mois en cours
     */
    public function getTransactionsMoisAttribute(): array
    {
        $debut = now()->startOfMonth();
        $fin = now()->endOfMonth();

        return [
            'recettes' => $this->recettes()
                ->whereBetween('date_transaction', [$debut, $fin])
                ->sum('montant'),
            'depenses' => $this->depenses()
                ->whereBetween('date_transaction', [$debut, $fin])
                ->sum('montant'),
            'nombre' => $this->transactions()
                ->whereBetween('date_transaction', [$debut, $fin])
                ->count(),
        ];
    }

    /**
     * Transactions de l'année en cours
     */
    public function getTransactionsAnneeAttribute(): array
    {
        $debut = now()->startOfYear();
        $fin = now()->endOfYear();

        return [
            'recettes' => $this->recettes()
                ->whereBetween('date_transaction', [$debut, $fin])
                ->sum('montant'),
            'depenses' => $this->depenses()
                ->whereBetween('date_transaction', [$debut, $fin])
                ->sum('montant'),
            'nombre' => $this->transactions()
                ->whereBetween('date_transaction', [$debut, $fin])
                ->count(),
        ];
    }

    /**
     * Évolution mensuelle (12 derniers mois)
     */
    public function getEvolutionMensuelleAttribute(): array
    {
        $evolution = [];

        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $debut = $date->copy()->startOfMonth();
            $fin = $date->copy()->endOfMonth();

            $recettes = $this->recettes()
                ->whereBetween('date_transaction', [$debut, $fin])
                ->sum('montant');

            $depenses = $this->depenses()
                ->whereBetween('date_transaction', [$debut, $fin])
                ->sum('montant');

            $evolution[] = [
                'mois' => $date->format('M Y'),
                'recettes' => $recettes,
                'depenses' => $depenses,
                'benefice' => $recettes - $depenses,
                'nombre_transactions' => $this->transactions()
                    ->whereBetween('date_transaction', [$debut, $fin])
                    ->count(),
            ];
        }

        return $evolution;
    }

    // ========== ANALYTICS ET INSIGHTS ==========

    /**
     * Top 5 des partenaires par volume
     */
    public function getTopPartenairesAttribute(): array
    {
        return $this->transactions()
            ->with('partenaire')
            ->whereNotNull('partenaire_id')
            ->select('partenaire_id', DB::raw('SUM(montant) as total'), DB::raw('COUNT(*) as nombre_transactions'))
            ->groupBy('partenaire_id')
            ->orderByDesc('total')
            ->limit(5)
            ->get()
            ->map(function ($transaction) {
                return [
                    'partenaire' => $transaction->partenaire->nom,
                    'total' => $transaction->total,
                    'nombre_transactions' => $transaction->nombre_transactions,
                    'moyenne' => $transaction->total / $transaction->nombre_transactions,
                ];
            })
            ->toArray();
    }

    /**
     * Analyse des modes de paiement
     */
    public function getAnalyseModesPaiementAttribute(): array
    {
        return $this->transactions()
            ->select('mode_paiement', DB::raw('SUM(montant) as total'), DB::raw('COUNT(*) as nombre'))
            ->groupBy('mode_paiement')
            ->orderByDesc('total')
            ->get()
            ->map(function ($item) {
                return [
                    'mode' => $item->mode_paiement,
                    'total' => $item->total,
                    'nombre' => $item->nombre,
                    'pourcentage' => $this->montant_total > 0 ?
                        round(($item->total / $this->montant_total) * 100, 2) : 0,
                ];
            })
            ->toArray();
    }

    /**
     * Transactions quotidiennes moyennes
     */
    public function getTransactionQuotidienneMoyenneAttribute(): float
    {
        $premiere = $this->transactions()->orderBy('date_transaction')->first();
        if (!$premiere)
            return 0;

        $jours = $premiere->date_transaction->diffInDays(now()) + 1;
        return $jours > 0 ? $this->montant_total / $jours : 0;
    }

    // ========== GESTION BUDGET ET ALERTES ==========

    /**
     * Pourcentage d'utilisation du budget
     */
    public function getPourcentageBudgetAttribute(): float
    {
        if ($this->budget <= 0)
            return 0;

        $consomme = $this->type === 'depense' ? $this->total_depenses : $this->chiffre_affaires;
        return min(100, ($consomme / $this->budget) * 100);
    }

    /**
     * Montant restant du budget
     */
    public function getMontantRestantBudgetAttribute(): float
    {
        if ($this->type === 'recette') {
            return max(0, $this->budget - $this->chiffre_affaires);
        }
        return max(0, $this->budget - $this->total_depenses);
    }

    /**
     * Vérifier si le seuil d'alerte est atteint
     */
    public function isSeuilAlerteAtteint(): bool
    {
        return $this->pourcentage_budget >= $this->seuil_alerte;
    }

    /**
     * Vérifier si le budget est dépassé
     */
    public function isBudgetDepasse(): bool
    {
        return $this->pourcentage_budget > 100;
    }

    /**
     * Projection fin de mois
     */
    public function getProjectionFinMoisAttribute(): array
    {
        $joursEcoules = now()->day;
        $joursRestants = now()->daysInMonth - $joursEcoules;

        if ($joursEcoules === 0)
            return ['projection' => 0, 'depassement' => false];

        $transactionsMois = $this->transactions_mois;
        $moyenneJournaliere = $transactionsMois['recettes'] - $transactionsMois['depenses'];
        $projectionFinMois = $moyenneJournaliere + ($moyenneJournaliere / $joursEcoules * $joursRestants);

        return [
            'projection' => $projectionFinMois,
            'depassement' => $this->budget > 0 && abs($projectionFinMois) > $this->budget,
            'pourcentage_projection' => $this->budget > 0 ?
                (abs($projectionFinMois) / $this->budget) * 100 : 0,
        ];
    }

    // ========== DASHBOARD COMPLET ==========

    /**
     * Tableau de bord complet de la catégorie
     */
    public function getDashboardComplet(): array
    {
        return [
            // Informations de base
            'informations' => [
                'id' => $this->id,
                'code_comptable' => $this->code_comptable,
                'nom' => $this->nom,
                'type' => $this->type,
                'budget' => $this->budget,
                'is_active' => $this->is_active,
                'created_at' => $this->created_at->format('d/m/Y'),
            ],

            // Métriques financières
            'financier' => [
                'chiffre_affaires' => $this->chiffre_affaires,
                'total_depenses' => $this->total_depenses,
                'benefice_perte' => $this->benefice_perte,
                'marge' => $this->marge,
                'transaction_quotidienne_moyenne' => $this->transaction_quotidienne_moyenne,
            ],

            // Budget et contrôle
            'budget' => [
                'budget_alloue' => $this->budget,
                'montant_consomme' => $this->type === 'depense' ? $this->total_depenses : $this->chiffre_affaires,
                'montant_restant' => $this->montant_restant_budget,
                'pourcentage_utilise' => $this->pourcentage_budget,
                'seuil_alerte_atteint' => $this->isSeuilAlerteAtteint(),
                'budget_depasse' => $this->isBudgetDepasse(),
                'projection_fin_mois' => $this->projection_fin_mois,
            ],

            // Statistiques temporelles
            'temporel' => [
                'mois_courant' => $this->transactions_mois,
                'annee_courante' => $this->transactions_annee,
                'evolution_mensuelle' => $this->evolution_mensuelle,
            ],

            // Analytics
            'analytics' => [
                'top_partenaires' => $this->top_partenaires,
                'modes_paiement' => $this->analyse_modes_paiement,
                'partenaires_associes' => $this->partenaires_associes,
            ],

            // Transactions récentes
            'transactions_recentes' => $this->transactions()
                ->with(['partenaire'])
                ->latest('date_transaction')
                ->latest('id')
                ->limit(10)
                ->get()
                ->map(function ($transaction) {
                    return [
                        'id' => $transaction->id,
                        'reference' => $transaction->reference,
                        'description' => $transaction->description,
                        'montant' => $transaction->montant,
                        'montant_formate' => $transaction->montant_formate,
                        'date' => $transaction->date_formattee,
                        'type' => $transaction->type,
                        'mode_paiement' => $transaction->mode_complet,
                        'partenaire' => $transaction->partenaire?->nom,
                    ];
                })->toArray(),
        ];
    }

    // ========== SCOPES ==========

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeRecette($query)
    {
        return $query->where('type', 'recette');
    }

    public function scopeDepense($query)
    {
        return $query->where('type', 'depense');
    }

    public function scopeAvecBudgetDepasse($query)
    {
        return $query->whereRaw('(
            SELECT COALESCE(SUM(montant), 0) 
            FROM transactions_comptables 
            WHERE categorie_id = categories.id
        ) > budget');
    }

    public function scopeAvecActiviteRecente($query, $jours = 30)
    {
        return $query->whereHas('transactions', function ($q) use ($jours) {
            $q->where('date_transaction', '>=', now()->subDays($jours));
        });
    }

    // ========== MÉTHODES UTILITAIRES ==========

    /**
     * Obtenir le statut de la catégorie
     */
    public function getStatutAttribute(): string
    {
        if (!$this->is_active)
            return 'inactive';
        if ($this->isBudgetDepasse())
            return 'budget_depasse';
        if ($this->isSeuilAlerteAtteint())
            return 'alerte';
        return 'normal';
    }

    /**
     * Générer un rapport mensuel
     */
    public function genererRapportMensuel(Carbon $mois = null): array
    {
        $mois = $mois ?? now();
        $debut = $mois->copy()->startOfMonth();
        $fin = $mois->copy()->endOfMonth();

        return [
            'periode' => $mois->format('F Y'),
            'recettes' => $this->recettes()->whereBetween('date_transaction', [$debut, $fin])->sum('montant'),
            'depenses' => $this->depenses()->whereBetween('date_transaction', [$debut, $fin])->sum('montant'),
            'nombre_transactions' => $this->transactions()->whereBetween('date_transaction', [$debut, $fin])->count(),
            'objectif_atteint' => $this->objectif_mensuel > 0 ?
                $this->transactions()->whereBetween('date_transaction', [$debut, $fin])->sum('montant') >= $this->objectif_mensuel :
                null,
        ];
    }
}
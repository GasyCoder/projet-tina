<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
    'objectif_mensuel', // Ajouter si nécessaire
    'seuil_alerte',     // Ajouter si nécessaire
];


   
protected $attributes = [
    'budget' => 0,
    'is_active' => true,
    'type' => 'depense',
    'objectif_mensuel' => 0, // Ajouter si nécessaire
    'seuil_alerte' => 80,    // Ajouter si nécessaire
];


  protected $casts = [
    'budget' => 'decimal:2',
    'is_active' => 'boolean',
    'objectif_mensuel' => 'decimal:2', // Ajouter si nécessaire
    'seuil_alerte' => 'integer',       // Ajouter si nécessaire
];

    /**
     * Relation avec les transactions comptables
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(TransactionComptable::class, 'categorie_id');
    }

    /**
     * Montant total des transactions
     */
    public function getMontantTotalAttribute(): float
    {
        return (float) $this->transactions()->sum('montant');
    }

    /**
     * Nombre de transactions
     */
    public function getNombreTransactionsAttribute(): int
    {
        return $this->transactions()->count();
    }

    /**
     * Total des transactions du mois en cours
     */
    public function getTransactionsMoisAttribute(): float
    {
        return (float) $this->transactions()
            ->whereMonth('date_transaction', now()->month)
            ->whereYear('date_transaction', now()->year)
            ->sum('montant');
    }

    /**
     * Moyenne des transactions
     */
    public function getMoyenneTransactionAttribute(): float
    {
        return (float) $this->transactions()->avg('montant') ?? 0;
    }

    /**
     * Dernière transaction formatée
     */
    public function getDerniereTransactionAttribute(): ?string
    {
        $derniere = $this->transactions()
            ->latest('date_transaction')
            ->first();

        return $derniere ? $derniere->date_transaction->format('d/m/Y') : null;
    }

    /**
     * Transactions récentes (5 dernières)
     */
    public function getRecentTransactionsAttribute(): array
    {
        return $this->transactions()
            ->with('partenaire')
            ->latest('date_transaction')
            ->limit(5)
            ->get()
            ->map(fn($transaction) => [
                'id' => $transaction->id,
                'reference' => $transaction->reference,
                'description' => $transaction->description,
                'montant' => $transaction->montant,
                'date' => $transaction->date_transaction?->format('d/m/Y'),
                'partenaire' => $transaction->partenaire?->nom,
            ])
            ->toArray();
    }

    /**
     * Vérifier si le budget est dépassé
     */
    public function isBudgetDepasse(): bool
    {
        if ($this->type === 'recette') {
            return false; // Pas de limite pour les recettes
        }

        return $this->montant_total > $this->budget;
    }

    /**
     * Pourcentage d'utilisation du budget
     */
    public function getPourcentageBudgetAttribute(): float
    {
        if ($this->budget <= 0 || $this->type === 'recette') {
            return 0;
        }

        return min(100, ($this->montant_total / $this->budget) * 100);
    }

    /**
     * Montant restant du budget
     */
    public function getMontantRestantBudgetAttribute(): float
    {
        if ($this->type === 'recette') {
            return 0;
        }

        return max(0, $this->budget - $this->montant_total);
    }

    /**
 * Dashboard complet de la catégorie
 */
public function getDashboardComplet(): array
{
    $transactions = $this->transactions();
    $moisCourant = now();
    $moisPrecedent = now()->subMonth();

    // Statistiques du mois précédent
    $montantMoisPrecedent = (float) $transactions
        ->whereMonth('date_transaction', $moisPrecedent->month)
        ->whereYear('date_transaction', $moisPrecedent->year)
        ->sum('montant');

    // Évolution par rapport au mois précédent
    $montantMoisCourant = $this->transactions_mois;
    $evolution = $montantMoisPrecedent > 0 
        ? (($montantMoisCourant - $montantMoisPrecedent) / $montantMoisPrecedent) * 100 
        : 0;

    return [
        'financier' => [
            'montant_total' => $this->montant_total,
            'montant_mois' => $montantMoisCourant,
            'montant_mois_precedent' => $montantMoisPrecedent,
            'evolution_mois' => $evolution,
            'budget' => $this->budget,
            'montant_restant_budget' => $this->montant_restant_budget,
            'pourcentage_budget' => $this->pourcentage_budget,
            'budget_depasse' => $this->isBudgetDepasse(),
        ],
        'transactions' => [
            'total' => $this->nombre_transactions,
            'entrees' => $transactions->where('statut', 'entrer')->count(),
            'sorties' => $transactions->where('statut', 'sortie')->count(),
            'moyenne' => $this->moyenne_transaction,
        ],
        'recent' => [
            'derniere_transaction' => $this->derniere_transaction,
            'recent_transactions' => $this->recent_transactions,
        ],
        'tendance' => [
            'evolution_mois' => $evolution,
            'tendance' => $evolution >= 0 ? 'hausse' : 'baisse',
        ]
    ];
}
    /**
     * Statistiques complètes de la catégorie
     */
    public function getStatistiquesCompletes(): array
    {
        $transactions = $this->transactions();
        $moisCourant = now();

        return [
            'montant_total' => $this->montant_total,
            'montant_mois' => $this->transactions_mois,
            'derniere_transaction' => $this->derniere_transaction,
            'budget' => $this->budget,
            'montant_restant_budget' => $this->montant_restant_budget,
            'pourcentage_budget' => $this->pourcentage_budget,
            'budget_depasse' => $this->isBudgetDepasse(),
            'transactions_entrees' => $transactions->where('statut', 'entrer')->count(),
            'transactions_sorties' => $transactions->where('statut', 'sortie')->count(),
            'montant_entrees' => $transactions->where('statut', 'entrer')->sum('montant'),
            'montant_sorties' => $transactions->where('statut', 'sortie')->sum('montant'),
            'recent_transactions' => $this->recent_transactions,
        ];
    }

    /**
     * Scope : catégories actives
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope : catégories de recette
     */
    public function scopeRecette($query)
    {
        return $query->where('type', 'recette');
    }

    /**
     * Scope : catégories de dépense
     */
    public function scopeDepense($query)
    {
        return $query->where('type', 'depense');
    }

    /**
     * Scope : avec le nombre de transactions
     */
    public function scopeWithTransactionsCount($query)
    {
        return $query->withCount('transactions');
    }

    /**
     * Scope : avec le montant total des transactions
     */
    public function scopeWithMontantTotal($query)
    {
        return $query->withSum('transactions', 'montant');
    }
}
    
     
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionComptable extends Model
{
    use HasFactory;

    protected $table = 'transactions_comptables';

    protected $fillable = [
        'categorie_id',
        'reference',
        'description',
        'montant',
        'date_transaction',
        'type',
        'partenaire_id',
        'justificatif',
        'statut',
        'notes',
    ];

    protected $attributes = [
        'type' => 'depense',
        'statut' => 'validee',
    ];

    protected $casts = [
        'date_transaction' => 'date',
        'montant' => 'decimal:2',
    ];

    /**
     * Relation avec la catégorie
     */
    public function categorie()
    {
        return $this->belongsTo(Categorie::class);
    }

    /**
     * Relation avec le partenaire (optionnel)
     */
    public function partenaire()
    {
        return $this->belongsTo(Partenaire::class);
    }

    /**
     * Scope pour filtrer par type
     */
    public function scopeType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope pour filtrer par statut
     */
    public function scopeStatut($query, $statut)
    {
        return $query->where('statut', $statut);
    }

    /**
     * Scope pour les transactions du mois
     */
    public function scopeMoisCourant($query)
    {
        return $query->whereMonth('date_transaction', now()->month)
            ->whereYear('date_transaction', now()->year);
    }

    /**
     * Scope pour une période donnée
     */
    public function scopePeriode($query, $dateDebut, $dateFin)
    {
        return $query->whereBetween('date_transaction', [$dateDebut, $dateFin]);
    }

    /**
     * Formatage du montant
     */
    public function getMontantFormateAttribute()
    {
        return number_format($this->montant, 0, ',', ' ') . ' Ar';
    }

    /**
     * Formatage de la date
     */
    public function getDateFormatteeAttribute()
    {
        return $this->date_transaction->format('d/m/Y');
    }
}
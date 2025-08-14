<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categorie extends Model
{
    use HasFactory;

    protected $table = 'categories';

    protected $fillable = [
        'code_comptable',
        'nom',
        'type',
        'description',
        'is_active',
    ];

    protected $attributes = [
        'type' => 'depense',
        'is_active' => true,
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Relation avec les transactions comptables
     */
    public function transactions()
    {
        return $this->hasMany(TransactionComptable::class, 'categorie_id');
    }

    /**
     * Calcul du montant total des transactions pour cette catégorie
     */
    public function getMontantTotalAttribute()
    {
        return $this->transactions()->sum('montant');
    }

    /**
     * Nombre de transactions pour cette catégorie
     */
    public function getNombreTransactionsAttribute()
    {
        return $this->transactions()->count();
    }

    /**
     * Transactions du mois en cours
     */
    public function getTransactionsMoisAttribute()
    {
        return $this->transactions()
            ->whereMonth('date_transaction', now()->month)
            ->whereYear('date_transaction', now()->year)
            ->sum('montant');
    }

    /**
     * Scope pour filtrer par type
     */
    public function scopeType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope pour les catégories actives
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
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
        'budget',
        'description',
        'is_active',
    ];

    protected $attributes = [
        'budget'    => 0,
        'is_active' => true,
    ];

    protected $casts = [
        'budget'    => 'decimal:2',
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
     * Montant total des transactions
     */
    public function getMontantTotalAttribute()
    {
        return $this->transactions()->sum('montant');
    }

    /**
     * Nombre de transactions
     */
    public function getNombreTransactionsAttribute()
    {
        return $this->transactions()->count();
    }

    /**
     * Total des transactions du mois en cours
     */
    public function getTransactionsMoisAttribute()
    {
        return $this->transactions()
            ->whereMonth('date_transaction', now()->month)
            ->whereYear('date_transaction', now()->year)
            ->sum('montant');
    }

    /**
     * Scope : catégories actives
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}

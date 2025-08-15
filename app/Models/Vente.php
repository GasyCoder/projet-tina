<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Vente extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'ventes';

    protected $fillable = [
        'reference',
        'date',
        'objet',
        'depot_id',
        'vendeur_nom',
        'montant_paye_mga',
        'montant_restant_mga',
        'statut_paiement',
        'mode_paiement',
        'observation'
    ];

    protected $casts = [
        'date' => 'date',
        'montant_paye_mga' => 'decimal:2',
        'montant_restant_mga' => 'decimal:2'
    ];

    /**
     * Relation avec le dépôt (Lieu)
     */
    public function depot()
    {
        return $this->belongsTo(Lieu::class, 'depot_id');
    }

    /**
     * Scope pour filtrer par statut de paiement
     */
    public function scopeStatut($query, $statut)
    {
        return $query->where('statut_paiement', $statut);
    }

    /**
     * Scope pour filtrer par mode de paiement
     */
    public function scopeModePaiement($query, $mode)
    {
        return $query->where('mode_paiement', $mode);
    }

    /**
     * Scope pour les ventes d'une date donnée
     */
    public function scopeParDate($query, $date)
    {
        return $query->whereDate('date', $date);
    }

    /**
     * Scope pour les ventes d'un vendeur donné
     */
    public function scopeParVendeur($query, $vendeurNom)
    {
        return $query->where('vendeur_nom', $vendeurNom);
    }
}

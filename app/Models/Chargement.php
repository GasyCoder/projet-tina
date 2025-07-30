<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Chargement extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $fillable = [
        'voyage_id',
        'date',
        'reference',
        'chargeur_nom',
        'chargeur_contact',
        'depart_id',
        'proprietaire_nom',
        'proprietaire_contact',
        'produit_id',
        'sacs_pleins_depart',
        'sacs_demi_depart',
        'poids_depart_kg',
        'observation'
    ];

    protected $casts = [
        'date' => 'date',
        'poids_depart_kg' => 'decimal:2'
    ];

    // Relations
    public function voyage()
    {
        return $this->belongsTo(Voyage::class);
    }

    public function depart()
    {
        return $this->belongsTo(Lieu::class, 'depart_id');
    }


    public function produit()
    {
        return $this->belongsTo(Produit::class);
    }

    // Calculs
    public function getPoidsRestantAttribute()
    {
        $poidsDecharge = $this->dechargements->sum('poids_arrivee_kg');
        return $this->poids_depart_kg - $poidsDecharge;
    }

    public function getPoidsDechargeAttribute()
    {
        return $this->dechargements->sum('poids_arrivee_kg');
    }

    public function getPourcentageDechargeAttribute()
    {
        if ($this->poids_depart_kg == 0) return 0;
        return ($this->getPoidsDechargeAttribute() / $this->poids_depart_kg) * 100;
    }

    /**
     * Relation : Un chargement peut avoir plusieurs déchargements
     */
    public function dechargements()
    {
        return $this->hasMany(Dechargement::class, 'chargement_id');
    }

    /**
     * Vérifier si le chargement a déjà un déchargement
     */
    public function hasDechargement()
    {
        return $this->dechargements()->exists();
    }

    /**
     * Obtenir le premier déchargement
     */
    public function firstDechargement()
    {
        return $this->dechargements()->first();
    }


    /**
     * Scope pour les chargements disponibles (non utilisés dans des transactions actives)
     */
    public function scopeDisponibles($query)
    {
        return $query->whereDoesntHave('transactions', function($q) {
            $q->where('statut', '!=', 'annule');
        });
    }

    /**
     * Relation avec les transactions
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'chargement_id');
    }

}

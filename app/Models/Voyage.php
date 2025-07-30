<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Voyage extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'voyages';


    protected $fillable = [
        'reference',
        'date',
        'vehicule_id',
        'statut',
        'ecart_sacs_pleins',
        'ecart_sacs_demi',
        'ecart_poids_kg',
        'observation',
        'chauffeur_phone',
        'chauffeur_nom'
    ];

    protected $casts = [
        'date' => 'date',
        'ecart_poids_kg' => 'decimal:2'
    ];

    // Relations

    public function vehicule()
    {
        return $this->belongsTo(Vehicule::class);
    }


    public function chargements()
    {
        return $this->hasMany(Chargement::class);
    }

    public function dechargements()
    {
        return $this->hasMany(Dechargement::class);
    }

    // Accesseurs calculés
    public function getTotalPoidsChargementAttribute()
    {
        return $this->chargements->sum('poids_depart_kg');
    }

    public function getTotalPoidsDechargeAttribute()
    {
        return $this->dechargements->sum('poids_depart_kg');
    }

    public function getEcartTotalKgAttribute()
    {
        return $this->getTotalPoidsChargementAttribute() - $this->getTotalPoidsDechargeAttribute();
    }

    // Scopes
    public function scopeEnCours($query)
    {
        return $query->where('statut', 'en_cours');
    }

    public function scopeTermine($query)
    {
        return $query->where('statut', 'termine');
    }



    /**
     * Vérifie si le voyage a des déchargements (requis pour vente)
     */
    public function hasValidDechargements()
    {
        return $this->dechargements()->exists();
    }

    /**
     * Vérifie si le voyage a des chargements (requis pour achat)
     */
    public function hasValidChargements()
    {
        return $this->chargements()->exists();
    }

    /**
     * Obtient les chargements disponibles pour transaction (pas déjà utilisés)
     */
    public function getChargementsDisponibles()
    {
        return $this->chargements()->whereDoesntHave('transactions', function($query) {
            $query->where('statut', '!=', 'annule');
        })->get();
    }

    /**
     * Obtient les déchargements disponibles pour transaction (pas déjà utilisés)
     */
    public function getDechargementsDisponibles()
    {
        return $this->dechargements()->whereDoesntHave('transactions', function($query) {
            $query->where('statut', '!=', 'annule');
        })->get();
    }

    /**
     * Vérifie si le voyage peut être utilisé pour un achat
     */
    public function canBeUsedForAchat()
    {
        return $this->hasValidChargements() && $this->getChargementsDisponibles()->count() > 0;
    }

    /**
     * Vérifie si le voyage peut être utilisé pour une vente
     */
    public function canBeUsedForVente()
    {
        return $this->hasValidDechargements() && $this->getDechargementsDisponibles()->count() > 0;
    }

    // =====================================
    // AJOUTS DANS App\Models\Chargement.php
    // =====================================

    /**
     * Relation avec les transactions
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'chargement_id');
    }

    /**
     * Vérifie si le chargement est déjà utilisé dans une transaction active
     */
    public function isUsedInTransaction()
    {
        return $this->transactions()->where('statut', '!=', 'annule')->exists();
    }

    /**
     * Obtient la transaction active liée à ce chargement
     */
    public function getActiveTransaction()
    {
        return $this->transactions()->where('statut', '!=', 'annule')->first();
    }

    /**
     * Scope pour les chargements disponibles
     */
    public function scopeDisponibles($query)
    {
        return $query->whereDoesntHave('transactions', function($q) {
            $q->where('statut', '!=', 'annule');
        });
    }

    /**
     * Relation avec chargement
     */
    public function chargement()
    {
        return $this->belongsTo(Chargement::class);
    }

    /**
     * Relation avec déchargement
     */
    public function dechargement()
    {
        return $this->belongsTo(Dechargement::class);
    }

    /**
     * Scope pour les transactions actives (non annulées)
     */
    public function scopeActives($query)
    {
        return $query->where('statut', '!=', 'annule');
    }
}
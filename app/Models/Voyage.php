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
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voyage extends Model
{
    use HasFactory;

    protected $fillable = [
        'reference',
        'date',
        'origine_id',
        'vehicule_id',
        'chauffeur_id',
        'statut',
        'ecart_sacs_pleins',
        'ecart_sacs_demi',
        'ecart_poids_kg',
        'observation'
    ];

    protected $casts = [
        'date' => 'date',
        'ecart_poids_kg' => 'decimal:2'
    ];

    // Relations
    public function origine()
    {
        return $this->belongsTo(Lieu::class, 'origine_id');
    }

    public function vehicule()
    {
        return $this->belongsTo(Vehicule::class);
    }

    public function chauffeur()
    {
        return $this->belongsTo(User::class, 'chauffeur_id');
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
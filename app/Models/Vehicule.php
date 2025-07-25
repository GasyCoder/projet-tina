<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicule extends Model
{
    use HasFactory;

    protected $fillable = [
        'immatriculation',
        'type',
        'marque',
        'modele',
        'capacite_max_kg',
        'statut',
        'chauffeur',
    ];

    protected $casts = [
        'capacite_max_kg' => 'integer'
    ];

    // Relations
    public function voyages()
    {
        return $this->hasMany(Voyage::class);
    }

    public function stockDepots()
    {
        return $this->hasMany(StockDepot::class, 'vehicule_sortie_id');
    }

    public function transferts()
    {
        return $this->hasMany(TransfertStock::class);
    }

    // Accesseurs
    public function getCapaciteMaxTonnesAttribute()
    {
        return $this->capacite_max_kg ? $this->capacite_max_kg / 1000 : null;
    }

    // Scopes
    public function scopeActif($query)
    {
        return $query->where('statut', 'actif');
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lieu extends Model
{
    use HasFactory;
    protected $table = 'lieux';
    protected $fillable = [
        'nom',
        'type',
        'region',
        'adresse',
        'telephone',
        'actif'
    ];

    protected $casts = [
        'actif' => 'boolean'
    ];

    // Relations
    public function voyagesDepart()
    {
        return $this->hasMany(Voyage::class, 'depart_id');
    }

    public function dechargements()
    {
        return $this->hasMany(Dechargement::class, 'lieu_livraison_id');
    }

 
    public function transfertsDepart()
    {
        return $this->hasMany(TransfertStock::class, 'depot_depart_id');
    }

    public function transfertsDestination()
    {
        return $this->hasMany(TransfertStock::class, 'depot_destination_id');
    }

    public function prixMarche()
    {
        return $this->hasMany(PrixMarche::class);
    }

    // Scopes
    public function scopeDepart($query)
    {
        return $query->where('type', 'depart');
    }

    public function scopeDepots($query)
    {
        return $query->where('type', 'depot');
    }

    public function scopeDestinations($query)
    {
        return $query->where('type', 'destination');
    }

    public function scopeActif($query)
    {
        return $query->where('actif', true);
    }

    public function scopeInactif($query)
    {
        return $query->where('actif', false);
    }

    public function scopeDestinationsEtDepots($query)
    {
        return $query->whereIn('type', ['destination', 'depot']);
    }

    public function scopeParRegion($query, $region)
    {
        return $query->where('region', $region);
    }
}
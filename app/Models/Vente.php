<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vente extends Model
{
    protected $fillable = [
        'produit_id', 
        'client',
        'poids',
        'prix_unitaire',
        'prix',
        'date',
        'date_livraison',
        'vehicule_id',
        'observations',
        'statut'
    ];

    protected $dates = ['date', 'date_livraison'];

    public function produit()
    {
        return $this->belongsTo(Produit::class);
    }

    public function vehicule()
    {
        return $this->belongsTo(Vehicule::class);
    }
}
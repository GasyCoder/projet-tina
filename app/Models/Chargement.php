<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chargement extends Model
{
    protected $fillable = [
        'voyage_id',
        'reference',
        'chargeur_nom',
        'chargeur_contact',
        'proprietaire_nom',
        'proprietaire_contact',
        'produit_id',
        'sacs_pleins_depart',
        'sacs_demi_depart',
        'poids_depart_kg',
        'observation'
    ];

    // Relations
    public function voyage()
    {
        return $this->belongsTo(Voyage::class);
    }

    public function produit()
    {
        return $this->belongsTo(Produit::class);
    }

    public function dechargements()
    {
        return $this->hasMany(Dechargement::class);
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
}

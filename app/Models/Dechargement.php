<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dechargement extends Model
{
    protected $fillable = [
        'voyage_id',
        'chargement_id', // ← NOUVELLE RELATION
        'reference',
        'type',
        'interlocuteur_nom',
        'interlocuteur_contact',
        'pointeur_nom',
        'pointeur_contact',
        'lieu_livraison_id',
        'sacs_pleins_arrivee',
        'sacs_demi_arrivee',
        'poids_arrivee_kg',
        'prix_unitaire_mga',
        'montant_total_mga',
        'paiement_mga',
        'reste_mga',
        'statut_commercial',
        'observation'
    ];

    // Relations
    public function voyage()
    {
        return $this->belongsTo(Voyage::class);
    }

    public function chargement()
    {
        return $this->belongsTo(Chargement::class);
    }

    public function lieuLivraison()
    {
        return $this->belongsTo(Lieu::class, 'lieu_livraison_id');
    }

    // Accesseurs calculés depuis le chargement
    public function getProprietaireNomAttribute()
    {
        return $this->chargement->proprietaire_nom ?? 'N/A';
    }

    public function getProduitAttribute()
    {
        return $this->chargement->produit ?? null;
    }

    public function getPoidsDepertKgAttribute()
    {
        return $this->chargement->poids_depart_kg ?? 0;
    }

    public function getEcartPoidsAttribute()
    {
        return $this->getPoidsDepertKgAttribute() - ($this->poids_arrivee_kg ?? 0);
    }

    public function getPourcentagePerteAttribute()
    {
        $poidsDepart = $this->getPoidsDepertKgAttribute();
        if ($poidsDepart == 0) return 0;
        return ($this->getEcartPoidsAttribute() / $poidsDepart) * 100;
    }
}
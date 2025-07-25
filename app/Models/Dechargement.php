<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dechargement extends Model
{
    use HasFactory;

    protected $fillable = [
        'voyage_id',
        'chargement_id',
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

    protected $casts = [
        'poids_arrivee_kg' => 'decimal:2',
        'prix_unitaire_mga' => 'decimal:2',
        'montant_total_mga' => 'decimal:2',
        'paiement_mga' => 'decimal:2',
        'reste_mga' => 'decimal:2'
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

    // Relation correcte en camelCase (convention Laravel)
    public function lieuLivraison()
    {
        return $this->belongsTo(Lieu::class, 'lieu_livraison_id');
    }

    // Alias en snake_case pour éviter l’erreur “Call to undefined relationship [lieu_livraison]”
    public function lieu_livraison()
    {
        return $this->lieuLivraison();
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

    public function getPoidsDepartKgAttribute()
    {
        return $this->chargement->poids_depart_kg ?? 0;
    }

    public function getEcartPoidsAttribute()
    {
        return $this->getPoidsDepartKgAttribute() - ($this->poids_arrivee_kg ?? 0);
    }

    public function getPourcentagePerteAttribute()
    {
        $poidsDepart = $this->getPoidsDepartKgAttribute();
        if ($poidsDepart == 0) return 0;
        return ($this->getEcartPoidsAttribute() / $poidsDepart) * 100;
    }

    // Scopes
    public function scopeParType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeVentes($query)
    {
        return $query->where('type', 'vente');
    }

    public function scopeAvecReste($query)
    {
        return $query->where('reste_mga', '>', 0);
    }
}

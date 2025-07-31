<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockDepot extends Model
{
    use HasFactory;

    protected $fillable = [
        'date_entree',
        'origine',
        'produit_id',
        'depot_id',
        'proprietaire_id',
        'sacs_pleins',
        'sacs_demi',
        'poids_entree_kg',
        'date_sortie',
        'vehicule_sortie_id',
        'poids_sortie_kg',
        'reste_kg',
        'statut',
        'prix_marche_actuel_mga',
        'decision_proprietaire',
        'observation'
    ];

    protected $casts = [
        'date_entree' => 'date',
        'date_sortie' => 'date',
        'poids_entree_kg' => 'decimal:2',
        'poids_sortie_kg' => 'decimal:2',
        'reste_kg' => 'decimal:2',
        'prix_marche_actuel_mga' => 'decimal:2'
    ];

    // Relations
    public function produit()
    {
        return $this->belongsTo(Produit::class);
    }

    public function depot()
    {
        return $this->belongsTo(Lieu::class, 'depot_id');
    }

    public function proprietaire()
    {
        return $this->belongsTo(User::class, 'proprietaire_id');
    }

    public function vehiculeSortie()
    {
        return $this->belongsTo(Vehicule::class, 'vehicule_sortie_id');
    }

    public function transferts()
    {
        return $this->hasMany(TransfertStock::class, 'stock_origine_id');
    }

    // Scopes
    public function scopeEnStock($query)
    {
        return $query->where('statut', 'en_stock');
    }

    public function scopeParProprietaire($query, $proprietaireId)
    {
        return $query->where('proprietaire_id', $proprietaireId);
    }

    
}
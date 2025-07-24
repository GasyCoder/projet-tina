<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produit extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'variete',
        'unite',
        'poids_moyen_sac_kg',
        'prix_reference_mga',
        'description',
        'actif'
    ];

    protected $casts = [
        'poids_moyen_sac_kg' => 'decimal:2',
        'prix_reference_mga' => 'decimal:2',
        'actif' => 'boolean'
    ];

    // Relations
    public function chargements()
    {
        return $this->hasMany(Chargement::class);
    }

    public function dechargements()
    {
        return $this->hasMany(Dechargement::class);
    }

    public function stockDepots()
    {
        return $this->hasMany(StockDepot::class);
    }

    public function prixMarche()
    {
        return $this->hasMany(PrixMarche::class);
    }

    // Accesseurs
    public function getPrixReferenceMgaFormattedAttribute()
    {
        return number_format($this->prix_reference_mga, 0, ',', ' ') . ' MGA';
    }

    public function getNomCompletAttribute()
    {
        return $this->nom . ($this->variete ? ' (' . $this->variete . ')' : '');
    }

    // Scopes
    public function scopeActif($query)
    {
        return $query->where('actif', true);
    }

    public function scopeParNom($query, $nom)
    {
        return $query->where('nom', 'like', '%' . $nom . '%');
    }
}
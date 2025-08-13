<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;



class SeuilAlerte extends Model
{
    use HasFactory;

    protected $table = 'seuils_alertes';

    protected $fillable = [
        'produit_id',
        'depot_id',
        'seuil_stock_minimum_kg',
        'seuil_stock_maximum_kg',
        'stock_securite_kg',
        'stock_alerte_kg',
        'jours_avant_peremption',
        'alerte_stock_bas_active',
        'alerte_stock_zero_active',
        'alerte_peremption_active',
        'alerte_surstock_active',
        'destinataires_alertes'
    ];

    protected $casts = [
        'seuil_stock_minimum_kg' => 'decimal:2',
        'seuil_stock_maximum_kg' => 'decimal:2',
        'stock_securite_kg' => 'decimal:2',
        'stock_alerte_kg' => 'decimal:2',
        'jours_avant_peremption' => 'integer',
        'alerte_stock_bas_active' => 'boolean',
        'alerte_stock_zero_active' => 'boolean',
        'alerte_peremption_active' => 'boolean',
        'alerte_surstock_active' => 'boolean',
        'destinataires_alertes' => 'array'
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

    // Méthodes métier
    public static function obtenirOuCreer($produitId, $depotId)
    {
        return static::firstOrCreate(
            ['produit_id' => $produitId, 'depot_id' => $depotId],
            [
                'seuil_stock_minimum_kg' => 100,
                'stock_alerte_kg' => 50,
                'jours_avant_peremption' => 30,
                'alerte_stock_bas_active' => true,
                'alerte_stock_zero_active' => true,
                'alerte_peremption_active' => true
            ]
        );
    }
}
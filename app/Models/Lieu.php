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
        'actif',
    ];

    protected $casts = [
        'actif' => 'boolean',
    ];

    /** Types autorisés (alignés avec la migration) */
    public const TYPE_ORIGINE  = 'origine';
    public const TYPE_DEPOT    = 'depot';
    public const TYPE_MAGASIN  = 'magasin';
    public const TYPE_BOUTIQUE = 'boutique';

    public static function types(): array
    {
        return [
            self::TYPE_ORIGINE,
            self::TYPE_DEPOT,
            self::TYPE_MAGASIN,
            self::TYPE_BOUTIQUE,
        ];
    }

    /* ================== Relations ================== */

    // Ex: un voyage a un lieu de départ (depart_id) qui pointe vers lieux.id
    public function voyagesDepart()
    {
        return $this->hasMany(Voyage::class, 'depart_id');
    }

    // Ex: un déchargement a un lieu de livraison (lieu_livraison_id) vers lieux.id
    public function dechargements()
    {
        return $this->hasMany(Dechargement::class, 'lieu_livraison_id');
    }

    /* ================== Scopes ================== */

    // Aligné avec l'enum: "origine"
    public function scopeOrigines($query)
    {
        return $query->where('type', self::TYPE_ORIGINE);
    }

    public function scopeDepots($query)
    {
        return $query->where('type', self::TYPE_DEPOT);
    }

    public function scopeMagasins($query)
    {
        return $query->where('type', self::TYPE_MAGASIN);
    }

    public function scopeBoutiques($query)
    {
        return $query->where('type', self::TYPE_BOUTIQUE);
    }

    // "Destinations" = magasins + boutiques
    public function scopeDestinations($query)
    {
        return $query->whereIn('type', [self::TYPE_MAGASIN, self::TYPE_BOUTIQUE]);
    }

    public function scopeActif($query)
    {
        return $query->where('actif', true);
    }

    public function scopeInactif($query)
    {
        return $query->where('actif', false);
    }

    // Compatibilité arrière (si ton code existant appelle encore ces noms)
    public function scopeDepart($query) // ancien nom → map vers "origines"
    {
        return $this->scopeOrigines($query);
    }

    public function scopeDestinationsEtDepots($query) // ancien nom → map vers destinations + depot
    {
        return $query->whereIn('type', [
            self::TYPE_MAGASIN,
            self::TYPE_BOUTIQUE,
            self::TYPE_DEPOT,
        ]);
    }

    public function scopeParRegion($query, $region)
    {
        return $query->where('region', $region);
    }
}

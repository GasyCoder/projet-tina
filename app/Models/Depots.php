<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Depots extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'numero_mouvement',
        'type_mouvement',
        'date_mouvement',
        'produit_id',
        'depot_id',
        'origine_mouvement',
        'destination_mouvement',
        'bon_commande_id',
        'numero_bl',
        'quantite_kg',
        'sacs_pleins',
        'sacs_demi',
        'stock_avant_kg',
        'stock_apres_kg',
        'prix_unitaire_achat_mga',
        'prix_unitaire_vente_mga',
        'valeur_stock_mga',
        'proprietaire_id',
        'type_proprietaire',
        'commission_taux',
        'zone_stockage',
        'emplacement_specifique',
        'temperature_stockage',
        'humidite_stockage',
        'date_peremption',
        'qualite_produit',
        'observations_qualite',
        'controle_qualite_fait',
        'controleur_qualite_id',
        'statut_stock',
        'statut_mouvement',
        'seuil_alerte_kg',
        'alerte_stock_bas',
        'alerte_peremption',
        'user_saisie_id',
        'user_validation_id',
        'date_validation',
        'observations',
        'photos_stock',
        'documents_associes'
    ];

    protected $casts = [
        'date_mouvement' => 'date',
        'date_peremption' => 'date',
        'date_validation' => 'datetime',
        'quantite_kg' => 'decimal:2',
        'stock_avant_kg' => 'decimal:2',
        'stock_apres_kg' => 'decimal:2',
        'prix_unitaire_achat_mga' => 'decimal:2',
        'prix_unitaire_vente_mga' => 'decimal:2',
        'valeur_stock_mga' => 'decimal:2',
        'commission_taux' => 'decimal:2',
        'temperature_stockage' => 'decimal:2',
        'humidite_stockage' => 'decimal:2',
        'seuil_alerte_kg' => 'decimal:2',
        'controle_qualite_fait' => 'boolean',
        'alerte_stock_bas' => 'boolean',
        'alerte_peremption' => 'boolean',
        'photos_stock' => 'array',
        'documents_associes' => 'array',
        'sacs_pleins' => 'integer',
        'sacs_demi' => 'integer'
    ];

    protected $appends = [
        'statut_badge',
        'type_mouvement_badge',
        'total_sacs',
        'jours_avant_peremption'
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

    public function controleurQualite()
    {
        return $this->belongsTo(User::class, 'controleur_qualite_id');
    }

    public function userSaisie()
    {
        return $this->belongsTo(User::class, 'user_saisie_id');
    }

    public function userValidation()
    {
        return $this->belongsTo(User::class, 'user_validation_id');
    }

    public function transfertsOrigine()
    {
        return $this->hasMany(TransfertDetail::class, 'stock_origine_id');
    }

    public function seuilAlertes()
    {
        return $this->hasOne(SeuilAlerte::class, ['produit_id', 'depot_id'], ['produit_id', 'depot_id']);
    }

    // Accesseurs
    public function getStatutBadgeAttribute()
    {
        return match ($this->statut_stock) {
            'actif' => ['class' => 'bg-green-100 text-green-800', 'text' => 'Actif'],
            'reserve' => ['class' => 'bg-blue-100 text-blue-800', 'text' => 'Réservé'],
            'quarantaine' => ['class' => 'bg-orange-100 text-orange-800', 'text' => 'Quarantaine'],
            'perime' => ['class' => 'bg-red-100 text-red-800', 'text' => 'Périmé'],
            'detruit' => ['class' => 'bg-gray-100 text-gray-800', 'text' => 'Détruit'],
            default => ['class' => 'bg-gray-100 text-gray-800', 'text' => 'Inconnu']
        };
    }

    public function getTypeMouvementBadgeAttribute()
    {
        return match ($this->type_mouvement) {
            'entree' => ['class' => 'bg-green-100 text-green-800', 'text' => '↗ Entrée'],
            'sortie' => ['class' => 'bg-red-100 text-red-800', 'text' => '↙ Sortie'],
            'ajustement' => ['class' => 'bg-yellow-100 text-yellow-800', 'text' => '⚖ Ajustement'],
            'transfert_in' => ['class' => 'bg-blue-100 text-blue-800', 'text' => '← Transfert In'],
            'transfert_out' => ['class' => 'bg-purple-100 text-purple-800', 'text' => '→ Transfert Out'],
            default => ['class' => 'bg-gray-100 text-gray-800', 'text' => 'Inconnu']
        };
    }

    public function getTotalSacsAttribute()
    {
        return $this->sacs_pleins + ($this->sacs_demi * 0.5);
    }

    public function getJoursAvantPeremptionAttribute()
    {
        if (!$this->date_peremption)
            return null;
        return now()->diffInDays($this->date_peremption, false);
    }

    // Scopes
    public function scopeEnStock($query)
    {
        return $query->where('statut_stock', 'actif');
    }

    public function scopeStockBas($query)
    {
        return $query->where('alerte_stock_bas', true);
    }

    public function scopePeremptionProche($query, $jours = 30)
    {
        return $query->where('date_peremption', '<=', now()->addDays($jours))
            ->where('statut_stock', 'actif');
    }

    public function scopeParDepot($query, $depotId)
    {
        return $query->where('depot_id', $depotId);
    }

    public function scopeParProduit($query, $produitId)
    {
        return $query->where('produit_id', $produitId);
    }

    // Méthodes métier statiques
    public static function getStockActuel($produitId, $depotId)
    {
        return static::where('produit_id', $produitId)
            ->where('depot_id', $depotId)
            ->where('statut_stock', 'actif')
            ->sum('quantite_kg');
    }

    public static function ajouterStock($data)
    {
        $stockAvant = static::getStockActuel($data['produit_id'], $data['depot_id']);

        $stock = static::create(array_merge($data, [
            'type_mouvement' => 'entree',
            'stock_avant_kg' => $stockAvant,
            'stock_apres_kg' => $stockAvant + $data['quantite_kg'],
            'statut_mouvement' => 'valide',
            'user_saisie_id' => auth()->id()
        ]));

        $stock->verifierAlertes();
        return $stock;
    }

    public static function retirerStock($produitId, $depotId, $quantite, $motif = 'sortie')
    {
        $stockAvant = static::getStockActuel($produitId, $depotId);

        if ($stockAvant < $quantite) {
            throw new \Exception('Stock insuffisant');
        }

        $stock = static::create([
            'type_mouvement' => 'sortie',
            'produit_id' => $produitId,
            'depot_id' => $depotId,
            'quantite_kg' => -$quantite,
            'stock_avant_kg' => $stockAvant,
            'stock_apres_kg' => $stockAvant - $quantite,
            'observations' => $motif,
            'statut_mouvement' => 'valide',
            'user_saisie_id' => auth()->id(),
            'date_mouvement' => now()
        ]);

        $stock->verifierAlertes();
        return $stock;
    }

    public static function ajouterRetour(Retour $retour)
    {
        return static::ajouterStock([
            'produit_id' => $retour->produit_id,
            'depot_id' => $retour->lieu_stockage_id,
            'quantite_kg' => $retour->quantite_retour_kg,
            'sacs_pleins' => $retour->sacs_pleins_retour,
            'sacs_demi' => $retour->sacs_demi_retour,
            'origine_mouvement' => 'retour_client',
            'observations' => 'Retour N°' . $retour->numero_retour,
            'qualite_produit' => $retour->etat_produit,
            'date_mouvement' => $retour->date_retour
        ]);
    }

    public function verifierAlertes()
    {
        $seuil = $this->seuilAlertes;
        if (!$seuil)
            return;

        $stockActuel = static::getStockActuel($this->produit_id, $this->depot_id);

        // Alerte stock bas
        if ($stockActuel <= $seuil->seuil_stock_minimum_kg && $seuil->alerte_stock_bas_active) {
            AlerteStock::creerAlerte('stock_bas', $this, $stockActuel, $seuil->seuil_stock_minimum_kg);
        }

        // Alerte péremption
        if ($this->date_peremption && $seuil->alerte_peremption_active) {
            $joursRestants = now()->diffInDays($this->date_peremption, false);
            if ($joursRestants <= $seuil->jours_avant_peremption) {
                AlerteStock::creerAlerte('peremption_proche', $this, $joursRestants, $seuil->jours_avant_peremption);
            }
        }
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (!$model->numero_mouvement) {
                $count = static::whereDate('date_mouvement', $model->date_mouvement ?? today())->count() + 1;
                $model->numero_mouvement = 'MVT' . date('Ymd') . str_pad($count, 4, '0', STR_PAD_LEFT);
            }
        });
    }
}
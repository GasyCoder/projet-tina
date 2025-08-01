<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Chargement extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $fillable = [
        'voyage_id',
        'date',
        'reference',
        'chargeur_nom',
        'chargeur_contact',
        'depart_id',
        'proprietaire_nom',
        'proprietaire_contact',
        'produit_id',
        'sacs_pleins_depart',
        'sacs_demi_depart',
        'poids_depart_kg',
        'observation'
    ];

    protected $casts = [
        'date' => 'date',
        'poids_depart_kg' => 'decimal:2'
    ];

    // Relations
    public function voyage()
    {
        return $this->belongsTo(Voyage::class);
    }

    public function depart()
    {
        return $this->belongsTo(Lieu::class, 'depart_id');
    }


    public function produit()
    {
        return $this->belongsTo(Produit::class);
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

    /**
     * Relation : Un chargement peut avoir plusieurs déchargements
     */
    public function dechargements()
    {
        return $this->hasMany(Dechargement::class, 'chargement_id');
    }

    /**
     * Vérifier si le chargement a déjà un déchargement
     */
    public function hasDechargement()
    {
        return $this->dechargements()->exists();
    }

    /**
     * Obtenir le premier déchargement
     */
    public function firstDechargement()
    {
        return $this->dechargements()->first();
    }


    /**
     * Scope pour les chargements disponibles (non utilisés dans des transactions actives)
     */
    public function scopeDisponibles($query)
    {
        return $query->whereDoesntHave('transactions', function($q) {
            $q->where('statut', '!=', 'annule');
        });
    }

    /**
     * Relation avec les transactions
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'chargement_id');
    }


    // ✅ NOUVEAU : Events pour gérer automatiquement le stock
    protected static function boot()
    {
        parent::boot();

        static::created(function ($chargement) {
            // Le stock est déjà géré dans VoyageShow::saveChargement()
            // Mais on peut ajouter des logs supplémentaires ici
            \Illuminate\Support\Facades\Log::info('Chargement created event', [
                'chargement_id' => $chargement->id,
                'produit_id' => $chargement->produit_id,
                'poids' => $chargement->poids_depart_kg
            ]);
        });

        static::updated(function ($chargement) {
            // Le stock est déjà géré dans VoyageShow::saveChargement()
            \Illuminate\Support\Facades\Log::info('Chargement updated event', [
                'chargement_id' => $chargement->id,
                'produit_id' => $chargement->produit_id,
                'poids' => $chargement->poids_depart_kg
            ]);
        });

        static::deleting(function ($chargement) {
            // Le stock est déjà géré dans VoyageShow::deleteChargement()
            \Illuminate\Support\Facades\Log::info('Chargement deleting event', [
                'chargement_id' => $chargement->id,
                'produit_id' => $chargement->produit_id,
                'poids' => $chargement->poids_depart_kg
            ]);
        });
    }

    // ✅ NOUVEAU : Méthodes utilitaires pour la gestion du stock
    
    /**
     * Calcule la quantité équivalente selon l'unité du produit
     */
    public function getQuantiteEquivalenteAttribute()
    {
        if (!$this->produit) {
            return $this->poids_depart_kg;
        }

        $poids = floatval($this->poids_depart_kg);
        
        switch ($this->produit->unite) {
            case 'sacs':
                $poidsMoyenSac = $this->produit->poids_moyen_sac_kg_max > 0 ? $this->produit->poids_moyen_sac_kg_max : 120;
                return $poids / $poidsMoyenSac;
                
            case 'tonnes':
                return $poids / 1000;
                
            default:
                return $poids;
        }
    }

    /**
     * Vérifie si le chargement peut être créé avec le stock disponible
     */
    public function peutEtreCharge()
    {
        if (!$this->produit) {
            return false;
        }

        $quantiteNecessaire = $this->quantite_equivalente;
        $stockDisponible = floatval($this->produit->qte_variable);

        return $stockDisponible >= $quantiteNecessaire;
    }

    /**
     * Obtient les informations de stock pour ce chargement
     */
    public function getInfoStockAttribute()
    {
        if (!$this->produit) {
            return null;
        }

        return [
            'produit_nom' => $this->produit->nom_complet,
            'produit_unite' => $this->produit->unite,
            'poids_charge' => $this->poids_depart_kg,
            'quantite_equivalente' => $this->quantite_equivalente,
            'stock_produit_avant' => $this->produit->qte_variable,
            'stock_produit_apres' => $this->produit->qte_variable - $this->quantite_equivalente,
        ];
    }

}

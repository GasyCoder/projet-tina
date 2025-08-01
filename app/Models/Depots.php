<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Depots extends Model
{
    use HasFactory;

    protected $table = 'depots'; // Spécifier le nom de la table

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
        'prix_marche_actuel_mga' => 'decimal:2',
        'sacs_pleins' => 'integer',
        'sacs_demi' => 'integer'
    ];

    // Relations
    public function produit()
    {
        return $this->belongsTo(Produit::class);
    }
    // Dans ton modèle
    public function lieu()
    {
        return $this->belongsTo(Lieu::class, 'lieu_livraison_id');
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

    public function scopeRecherche($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('origine', 'like', '%' . $search . '%')
                ->orWhere('observation', 'like', '%' . $search . '%')
                ->orWhere('decision_proprietaire', 'like', '%' . $search . '%')
                ->orWhereHas('produit', function ($query) use ($search) {
                    $query->where('nom', 'like', '%' . $search . '%');
                })
                ->orWhereHas('proprietaire', function ($query) use ($search) {
                    $query->where('name', 'like', '%' . $search . '%');
                });
        });
    }

    // Mutateurs
    public function setPoidsEntreeKgAttribute($value)
    {
        $this->attributes['poids_entree_kg'] = $value;
        // Calculer automatiquement le reste si pas de sortie
        if (!$this->poids_sortie_kg) {
            $this->attributes['reste_kg'] = $value;
        }
    }

    public function setPoidsSortieKgAttribute($value)
    {
        $this->attributes['poids_sortie_kg'] = $value;
        // Recalculer le reste
        $this->attributes['reste_kg'] = $this->poids_entree_kg - $value;

        // Mettre à jour le statut
        if ($this->reste_kg <= 0) {
            $this->attributes['statut'] = 'sorti';
        } else {
            $this->attributes['statut'] = 'en_stock';
        }
    }

    // Accesseurs
    public function getValeurStockAttribute()
    {
        return $this->reste_kg * $this->prix_marche_actuel_mga;
    }

    public function getStatutBadgeAttribute()
    {
        return match ($this->statut) {
            'en_stock' => ['class' => 'bg-green-100 text-green-800', 'text' => 'En stock'],
            'sorti' => ['class' => 'bg-gray-100 text-gray-800', 'text' => 'Sorti'],
            'en_attente' => ['class' => 'bg-yellow-100 text-yellow-800', 'text' => 'En attente'],
            default => ['class' => 'bg-gray-100 text-gray-800', 'text' => 'Inconnu']
        };
    }

    // Méthodes utilitaires
    public function peutSortir($quantite = null)
    {
        if ($this->statut !== 'en_stock') {
            return false;
        }

        if ($quantite === null) {
            return $this->reste_kg > 0;
        }

        return $this->reste_kg >= $quantite;
    }

    public function calculerReste()
    {
        $this->reste_kg = $this->poids_entree_kg - ($this->poids_sortie_kg ?? 0);
        $this->save();
        return $this->reste_kg;
    }

    // Événements du modèle
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($depot) {
            if (!$depot->statut) {
                $depot->statut = 'en_stock';
            }
            if (!$depot->reste_kg) {
                $depot->reste_kg = $depot->poids_entree_kg;
            }
        });

        static::updating(function ($depot) {
            // Recalculer le reste si nécessaire
            if ($depot->isDirty(['poids_entree_kg', 'poids_sortie_kg'])) {
                $depot->reste_kg = $depot->poids_entree_kg - ($depot->poids_sortie_kg ?? 0);

                // Mettre à jour le statut
                if ($depot->reste_kg <= 0) {
                    $depot->statut = 'sorti';
                } elseif ($depot->reste_kg < $depot->poids_entree_kg) {
                    $depot->statut = 'partiellement_sorti';
                } else {
                    $depot->statut = 'en_stock';
                }
            }
        });
    }
}
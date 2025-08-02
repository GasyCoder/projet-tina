<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

/**
 * MODÈLE PRINCIPAL - OPÉRATION STOCK
 * Unifie toutes les opérations de stock dans la table dechargements
 */
class OperationStock extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'dechargements';

    protected $fillable = [
        'type',
        'numero_sequence',
        'reference',
        'voyage_id',
        'chargement_id',
        'date',
        'interlocuteur_nom',
        'interlocuteur_contact',
        'pointeur_nom',
        'lieu_livraison_id',
        'poids_arrivee_kg',
        'sacs_pleins_arrivee',
        'sacs_demi_arrivee',
        'prix_unitaire_mga',
        'montant_total_mga',
        'paiement_mga',
        'reste_mga',
        'statut_commercial',
        'statut_stock',
        'priorite',
        'observation',
        'tags',
        'metadata'
    ];

    protected $casts = [
        'date' => 'datetime',
        'poids_arrivee_kg' => 'decimal:2',
        'prix_unitaire_mga' => 'decimal:2',
        'montant_total_mga' => 'decimal:2',
        'paiement_mga' => 'decimal:2',
        'reste_mga' => 'decimal:2',
        'tags' => 'array',
        'metadata' => 'array',
        'priorite' => 'integer'
    ];

    protected $appends = ['type_label', 'priorite_label', 'statut_badge'];

    // Relations
    public function produit()
    {
        return $this->hasOneThrough(
            Produit::class,
            Chargement::class,
            'id',
            'id',
            'chargement_id',
            'produit_id'
        );
    }

    public function chargement()
    {
        return $this->belongsTo(Chargement::class);
    }

    public function voyage()
    {
        return $this->belongsTo(Voyage::class);
    }

    public function lieuLivraison()
    {
        return $this->belongsTo(Lieu::class, 'lieu_livraison_id');
    }

    public function utilisateur()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Scopes par type d'opération
    public function scopeVentes($query)
    {
        return $query->where('type', 'vente');
    }

    public function scopeRetours($query)
    {
        return $query->where('type', 'retour');
    }

    public function scopeDepots($query)
    {
        return $query->where('type', 'depot');
    }

    // Scopes par priorité
    public function scopeHautePriorite($query)
    {
        return $query->where('priorite', '>=', 3);
    }

    public function scopeUrgent($query)
    {
        return $query->where('priorite', 4);
    }

    // Scopes par statut
    public function scopeEnAttente($query)
    {
        return $query->where('statut_stock', 'en_attente');
    }

    public function scopeConfirme($query)
    {
        return $query->where('statut_stock', 'confirme');
    }

    // Accesseurs
    public function getTypeLabelAttribute()
    {
        return match ($this->type) {
            'vente' => '🛒 Vente',
            'retour' => '↩️ Retour',
            'depot' => '🏬 Dépôt',
            'transfert' => '🔁 Transfert',
            default => '❓ Inconnu'
        };
    }

    public function getPrioriteLabelAttribute()
    {
        return match ($this->priorite) {
            4 => '🔴 Urgente',
            3 => '🟠 Haute',
            2 => '🟡 Normale',
            1 => '🔵 Basse',
            default => '⚪ Non définie'
        };
    }

    public function getStatutBadgeAttribute()
    {
        return match ($this->statut_stock) {
            'en_attente' => ['class' => 'bg-yellow-100 text-yellow-800', 'text' => 'En attente'],
            'confirme' => ['class' => 'bg-green-100 text-green-800', 'text' => 'Confirmé'],
            'livre' => ['class' => 'bg-blue-100 text-blue-800', 'text' => 'Livré'],
            'annule' => ['class' => 'bg-red-100 text-red-800', 'text' => 'Annulé'],
            default => ['class' => 'bg-gray-100 text-gray-800', 'text' => 'Inconnu']
        };
    }

    // Méthodes métier
    public function genererNumeroSequence()
    {
        $prefix = strtoupper(substr($this->type, 0, 3));
        $date = $this->date->format('Ymd');
        $count = static::where('type', $this->type)
            ->whereDate('date', $this->date)
            ->count() + 1;

        return $prefix . '-' . $date . '-' . str_pad($count, 3, '0', STR_PAD_LEFT);
    }

    public function calculerImpactStock()
    {
        return match ($this->type) {
            'vente', 'transfert_sortie' => -$this->poids_arrivee_kg,
            'retour', 'depot', 'transfert_entree' => $this->poids_arrivee_kg,
            default => 0
        };
    }

    public function estPayeIntegralement()
    {
        return $this->type === 'vente' && $this->reste_mga <= 0;
    }

    public function peutEtreModifie()
    {
        return in_array($this->statut_stock, ['en_attente', 'confirme']);
    }

    // Boot method pour auto-génération
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (!$model->numero_sequence) {
                $model->numero_sequence = $model->genererNumeroSequence();
            }

            if (!$model->priorite) {
                $model->priorite = match ($model->type) {
                    'vente' => 4, // Urgente
                    'transfert' => 3, // Haute
                    'retour' => 2, // Normale
                    'depot' => 1, // Basse
                    default => 2
                };
            }
        });

        static::created(function ($model) {
            // Enregistrer dans l'historique
            HistoriqueStock::enregistrerMouvement($model);
        });
    }
}


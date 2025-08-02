<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TransfertStock extends Model
{

    use HasFactory, SoftDeletes;

    protected $fillable = [
        'stock_origine_id',
        'proprietaire_id',
        'sacs_pleins',
        'sacs_demi',
        'poids_kg',
        'motif',
        'statut',
        'cout_transport_mga',
        'observation',
        'numero_transfert',
        'depot_origine_id',
        'depot_destination_id',
        'vehicule_id',
        'chauffeur_nom',
        'chauffeur_contact',
        'date_prevue',
        'date_transfert',
        'date_depart',
        'date_arrivee',
        'produits',
        'priorite',
        'progression',
        'user_creation_id',
        'user_validation_id'

    ];

    protected $casts = [
        'date_transfert' => 'date',
        'poids_kg' => 'decimal:2',
        'cout_transport_mga' => 'decimal:2',
        'date_prevue' => 'datetime',
        'date_depart' => 'datetime',
        'date_arrivee' => 'datetime',
        'produits' => 'array',
        'progression' => 'integer'
    ];
    protected $appends = ['statut_badge', 'priorite_label', 'duree_estimee'];

    // Relations
    public function stockOrigine()
    {
        return $this->belongsTo(Depots::class, 'stock_origine_id');
    }

    public function depotOrigine()
    {
        return $this->belongsTo(Lieu::class, 'depot_origine_id');
    }

    public function userCreation()
    {
        return $this->belongsTo(User::class, 'user_creation_id');
    }

    public function userValidation()
    {
        return $this->belongsTo(User::class, 'user_validation_id');
    }
    public function depotDestination()
    {
        return $this->belongsTo(Lieu::class, 'depot_destination_id');
    }

    public function proprietaire()
    {
        return $this->belongsTo(User::class, 'proprietaire_id');
    }

    public function vehicule()
    {
        return $this->belongsTo(Vehicule::class);
    }

    // Scopes
    public function scopeEnCours($query)
    {
        return $query->where('statut', 'en_cours');
    }

    public function scopeTermine($query)
    {
        return $query->where('statut', 'termine');
    }

    // Scopes
    public function scopeEnPreparation($query)
    {
        return $query->where('statut', 'en_preparation');
    }

    public function scopeEnTransit($query)
    {
        return $query->where('statut', 'en_transit');
    }

    public function scopeUrgent($query)
    {
        return $query->where('priorite', 'urgente');
    }

    // Accesseurs
    public function getStatutBadgeAttribute()
    {
        return match ($this->statut) {
            'en_preparation' => ['class' => 'bg-yellow-100 text-yellow-800', 'text' => 'En préparation'],
            'en_transit' => ['class' => 'bg-blue-100 text-blue-800', 'text' => 'En transit'],
            'recu' => ['class' => 'bg-green-100 text-green-800', 'text' => 'Reçu'],
            'annule' => ['class' => 'bg-red-100 text-red-800', 'text' => 'Annulé'],
            default => ['class' => 'bg-gray-100 text-gray-800', 'text' => 'Inconnu']
        };
    }

    public function getPrioriteLabelAttribute()
    {
        return match ($this->priorite) {
            'urgente' => '🔴 Urgente',
            'haute' => '🟠 Haute',
            'normale' => '🟡 Normale',
            'basse' => '🔵 Basse',
            default => '⚪ Non définie'
        };
    }


    // Méthodes métier
    public function demarrer()
    {
        $this->update([
            'statut' => 'en_transit',
            'date_depart' => now(),
            'progression' => 5
        ]);
    }

    public function confirmerReception()
    {
        $this->update([
            'statut' => 'recu',
            'date_arrivee' => now(),
            'progression' => 100
        ]);

        // Créer les opérations de stock correspondantes
        $this->creerOperationsStock();
    }

    private function creerOperationsStock()
    {
        foreach ($this->produits as $produit) {
            // Sortie du dépôt origine
            OperationStock::create([
                'type' => 'transfert_sortie',
                'reference' => 'TSORT-' . $this->numero_transfert,
                'lieu_livraison_id' => $this->depot_origine_id,
                'poids_arrivee_kg' => $produit['quantite'],
                'observation' => "Transfert vers {$this->depotDestination->nom}",
                'date' => $this->date_depart
            ]);

            // Entrée au dépôt destination
            OperationStock::create([
                'type' => 'transfert_entree',
                'reference' => 'TENT-' . $this->numero_transfert,
                'lieu_livraison_id' => $this->depot_destination_id,
                'poids_arrivee_kg' => $produit['quantite'],
                'observation' => "Transfert depuis {$this->depotOrigine->nom}",
                'date' => $this->date_arrivee
            ]);
        }
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (!$model->numero_transfert) {
                $count = static::whereDate('created_at', today())->count() + 1;
                $model->numero_transfert = 'T' . date('Ymd') . str_pad($count, 3, '0', STR_PAD_LEFT);
            }
        });
    }
    public function getDureeEstimeeAttribute()
    {
        if ($this->date_depart && $this->date_arrivee) {
            return $this->date_depart->diffInHours($this->date_arrivee);
        }
        return null;
    }


}
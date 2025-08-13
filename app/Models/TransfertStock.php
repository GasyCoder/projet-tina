<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class TransfertStock extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'numero_transfert',
        'date_creation',
        'date_prevue_expedition',
        'date_prevue_reception',
        'depot_origine_id',
        'depot_destination_id',
        'responsable_origine_id',
        'responsable_destination_id',
        'vehicule_id',
        'chauffeur_nom',
        'chauffeur_contact',
        'distance_km',
        'cout_transport_prevu_mga',
        'cout_transport_reel_mga',
        'statut_transfert',
        'priorite',
        'progression_pourcent',
        'date_expedition_reelle',
        'date_reception_reelle',
        'date_validation_finale',
        'motif_transfert',
        'description_motif',
        'observations_expedition',
        'observations_reception',
        'controle_expedition_fait',
        'controle_reception_fait',
        'controleur_expedition_id',
        'controleur_reception_id',
        'assurance_souscrite',
        'numero_police_assurance',
        'valeur_assuree_mga',
        'conditions_transport',
        'temperature_controlee',
        'temperature_min',
        'temperature_max',
        'user_creation_id',
        'user_validation_id'
    ];

    protected $casts = [
        'date_creation' => 'date',
        'date_prevue_expedition' => 'date',
        'date_prevue_reception' => 'date',
        'date_expedition_reelle' => 'datetime',
        'date_reception_reelle' => 'datetime',
        'date_validation_finale' => 'datetime',
        'distance_km' => 'decimal:2',
        'cout_transport_prevu_mga' => 'decimal:2',
        'cout_transport_reel_mga' => 'decimal:2',
        'valeur_assuree_mga' => 'decimal:2',
        'temperature_min' => 'decimal:2',
        'temperature_max' => 'decimal:2',
        'progression_pourcent' => 'integer',
        'controle_expedition_fait' => 'boolean',
        'controle_reception_fait' => 'boolean',
        'assurance_souscrite' => 'boolean',
        'temperature_controlee' => 'boolean'
    ];

    protected $appends = [
        'statut_badge',
        'priorite_badge',
        'duree_prevue',
        'duree_reelle',
        'retard_jours'
    ];

    // Relations
    public function depotOrigine()
    {
        return $this->belongsTo(Lieu::class, 'depot_origine_id');
    }

    public function depotDestination()
    {
        return $this->belongsTo(Lieu::class, 'depot_destination_id');
    }

    public function responsableOrigine()
    {
        return $this->belongsTo(User::class, 'responsable_origine_id');
    }

    public function responsableDestination()
    {
        return $this->belongsTo(User::class, 'responsable_destination_id');
    }

    public function vehicule()
    {
        return $this->belongsTo(Vehicule::class);
    }

    public function controleurExpedition()
    {
        return $this->belongsTo(User::class, 'controleur_expedition_id');
    }

    public function controleurReception()
    {
        return $this->belongsTo(User::class, 'controleur_reception_id');
    }

    public function userCreation()
    {
        return $this->belongsTo(User::class, 'user_creation_id');
    }

    public function userValidation()
    {
        return $this->belongsTo(User::class, 'user_validation_id');
    }

    public function details()
    {
        return $this->hasMany(TransfertDetail::class);
    }

    // Accesseurs
    public function getStatutBadgeAttribute()
    {
        return match ($this->statut_transfert) {
            'planifie' => ['class' => 'bg-gray-100 text-gray-800', 'text' => '📋 Planifié'],
            'en_preparation' => ['class' => 'bg-yellow-100 text-yellow-800', 'text' => '⏳ En préparation'],
            'en_transit' => ['class' => 'bg-blue-100 text-blue-800', 'text' => '🚛 En transit'],
            'livre' => ['class' => 'bg-green-100 text-green-800', 'text' => '📦 Livré'],
            'receptionne' => ['class' => 'bg-purple-100 text-purple-800', 'text' => '✅ Réceptionné'],
            'annule' => ['class' => 'bg-red-100 text-red-800', 'text' => '❌ Annulé'],
            default => ['class' => 'bg-gray-100 text-gray-800', 'text' => 'Inconnu']
        };
    }

    public function getPrioriteBadgeAttribute()
    {
        return match ($this->priorite) {
            'urgente' => ['class' => 'bg-red-100 text-red-800', 'text' => '🔴 Urgente'],
            'haute' => ['class' => 'bg-orange-100 text-orange-800', 'text' => '🟠 Haute'],
            'normale' => ['class' => 'bg-green-100 text-green-800', 'text' => '🟢 Normale'],
            'basse' => ['class' => 'bg-blue-100 text-blue-800', 'text' => '🔵 Basse'],
            default => ['class' => 'bg-gray-100 text-gray-800', 'text' => '⚪ Non définie']
        };
    }

    public function getDureePrevueAttribute()
    {
        if (!$this->date_prevue_expedition || !$this->date_prevue_reception)
            return null;
        return $this->date_prevue_expedition->diffInDays($this->date_prevue_reception);
    }

    public function getDureeReelleAttribute()
    {
        if (!$this->date_expedition_reelle || !$this->date_reception_reelle)
            return null;
        return $this->date_expedition_reelle->diffInDays($this->date_reception_reelle);
    }

    public function getRetardJoursAttribute()
    {
        if (!$this->date_prevue_reception)
            return null;
        $dateReference = $this->date_reception_reelle ?? now();
        return $this->date_prevue_reception->diffInDays($dateReference, false);
    }

    // Scopes
    public function scopeEnRetard($query)
    {
        return $query->where('date_prevue_reception', '<', now())
            ->whereNotIn('statut_transfert', ['receptionne', 'annule']);
    }

    public function scopeUrgent($query)
    {
        return $query->where('priorite', 'urgente');
    }

    public function scopeEnCours($query)
    {
        return $query->whereIn('statut_transfert', ['en_preparation', 'en_transit', 'livre']);
    }

    // Méthodes métier
    public function demarrer()
    {
        $this->update([
            'statut_transfert' => 'en_transit',
            'date_expedition_reelle' => now(),
            'progression_pourcent' => 10
        ]);

        // Créer les sorties de stock pour chaque produit
        foreach ($this->details as $detail) {
            Depots::retirerStock(
                $detail->produit_id,
                $this->depot_origine_id,
                $detail->quantite_expedie_kg,
                'Transfert N°' . $this->numero_transfert
            );
        }

        HistoriqueMouvement::creerPourTransfert($this, 'expedition');
    }

    public function livrer()
    {
        $this->update([
            'statut_transfert' => 'livre',
            'progression_pourcent' => 80
        ]);
    }

    public function receptionner()
    {
        $this->update([
            'statut_transfert' => 'receptionne',
            'date_reception_reelle' => now(),
            'progression_pourcent' => 100
        ]);

        // Créer les entrées de stock pour chaque produit
        foreach ($this->details as $detail) {
            Depots::ajouterStock([
                'produit_id' => $detail->produit_id,
                'depot_id' => $this->depot_destination_id,
                'quantite_kg' => $detail->quantite_recu_kg,
                'origine_mouvement' => 'transfert',
                'observations' => 'Transfert N°' . $this->numero_transfert . ' depuis ' . $this->depotOrigine->nom,
                'date_mouvement' => now()
            ]);
        }

        HistoriqueMouvement::creerPourTransfert($this, 'reception');
    }

    public function annuler($motif)
    {
        $this->update([
            'statut_transfert' => 'annule',
            'observations_expedition' => $this->observations_expedition . ' | Annulé: ' . $motif
        ]);
    }

    public function calculerValeurTotale()
    {
        return $this->details()->sum(\DB::raw('quantite_prevue_kg * prix_unitaire_mga'));
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (!$model->numero_transfert) {
                $count = static::whereDate('date_creation', $model->date_creation ?? today())->count() + 1;
                $model->numero_transfert = 'TRF' . date('Ymd') . str_pad($count, 4, '0', STR_PAD_LEFT);
            }
        });
    }
}

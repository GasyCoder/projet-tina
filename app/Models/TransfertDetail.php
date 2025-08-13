<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class TransfertDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'transfert_id',
        'produit_id',
        'stock_origine_id',
        'quantite_prevue_kg',
        'sacs_pleins_prevus',
        'sacs_demi_prevus',
        'quantite_expedie_kg',
        'sacs_pleins_expedies',
        'sacs_demi_expedies',
        'quantite_recu_kg',
        'sacs_pleins_recus',
        'sacs_demi_recus',
        'ecart_expedition_kg',
        'ecart_reception_kg',
        'perte_transport_kg',
        'perte_transport_pourcent',
        'prix_unitaire_mga',
        'valeur_prevue_mga',
        'valeur_expedie_mga',
        'valeur_recu_mga',
        'qualite_expedition',
        'qualite_reception',
        'observations_qualite',
        'statut_detail',
        'motif_refus'
    ];

    protected $casts = [
        'quantite_prevue_kg' => 'decimal:2',
        'quantite_expedie_kg' => 'decimal:2',
        'quantite_recu_kg' => 'decimal:2',
        'ecart_expedition_kg' => 'decimal:2',
        'ecart_reception_kg' => 'decimal:2',
        'perte_transport_kg' => 'decimal:2',
        'perte_transport_pourcent' => 'decimal:2',
        'prix_unitaire_mga' => 'decimal:2',
        'valeur_prevue_mga' => 'decimal:2',
        'valeur_expedie_mga' => 'decimal:2',
        'valeur_recu_mga' => 'decimal:2',
        'sacs_pleins_prevus' => 'integer',
        'sacs_demi_prevus' => 'integer',
        'sacs_pleins_expedies' => 'integer',
        'sacs_demi_expedies' => 'integer',
        'sacs_pleins_recus' => 'integer',
        'sacs_demi_recus' => 'integer'
    ];

    protected $appends = ['statut_badge', 'total_sacs_prevus', 'total_sacs_expedies', 'total_sacs_recus'];

    // Relations
    public function transfert()
    {
        return $this->belongsTo(TransfertStock::class);
    }

    public function produit()
    {
        return $this->belongsTo(Produit::class);
    }

    public function stockOrigine()
    {
        return $this->belongsTo(Depots::class, 'stock_origine_id');
    }

    // Accesseurs
    public function getStatutBadgeAttribute()
    {
        return match ($this->statut_detail) {
            'en_attente' => ['class' => 'bg-gray-100 text-gray-800', 'text' => 'En attente'],
            'expedie' => ['class' => 'bg-blue-100 text-blue-800', 'text' => 'Expédié'],
            'en_transit' => ['class' => 'bg-yellow-100 text-yellow-800', 'text' => 'En transit'],
            'recu' => ['class' => 'bg-green-100 text-green-800', 'text' => 'Reçu'],
            'refuse' => ['class' => 'bg-red-100 text-red-800', 'text' => 'Refusé'],
            'perdu' => ['class' => 'bg-purple-100 text-purple-800', 'text' => 'Perdu'],
            default => ['class' => 'bg-gray-100 text-gray-800', 'text' => 'Inconnu']
        };
    }

    public function getTotalSacsPrevusAttribute()
    {
        return $this->sacs_pleins_prevus + ($this->sacs_demi_prevus * 0.5);
    }

    public function getTotalSacsExpediesAttribute()
    {
        return $this->sacs_pleins_expedies + ($this->sacs_demi_expedies * 0.5);
    }

    public function getTotalSacsRecusAttribute()
    {
        return $this->sacs_pleins_recus + ($this->sacs_demi_recus * 0.5);
    }

    // Méthodes pour calculer les écarts et pertes
    public function calculerEcarts()
    {
        $this->ecart_expedition_kg = $this->quantite_prevue_kg - $this->quantite_expedie_kg;
        $this->ecart_reception_kg = $this->quantite_expedie_kg - $this->quantite_recu_kg;
        $this->perte_transport_kg = $this->quantite_expedie_kg - $this->quantite_recu_kg;

        if ($this->quantite_expedie_kg > 0) {
            $this->perte_transport_pourcent = ($this->perte_transport_kg / $this->quantite_expedie_kg) * 100;
        }

        $this->valeur_expedie_mga = $this->quantite_expedie_kg * $this->prix_unitaire_mga;
        $this->valeur_recu_mga = $this->quantite_recu_kg * $this->prix_unitaire_mga;

        $this->save();
    }
}
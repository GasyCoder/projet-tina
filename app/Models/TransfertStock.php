<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransfertStock extends Model
{
    use HasFactory;

    protected $fillable = [
        'stock_origine_id',
        'depot_origine_id',
        'depot_destination_id',
        'proprietaire_id',
        'vehicule_id',
        'date_transfert',
        'sacs_pleins',
        'sacs_demi',
        'poids_kg',
        'motif',
        'statut',
        'cout_transport_mga',
        'observation'
    ];

    protected $casts = [
        'date_transfert' => 'date',
        'poids_kg' => 'decimal:2',
        'cout_transport_mga' => 'decimal:2'
    ];

    // Relations
    public function stockOrigine()
    {
        return $this->belongsTo(StockDepot::class, 'stock_origine_id');
    }

    public function depotOrigine()
    {
        return $this->belongsTo(Lieu::class, 'depot_origine_id');
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
}
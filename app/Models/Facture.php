<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Facture extends Model
{
    use HasFactory;

    protected $fillable = [
        'reference',
        'date',
        'client_nom',
        'client_id',
        'vendeur_nom',
        'vendeur_id', 
        'montant_total_mga',
        'montant_paye_mga',
        'montant_restant_mga',
        'statut',
        'date_echeance',
        'voyage_id',
        'dechargement_id',
        'observation'
    ];

    protected $casts = [
        'date' => 'date',
        'date_echeance' => 'date',
        'montant_total_mga' => 'decimal:2',
        'montant_paye_mga' => 'decimal:2',
        'montant_restant_mga' => 'decimal:2'
    ];

    // Relations
    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function vendeur()
    {
        return $this->belongsTo(User::class, 'vendeur_id');
    }

    public function voyage()
    {
        return $this->belongsTo(Voyage::class);
    }

    public function dechargement()
    {
        return $this->belongsTo(Dechargement::class);
    }

    // Accesseurs intelligents
    public function getClientDisplayAttribute()
    {
        return $this->client_nom ?: ($this->client ? $this->client->name : 'Inconnu');
    }

    public function getVendeurDisplayAttribute()
    {
        return $this->vendeur_nom ?: ($this->vendeur ? $this->vendeur->name : 'Inconnu');
    }

    // Scopes
    public function scopeImpayees($query)
    {
        return $query->where('montant_restant_mga', '>', 0);
    }

    public function scopeEchues($query)
    {
        return $query->where('date_echeance', '<', now())->where('statut', '!=', 'payee');
    }

    public function scopeParClient($query, $nom)
    {
        return $query->where(function ($q) use ($nom) {
            $q->where('client_nom', 'like', '%' . $nom . '%')
              ->orWhereHas('client', function ($subQ) use ($nom) {
                  $subQ->where('name', 'like', '%' . $nom . '%');
              });
        });
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'reference',
        'date', 
        'type',
        'from_nom',
        'from_user_id',
        'from_compte',
        'to_nom',
        'to_user_id', 
        'to_compte',
        'montant_mga',
        'objet',
        'voyage_id',
        'chargement_id',
        'dechargement_id',
        'produit_id',
        'mode_paiement',
        'statut',
        'quantite',
        'unite',
        'prix_unitaire_mga',
        'observation'
    ];

    protected $casts = [
        'date' => 'date',
        'montant_mga' => 'decimal:2',
        'quantite' => 'decimal:2',
        'prix_unitaire_mga' => 'decimal:2'
    ];

    // Relations
    public function fromUser()
    {
        return $this->belongsTo(User::class, 'from_user_id');
    }

    public function toUser()
    {
        return $this->belongsTo(User::class, 'to_user_id');
    }

    public function voyage()
    {
        return $this->belongsTo(Voyage::class);
    }

    public function chargement()
    {
        return $this->belongsTo(Chargement::class);
    }

    public function dechargement()
    {
        return $this->belongsTo(Dechargement::class);
    }

    public function produit()
    {
        return $this->belongsTo(Produit::class);
    }

    // Accesseurs intelligents (nom ou user)
    public function getFromNomDisplayAttribute()
    {
        return $this->from_nom ?: ($this->fromUser ? $this->fromUser->name : 'Inconnu');
    }

    public function getToNomDisplayAttribute()
    {
        return $this->to_nom ?: ($this->toUser ? $this->toUser->name : 'Inconnu');
    }

    public function getMontantMgaFormattedAttribute()
    {
        return number_format($this->montant_mga, 0, ',', ' ') . ' MGA';
    }

    // Scopes
    public function scopeParType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeParPersonne($query, $nom)
    {
        return $query->where(function ($q) use ($nom) {
            $q->where('from_nom', 'like', '%' . $nom . '%')
              ->orWhere('to_nom', 'like', '%' . $nom . '%')
              ->orWhereHas('fromUser', function ($subQ) use ($nom) {
                  $subQ->where('name', 'like', '%' . $nom . '%');
              })
              ->orWhereHas('toUser', function ($subQ) use ($nom) {
                  $subQ->where('name', 'like', '%' . $nom . '%');
              });
        });
    }

    public function scopeConfirmees($query)
    {
        return $query->where('statut', 'confirme');
    }

    public function scopePeriode($query, $dateDebut, $dateFin)
    {
        return $query->whereBetween('date', [$dateDebut, $dateFin]);
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Compte extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nom_proprietaire',
        'type_compte',
        'nom_compte',
        'numero_compte',
        'solde_actuel_mga',
        'derniere_transaction_id',
        'actif'
    ];

    protected $casts = [
        'solde_actuel_mga' => 'decimal:2',
        'actif' => 'boolean'
    ];

    // Relations
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function derniereTransaction()
    {
        return $this->belongsTo(Transaction::class, 'derniere_transaction_id');
    }

    // Accesseur intelligent pour le nom du propriétaire
    public function getProprietaireDisplayAttribute()
    {
        return $this->nom_proprietaire ?: ($this->user ? $this->user->name : 'Inconnu');
    }

    public function getSoldeFormattedAttribute()
    {
        return number_format($this->solde_actuel_mga, 0, ',', ' ') . ' MGA';
    }

    // Scopes
    public function scopeParType($query, $type)
    {
        return $query->where('type_compte', $type);
    }

    public function scopeActif($query)
    {
        return $query->where('actif', true);
    }

    public function scopeParProprietaire($query, $nom)
    {
        return $query->where(function ($q) use ($nom) {
            $q->where('nom_proprietaire', 'like', '%' . $nom . '%')
              ->orWhereHas('user', function ($subQ) use ($nom) {
                  $subQ->where('name', 'like', '%' . $nom . '%');
              });
        });
    }
}

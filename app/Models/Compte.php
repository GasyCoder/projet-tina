<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Compte extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'user_id',
        'nom',
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

    // Accesseurs
    public function getProprietaireDisplayAttribute()
    {
        return $this->nom_proprietaire ?: ($this->user ? $this->user->name : 'Inconnu');
    }

    public function getSoldeFormattedAttribute()
    {
        return number_format($this->solde_actuel_mga, 0, ',', ' ') . ' MGA';
    }

    // ✅ SCOPES SELON VOS VRAIS TYPES DE COMPTES
    public function scopePrincipal($query)
    {
        return $query->where('type_compte', 'principal');
    }

    public function scopeMobileMoney($query)
    {
        return $query->where('type_compte', 'mobile_money');
    }

    public function scopeBanque($query)
    {
        return $query->where('type_compte', 'banque');
    }

    public function scopeCredit($query)
    {
        return $query->where('type_compte', 'credit');
    }

    public function scopeActif($query)
    {
        return $query->where('actif', true);
    }

    public function scopeParType($query, $type)
    {
        return $query->where('type_compte', $type);
    }

    public function scopeParProprietaire($query, $nom)
    {
        return $query->where('nom_proprietaire', 'like', '%' . $nom . '%');
    }
}
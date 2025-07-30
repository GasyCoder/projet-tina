<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;


   


    // Scopes utiles
    public function scopeRevenus($query)
    {
        return $query->whereIn('type', ['vente', 'depot', 'commission']);
    }

    public function scopeDepenses($query)
    {
        return $query->whereIn('type', ['achat', 'frais', 'avance', 'paiement', 'retrait']);
    }
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
        'reference',
        'type',
        'objet',
        'montant_mga',
        'statut',
        'date',
        'voyage_id',
        'from_nom',
        'to_nom',
        'reste_a_payer',
        'observation'
    ];

    protected $casts = [
        'montant_mga' => 'decimal:2',
        'quantite' => 'decimal:2',
        'prix_unitaire_mga' => 'decimal:2',
         'date' => 'datetime',
        'reste_a_payer' => 'decimal:2',
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

    // Accesseurs
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

    // ✅ SCOPES SELON VOS VRAIS TYPES
    public function scopeAchats($query)
    {
        return $query->where('type', 'achat');
    }

    public function scopeVentes($query)
    {
        return $query->where('type', 'vente');
    }

    public function scopeTransferts($query)
    {
        return $query->where('type', 'transfert');
    }

    public function scopeFrais($query)
    {
        return $query->where('type', 'frais');
    }

    public function scopeCommissions($query)
    {
        return $query->where('type', 'commission');
    }

    public function scopePaiements($query)
    {
        return $query->where('type', 'paiement');
    }

    public function scopeAvances($query)
    {
        return $query->where('type', 'avance');
    }

    public function scopeDepots($query)
    {
        return $query->where('type', 'depot');
    }

    public function scopeRetraits($query)
    {
        return $query->where('type', 'retrait');
    }

    // ✅ SCOPES SELON VOS VRAIS STATUTS
    public function scopeConfirme($query)
    {
        return $query->where('statut', 'confirme');
    }

    public function scopeAttente($query)
    {
        return $query->where('statut', 'attente');
    }

    public function scopeAnnule($query)
    {
        return $query->where('statut', 'annule');
    }

    // ✅ SCOPES UTILES
    public function scopePeriode($query, $dateDebut, $dateFin)
    {
        return $query->whereBetween('date', [$dateDebut, $dateFin]);
    }

    public function scopeParPersonne($query, $nom)
    {
        return $query->where(function ($q) use ($nom) {
            $q->where('from_nom', 'like', '%' . $nom . '%')
              ->orWhere('to_nom', 'like', '%' . $nom . '%');
        });
    }

    public function scopeParType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeRecent($query, $jours = 30)
    {
        return $query->where('date', '>=', now()->subDays($jours));
    }

    // ✅ LOGIQUE MÉTIER : ENTRÉES VS SORTIES
    public function scopeEntrees($query)
    {
        return $query->whereIn('type', ['vente', 'depot', 'transfert']); // Ce qui fait entrer de l'argent
    }

    public function scopeSorties($query)
    {
        return $query->whereIn('type', ['achat', 'frais', 'commission', 'paiement', 'avance', 'retrait']); // Ce qui fait sortir de l'argent
    }
}


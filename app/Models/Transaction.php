<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaction extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'reference',
        'date',
        'type',
        'from_nom',
        'to_nom',
        'to_compte',
        'montant_mga',
        'objet',
        'voyage_id',
        'dechargement_id',
        'produit_id',
        'mode_paiement',
        'statut',
        'quantite',
        'unite',
        'prix_unitaire_mga',
        'reste_a_payer',
        'observation',
    ];

    protected $casts = [
        'montant_mga' => 'decimal:2',
        'quantite' => 'decimal:2',
        'prix_unitaire_mga' => 'decimal:2',
        'reste_a_payer' => 'decimal:2',
        'date' => 'datetime',
    ];

    // Relations
    public function produit()
    {
        return $this->belongsTo(Produit::class);
    }

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

    public function dechargement()
    {
        return $this->belongsTo(Dechargement::class);
    }

    // Accessors
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

    public function getQuantiteFormattedAttribute()
    {
        return $this->quantite ? number_format($this->quantite, 2, ',', ' ') . ' ' . ($this->unite ?? '') : 'N/A';
    }

    public function getPrixUnitaireMgaFormattedAttribute()
    {
        return $this->prix_unitaire_mga ? number_format($this->prix_unitaire_mga, 0, ',', ' ') . ' MGA/' . ($this->unite ?? '') : 'N/A';
    }

    // Model Events for Stock Updates
    protected static function boot()
    {
        parent::boot();

        static::created(function ($transaction) {
            if ($transaction->type === 'achat' && $transaction->produit_id && $transaction->quantite) {
                $produit = Produit::find($transaction->produit_id);
                if ($produit) {
                    $produit->increment('poids_moyen_sac_kg', $transaction->quantite);
                    \Illuminate\Support\Facades\Log::info('Stock updated for purchase', [
                        'transaction_id' => $transaction->id,
                        'produit_id' => $transaction->produit_id,
                        'quantite' => $transaction->quantite,
                        'new_stock' => $produit->poids_moyen_sac_kg,
                    ]);
                }
            } elseif ($transaction->type === 'vente' && $transaction->produit_id && $transaction->quantite) {
                $produit = Produit::find($transaction->produit_id);
                if ($produit) {
                    if ($produit->poids_moyen_sac_kg >= $transaction->quantite) {
                        $produit->decrement('poids_moyen_sac_kg', $transaction->quantite);
                        \Illuminate\Support\Facades\Log::info('Stock decreased for sale', [
                            'transaction_id' => $transaction->id,
                            'produit_id' => $transaction->produit_id,
                            'quantite' => $transaction->quantite,
                            'new_stock' => $produit->poids_moyen_sac_kg,
                        ]);
                    } else {
                        \Illuminate\Support\Facades\Log::error('Stock insuffisant pour vente', [
                            'transaction_id' => $transaction->id,
                            'produit_id' => $transaction->produit_id,
                            'quantite' => $transaction->quantite,
                            'stock_actuel' => $produit->poids_moyen_sac_kg,
                        ]);
                        throw new \Exception('Stock insuffisant pour la vente.');
                    }
                }
            }
        });

        static::updated(function ($transaction) {
            if ($transaction->type === 'achat' && $transaction->produit_id && $transaction->quantite) {
                $originalQuantite = $transaction->getOriginal('quantite');
                if ($originalQuantite !== $transaction->quantite) {
                    $produit = Produit::find($transaction->produit_id);
                    if ($produit) {
                        $difference = $transaction->quantite - ($originalQuantite ?? 0);
                        $produit->increment('poids_moyen_sac_kg', $difference);
                        \Illuminate\Support\Facades\Log::info('Stock updated for purchase edit', [
                            'transaction_id' => $transaction->id,
                            'produit_id' => $transaction->produit_id,
                            'quantite_old' => $originalQuantite,
                            'quantite_new' => $transaction->quantite,
                            'difference' => $difference,
                            'new_stock' => $produit->poids_moyen_sac_kg,
                        ]);
                    }
                }
            } elseif ($transaction->type === 'vente' && $transaction->produit_id && $transaction->quantite) {
                $originalQuantite = $transaction->getOriginal('quantite');
                if ($originalQuantite !== $transaction->quantite) {
                    $produit = Produit::find($transaction->produit_id);
                    if ($produit) {
                        $oldQuantite = $originalQuantite ?? 0;
                        $newQuantite = $transaction->quantite;
                        $difference = $newQuantite - $oldQuantite;
                        if ($produit->poids_moyen_sac_kg >= $difference) {
                            $produit->decrement('poids_moyen_sac_kg', $difference);
                            \Illuminate\Support\Facades\Log::info('Stock updated for sale edit', [
                                'transaction_id' => $transaction->id,
                                'produit_id' => $transaction->produit_id,
                                'quantite_old' => $originalQuantite,
                                'quantite_new' => $transaction->quantite,
                                'difference' => $difference,
                                'new_stock' => $produit->poids_moyen_sac_kg,
                            ]);
                        } else {
                            \Illuminate\Support\Facades\Log::error('Stock insuffisant pour modification de vente', [
                                'transaction_id' => $transaction->id,
                                'produit_id' => $transaction->produit_id,
                                'quantite' => $transaction->quantite,
                                'stock_actuel' => $produit->poids_moyen_sac_kg,
                            ]);
                            throw new \Exception('Stock insuffisant pour la modification de la vente.');
                        }
                    }
                }
            }
        });
    }

    // Scopes (unchanged)
    public function scopeRevenus($query)
    {
        return $query->where('type', 'vente');
    }

    public function scopeDepenses($query)
    {
        return $query->whereIn('type', ['achat', 'autre']);
    }

    public function scopeVentes($query)
    {
        return $query->where('type', 'vente');
    }

    public function scopeAchats($query)
    {
        return $query->where('type', 'achat');
    }

    public function scopeAutres($query)
    {
        return $query->where('type', 'autre');
    }

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

    public function scopePartiellementPayee($query)
    {
        return $query->where('statut', 'partiellement_payee');
    }

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

    public function scopeParProduit($query, $produitId)
    {
        return $query->where('produit_id', $produitId);
    }

    public function scopeRecent($query, $jours = 30)
    {
        return $query->where('date', '>=', now()->subDays($jours));
    }

    public function scopeEntrees($query)
    {
        return $query->where('type', 'vente');
    }

    public function scopeSorties($query)
    {
        return $query->whereIn('type', ['achat', 'autre']);
    }
}
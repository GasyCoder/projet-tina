<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PartenaireTransactionDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_id',
        'produit_id',
        'description',
        'quantite',
        'prix_unitaire_mga',
        'montant_mga',
        'type_detail', // 'achat_produit', 'credit', 'frais', 'autre'
    ];

    protected $casts = [
        'quantite' => 'decimal:2',
        'prix_unitaire_mga' => 'decimal:2',
        'montant_mga' => 'decimal:2'
    ];

    // Relations
    public function transaction()
    {
        return $this->belongsTo(PartenaireTransaction::class, 'transaction_id');
    }

    public function produit()
    {
        return $this->belongsTo(Produit::class);
    }

    // Accesseurs
    public function getMontantFormattedAttribute()
    {
        return number_format($this->montant_mga, 0, ',', ' ') . ' Ar';
    }

    public function getPrixUnitaireFormattedAttribute()
    {
        return number_format($this->prix_unitaire_mga, 0, ',', ' ') . ' Ar';
    }

    public function getQuantiteFormattedAttribute()
    {
        if ($this->produit) {
            return number_format($this->quantite, 2, ',', ' ') . ' ' . ($this->produit->unite ?? '');
        }
        return number_format($this->quantite, 2, ',', ' ');
    }

    public function getTypeDetailLibelleAttribute()
    {
        return match ($this->type_detail) {
            'achat_produit' => 'Achat Produit',
            'credit' => 'Crédit',
            'frais' => 'Frais',
            'autre' => 'Autre',
            default => 'Non spécifié'
        };
    }

    public function getDescriptionCompleteAttribute()
    {
        if ($this->produit) {
            return $this->produit->nom . ($this->produit->variete ? ' (' . $this->produit->variete . ')' : '') .
                ' - ' . $this->description;
        }
        return $this->description;
    }

    // Scopes
    public function scopeParTransaction($query, $transactionId)
    {
        return $query->where('transaction_id', $transactionId);
    }

    public function scopeAchatProduit($query)
    {
        return $query->where('type_detail', 'achat_produit');
    }

    public function scopeCredit($query)
    {
        return $query->where('type_detail', 'credit');
    }

    public function scopeFrais($query)
    {
        return $query->where('type_detail', 'frais');
    }
}
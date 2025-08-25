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
        'unite',                // ✅ NOUVEAU CHAMP
        'prix_unitaire_mga',
        'montant_mga',
        'type_detail',
    ];

    protected $casts = [
        'quantite'           => 'decimal:2',
        'prix_unitaire_mga'  => 'decimal:2',
        'montant_mga'        => 'decimal:2',
    ];

    // === RELATIONS ===
    
    public function transaction()
    {
        return $this->belongsTo(PartenaireTransaction::class, 'transaction_id');
    }

    public function produit()
    {
        return $this->belongsTo(Produit::class);
    }

    // === ACCESSEURS ===
    
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
        return number_format($this->quantite, 2);
    }

    /**
     * ✅ NOUVEAU : Affichage de la quantité avec unité
     */
    public function getQuantiteAvecUniteAttribute()
    {
        $quantite = $this->quantite_formatted;
        if ($this->unite) {
            return $quantite . ' ' . $this->unite;
        }
        return $quantite;
    }

    /**
     * ✅ NOUVEAU : Prix avec unité
     */
    public function getPrixUnitaireAvecUniteAttribute()
    {
        $prix = $this->prix_unitaire_formatted;
        if ($this->unite) {
            return $prix . '/' . $this->unite;
        }
        return $prix;
    }

    public function getTypeDetailLibelleAttribute()
    {
        return match ($this->type_detail) {
            'achat_produit' => 'Achat Produit',
            'credit'        => 'Crédit',
            'frais'         => 'Frais',
            'autre'         => 'Autre',
            default         => ucfirst($this->type_detail ?? ''),
        };
    }

    public function getDescriptionCompleteAttribute()
    {
        $desc = $this->description;
        
        if ($this->produit) {
            $desc .= ' (' . $this->produit->nom . ')';
        }
        
        if ($this->quantite > 0) {
            $desc .= ' - ' . $this->quantite_avec_unite;
        }
        
        return $desc;
    }

    // === CONSTANTES POUR LES UNITÉS ===
    
    public static function getUnitesDisponibles()
    {
        return [
            'kg' => 'Kilogramme',
            'g' => 'Gramme', 
            'sac' => 'Sac',
            'carton' => 'Carton',
            'piece' => 'Pièce',
            'litre' => 'Litre',
            'ml' => 'Millilitre',
            'metre' => 'Mètre',
            'cm' => 'Centimètre',
            'unite' => 'Unité',
        ];
    }

    public function getUniteLibelleAttribute()
    {
        $unites = self::getUnitesDisponibles();
        return $unites[$this->unite] ?? $this->unite;
    }

    // === SCOPES ===
    
    public function scopeParTransaction($q, $transactionId)
    {
        return $q->where('transaction_id', $transactionId);
    }

    public function scopeParProduit($q, $produitId)
    {
        return $q->where('produit_id', $produitId);
    }

    public function scopeParType($q, $type)
    {
        return $q->where('type_detail', $type);
    }

    public function scopeParUnite($q, $unite)
    {
        return $q->where('unite', $unite);
    }

    // === MÉTHODES MÉTIER ===
    
    // Vérifier la cohérence du calcul
    public function verifierCalcul()
    {
        if ($this->quantite && $this->prix_unitaire_mga) {
            $montantCalcule = $this->quantite * $this->prix_unitaire_mga;
            return abs($montantCalcule - $this->montant_mga) < 0.01; // Tolérance de 1 centime
        }
        
        return true; // Si pas de calcul automatique, considérer comme correct
    }

    // Recalculer le montant basé sur quantité × prix unitaire
    public function recalculerMontant()
    {
        if ($this->quantite && $this->prix_unitaire_mga) {
            $this->montant_mga = $this->quantite * $this->prix_unitaire_mga;
            return true;
        }
        
        return false;
    }

    // Obtenir les statistiques d'un produit dans les détails
    public static function getStatistiquesProduit($produitId, $dateDebut = null, $dateFin = null)
    {
        $query = self::where('produit_id', $produitId);
        
        if ($dateDebut && $dateFin) {
            $query->whereHas('transaction', function($q) use ($dateDebut, $dateFin) {
                $q->whereBetween('date_transaction', [$dateDebut, $dateFin]);
            });
        }
        
        return [
            'total_quantite' => $query->sum('quantite'),
            'total_montant' => $query->sum('montant_mga'),
            'prix_moyen' => $query->avg('prix_unitaire_mga'),
            'nombre_transactions' => $query->distinct('transaction_id')->count(),
            'unites_utilisees' => $query->distinct('unite')->pluck('unite')->filter()->toArray(),
        ];
    }

    /**
     * ✅ NOUVEAU : Statistiques par unité
     */
    public static function getStatistiquesParUnite($dateDebut = null, $dateFin = null)
    {
        $query = self::query();
        
        if ($dateDebut && $dateFin) {
            $query->whereHas('transaction', function($q) use ($dateDebut, $dateFin) {
                $q->whereBetween('date_transaction', [$dateDebut, $dateFin]);
            });
        }
        
        return $query->selectRaw('
            unite,
            COUNT(*) as nombre_utilisations,
            SUM(quantite) as total_quantite,
            SUM(montant_mga) as total_montant,
            AVG(prix_unitaire_mga) as prix_moyen
        ')
        ->whereNotNull('unite')
        ->groupBy('unite')
        ->orderBy('nombre_utilisations', 'desc')
        ->get();
    }
}
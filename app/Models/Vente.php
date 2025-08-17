<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Vente extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'ventes';

    /**
     * Colonnes autorisées en écriture (alignées avec ta migration)
     */
    protected $fillable = [
        'reference',
        'date',
        'objet',
        'depot_id',
        'vendeur_nom',
        'montant_paye_mga',
        'montant_restant_mga',
        'statut_paiement',        // 'paye' | 'partiel'
        'type_paiement',          // 'Principal' | 'MobileMoney' | 'Banque'
        'sous_type_paiement',     // 'Mvola' | 'OrangeMoney' | 'AirtelMoney' | 'BNI' | 'BFV' | ...
        'compte_id',
        'observation',
    ];

    /**
     * Casts
     */
    protected $casts = [
        'date'                 => 'date',
        'montant_paye_mga'     => 'decimal:2',
        'montant_restant_mga'  => 'decimal:2',
    ];

    /**
     * Attributs virtuels exposés (pour la vue)
     */
    protected $appends = [
        'badge_paiement',
        'badge_paiement_color',
    ];

    /* =========================
     |   Relations
     ========================= */
    public function depot()
    {
        return $this->belongsTo(Lieu::class, 'depot_id');
    }

    public function compte()
    {
        return $this->belongsTo(Compte::class);
    }

    /* =========================
     |   Accessors UI
     ========================= */
    /**
     * Exemple : "💵 Espèces" / "📱 Mobile Money — Mvola" / "🏦 Banque — BNI"
     */
    public function getBadgePaiementAttribute(): string
    {
        $type = $this->type_paiement;
        $sub  = $this->sous_type_paiement;

        return match ($type) {
            'Principal'   => '💵 Espèces',
            'MobileMoney' => '📱 Mobile Money' . ($sub ? " — {$sub}" : ''),
            'Banque'      => '🏦 Banque' . ($sub ? " — {$sub}" : ''),
            default       => ucfirst((string) $type),
        };
    }

    /**
     * Classes Tailwind pour la couleur du badge
     */
    public function getBadgePaiementColorAttribute(): string
    {
        // Si tu veux des couleurs par sous-type, ajoute un match($this->sous_type_paiement) ici
        return match ($this->type_paiement) {
            'Principal'   => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200',
            'MobileMoney' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/40 dark:text-blue-200',
            'Banque'      => 'bg-purple-100 text-purple-800 dark:bg-purple-900/40 dark:text-purple-200',
            default       => 'bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-200',
        };
    }

    /* =========================
     |   Scopes utiles
     ========================= */
    public function scopeStatut($q, string $statut)
    {
        return $q->where('statut_paiement', $statut);
    }

    public function scopeTypePaiement($q, string $type)
    {
        return $q->where('type_paiement', $type);
    }

    public function scopeSousType($q, string $sousType)
    {
        return $q->where('sous_type_paiement', $sousType);
    }

    public function scopeParDate($q, $date)
    {
        return $q->whereDate('date', $date);
    }

    public function scopeParVendeur($q, string $nom)
    {
        return $q->where('vendeur_nom', $nom);
    }
}

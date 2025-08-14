<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Achat extends Model
{
    use HasFactory, SoftDeletes;

    // Table par défaut "achats" -> pas besoin de $table

    protected $fillable = [
        'reference',
        'date',
        'from_nom',
        'to_nom',
        'montant_mga',
        'objet',
        'mode_paiement',
        'statut',
        'observation',
    ];

    protected $casts = [
        'date'         => 'date',       // ta colonne est de type DATE (pas datetime)
        'montant_mga'  => 'decimal:2',
        'statut'       => 'boolean',    // ta migration utilise un boolean
    ];

    /**
     * Constantes utilitaires
     */
    public const MODE_ESPECES     = 'especes';
    public const MODE_AIRTEL      = 'AirtelMoney';
    public const MODE_ORANGE      = 'OrangeMoney';
    public const MODE_MVOLA       = 'Mvola';
    public const MODE_BANQUE      = 'banque';

    public static function modesPaiement(): array
    {
        return [
            self::MODE_ESPECES,
            self::MODE_AIRTEL,
            self::MODE_ORANGE,
            self::MODE_MVOLA,
            self::MODE_BANQUE,
        ];
    }

    /**
     * Accessors (présentation)
     */
    public function getMontantMgaFormattedAttribute(): string
    {
        return number_format((float) $this->montant_mga, 0, ',', ' ') . ' MGA';
    }

    public function getFromNomDisplayAttribute(): string
    {
        return $this->from_nom ?: 'Inconnu';
    }

    public function getToNomDisplayAttribute(): string
    {
        return $this->to_nom ?: 'Inconnu';
    }

    public function getModePaiementLabelAttribute(): string
    {
        return match ($this->mode_paiement) {
            self::MODE_ESPECES => 'Espèces',
            self::MODE_AIRTEL  => 'AirtelMoney',
            self::MODE_ORANGE  => 'OrangeMoney',
            self::MODE_MVOLA   => 'Mvola',
            self::MODE_BANQUE  => 'Banque',
            default            => ucfirst((string) $this->mode_paiement),
        };
    }

    /**
     * Scopes utiles (basés uniquement sur les colonnes existantes)
     */
    public function scopePeriode($query, $dateDebut, $dateFin)
    {
        return $query->whereBetween('date', [$dateDebut, $dateFin]);
    }

    public function scopeParPersonne($query, string $nom)
    {
        return $query->where(function ($q) use ($nom) {
            $q->where('from_nom', 'like', "%{$nom}%")
              ->orWhere('to_nom', 'like', "%{$nom}%");
        });
    }

    public function scopeRecent($query, int $jours = 30)
    {
        return $query->where('date', '>=', now()->subDays($jours));
    }

    public function scopeConfirme($query)
    {
        // boolean true = confirmé (selon ta migration)
        return $query->where('statut', true);
    }

    public function scopeAttente($query)
    {
        // boolean false = en attente (ou non confirmé)
        return $query->where('statut', false);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Compte extends Model
{
    use HasFactory;

    // === Constantes utiles ===
    public const TYPE_PRINCIPAL  = 'Principal';
    public const TYPE_MOBILEMONEY = 'MobileMoney';
    public const TYPE_BANQUE     = 'Banque';

    // Ajuste la liste aux besoins réels
    public const MM_SOUS_TYPES   = ['Mvola', 'OrangeMoney', 'AirtelMoney'];
    public const BANK_SOUS_TYPES = ['BNI', 'BFV', 'BOA', 'BMOI']; // modifie selon ton contexte

    protected $fillable = [
        'user_id',
        'nom_proprietaire',
        'type_compte',
        'type_compte_mobilemoney_or_banque', // <— nouveau
        'numero_compte',
        'solde_actuel_mga',
        'actif',
    ];

    protected $casts = [
        'solde_actuel_mga' => 'decimal:2',
        'actif'            => 'boolean',
    ];

    // Relations
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Accessors
    public function getProprietaireDisplayAttribute(): string
    {
        return $this->nom_proprietaire ?: ($this->user?->name ?? 'Inconnu');
    }

    public function getSoldeFormattedAttribute(): string
    {
        return number_format((float)$this->solde_actuel_mga, 0, ',', ' ') . ' MGA';
    }

    public function getTypeLabelAttribute(): string
    {
        // "MobileMoney (Mvola)" / "Banque (BNI)" / "Principal"
        return match ($this->type_compte) {
            self::TYPE_MOBILEMONEY, self::TYPE_BANQUE =>
                $this->type_compte . ($this->type_compte_mobilemoney_or_banque ? ' ('.$this->type_compte_mobilemoney_or_banque.')' : ''),
            default => self::TYPE_PRINCIPAL,
        };
    }

    // Scopes
    public function scopePrincipal($q)
    {
        return $q->where('type_compte', self::TYPE_PRINCIPAL);
    }

    public function scopeMobileMoney($q, ?string $sousType = null)
    {
        $q->where('type_compte', self::TYPE_MOBILEMONEY);
        if ($sousType) {
            $q->where('type_compte_mobilemoney_or_banque', $sousType);
        }
        return $q;
    }

    public function scopeBanque($q, ?string $banque = null)
    {
        $q->where('type_compte', self::TYPE_BANQUE);
        if ($banque) {
            $q->where('type_compte_mobilemoney_or_banque', $banque);
        }
        return $q;
    }

    public function scopeActif($q)
    {
        return $q->where('actif', true);
    }

    public function scopeParType($q, string $type)
    {
        return $q->where('type_compte', $type);
    }

    public function scopeParProprietaire($q, string $nom)
    {
        return $q->where('nom_proprietaire', 'like', "%{$nom}%");
    }

    /** Vrai si le solde couvre le montant (comparaison à 2 décimales) */
    public function hasSolde(float $montant): bool
    {
        return bccomp((string)$this->solde_actuel_mga, (string)$montant, 2) >= 0;
    }

    /**
     * Débite de façon atomique: UPDATE ... WHERE id = ? AND solde >= ?
     * Retourne true si 1 ligne affectée -> débit réussi
     */
    public function tryDebit(float $montant): bool
    {
        return static::whereKey($this->id)
            ->where('solde_actuel_mga', '>=', $montant)
            ->decrement('solde_actuel_mga', $montant) === 1;
    }

    /** Crédit simple (pour symétrie et lisibilité) */
    public function credit(float $montant): void
    {
        $this->increment('solde_actuel_mga', $montant);
    }
}
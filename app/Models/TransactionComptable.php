<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class TransactionComptable extends Model
{
    use HasFactory;

    protected $table = 'transactions_comptables';

    protected $fillable = [
        'reference',
        'description',
        'montant',
        'date_transaction',
        'type',
        'mode_paiement',
        'type_compte_mobilemoney_or_banque', // ✅ CORRIGÉ pour correspondre à la migration
        'categorie_id',
        'partenaire_id',
        'user_id',
        'notes',
        'date_echeance',
    ];

    protected $casts = [
        'montant' => 'decimal:2',
        'date_transaction' => 'date',
        'date_echeance' => 'date',
    ];

    protected $attributes = [
        'mode_paiement' => 'especes',
        'priorite' => 'normale',
        'recurrence' => 'unique',
    ];

    // Relations
    public function categorie(): BelongsTo
    {
        return $this->belongsTo(Categorie::class);
    }

    public function partenaire(): BelongsTo
    {
        return $this->belongsTo(Partenaire::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Accesseurs
    public function getMontantFormateAttribute(): string
    {
        $signe = $this->type === 'recette' ? '+' : '-';
        return $signe . number_format($this->montant, 0, ',', ' ') . ' Ar';
    }

    public function getDateFormatteeAttribute(): string
    {
        return $this->date_transaction?->format('d/m/Y') ?? '';
    }

    public function getModeCompletAttribute(): string
    {
        if ($this->mode_paiement === 'especes') {
            return 'Espèces (Compte Principal)';
        }

        if ($this->type_compte_mobilemoney_or_banque) { // ✅ CORRIGÉ
            return $this->type_compte_mobilemoney_or_banque;
        }

        return ucfirst($this->mode_paiement);
    }

    // ... (le reste du code reste le même)

    // Boot
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($transaction) {
            // Générer référence si vide
            if (empty($transaction->reference)) {
                $transaction->reference = self::genererReference();
            }

            // Définir le type selon la catégorie
            if (empty($transaction->type) && $transaction->categorie_id) {
                $categorie = Categorie::find($transaction->categorie_id);
                $transaction->type = $categorie?->type ?? 'depense';
            }
        });
    }
}
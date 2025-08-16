<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PartenaireTransaction extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'partenaire_id',
        'reference',
        'type', // 'entree' ou 'sortie'
        'montant_mga',
        'motif',
        'mode_paiement', // Pour les entrées
        'compte_source_id', // Compte de Mme Tina pour les entrées
        'compte_destination_id', // Compte du partenaire
        'statut',
        'date_transaction',
        'observation'
    ];

    protected $casts = [
        'montant_mga' => 'decimal:2',
        'date_transaction' => 'datetime',
        'statut' => 'boolean'
    ];

    // Relations
    public function partenaire()
    {
        return $this->belongsTo(Partenaire::class);
    }

    public function compteSource()
    {
        return $this->belongsTo(Compte::class, 'compte_source_id');
    }

    public function compteDestination()
    {
        return $this->belongsTo(Compte::class, 'compte_destination_id');
    }

    public function details()
    {
        return $this->hasMany(PartenaireTransactionDetail::class, 'transaction_id');
    }

    // Accesseurs
    public function getMontantFormattedAttribute()
    {
        return number_format($this->montant_mga, 0, ',', ' ') . ' Ar';
    }

    public function getTypeLibelleAttribute()
    {
        return $this->type === 'entree' ? 'Entrée' : 'Sortie';
    }

    public function getModePaiementLibelleAttribute()
    {
        return match ($this->mode_paiement) {
            'especes' => 'Espèces',
            'AirtelMoney' => 'AirtelMoney',
            'OrangeMoney' => 'OrangeMoney',
            'Mvola' => 'Mvola',
            'banque' => 'Banque',
            default => ucfirst($this->mode_paiement ?? '')
        };
    }

    // Scopes
    public function scopeEntrees($query)
    {
        return $query->where('type', 'entree');
    }

    public function scopeSorties($query)
    {
        return $query->where('type', 'sortie');
    }

    public function scopeParPartenaire($query, $partenaireId)
    {
        return $query->where('partenaire_id', $partenaireId);
    }

    public function scopeRecent($query, $jours = 30)
    {
        return $query->where('date_transaction', '>=', now()->subDays($jours));
    }

    public function scopePeriode($query, $dateDebut, $dateFin)
    {
        return $query->whereBetween('date_transaction', [$dateDebut, $dateFin]);
    }

    // Méthodes statiques
    public static function genererReference($type)
    {
        $prefix = $type === 'entree' ? 'ENT' : 'SORT';
        $year = date('Y');
        $count = self::where('type', $type)
            ->whereYear('created_at', $year)
            ->count() + 1;

        return $prefix . '-' . $year . '-' . str_pad($count, 3, '0', STR_PAD_LEFT);
    }

    // Calculer le total des détails pour les sorties
    public function getTotalDetailsAttribute()
    {
        return $this->details->sum('montant_mga');
    }

    public function getSoldeRestantAttribute()
    {
        if ($this->type === 'sortie') {
            return $this->montant_mga - $this->total_details;
        }
        return 0;
    }
}
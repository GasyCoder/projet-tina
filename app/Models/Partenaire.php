<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Partenaire extends Model
{
    use HasFactory;

    protected $table = 'partenaires';

    protected $fillable = [
        'nom',
        'telephone',
        'adresse',
        'type',
        'is_active',
        'solde_actuel_mga', // Nouveau champ pour le solde
        'compte_id', // Compte associé au partenaire
    ];

    protected $attributes = [
        'type' => 'fournisseur',
        'is_active' => true,
        'solde_actuel_mga' => 0,
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'solde_actuel_mga' => 'decimal:2',
    ];

    // Relations
    public function transactions()
    {
        return $this->hasMany(PartenaireTransaction::class);
    }

    public function entrees()
    {
        return $this->hasMany(PartenaireTransaction::class)->where('type', 'entree');
    }

    public function sorties()
    {
        return $this->hasMany(PartenaireTransaction::class)->where('type', 'sortie');
    }

    public function compte()
    {
        return $this->belongsTo(Compte::class);
    }

    // Accesseurs
    public function getSoldeFormattedAttribute()
    {
        return number_format($this->solde_actuel_mga, 0, ',', ' ') . ' Ar';
    }

    public function getHasUnpaidDebtAttribute()
    {
        // Vérifier s'il y a une dette impayée depuis plus de 30 jours
        return $this->sorties()
            ->where('date_transaction', '<=', now()->subDays(30))
            ->where('statut', false)
            ->exists();
    }

    // Statistiques du mois en cours
    public function getTotalEntreesMoisAttribute()
    {
        return $this->entrees()
            ->whereYear('date_transaction', now()->year)
            ->whereMonth('date_transaction', now()->month)
            ->sum('montant_mga');
    }

    public function getTotalSortiesMoisAttribute()
    {
        return $this->sorties()
            ->whereYear('date_transaction', now()->year)
            ->whereMonth('date_transaction', now()->month)
            ->sum('montant_mga');
    }

    public function getTotalEntreesAttribute()
    {
        return $this->entrees()->sum('montant_mga');
    }

    public function getTotalSortiesAttribute()
    {
        return $this->sorties()->sum('montant_mga');
    }

    // Méthodes pour gérer le solde
    public function ajouterEntree($montant)
    {
        $this->increment('solde_actuel_mga', $montant);
    }

    public function retirerSortie($montant)
    {
        $this->decrement('solde_actuel_mga', $montant);
    }

    public function calculerSolde()
    {
        $totalEntrees = $this->total_entrees;
        $totalSorties = $this->total_sorties;
        $this->update(['solde_actuel_mga' => $totalEntrees - $totalSorties]);
        return $this->solde_actuel_mga;
    }

    // Scopes
    public function scopeActif($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeParType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeAvecSoldePositif($query)
    {
        return $query->where('solde_actuel_mga', '>', 0);
    }

    public function scopeAvecSoldeNegatif($query)
    {
        return $query->where('solde_actuel_mga', '<', 0);
    }
}
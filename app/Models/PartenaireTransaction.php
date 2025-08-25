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
        'entree_source_id',
        'reference',
        'type', // 'entree' | 'sortie'
        'montant_mga',
        'motif',
        'mode_paiement',       
        'sous_type_compte',
        'statut',
        'date_transaction',
        'observation',
    ];

    protected $casts = [
        'montant_mga'      => 'decimal:2',
        'date_transaction' => 'datetime',
        'statut'           => 'boolean',
    ];

    // === RELATIONS ===
    
    public function partenaire()
    {
        return $this->belongsTo(Partenaire::class);
    }

    public function details()
    {
        return $this->hasMany(PartenaireTransactionDetail::class, 'transaction_id');
    }

    // Relations pour le lien entrée-sortie
    public function entreeSource()
    {
        return $this->belongsTo(PartenaireTransaction::class, 'entree_source_id');
    }

    public function sortiesLiees()
    {
        return $this->hasMany(PartenaireTransaction::class, 'entree_source_id');
    }

    // === ACCESSEURS ===
    
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
            'especes'      => 'Espèces',
            'AirtelMoney'  => 'AirtelMoney',
            'OrangeMoney'  => 'OrangeMoney',
            'Mvola'        => 'Mvola',
            'banque'       => 'Banque',
            'MobileMoney'  => 'MobileMoney',
            'Banque'       => 'Banque',
            default        => ucfirst($this->mode_paiement ?? ''),
        };
    }

    // Pour les entrées : montant disponible après sorties
    public function getMontantDisponibleAttribute()
    {
        if ($this->type !== 'entree') return 0;
        
        $montantUtilise = $this->sortiesLiees()->sum('montant_mga');
        return $this->montant_mga - $montantUtilise;
    }

    // Pourcentage d'utilisation d'une entrée
    public function getPourcentageUtiliseAttribute()
    {
        if ($this->type !== 'entree' || $this->montant_mga == 0) return 0;
        
        $montantUtilise = $this->sortiesLiees()->sum('montant_mga');
        return ($montantUtilise / $this->montant_mga) * 100;
    }

    // Montant utilisé d'une entrée
    public function getMontantUtiliseAttribute()
    {
        if ($this->type !== 'entree') return 0;
        
        return $this->sortiesLiees()->sum('montant_mga');
    }

    // === SCOPES ===
    
    public function scopeEntrees($q) 
    { 
        return $q->where('type', 'entree'); 
    }
    
    public function scopeSorties($q) 
    { 
        return $q->where('type', 'sortie'); 
    }
    
    public function scopeParPartenaire($q, $id) 
    { 
        return $q->where('partenaire_id', $id); 
    }
    
    public function scopeRecent($q, $jours = 30) 
    { 
        return $q->where('date_transaction', '>=', now()->subDays($jours)); 
    }
    
    public function scopePeriode($q, $debut, $fin) 
    { 
        return $q->whereBetween('date_transaction', [$debut, $fin]); 
    }
    
    public function scopeDepuisEntree($q, $entreeId) 
    { 
        return $q->where('entree_source_id', $entreeId); 
    }

    public function scopeSortiesLibres($q)
    {
        return $q->where('type', 'sortie')->whereNull('entree_source_id');
    }

    public function scopeSortiesLiees($q)
    {
        return $q->where('type', 'sortie')->whereNotNull('entree_source_id');
    }

    // === MÉTHODES STATIQUES ===
    
    public static function genererReference($type)
    {
        $prefix = $type === 'entree' ? 'ENT' : 'SORT';
        $year   = date('Y');
        $count  = self::where('type', $type)->whereYear('created_at', $year)->count() + 1;
        return $prefix.'-'.$year.'-'.str_pad($count, 3, '0', STR_PAD_LEFT);
    }

    // === MÉTHODES MÉTIER ===
    
    public function getTotalDetailsAttribute()
    {
        return $this->details->sum('montant_mga');
    }

    public function getSoldeRestantAttribute()
    {
        return $this->type === 'sortie' ? $this->montant_mga - $this->total_details : 0;
    }

    // Vérifier si une entrée peut encore être utilisée
    public function peutEtreUtilisee($montant = 0)
    {
        if ($this->type !== 'entree') return false;
        
        return $this->montant_disponible >= $montant;
    }

    // Obtenir le résumé d'une entrée
    public function getResumeEntree()
    {
        if ($this->type !== 'entree') return null;

        return [
            'montant_initial' => $this->montant_mga,
            'montant_utilise' => $this->montant_utilise,
            'montant_disponible' => $this->montant_disponible,
            'pourcentage_utilise' => $this->pourcentage_utilise,
            'nombre_sorties' => $this->sortiesLiees()->count(),
        ];
    }
}
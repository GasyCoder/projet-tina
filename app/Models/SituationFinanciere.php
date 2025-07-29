<?php
// app/Models/SituationFinanciere.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class SituationFinanciere extends Model
{
    use HasFactory;

    protected $table = 'situations_financieres';

    protected $fillable = [
        'date_situation',
        'lieu',
        'description',
        'montant_initial',
        'montant_final',
        'commentaire',
    ];

    protected $casts = [
        'date_situation' => 'date',
        'montant_initial' => 'decimal:2',
        'montant_final' => 'decimal:2',
    ];

    // Accesseurs
    public function getMontantInitialFormattedAttribute()
    {
        return number_format($this->montant_initial, 0, ',', ' ') . ' MGA';
    }

    public function getMontantFinalFormattedAttribute()
    {
        return number_format($this->montant_final, 0, ',', ' ') . ' MGA';
    }

    public function getEcartAttribute()
    {
        return $this->montant_final - $this->montant_initial;
    }

    public function getEcartFormattedAttribute()
    {
        $ecart = $this->ecart;
        $signe = $ecart >= 0 ? '+' : '';
        return $signe . number_format($ecart, 0, ',', ' ') . ' MGA';
    }

    public function getLieuLabelAttribute()
    {
        return match($this->lieu) {
            'mahajanga' => '🏢 Mahajanga',
            'antananarivo' => '🏢 Antananarivo',
            'autre' => '🏢 Autres lieux',
            default => ucfirst($this->lieu)
        };
    }

    // Scopes
    public function scopeParLieu($query, $lieu)
    {
        return $query->where('lieu', $lieu);
    }

    public function scopeParPeriode($query, $dateDebut, $dateFin)
    {
        return $query->whereBetween('date_situation', [$dateDebut, $dateFin]);
    }

    public function scopeParMois($query, $mois, $annee = null)
    {
        $annee = $annee ?? Carbon::now()->year;
        return $query->whereYear('date_situation', $annee)
                    ->whereMonth('date_situation', $mois);
    }

    // Méthodes statiques utilitaires
    public static function getTotalParLieu($lieu, $dateDebut = null, $dateFin = null)
    {
        $query = static::where('lieu', $lieu);
        
        if ($dateDebut) {
            $query->whereDate('date_situation', '>=', $dateDebut);
        }
        
        if ($dateFin) {
            $query->whereDate('date_situation', '<=', $dateFin);
        }

        return $query->selectRaw('
                SUM(montant_initial) as total_initial,
                SUM(montant_final) as total_final,
                SUM(montant_final - montant_initial) as ecart_total,
                COUNT(*) as nombre_entrees
            ')
            ->first();
    }

    public static function getResumeGlobal($dateDebut = null, $dateFin = null)
    {
        $query = static::query();
        
        if ($dateDebut) {
            $query->whereDate('date_situation', '>=', $dateDebut);
        }
        
        if ($dateFin) {
            $query->whereDate('date_situation', '<=', $dateFin);
        }

        return $query->selectRaw('
                SUM(montant_initial) as total_initial,
                SUM(montant_final) as total_final,
                SUM(montant_final - montant_initial) as ecart_total,
                COUNT(*) as nombre_entrees
            ')
            ->first();
    }

    public static function getEvolutionParMois($annee = null)
    {
        $annee = $annee ?? Carbon::now()->year;
        
        return static::whereYear('date_situation', $annee)
            ->selectRaw('
                MONTH(date_situation) as mois,
                SUM(montant_initial) as total_initial,
                SUM(montant_final) as total_final,
                SUM(montant_final - montant_initial) as ecart_total,
                COUNT(*) as nombre_entrees
            ')
            ->groupBy('mois')
            ->orderBy('mois')
            ->get();
    }

    public static function getLieuxAvecTotaux($dateDebut = null, $dateFin = null)
    {
        $query = static::query();
        
        if ($dateDebut) {
            $query->whereDate('date_situation', '>=', $dateDebut);
        }
        
        if ($dateFin) {
            $query->whereDate('date_situation', '<=', $dateFin);
        }

        return $query->selectRaw('
                lieu,
                SUM(montant_initial) as total_initial,
                SUM(montant_final) as total_final,
                SUM(montant_final - montant_initial) as ecart_total,
                COUNT(*) as nombre_entrees
            ')
            ->groupBy('lieu')
            ->orderBy('ecart_total', 'desc')
            ->get();
    }
}
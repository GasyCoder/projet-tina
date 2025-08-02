<?php
// app/Models/MouvementFinancier.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class MouvementFinancier extends Model
{
    use HasFactory;

    protected $table = 'mouvements_financiers';

    protected $fillable = [
        'date_mouvement',
        'compte_id',
        'type_mouvement',
        'description',
        'montant',
        'commentaire',
        'lieu',
        'valide',
    ];

    protected $casts = [
        'date_mouvement' => 'date',
        'montant' => 'decimal:2',
        'valide' => 'boolean',
    ];

    // Relations
    public function compte()
    {
        return $this->belongsTo(Compte::class);
    }

    // Events pour mettre à jour automatiquement les soldes
    protected static function booted()
    {
        static::created(function ($mouvement) {
            $mouvement->mettreAJourSoldeCompte();
        });

        static::updated(function ($mouvement) {
            $mouvement->mettreAJourSoldeCompte();
        });

        static::deleted(function ($mouvement) {
            $mouvement->mettreAJourSoldeCompte();
        });
    }

    // Méthode pour mettre à jour le solde du compte
    public function mettreAJourSoldeCompte()
    {
        if (!$this->compte_id) return;

        // Calculer le nouveau solde basé sur TOUS les mouvements de ce compte
        $totalMouvements = static::where('compte_id', $this->compte_id)
            ->selectRaw('
                SUM(CASE WHEN type_mouvement = "entree" THEN montant ELSE 0 END) as total_entrees,
                SUM(CASE WHEN type_mouvement = "sortie" THEN montant ELSE 0 END) as total_sorties
            ')
            ->first();

        $nouveauSolde = ($totalMouvements->total_entrees ?? 0) - ($totalMouvements->total_sorties ?? 0);

        // Mettre à jour le solde du compte
        Compte::where('id', $this->compte_id)->update([
            'solde_actuel_mga' => $nouveauSolde,
            'derniere_transaction_id' => $this->id
        ]);
    }

    // Accesseurs
    public function getMontantFormattedAttribute()
    {
        return number_format($this->montant, 0, ',', ' ') . ' MGA';
    }

    public function getMontantSigneAttribute()
    {
        return $this->type_mouvement === 'entree' ? $this->montant : -$this->montant;
    }

    public function getMontantSigneFormattedAttribute()
    {
        $signe = $this->type_mouvement === 'entree' ? '+' : '-';
        return $signe . $this->montant_formatted;
    }

    public function getTypeLabelAttribute()
    {
        return match($this->type_mouvement) {
            'entree' => '💰 Entrée',
            'sortie' => '💸 Sortie',
            default => ucfirst($this->type_mouvement)
        };
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
    public function scopeEntrees($query)
    {
        return $query->where('type_mouvement', 'entree');
    }

    public function scopeSorties($query)
    {
        return $query->where('type_mouvement', 'sortie');
    }

    public function scopeParDate($query, $date)
    {
        return $query->whereDate('date_mouvement', $date);
    }

    public function scopeParPeriode($query, $dateDebut, $dateFin)
    {
        return $query->whereBetween('date_mouvement', [$dateDebut, $dateFin]);
    }

    public function scopeParLieu($query, $lieu)
    {
        return $query->where('lieu', $lieu);
    }

    public function scopeParCompte($query, $compteId)
    {
        return $query->where('compte_id', $compteId);
    }

    public function scopeValides($query)
    {
        return $query->where('valide', true);
    }

    // Méthodes statiques utilitaires
    public static function getResumeJour($date, $lieu = null)
    {
        $query = static::whereDate('date_mouvement', $date);
        
        if ($lieu) {
            $query->where('lieu', $lieu);
        }

        return $query->selectRaw('
                SUM(CASE WHEN type_mouvement = "entree" THEN montant ELSE 0 END) as total_entrees,
                SUM(CASE WHEN type_mouvement = "sortie" THEN montant ELSE 0 END) as total_sorties,
                SUM(CASE WHEN type_mouvement = "entree" THEN montant ELSE -montant END) as ecart,
                COUNT(*) as nombre_mouvements
            ')
            ->first();
    }

    public static function getResumeParCompte($date, $lieu = null)
    {
        $query = static::with('compte')
                      ->whereDate('date_mouvement', $date);
        
        if ($lieu) {
            $query->where('lieu', $lieu);
        }

        return $query->selectRaw('
                compte_id,
                SUM(CASE WHEN type_mouvement = "entree" THEN montant ELSE 0 END) as entrees,
                SUM(CASE WHEN type_mouvement = "sortie" THEN montant ELSE 0 END) as sorties,
                SUM(CASE WHEN type_mouvement = "entree" THEN montant ELSE -montant END) as solde_net,
                COUNT(*) as nb_mouvements
            ')
            ->groupBy('compte_id')
            ->get();
    }

    public static function getTableauSemaine($dateDebut, $dateFin, $lieu = null)
    {
        // Récupérer tous les mouvements de la période
        $query = static::with('compte')
                      ->whereBetween('date_mouvement', [$dateDebut, $dateFin]);
        
        if ($lieu) {
            $query->where('lieu', $lieu);
        }

        $mouvements = $query->get();
        
        // Grouper par date puis par compte
        $mouvementsGroupes = $mouvements->groupBy([
            function($item) {
                return $item->date_mouvement->format('Y-m-d');
            },
            'compte_id'
        ]);
        
        // Récupérer tous les comptes actifs
        $comptes = Compte::actif()->orderBy('type_compte')->get();
        
        $tableau = [];
        
        // Générer le tableau pour chaque jour de la période
        for ($date = Carbon::parse($dateDebut); $date->lte(Carbon::parse($dateFin)); $date->addDay()) {
            $dateStr = $date->format('Y-m-d');
            
            $tableau[$dateStr] = [
                'date' => $date->copy(),
                'comptes' => [],
                'total_entrees' => 0,
                'total_sorties' => 0,
                'ecart' => 0,
            ];
            
            // Pour chaque compte, calculer les totaux du jour
            foreach ($comptes as $compte) {
                $mouvementsCompteJour = $mouvementsGroupes->get($dateStr, collect())->get($compte->id, collect());
                
                $entrees = $mouvementsCompteJour->where('type_mouvement', 'entree')->sum('montant');
                $sorties = $mouvementsCompteJour->where('type_mouvement', 'sortie')->sum('montant');
                $solde = $entrees - $sorties;
                
                $tableau[$dateStr]['comptes'][$compte->id] = [
                    'compte' => $compte,
                    'entrees' => $entrees,
                    'sorties' => $sorties,
                    'solde' => $solde,
                    'mouvements' => $mouvementsCompteJour,
                ];
                
                // Ajouter aux totaux du jour
                $tableau[$dateStr]['total_entrees'] += $entrees;
                $tableau[$dateStr]['total_sorties'] += $sorties;
                $tableau[$dateStr]['ecart'] += $solde;
            }
        }
        
        return $tableau;
    }

    public static function getEvolutionParPeriode($dateDebut, $dateFin, $lieu = null)
    {
        $query = static::whereBetween('date_mouvement', [$dateDebut, $dateFin]);
        
        if ($lieu) {
            $query->where('lieu', $lieu);
        }

        return $query->selectRaw('
                DATE(date_mouvement) as date,
                SUM(CASE WHEN type_mouvement = "entree" THEN montant ELSE 0 END) as entrees,
                SUM(CASE WHEN type_mouvement = "sortie" THEN montant ELSE 0 END) as sorties,
                SUM(CASE WHEN type_mouvement = "entree" THEN montant ELSE -montant END) as ecart
            ')
            ->groupBy('date')
            ->orderBy('date')
            ->get();
    }

    // Méthode pour recalculer tous les soldes (utilitaire de maintenance)
    public static function recalculerTousLesSoldes()
    {
        $comptes = Compte::all();
        
        foreach ($comptes as $compte) {
            $totalMouvements = static::where('compte_id', $compte->id)
                ->selectRaw('
                    SUM(CASE WHEN type_mouvement = "entree" THEN montant ELSE 0 END) as total_entrees,
                    SUM(CASE WHEN type_mouvement = "sortie" THEN montant ELSE 0 END) as total_sorties
                ')
                ->first();

            $nouveauSolde = ($totalMouvements->total_entrees ?? 0) - ($totalMouvements->total_sorties ?? 0);
            
            $compte->update(['solde_actuel_mga' => $nouveauSolde]);
        }
    }
}
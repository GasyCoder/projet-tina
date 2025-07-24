<?php

namespace App\Models;

use Carbon\Carbon;
use App\Models\Alerte;
use Illuminate\Database\Eloquent\Model;

class PrixMarche extends Model
{
    protected $table = 'prix_marche';

    protected $fillable = [
        'produit_id',
        'lieu_id', 
        'date',
        'prix_mga',
        'unite',
        'source',
        'observation'
    ];

    protected $casts = [
        'date' => 'date',
        'prix_mga' => 'decimal:2'
    ];

    /**
     * 🤖 MÉTHODE D'AUTOMATISATION PRINCIPALE
     */
    public static function updatePrixMarche($dechargement)
    {
        // Vérifier si ce lieu de livraison existe
        if (!$dechargement->lieu_livraison_id) {
            return;
        }

        $aujourd_hui = Carbon::now()->toDateString();

        // Vérifier si on a déjà un prix pour ce produit/lieu/date
        $prixExistant = static::where([
            'produit_id' => $dechargement->produit_id,
            'lieu_id' => $dechargement->lieu_livraison_id,
            'date' => $aujourd_hui
        ])->first();

        if ($prixExistant) {
            // 📊 MISE À JOUR : Calculer prix moyen si plusieurs ventes
            $prixMoyen = static::calculerPrixMoyen(
                $dechargement->produit_id,
                $dechargement->lieu_livraison_id,
                $aujourd_hui
            );

            $prixExistant->update([
                'prix_mga' => $prixMoyen,
                'observation' => 'Prix moyen de ' . static::compterVentesToday($dechargement->produit_id, $dechargement->lieu_livraison_id) . ' ventes'
            ]);
        } else {
            // ✨ CRÉATION : Premier prix du jour pour ce produit/lieu
            static::create([
                'produit_id' => $dechargement->produit_id,
                'lieu_id' => $dechargement->lieu_livraison_id,
                'date' => $aujourd_hui,
                'prix_mga' => $dechargement->prix_unitaire_mga,
                'unite' => 'kg', // Standardisé
                'source' => 'vente_realisee',
                'observation' => 'Prix de vente réel - Référence: ' . $dechargement->reference
            ]);
        }

        // 🚨 DÉCLENCHER ALERTES si prix chute
        static::verifierAlertePrix($dechargement->produit_id, $dechargement->lieu_livraison_id);
    }

    /**
     * 📊 Calculer prix moyen des ventes du jour
     */
    public static function calculerPrixMoyen($produit_id, $lieu_id, $date)
    {
        return Dechargement::where('produit_id', $produit_id)
            ->where('lieu_livraison_id', $lieu_id)
            ->where('type', 'vente')
            ->whereDate('created_at', $date)
            ->whereNotNull('prix_unitaire_mga')
            ->avg('prix_unitaire_mga');
    }

    /**
     * 🔢 Compter ventes du jour
     */
    public static function compterVentesToday($produit_id, $lieu_id)
    {
        return Dechargement::where('produit_id', $produit_id)
            ->where('lieu_livraison_id', $lieu_id)
            ->where('type', 'vente')
            ->whereDate('created_at', today())
            ->count();
    }

    /**
     * 🚨 Vérifier si prix chute dangereusement
     */
    public static function verifierAlertePrix($produit_id, $lieu_id)
    {
        $prix7jours = static::where('produit_id', $produit_id)
            ->where('lieu_id', $lieu_id)
            ->where('date', '>=', now()->subDays(7))
            ->orderBy('date', 'desc')
            ->limit(7)
            ->pluck('prix_mga');

        if ($prix7jours->count() >= 2) {
            $prixAujourdhui = $prix7jours->first();
            $prixMoyenSemaine = $prix7jours->avg();
            
            // Si prix d'aujourd'hui < 85% du prix moyen de la semaine
            if ($prixAujourdhui < ($prixMoyenSemaine * 0.85)) {
                // 🚨 DÉCLENCHER ALERTE
                static::envoyerAlertePrix($produit_id, $lieu_id, $prixAujourdhui, $prixMoyenSemaine);
            }
        }
    }

    /**
     * 📢 Envoyer alerte de prix
     */
    public static function envoyerAlertePrix($produit_id, $lieu_id, $prixActuel, $prixMoyen)
    {
        $produit = Produit::find($produit_id);
        $lieu = Lieu::find($lieu_id);
        $pourcentageBaisse = round((($prixMoyen - $prixActuel) / $prixMoyen) * 100, 1);
        
        $proprietaires = StockDepot::where('produit_id', $produit_id)
            ->where('statut', 'en_stock')
            ->with('proprietaire')
            ->get()
            ->pluck('proprietaire')
            ->unique();

        foreach ($proprietaires as $proprietaire) {
                Alerte::creerAlerte(
                $proprietaire->id,
                'prix_baisse',
                "Prix en baisse : {$produit->nom}",
                "Le prix de {$produit->nom} à {$lieu->nom} a chuté de {$pourcentageBaisse}% (actuellement " . number_format($prixActuel, 0) . " MGA)",
                [
                    'produit_id' => $produit_id,
                    'lieu_id' => $lieu_id,
                    'prix_actuel' => $prixActuel,
                    'prix_moyen' => $prixMoyen,
                    'pourcentage_baisse' => $pourcentageBaisse
                ]
            );
        }
    }

    /**
     * 📈 Obtenir tendance des prix (7 derniers jours)
     */
    public static function getTendancePrix($produit_id, $lieu_id, $jours = 7)
    {
        return static::where('produit_id', $produit_id)
            ->where('lieu_id', $lieu_id)
            ->where('date', '>=', now()->subDays($jours))
            ->orderBy('date', 'asc')
            ->get(['date', 'prix_mga']);
    }

    /**
     * 🎯 Meilleurs prix actuels pour un produit
     */
    public static function getMeilleursPrix($produit_id, $jours = 3)
    {
        return static::where('produit_id', $produit_id)
            ->where('date', '>=', now()->subDays($jours))
            ->with('lieu')
            ->orderBy('prix_mga', 'desc')
            ->get()
            ->groupBy('lieu_id')
            ->map(function ($group) {
                return $group->first(); // Prix le plus récent par lieu
            })
            ->sortByDesc('prix_mga');
    }

    // Relations
    public function produit()
    {
        return $this->belongsTo(Produit::class);
    }

    public function lieu()
    {
        return $this->belongsTo(Lieu::class);
    }
}
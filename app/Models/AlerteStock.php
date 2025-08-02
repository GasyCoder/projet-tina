<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;



class AlerteStock extends Model
{
    use HasFactory;

    protected $table = 'alertes_stocks';

    protected $fillable = [
        'type_alerte',
        'produit_id',
        'lieu_id',
        'seuil_critique',
        'quantite_actuelle',
        'message',
        'niveau_urgence',
        'statut',
        'user_assign_id',
        'date_detection',
        'date_resolution'
    ];

    protected $casts = [
        'seuil_critique' => 'decimal:2',
        'quantite_actuelle' => 'decimal:2',
        'date_detection' => 'datetime',
        'date_resolution' => 'datetime'
    ];

    // Relations
    public function produit()
    {
        return $this->belongsTo(Produit::class);
    }

    public function lieu()
    {
        return $this->belongsTo(Lieu::class);
    }

    public function userAssign()
    {
        return $this->belongsTo(User::class, 'user_assign_id');
    }

    // Méthode pour vérifier et créer des alertes
    public static function verifierAlertes($produitId, $lieuId, $stockActuel)
    {
        $seuilCritique = 10; // À paramétrer par produit/lieu

        if ($stockActuel <= $seuilCritique) {
            $typeAlerte = $stockActuel <= 0 ? 'stock_zero' : 'stock_bas';
            $niveauUrgence = $stockActuel <= 0 ? 'critical' : 'warning';

            // Créer l'alerte si elle n'existe pas déjà
            static::firstOrCreate([
                'type_alerte' => $typeAlerte,
                'produit_id' => $produitId,
                'lieu_id' => $lieuId,
                'statut' => 'active'
            ], [
                'quantite_actuelle' => $stockActuel,
                'seuil_critique' => $seuilCritique,
                'niveau_urgence' => $niveauUrgence,
                'message' => "Stock {$typeAlerte} détecté",
                'date_detection' => now()
            ]);
        }
    }
}
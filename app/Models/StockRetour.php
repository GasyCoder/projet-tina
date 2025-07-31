<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StockRetour extends Model
{
    use HasFactory;

    protected $fillable = [
        'dechargement_id', 'reference', 'date_retour',
        'produit_id', 'produit_nom', 'lieu_stockage_id',
        'quantite_kg', 'sacs_pleins', 'sacs_demi',
        'prix_unitaire_mga', 'valeur_totale_mga',
        'client_nom', 'client_contact', 'statut', 'observation'
    ];

    protected $casts = [
        'quantite_kg' => 'decimal:2',
        'prix_unitaire_mga' => 'decimal:2',
        'valeur_totale_mga' => 'decimal:2',
        'date_retour' => 'date',
    ];

    // Relations
    public function dechargement()
    {
        return $this->belongsTo(Dechargement::class);
    }

    public function produit()
    {
        return $this->belongsTo(Produit::class);
    }

    public function lieuStockage()
    {
        return $this->belongsTo(Lieu::class, 'lieu_stockage_id');
    }

    // Créer automatiquement depuis un déchargement
    public static function creerDepuisDecharement(Dechargement $dechargement)
    {
        return self::create([
            'dechargement_id' => $dechargement->id,
            'reference' => 'RET-' . $dechargement->reference,
            'date_retour' => $dechargement->date,
            'produit_id' => $dechargement->chargement->produit_id ?? null,
            'produit_nom' => $dechargement->chargement->produit->nom ?? 'Produit inconnu',
            'lieu_stockage_id' => $dechargement->lieu_livraison_id,
            'quantite_kg' => $dechargement->poids_arrivee_kg,
            'sacs_pleins' => $dechargement->sacs_pleins_arrivee,
            'sacs_demi' => $dechargement->sacs_demi_arrivee,
            'prix_unitaire_mga' => $dechargement->prix_unitaire_mga,
            'valeur_totale_mga' => $dechargement->montant_total_mga,
            'client_nom' => $dechargement->interlocuteur_nom,
            'client_contact' => $dechargement->interlocuteur_contact,
            'statut' => 'en_stock',
            'observation' => 'Créé automatiquement depuis déchargement retour'
        ]);
    }
}
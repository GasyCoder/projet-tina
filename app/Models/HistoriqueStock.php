<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class HistoriqueStock extends Model
{
    use HasFactory;

    protected $fillable = [
        'operation_type',
        'operation_id',
        'produit_id',
        'lieu_id',
        'quantite_avant',
        'quantite_mouvement',
        'quantite_apres',
        'prix_unitaire',
        'valeur_mouvement',
        'reference_operation',
        'user_id',
        'date_operation',
        'metadata'
    ];

    protected $casts = [
        'quantite_avant' => 'decimal:2',
        'quantite_mouvement' => 'decimal:2',
        'quantite_apres' => 'decimal:2',
        'prix_unitaire' => 'decimal:2',
        'valeur_mouvement' => 'decimal:2',
        'date_operation' => 'datetime',
        'metadata' => 'array'
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

    public function utilisateur()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Méthode statique pour enregistrer un mouvement
    public static function enregistrerMouvement(OperationStock $operation)
    {
        $produit = $operation->produit;
        $lieu = $operation->lieuLivraison;

        if (!$produit || !$lieu)
            return;

        // Calculer stock avant
        $stockAvant = static::where('produit_id', $produit->id)
            ->where('lieu_id', $lieu->id)
            ->latest('date_operation')
            ->value('quantite_apres') ?? 0;

        $quantiteMouvement = $operation->calculerImpactStock();
        $stockApres = $stockAvant + $quantiteMouvement;

        static::create([
            'operation_type' => $operation->type,
            'operation_id' => $operation->id,
            'produit_id' => $produit->id,
            'lieu_id' => $lieu->id,
            'quantite_avant' => $stockAvant,
            'quantite_mouvement' => abs($quantiteMouvement),
            'quantite_apres' => $stockApres,
            'prix_unitaire' => $operation->prix_unitaire_mga,
            'valeur_mouvement' => abs($quantiteMouvement) * $operation->prix_unitaire_mga,
            'reference_operation' => $operation->reference,
            'user_id' => Auth::id(),
            'date_operation' => $operation->date
        ]);

        // Vérifier les alertes
        AlerteStock::verifierAlertes($produit->id, $lieu->id, $stockApres);
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Retour extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'dechargements'; // Utilisation de la table dechargements

    protected $fillable = [
        'dechargement_id',
        'voyage_id',
        'chargement_id',
        'reference',
        'type', // Ajout du type pour la différenciation
        'date_retour',
        'proprietaire_nom',
        'proprietaire_contact',
        'produit_id',
        'lieu_stockage_id',
        'quantite_kg',
        'quantite_sacs_pleins',
        'quantite_sacs_demi',
        'prix_unitaire_mga',
        'montant_total_mga',
        'statut',
        'motif_retour',
        'observation',
        'created_by',
        'processed_at'
    ];

    protected $casts = [
        'quantite_kg' => 'decimal:2',
        'prix_unitaire_mga' => 'decimal:2',
        'montant_total_mga' => 'decimal:2',
        'date_retour' => 'date',
        'processed_at' => 'datetime',
    ];

    protected $appends = [
        'quantite_totale_sacs',
        'valeur_stock',
        'statut_lisible'
    ];

    // Scope global pour ne récupérer que les retours
    protected static function booted()
    {
        static::addGlobalScope('retour', function ($query) {
            $query->where('type', 'retour');
        });
    }

    // Relations
    public function dechargement()
    {
        return $this->belongsTo(Dechargement::class);
    }

    public function voyage()
    {
        return $this->belongsTo(Voyage::class);
    }

    public function chargement()
    {
        return $this->belongsTo(Chargement::class);
    }


    public function lieuLivraison()
    {
        return $this->belongsTo(Lieu::class, 'lieu_livraison_id');
    }
    public function produit()
    {
        return $this->hasOneThrough(
            Produit::class,
            Chargement::class,
            'id', // Foreign key sur chargements
            'id', // Foreign key sur produits
            'chargement_id', // Local key sur ventes
            'produit_id' // Local key sur chargements
        );
    }
    public function lieuStockage()
    {
        return $this->belongsTo(Lieu::class, 'lieu_stockage_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Accesseurs
    public function getQuantiteTotaleSacsAttribute()
    {
        return $this->quantite_sacs_pleins + ($this->quantite_sacs_demi * 0.5);
    }

    public function getValeurStockAttribute()
    {
        return $this->quantite_kg * $this->prix_unitaire_mga;
    }

    public function getStatutLisibleAttribute()
    {
        return match($this->statut) {
            'en_stock' => 'En stock',
            'vendu' => 'Vendu',
            'perdu' => 'Perdu',
            default => 'Inconnu'
        };
    }

    // Scopes
    public function scopeEnStock($query)
    {
        return $query->where('statut', 'en_stock');
    }

    public function scopeVendu($query)
    {
        return $query->where('statut', 'vendu');
    }

    public function scopePerdu($query)
    {
        return $query->where('statut', 'perdu');
    }

    public function scopeParProduit($query, $produitId)
    {
        return $query->where('produit_id', $produitId);
    }

    public function scopeParLieu($query, $lieuId)
    {
        return $query->where('lieu_stockage_id', $lieuId);
    }

    public function scopeParPeriode($query, $dateDebut, $dateFin)
    {
        return $query->whereBetween('date_retour', [$dateDebut, $dateFin]);
    }

    // Méthodes métier
    public static function creerDepuisDechargement(Dechargement $dechargement)
    {
        if ($dechargement->type !== 'retour') {
            throw new \InvalidArgumentException('Le déchargement doit être de type retour');
        }

        $retour = self::create([
            'dechargement_id' => $dechargement->id,
            'voyage_id' => $dechargement->voyage_id,
            'chargement_id' => $dechargement->chargement_id,
            'reference' => 'RET-' . $dechargement->reference,
            'type' => 'retour',
            'date_retour' => $dechargement->date,
            'proprietaire_nom' => $dechargement->interlocuteur_nom,
            'proprietaire_contact' => $dechargement->interlocuteur_contact,
            'produit_id' => $dechargement->produit->id ?? null,
            'lieu_stockage_id' => $dechargement->lieu_livraison_id,
            'quantite_kg' => $dechargement->poids_arrivee_kg,
            'quantite_sacs_pleins' => $dechargement->sacs_pleins_arrivee,
            'quantite_sacs_demi' => $dechargement->sacs_demi_arrivee,
            'prix_unitaire_mga' => $dechargement->prix_unitaire_mga,
            'montant_total_mga' => $dechargement->montant_total_mga,
            'statut' => 'en_stock',
            'motif_retour' => $dechargement->observation,
            'observation' => 'Créé automatiquement depuis le déchargement ' . $dechargement->reference,
            'created_by' => auth()->id(),
            'processed_at' => now()
        ]);

        // Mettre à jour le stock
        StockRetour::ajouterStock($retour);

        return $retour;
    }

    public function marquerCommeVendu($acheteur, $prixVente = null)
    {
        $this->update([
            'statut' => 'vendu',
            'observation' => $this->observation . ' | Vendu à: ' . $acheteur,
            'prix_unitaire_mga' => $prixVente ?? $this->prix_unitaire_mga,
            'processed_at' => now()
        ]);

        // Retirer du stock
        StockRetour::retirerStock($this);

        return $this;
    }

    public function marquerCommePerdu($raison)
    {
        $this->update([
            'statut' => 'perdu',
            'observation' => $this->observation . ' | Perdu: ' . $raison,
            'processed_at' => now()
        ]);

        // Retirer du stock
        StockRetour::retirerStock($this);

        return $this;
    }

    public function estEnStock()
    {
        return $this->statut === 'en_stock';
    }

    public function estVendu()
    {
        return $this->statut === 'vendu';
    }

    public function estPerdu()
    {
        return $this->statut === 'perdu';
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Vente extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'dechargements'; // Utilise la même table que Dechargement

    protected $fillable = [
        'voyage_id',
        'chargement_id',
        'reference',
        'type',
        'date',
        'interlocuteur_nom',
        'interlocuteur_contact',
        'pointeur_nom',
        'pointeur_contact',
        'lieu_livraison_id',
        'sacs_pleins_arrivee',
        'sacs_demi_arrivee',
        'poids_arrivee_kg',
        'prix_unitaire_mga',
        'montant_total_mga',
        'paiement_mga',
        'reste_mga',
        'statut_commercial',
        'observation'
    ];

    protected $casts = [
        'poids_arrivee_kg' => 'decimal:2',
        'prix_unitaire_mga' => 'decimal:2',
        'montant_total_mga' => 'decimal:2',
        'paiement_mga' => 'decimal:2',
        'reste_mga' => 'decimal:2',
        'date' => 'date',
    ];

    protected $appends = [
        'client',
        'poids',
        'montant',
        'paiement',
        'reste',
        'statut',
        'prix_unitaire'
    ];

    // Scope global pour ne récupérer que les ventes
    protected static function booted()
    {
        static::addGlobalScope('vente', function ($query) {
            $query->where('type', 'vente');
        });
    }

    // Relations
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

    // Accesseurs pour compatibilité avec le template Blade
    public function getClientAttribute()
    {
        return $this->interlocuteur_nom ?? 'Client inconnu';
    }

    public function getPoidsAttribute()
    {
        return $this->poids_arrivee_kg ?? 0;
    }

    public function getMontantAttribute()
    {
        return $this->montant_total_mga ?? 0;
    }

    public function getPaiementAttribute()
    {
        return $this->paiement_mga ?? 0;
    }

    public function getResteAttribute()
    {
        return $this->reste_mga ?? 0;
    }

    public function getStatutAttribute()
    {
        return $this->statut_commercial ?? 'en_attente';
    }

    public function getPrixUnitaireAttribute()
    {
        return $this->prix_unitaire_mga ?? 0;
    }

    // Accesseurs calculés depuis le chargement
    public function getProprietaireNomAttribute()
    {
        return $this->chargement->proprietaire_nom ?? 'N/A';
    }

    public function getPoidsDepartKgAttribute()
    {
        return $this->chargement->poids_depart_kg ?? 0;
    }

    public function getEcartPoidsAttribute()
    {
        return $this->getPoidsDepartKgAttribute() - ($this->poids_arrivee_kg ?? 0);
    }

    public function getPourcentagePerteAttribute()
    {
        $poidsDepart = $this->getPoidsDepartKgAttribute();
        if ($poidsDepart == 0)
            return 0;
        return ($this->getEcartPoidsAttribute() / $poidsDepart) * 100;
    }

    public function getPourcentagePaiementAttribute()
    {
        $montant = $this->montant_total_mga ?? 0;
        $paiement = $this->paiement_mga ?? 0;

        if ($montant == 0)
            return 0;
        return ($paiement / $montant) * 100;
    }

    // Scopes
    public function scopeAvecReste($query)
    {
        return $query->where('reste_mga', '>', 0);
    }

    public function scopePayeesEntierement($query)
    {
        return $query->where('reste_mga', '<=', 0);
    }

    public function scopeParStatut($query, $statut)
    {
        return $query->where('statut_commercial', $statut);
    }

    public function scopeParClient($query, $client)
    {
        return $query->where('interlocuteur_nom', 'like', '%' . $client . '%');
    }

    public function scopeParPeriode($query, $dateDebut, $dateFin)
    {
        return $query->whereBetween('date', [$dateDebut, $dateFin]);
    }

    // Mutateurs pour faciliter la création
    public function setClientAttribute($value)
    {
        $this->attributes['interlocuteur_nom'] = $value;
    }

    public function setPoidsAttribute($value)
    {
        $this->attributes['poids_arrivee_kg'] = $value;
    }

    public function setMontantAttribute($value)
    {
        $this->attributes['montant_total_mga'] = $value;
    }

    public function setPaiementAttribute($value)
    {
        $this->attributes['paiement_mga'] = $value;
        // Calcul automatique du reste
        $montant = $this->attributes['montant_total_mga'] ?? 0;
        $this->attributes['reste_mga'] = $montant - $value;
    }

    public function setStatutAttribute($value)
    {
        $this->attributes['statut_commercial'] = $value;
    }

    // Méthodes utilitaires
    public function estPayeeEntierement()
    {
        return ($this->reste_mga ?? 0) <= 0;
    }

    public function estEnAttente()
    {
        return $this->statut_commercial === 'en_attente';
    }

    public function estLivree()
    {
        return $this->statut_commercial === 'livre';
    }

    public function estValide()
    {
        return $this->statut_commercial === 'valide';
    }

    public function estAnnelee()
    {
        return $this->statut_commercial === 'annule';
    }

    public function peutEtreModifiee()
    {
        return !$this->estLivree();
    }

    public function peutEtreSupprimee()
    {
        return $this->estEnAttente() || $this->estValide();
    }
}
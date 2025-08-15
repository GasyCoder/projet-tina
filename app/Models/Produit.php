<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produit extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'variete',
        'unite',
        'poids_moyen_sac_kg_max',
        'qte_variable', // Ajouté
        'prix_reference_mga',
        'description',
        'actif'
    ];

    protected $casts = [
        'poids_moyen_sac_kg_max' => 'decimal:2',
        'qte_variable' => 'decimal:2', // Ajouté
        'prix_reference_mga' => 'decimal:2',
        'actif' => 'boolean'
    ];

    // Relations
    public function chargements()
    {
        return $this->hasMany(Chargement::class);
    }

    public function dechargements()
    {
        return $this->hasMany(Dechargement::class);
    }

    // Accesseurs
    public function getPrixReferenceMgaFormattedAttribute()
    {
        return number_format($this->prix_reference_mga, 0, ',', ' ') . ' MGA';
    }

    public function getPoidsMoyenSacKgFormattedAttribute()
    {
        return number_format($this->poids_moyen_sac_kg_max, 2, ',', ' ') . ' ' . ($this->unite ?? '');
    }

    public function getQteVariableFormattedAttribute()
    {
        return number_format($this->qte_variable, 2, ',', ' ') . ' ' . ($this->unite ?? '');
    }

    public function getNomCompletAttribute()
    {
        return $this->nom . ($this->variete ? ' (' . $this->variete . ')' : '');
    }

    // Méthodes pour la gestion des stocks
    public function getStockActuelAttribute()
    {
        return $this->qte_variable; // Utilise qte_variable comme stock actuel
    }

    public function getCapaciteMaximaleAttribute()
    {
        return $this->poids_moyen_sac_kg_max; // Capacité maximale de stockage
    }

    public function peutStockerQuantite($quantite)
    {
        return ($this->qte_variable + $quantite) <= $this->poids_moyen_sac_kg_max;
    }

    public function peutVendreQuantite($quantite)
    {
        return $this->qte_variable >= $quantite;
    }

    // Scopes
    public function scopeActif($query)
    {
        return $query->where('actif', true);
    }

    public function scopeParNom($query, $nom)
    {
        return $query->where('nom', 'like', '%' . $nom . '%');
    }

    public function scopeAvecStock($query)
    {
        return $query->where('qte_variable', '>', 0);
    }

    public function scopeStockFaible($query, $seuil = 10)
    {
        return $query->where('qte_variable', '<=', $seuil);
    }


    public function getCapaciteDisponibleAttribute()
    {
        return max(0, $this->poids_moyen_sac_kg_max - $this->qte_variable);
    }

    public function getPourcentageUtilisationAttribute()
    {
        if ($this->poids_moyen_sac_kg_max <= 0) {
            return 0;
        }
        
        return ($this->qte_variable / $this->poids_moyen_sac_kg_max) * 100;
    }

    public function getStatutStockAttribute()
    {
        if ($this->qte_variable == 0) {
            return 'vide';
        } elseif ($this->qte_variable <= $this->poids_moyen_sac_kg_max * 0.2) {
            return 'faible';
        } elseif ($this->qte_variable <= $this->poids_moyen_sac_kg_max * 0.8) {
            return 'normal';
        } else {
            return 'plein';
        }
    }
}
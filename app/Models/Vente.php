<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Vente extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'numero_vente',
        'date_vente',
        'chargement_id',
        'produit_id',
        'lieu_livraison_id',
        'client_nom',
        'client_contact',
        'client_adresse',
        'client_type',
        'quantite_kg',
        'sacs_pleins',
        'sacs_demi',
        'prix_unitaire_mga',
        'prix_total_mga',
        'montant_paye_mga',
        'montant_restant_mga',
        'statut_paiement',
        'mode_paiement',
        'transporteur_nom',
        'vehicule_immatriculation',
        'frais_transport_mga',
        'date_livraison_prevue',
        'date_livraison_reelle',
        'statut_vente',
        'statut_livraison',
        'qualite_produit',
        'observations',
        'remarques_client',
        'user_creation_id',
        'user_validation_id',
        'date_validation',
        'numero_facture',
        'date_facture',
        'facture_envoyee',
        'tva_taux',
        'tva_montant_mga'
    ];

    protected $casts = [
        'date_vente' => 'date',
        'date_livraison_prevue' => 'datetime',
        'date_livraison_reelle' => 'datetime',
        'date_validation' => 'datetime',
        'date_facture' => 'date',
        'quantite_kg' => 'decimal:2',
        'prix_unitaire_mga' => 'decimal:2',
        'prix_total_mga' => 'decimal:2',
        'montant_paye_mga' => 'decimal:2',
        'montant_restant_mga' => 'decimal:2',
        'frais_transport_mga' => 'decimal:2',
        'tva_taux' => 'decimal:2',
        'tva_montant_mga' => 'decimal:2',
        'facture_envoyee' => 'boolean',
        'sacs_pleins' => 'integer',
        'sacs_demi' => 'integer'
    ];

    protected $appends = [
        'statut_badge',
        'statut_paiement_badge',
        'total_sacs',
        'benefice_estimee'
    ];

    // Relations
    public function chargement()
    {
        return $this->belongsTo(Chargement::class);
    }

    public function produit()
    {
        return $this->belongsTo(Produit::class);
    }

    public function lieuLivraison()
    {
        return $this->belongsTo(Lieu::class, 'lieu_livraison_id');
    }

    public function userCreation()
    {
        return $this->belongsTo(User::class, 'user_creation_id');
    }

    public function userValidation()
    {
        return $this->belongsTo(User::class, 'user_validation_id');
    }

    public function retours()
    {
        return $this->hasMany(Retour::class);
    }

    // public function paiements()
    // {
    //     return $this->hasMany(PaiementVente::class);
    // }

    // Accesseurs
    public function getStatutBadgeAttribute()
    {
        return match ($this->statut_vente) {
            'brouillon' => ['class' => 'bg-gray-100 text-gray-800', 'text' => 'Brouillon'],
            'confirmee' => ['class' => 'bg-blue-100 text-blue-800', 'text' => 'Confirmée'],
            'en_preparation' => ['class' => 'bg-yellow-100 text-yellow-800', 'text' => 'En préparation'],
            'expediee' => ['class' => 'bg-orange-100 text-orange-800', 'text' => 'Expédiée'],
            'livree' => ['class' => 'bg-green-100 text-green-800', 'text' => 'Livrée'],
            'annulee' => ['class' => 'bg-red-100 text-red-800', 'text' => 'Annulée'],
            default => ['class' => 'bg-gray-100 text-gray-800', 'text' => 'Inconnu']
        };
    }

    public function getStatutPaiementBadgeAttribute()
    {
        return match ($this->statut_paiement) {
            'impaye' => ['class' => 'bg-red-100 text-red-800', 'text' => 'Impayé'],
            'partiel' => ['class' => 'bg-yellow-100 text-yellow-800', 'text' => 'Partiel'],
            'paye' => ['class' => 'bg-green-100 text-green-800', 'text' => 'Payé'],
            'rembourse' => ['class' => 'bg-purple-100 text-purple-800', 'text' => 'Remboursé'],
            default => ['class' => 'bg-gray-100 text-gray-800', 'text' => 'Inconnu']
        };
    }

    public function getTotalSacsAttribute()
    {
        return $this->sacs_pleins + ($this->sacs_demi * 0.5);
    }

    public function getBeneficeEstimeeAttribute()
    {
        $coutAchat = $this->chargement?->prix_achat_total_mga ?? 0;
        return $this->prix_total_mga - $coutAchat;
    }

    // Scopes
    public function scopeEnRetard($query)
    {
        return $query->where('date_livraison_prevue', '<', now())
            ->whereNotIn('statut_vente', ['livree', 'annulee']);
    }

    public function scopeParStatutPaiement($query, $statut)
    {
        return $query->where('statut_paiement', $statut);
    }

    public function scopeParPeriode($query, $dateDebut, $dateFin)
    {
        return $query->whereBetween('date_vente', [$dateDebut, $dateFin]);
    }

    // Méthodes métier
    public function confirmer()
    {
        $this->update([
            'statut_vente' => 'confirmee',
            'user_validation_id' => auth()->id(),
            'date_validation' => now()
        ]);

        HistoriqueMouvement::creerPourVente($this);
    }

    public function annuler($motif = null)
    {
        $this->update([
            'statut_vente' => 'annulee',
            'observations' => $this->observations . ' | Annulée: ' . $motif
        ]);
    }

    public function ajouterPaiement($montant, $mode = 'especes')
    {
        $this->paiements()->create([
            'montant_mga' => $montant,
            'mode_paiement' => $mode,
            'date_paiement' => now(),
            'user_id' => auth()->id()
        ]);

        $this->calculerStatutPaiement();
    }

    private function calculerStatutPaiement()
    {
        $totalPaye = $this->paiements()->sum('montant_mga');

        if ($totalPaye >= $this->prix_total_mga) {
            $statut = 'paye';
            $restant = 0;
        } elseif ($totalPaye > 0) {
            $statut = 'partiel';
            $restant = $this->prix_total_mga - $totalPaye;
        } else {
            $statut = 'impaye';
            $restant = $this->prix_total_mga;
        }

        $this->update([
            'statut_paiement' => $statut,
            'montant_paye_mga' => $totalPaye,
            'montant_restant_mga' => $restant
        ]);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (!$model->numero_vente) {
                $count = static::whereDate('date_vente', $model->date_vente ?? today())->count() + 1;
                $model->numero_vente = 'VTE' . date('Ymd') . str_pad($count, 4, '0', STR_PAD_LEFT);
            }
        });
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Retour extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'numero_retour',
        'date_retour',
        'vente_id',
        'produit_id',
        'lieu_stockage_id',
        'client_nom',
        'client_contact',
        'quantite_retour_kg',
        'sacs_pleins_retour',
        'sacs_demi_retour',
        'motif_retour',
        'description_motif',
        'responsabilite',
        'etat_produit',
        'produit_revendable',
        'valeur_recuperable_mga',
        'perte_estimee_mga',
        'statut_retour',
        'action_prise',
        'montant_rembourse_mga',
        'date_traitement',
        'transporteur_retour',
        'frais_retour_mga',
        'prise_charge_frais',
        'photos_produit',
        'documents_justificatifs',
        'user_reception_id',
        'user_traitement_id',
        'observations'
    ];

    protected $casts = [
        'date_retour' => 'date',
        'date_traitement' => 'date',
        'quantite_retour_kg' => 'decimal:2',
        'valeur_recuperable_mga' => 'decimal:2',
        'perte_estimee_mga' => 'decimal:2',
        'montant_rembourse_mga' => 'decimal:2',
        'frais_retour_mga' => 'decimal:2',
        'produit_revendable' => 'boolean',
        'photos_produit' => 'array',
        'documents_justificatifs' => 'array',
        'sacs_pleins_retour' => 'integer',
        'sacs_demi_retour' => 'integer'
    ];

    protected $appends = ['statut_badge', 'motif_badge', 'total_sacs_retour'];

    // Relations
    public function vente()
    {
        return $this->belongsTo(Vente::class);
    }

    public function produit()
    {
        return $this->belongsTo(Produit::class);
    }

    public function lieuStockage()
    {
        return $this->belongsTo(Lieu::class, 'lieu_stockage_id');
    }

    public function userReception()
    {
        return $this->belongsTo(User::class, 'user_reception_id');
    }

    public function userTraitement()
    {
        return $this->belongsTo(User::class, 'user_traitement_id');
    }

    // Accesseurs
    public function getStatutBadgeAttribute()
    {
        return match ($this->statut_retour) {
            'en_attente' => ['class' => 'bg-yellow-100 text-yellow-800', 'text' => 'En attente'],
            'accepte' => ['class' => 'bg-green-100 text-green-800', 'text' => 'Accepté'],
            'refuse' => ['class' => 'bg-red-100 text-red-800', 'text' => 'Refusé'],
            'en_cours_traitement' => ['class' => 'bg-blue-100 text-blue-800', 'text' => 'En traitement'],
            'traite' => ['class' => 'bg-purple-100 text-purple-800', 'text' => 'Traité'],
            'archive' => ['class' => 'bg-gray-100 text-gray-800', 'text' => 'Archivé'],
            default => ['class' => 'bg-gray-100 text-gray-800', 'text' => 'Inconnu']
        };
    }

    public function getMotifBadgeAttribute()
    {
        return match ($this->motif_retour) {
            'defaut_qualite' => ['class' => 'bg-red-100 text-red-800', 'text' => 'Défaut qualité'],
            'erreur_livraison' => ['class' => 'bg-orange-100 text-orange-800', 'text' => 'Erreur livraison'],
            'annulation_client' => ['class' => 'bg-blue-100 text-blue-800', 'text' => 'Annulation'],
            'surplus' => ['class' => 'bg-green-100 text-green-800', 'text' => 'Surplus'],
            'autre' => ['class' => 'bg-gray-100 text-gray-800', 'text' => 'Autre'],
            default => ['class' => 'bg-gray-100 text-gray-800', 'text' => 'Non défini']
        };
    }

    public function getTotalSacsRetourAttribute()
    {
        return $this->sacs_pleins_retour + ($this->sacs_demi_retour * 0.5);
    }

    // Méthodes métier
    public function accepter()
    {
        $this->update([
            'statut_retour' => 'accepte',
            'user_traitement_id' => auth()->id()
        ]);

        if ($this->produit_revendable) {
            DepotStock::ajouterRetour($this);
        }

        HistoriqueMouvement::creerPourRetour($this);
    }

    public function refuser($motif)
    {
        $this->update([
            'statut_retour' => 'refuse',
            'observations' => $this->observations . ' | Refusé: ' . $motif,
            'user_traitement_id' => auth()->id()
        ]);
    }

    public function traiter($action, $montant = 0)
    {
        $this->update([
            'statut_retour' => 'traite',
            'action_prise' => $action,
            'montant_rembourse_mga' => $montant,
            'date_traitement' => now(),
            'user_traitement_id' => auth()->id()
        ]);

        if ($action === 'remboursement' && $montant > 0) {
            $this->vente->ajouterPaiement(-$montant, 'retour');
        }
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (!$model->numero_retour) {
                $count = static::whereDate('date_retour', $model->date_retour ?? today())->count() + 1;
                $model->numero_retour = 'RET' . date('Ymd') . str_pad($count, 4, '0', STR_PAD_LEFT);
            }
        });
    }
}

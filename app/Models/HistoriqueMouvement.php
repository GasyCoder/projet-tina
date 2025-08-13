<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class HistoriqueMouvement extends Model
{
    use HasFactory;

    protected $table = 'historique_mouvements';

    protected $fillable = [
        'numero_mouvement',
        'type_operation',
        'date_operation',
        'produit_id',
        'depot_id',
        'operation_type',
        'operation_id',
        'quantite_avant_kg',
        'quantite_mouvement_kg',
        'quantite_apres_kg',
        'sens_mouvement',
        'prix_unitaire_mga',
        'valeur_mouvement_mga',
        'user_id',
        'user_nom',
        'observations'
    ];

    protected $casts = [
        'date_operation' => 'date',
        'quantite_avant_kg' => 'decimal:2',
        'quantite_mouvement_kg' => 'decimal:2',
        'quantite_apres_kg' => 'decimal:2',
        'prix_unitaire_mga' => 'decimal:2',
        'valeur_mouvement_mga' => 'decimal:2'
    ];

    protected $appends = ['operation_badge', 'sens_badge'];

    // Relations
    public function produit()
    {
        return $this->belongsTo(Produit::class);
    }

    public function depot()
    {
        return $this->belongsTo(Lieu::class, 'depot_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function operation()
    {
        return $this->morphTo();
    }

    // Accesseurs
    public function getOperationBadgeAttribute()
    {
        return match ($this->type_operation) {
            'vente' => ['class' => 'bg-green-100 text-green-800', 'text' => '💰 Vente'],
            'retour' => ['class' => 'bg-orange-100 text-orange-800', 'text' => '↩️ Retour'],
            'entree_depot' => ['class' => 'bg-blue-100 text-blue-800', 'text' => '📥 Entrée'],
            'sortie_depot' => ['class' => 'bg-red-100 text-red-800', 'text' => '📤 Sortie'],
            'transfert' => ['class' => 'bg-purple-100 text-purple-800', 'text' => '🔄 Transfert'],
            'ajustement' => ['class' => 'bg-yellow-100 text-yellow-800', 'text' => '⚖️ Ajustement'],
            'inventaire' => ['class' => 'bg-gray-100 text-gray-800', 'text' => '📊 Inventaire'],
            default => ['class' => 'bg-gray-100 text-gray-800', 'text' => 'Autre']
        };
    }

    public function getSensBadgeAttribute()
    {
        return match ($this->sens_mouvement) {
            'entree' => ['class' => 'bg-green-100 text-green-800', 'text' => '↗️ Entrée'],
            'sortie' => ['class' => 'bg-red-100 text-red-800', 'text' => '↙️ Sortie'],
            default => ['class' => 'bg-gray-100 text-gray-800', 'text' => 'Neutre']
        };
    }

    // Méthodes statiques pour créer l'historique
    public static function creerPourVente(Vente $vente)
    {
        return static::create([
            'numero_mouvement' => 'HIST-' . $vente->numero_vente,
            'type_operation' => 'vente',
            'date_operation' => $vente->date_vente,
            'produit_id' => $vente->produit_id,
            'depot_id' => $vente->lieu_livraison_id,
            'operation_type' => Vente::class,
            'operation_id' => $vente->id,
            'quantite_mouvement_kg' => $vente->quantite_kg,
            'sens_mouvement' => 'sortie',
            'prix_unitaire_mga' => $vente->prix_unitaire_mga,
            'valeur_mouvement_mga' => $vente->prix_total_mga,
            'user_id' => $vente->user_creation_id,
            'user_nom' => $vente->userCreation->name,
            'observations' => 'Vente à ' . $vente->client_nom
        ]);
    }

    public static function creerPourRetour(Retour $retour)
    {
        return static::create([
            'numero_mouvement' => 'HIST-' . $retour->numero_retour,
            'type_operation' => 'retour',
            'date_operation' => $retour->date_retour,
            'produit_id' => $retour->produit_id,
            'depot_id' => $retour->lieu_stockage_id,
            'operation_type' => Retour::class,
            'operation_id' => $retour->id,
            'quantite_mouvement_kg' => $retour->quantite_retour_kg,
            'sens_mouvement' => 'entree',
            'valeur_mouvement_mga' => $retour->valeur_recuperable_mga,
            'user_id' => $retour->user_reception_id,
            'user_nom' => $retour->userReception->name,
            'observations' => 'Retour de ' . $retour->client_nom . ' - ' . $retour->motif_retour
        ]);
    }

    public static function creerPourTransfert(TransfertStock $transfert, $phase)
    {
        $depotId = $phase === 'expedition' ? $transfert->depot_origine_id : $transfert->depot_destination_id;
        $sens = $phase === 'expedition' ? 'sortie' : 'entree';

        return static::create([
            'numero_mouvement' => 'HIST-' . $transfert->numero_transfert . '-' . strtoupper($phase),
            'type_operation' => 'transfert',
            'date_operation' => $phase === 'expedition' ? $transfert->date_expedition_reelle : $transfert->date_reception_reelle,
            'depot_id' => $depotId,
            'operation_type' => TransfertStock::class,
            'operation_id' => $transfert->id,
            'sens_mouvement' => $sens,
            'user_id' => auth()->id(),
            'user_nom' => auth()->user()->name,
            'observations' => ucfirst($phase) . ' - Transfert entre ' . $transfert->depotOrigine->nom . ' et ' . $transfert->depotDestination->nom
        ]);
    }
}
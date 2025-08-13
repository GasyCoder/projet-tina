<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;




class AlerteStock extends Model
{
    use HasFactory;

    protected $table = 'alertes_stock';

    protected $fillable = [
        'type_alerte',
        'niveau_urgence',
        'produit_id',
        'depot_id',
        'stock_id',
        'titre_alerte',
        'message_alerte',
        'seuil_defini',
        'valeur_actuelle',
        'alerte_active',
        'alerte_vue',
        'alerte_traitee',
        'date_alerte',
        'date_vue',
        'date_traitement',
        'user_destinataire_id',
        'user_traitement_id',
        'action_prise'
    ];

    protected $casts = [
        'date_alerte' => 'datetime',
        'date_vue' => 'datetime',
        'date_traitement' => 'datetime',
        'seuil_defini' => 'decimal:2',
        'valeur_actuelle' => 'decimal:2',
        'alerte_active' => 'boolean',
        'alerte_vue' => 'boolean',
        'alerte_traitee' => 'boolean'
    ];

    protected $appends = ['urgence_badge', 'type_badge'];

    // Relations
    public function produit()
    {
        return $this->belongsTo(Produit::class);
    }

    public function depot()
    {
        return $this->belongsTo(Lieu::class, 'depot_id');
    }

    public function stock()
    {
        return $this->belongsTo(Depots::class, 'stock_id');
    }

    public function userDestinataire()
    {
        return $this->belongsTo(User::class, 'user_destinataire_id');
    }

    public function userTraitement()
    {
        return $this->belongsTo(User::class, 'user_traitement_id');
    }

    // Accesseurs
    public function getUrgenceBadgeAttribute()
    {
        return match ($this->niveau_urgence) {
            'critique' => ['class' => 'bg-red-100 text-red-800', 'text' => '🔴 Critique'],
            'urgent' => ['class' => 'bg-orange-100 text-orange-800', 'text' => '🟠 Urgent'],
            'attention' => ['class' => 'bg-yellow-100 text-yellow-800', 'text' => '🟡 Attention'],
            'info' => ['class' => 'bg-blue-100 text-blue-800', 'text' => '🔵 Info'],
            default => ['class' => 'bg-gray-100 text-gray-800', 'text' => 'Inconnu']
        };
    }

    public function getTypeBadgeAttribute()
    {
        return match ($this->type_alerte) {
            'stock_bas' => ['class' => 'bg-orange-100 text-orange-800', 'text' => '📉 Stock bas'],
            'stock_zero' => ['class' => 'bg-red-100 text-red-800', 'text' => '❌ Stock zéro'],
            'peremption_proche' => ['class' => 'bg-yellow-100 text-yellow-800', 'text' => '⏰ Péremption'],
            'qualite_degradee' => ['class' => 'bg-purple-100 text-purple-800', 'text' => '⚠️ Qualité'],
            'mouvement_suspect' => ['class' => 'bg-red-100 text-red-800', 'text' => '🚨 Suspect'],
            default => ['class' => 'bg-gray-100 text-gray-800', 'text' => 'Autre']
        };
    }

    // Scopes
    public function scopeActives($query)
    {
        return $query->where('alerte_active', true);
    }

    public function scopeNonVues($query)
    {
        return $query->where('alerte_vue', false);
    }

    public function scopeNonTraitees($query)
    {
        return $query->where('alerte_traitee', false);
    }

    public function scopeParNiveauUrgence($query, $niveau)
    {
        return $query->where('niveau_urgence', $niveau);
    }

    public function scopeParUtilisateur($query, $userId)
    {
        return $query->where('user_destinataire_id', $userId);
    }

    // Méthodes métier
    public static function creerAlerte($type, $stock, $valeurActuelle, $seuil)
    {
        $messages = [
            'stock_bas' => 'Le stock de ' . $stock->produit->nom . ' est en dessous du seuil minimum',
            'stock_zero' => 'Le stock de ' . $stock->produit->nom . ' est épuisé',
            'peremption_proche' => 'Le stock de ' . $stock->produit->nom . ' expire bientôt',
            'qualite_degradee' => 'La qualité du stock de ' . $stock->produit->nom . ' s\'est dégradée'
        ];

        $niveaux = [
            'stock_bas' => 'attention',
            'stock_zero' => 'critique',
            'peremption_proche' => 'urgent',
            'qualite_degradee' => 'urgent'
        ];

        return static::create([
            'type_alerte' => $type,
            'niveau_urgence' => $niveaux[$type] ?? 'info',
            'produit_id' => $stock->produit_id,
            'depot_id' => $stock->depot_id,
            'stock_id' => $stock->id,
            'titre_alerte' => 'Alerte de stock - ' . $stock->depot->nom,
            'message_alerte' => $messages[$type] ?? 'Alerte de stock',
            'seuil_defini' => $seuil,
            'valeur_actuelle' => $valeurActuelle,
            'date_alerte' => now(),
            'user_destinataire_id' => $stock->proprietaire_id ?? 1, // Admin par défaut
            'alerte_active' => true
        ]);
    }

    public function marquerVue()
    {
        $this->update([
            'alerte_vue' => true,
            'date_vue' => now()
        ]);
    }

    public function traiter($action)
    {
        $this->update([
            'alerte_traitee' => true,
            'date_traitement' => now(),
            'user_traitement_id' => auth()->id(),
            'action_prise' => $action
        ]);
    }

    public function desactiver()
    {
        $this->update(['alerte_active' => false]);
    }
}
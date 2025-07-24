<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'type',
        'contact',
        'adresse',
        'actif'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'actif' => 'boolean'
    ];

    // Relations - En tant que chauffeur
    public function voyagesChauffeur()
    {
        return $this->hasMany(Voyage::class, 'chauffeur_id');
    }

    // Relations - En tant que chargeur
    public function chargementsChargeur()
    {
        return $this->hasMany(Chargement::class, 'chargeur_id');
    }

    // Relations - En tant que propriétaire
    public function chargementsProprietaire()
    {
        return $this->hasMany(Chargement::class, 'proprietaire_id');
    }

    public function dechargementsProprietaire()
    {
        return $this->hasMany(Dechargement::class, 'proprietaire_id');
    }

    public function stockDepots()
    {
        return $this->hasMany(StockDepot::class, 'proprietaire_id');
    }

    public function transferts()
    {
        return $this->hasMany(TransfertStock::class, 'proprietaire_id');
    }

    // Relations - En tant qu'interlocuteur/pointeur
    public function dechargementsInterlocuteur()
    {
        return $this->hasMany(Dechargement::class, 'interlocuteur_id');
    }

    public function dechargementsPointeur()
    {
        return $this->hasMany(Dechargement::class, 'pointeur_id');
    }

    // Scopes
    public function scopeChauffeurs($query)
    {
        return $query->where('type', 'chauffeur');
    }

    public function scopeProprietaires($query)
    {
        return $query->where('type', 'proprietaire');
    }

    public function scopeAdmin($query)
    {
        return $query->where('type', 'admin');
    }

    // Méthodes utilitaires
    public function isAdmin()
    {
        return $this->type === 'admin';
    }

    public function isChauffeur()
    {
        return $this->type === 'chauffeur';
    }

    public function isProprietaire()
    {
        return $this->type === 'proprietaire';
    }


    // Relations financières
    public function transactionsEnvoyees()
    {
        return $this->hasMany(Transaction::class, 'from_user_id');
    }

    public function transactionsRecues()
    {
        return $this->hasMany(Transaction::class, 'to_user_id');
    }

    // Toutes les transactions de cet utilisateur (envoyées + reçues)
    public function toutesTransactions()
    {
        return Transaction::where('from_user_id', $this->id)
                        ->orWhere('to_user_id', $this->id);
    }

    public function comptes()
    {
        return $this->hasMany(Compte::class);
    }

    public function facturesClients()
    {
        return $this->hasMany(Facture::class, 'client_id');
    }

    public function facturesVendeur()
    {
        return $this->hasMany(Facture::class, 'vendeur_id');
    }

    // Méthodes utilitaires financières
    public function getSoldeTotal()
    {
        return $this->comptes()->sum('solde_actuel_mga');
    }

    public function getSoldePrincipal()
    {
        return $this->comptes()
                    ->where('type_compte', 'principal')
                    ->sum('solde_actuel_mga');
    }

    public function getSoldeMobileMoney()
    {
        return $this->comptes()
                    ->where('type_compte', 'mobile_money')
                    ->sum('solde_actuel_mga');
    }

    public function getSoldeBanque()
    {
        return $this->comptes()
                    ->where('type_compte', 'banque')
                    ->sum('solde_actuel_mga');
    }

    // Statistiques financières
    public function getTotalVentesMois($mois = null)
    {
        $mois = $mois ?: now()->format('Y-m');
        
        return $this->transactionsRecues()
                    ->where('type', 'vente')
                    ->whereDate('date', 'like', $mois . '%')
                    ->sum('montant_mga');
    }

    public function getTotalAchatsMois($mois = null)
    {
        $mois = $mois ?: now()->format('Y-m');
        
        return $this->transactionsEnvoyees()
                    ->where('type', 'achat')
                    ->whereDate('date', 'like', $mois . '%')
                    ->sum('montant_mga');
    }

    public function getBeneficeMois($mois = null)
    {
        return $this->getTotalVentesMois($mois) - $this->getTotalAchatsMois($mois);
    }

    // Factures impayées
    public function getFacturesImpayees()
    {
        return $this->facturesVendeur()
                    ->where('montant_restant_mga', '>', 0)
                    ->sum('montant_restant_mga');
    }

    public function getDettesEnCours()
    {
        return $this->facturesClients()
                    ->where('montant_restant_mga', '>', 0)
                    ->sum('montant_restant_mga');
    }

    // Rechercher transactions par nom (même si personne externe)
    public function getTransactionsAvecPersonne($nomPersonne)
    {
        return Transaction::where(function ($query) use ($nomPersonne) {
            // Comme expéditeur
            $query->where('from_user_id', $this->id)
                ->where(function ($subQuery) use ($nomPersonne) {
                    $subQuery->where('to_nom', 'like', '%' . $nomPersonne . '%')
                            ->orWhereHas('toUser', function ($userQuery) use ($nomPersonne) {
                                $userQuery->where('name', 'like', '%' . $nomPersonne . '%');
                            });
                });
        })->orWhere(function ($query) use ($nomPersonne) {
            // Comme destinataire
            $query->where('to_user_id', $this->id)
                ->where(function ($subQuery) use ($nomPersonne) {
                    $subQuery->where('from_nom', 'like', '%' . $nomPersonne . '%')
                            ->orWhereHas('fromUser', function ($userQuery) use ($nomPersonne) {
                                $userQuery->where('name', 'like', '%' . $nomPersonne . '%');
                            });
                });
        });
    }

    // Créer une transaction rapidement
    public function envoyerArgent($destinataire, $montant, $objet, $type = 'transfert', $modePayement = 'especes')
    {
        $transactionData = [
            'reference' => Transaction::genererReference($type),
            'date' => now()->toDateString(),
            'type' => $type,
            'from_user_id' => $this->id,
            'montant_mga' => $montant,
            'objet' => $objet,
            'mode_paiement' => $modePayement
        ];

        // Si destinataire est un User
        if ($destinataire instanceof User) {
            $transactionData['to_user_id'] = $destinataire->id;
        } else {
            // Si destinataire est un nom libre
            $transactionData['to_nom'] = $destinataire;
        }

        return Transaction::create($transactionData);
    }

    public function recevoirArgent($expediteur, $montant, $objet, $type = 'transfert', $modePayement = 'especes')
    {
        $transactionData = [
            'reference' => Transaction::genererReference($type),
            'date' => now()->toDateString(),
            'type' => $type,
            'to_user_id' => $this->id,
            'montant_mga' => $montant,
            'objet' => $objet,
            'mode_paiement' => $modePayement
        ];

        // Si expéditeur est un User
        if ($expediteur instanceof User) {
            $transactionData['from_user_id'] = $expediteur->id;
        } else {
            // Si expéditeur est un nom libre
            $transactionData['from_nom'] = $expediteur;
        }

        return Transaction::create($transactionData);
    }

    // Accesseurs formatés
    public function getSoldeTotalFormattedAttribute()
    {
        return number_format($this->getSoldeTotal(), 0, ',', ' ') . ' MGA';
    }

    public function getBeneficeMoisFormattedAttribute()
    {
        $benefice = $this->getBeneficeMois();
        $couleur = $benefice >= 0 ? 'success' : 'danger';
        $signe = $benefice >= 0 ? '+' : '';
        
        return [
            'montant' => number_format($benefice, 0, ',', ' ') . ' MGA',
            'couleur' => $couleur,
            'signe' => $signe
        ];
    }
}

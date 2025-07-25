<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vente extends Model
{
    // Si ta table ne suit pas la convention "ventes" (pluriel),
    // précise le nom avec cette propriété :
    // protected $table = 'nom_de_ta_table';

    // Si tu as des colonnes à protéger ou autoriser en mass assignment :
    // protected $fillable = ['montant', 'statut', 'created_at', ...];

    // Si tu utilises les timestamps Laravel (created_at, updated_at)
    // tu peux les laisser comme ça (true par défaut)
    // sinon mettre protected $timestamps = false;
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Alerte extends Model
{
    protected $fillable = [
        'user_id', 'type', 'titre', 'message', 'data', 'lu', 'date_alerte'
    ];

    protected $casts = [
        'data' => 'array',
        'lu' => 'boolean',
        'date_alerte' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Créer une alerte simple
    public static function creerAlerte($user_id, $type, $titre, $message, $data = null)
    {
        return static::create([
            'user_id' => $user_id,
            'type' => $type,
            'titre' => $titre,
            'message' => $message,
            'data' => $data,
            'date_alerte' => now()
        ]);
    }
}

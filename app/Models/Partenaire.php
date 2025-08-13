<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Partenaire extends Model
{
    use HasFactory;

    protected $table = 'partenaires';

    protected $fillable = [
        'nom',
        'telephone',
        'adresse',
        'type',
        'is_active',
    ];

    protected $attributes = [
        'type' => 'fournisseur',
        'is_active' => true,
    ];
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVentesTable extends Migration
{
    public function up()
    {
        Schema::create('ventes', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique();
            $table->date('date');
            $table->text('objet')->nullable();
            $table->foreignId('depot_id')->nullable()->constrained('lieux')->onDelete('cascade'); // type = depot / boutique / magazin
            $table->string('vendeur_nom')->nullable();
            // Informations paiement
            $table->decimal('montant_paye_mga', 15, 2)->default(0);
            $table->decimal('montant_restant_mga', 15, 2)->default(0);
            $table->enum('statut_paiement', ['paye','partiel'])->default('paye');
            $table->enum('mode_paiement', [
                'especes',
                'AirtelMoney', 
                'OrangeMoney', 
                'Mvola', 
                'banque',
            ])->default('especes');

            $table->text('observation')->nullable();    
            $table->timestamps();
            $table->softDeletes();

            // Index pour optimiser les recherches
            $table->index(['date', 'statut_paiement', 'vendeur_nom']);
            $table->index(['depot_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('ventes');
    }
}
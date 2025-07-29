<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transferts_stock', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stock_origine_id')->constrained('stock_depots');
            $table->foreignId('depot_origine_id')->constrained('lieux');
            $table->foreignId('depot_destination_id')->constrained('lieux');
            $table->foreignId('proprietaire_id')->constrained('users');
            $table->foreignId('vehicule_id')->constrained('vehicules');
            $table->date('date_transfert');
            
            // Quantités transférées
            $table->integer('sacs_pleins');
            $table->integer('sacs_demi')->default(0);
            $table->decimal('poids_kg', 10, 2);
            
            // Informations transfert
            $table->enum('motif', ['prix_bas', 'meilleur_marche', 'demande_client', 'autre']);
            $table->enum('statut', ['en_cours', 'termine', 'annule'])->default('en_cours');
            $table->decimal('cout_transport_mga', 12, 2)->nullable(); // Coût en MGA
            
            $table->text('observation')->nullable(); // Français + Malagasy
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transfert_stocks');
    }
};

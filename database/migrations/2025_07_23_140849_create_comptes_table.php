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
        Schema::create('comptes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users'); // Optionnel maintenant
            $table->string('nom_proprietaire')->nullable(); // Nom libre du propriétaire
            $table->enum('type_compte', [
                'principal',     // Compte principal espèces
                'mobile_money',  // Airtel Money, Orange Money, MVola
                'banque',       // Compte bancaire
                'credit'        // Solde crédit/dette
            ]);
            $table->string('nom_compte'); // 'Espèces', 'Airtel Money', 'BOA 207142800027'
            $table->string('numero_compte')->nullable(); // Numéro de compte
            $table->decimal('solde_actuel_mga', 15, 2)->default(0); // Solde en MGA
            $table->foreignId('derniere_transaction_id')->nullable()->constrained('transactions');
            $table->boolean('actif')->default(true);
            $table->timestamps();
            
            // Index
            $table->index(['user_id', 'nom_proprietaire']);
            $table->index(['type_compte', 'nom_compte']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comptes');
    }
};

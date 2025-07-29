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
        Schema::create('factures', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique(); // F001, F002
            $table->date('date');
            
            // Client - SAISIE LIBRE
            $table->string('client_nom'); // Nom libre du client
            $table->foreignId('client_id')->nullable()->constrained('users'); // Lien optionnel
            
            // Vendeur - SAISIE LIBRE  
            $table->string('vendeur_nom'); // Nom libre du vendeur
            $table->foreignId('vendeur_id')->nullable()->constrained('users'); // Lien optionnel
            
            $table->decimal('montant_total_mga', 15, 2);
            $table->decimal('montant_paye_mga', 15, 2)->default(0);
            $table->decimal('montant_restant_mga', 15, 2);
            
            $table->enum('statut', ['brouillon', 'envoyee', 'payee', 'annulee'])->default('brouillon');
            $table->date('date_echeance')->nullable();
            
            // Liens avec logistique
            $table->foreignId('voyage_id')->nullable()->constrained('voyages');
            $table->foreignId('dechargement_id')->nullable()->constrained('dechargements');
            
            $table->text('observation')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('factures');
    }
};

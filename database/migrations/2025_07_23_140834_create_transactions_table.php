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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique(); // P001, T001, V001, F001
            $table->date('date');
            $table->enum('type', [
                'achat',        // Achat de produits
                'vente',        // Vente de produits  
                'autre'         // autre d'argent
            ]);
            // Qui donne l'argent - SAISIE LIBRE
            $table->string('from_nom')->nullable(); // Nom libre (peut être externe)
            // Qui reçoit l'argent - SAISIE LIBRE
            $table->string('to_nom')->nullable(); // Nom libre (peut être externe)  
            $table->string('to_compte')->nullable(); // 'airtel_money, orange_money, mvola'
            
            $table->decimal('montant_mga', 15, 2); // Montant en MGA
            $table->text('objet')->nullable(); // Description transaction
            
            // Liens optionnels avec système logistique
            $table->foreignId('voyage_id')->nullable()->constrained('voyages');
            $table->foreignId('dechargement_id')->nullable()->constrained('dechargements');
            $table->foreignId('produit_id')->nullable()->constrained('produits');
            
            // Détails techniques
            $table->enum('mode_paiement', [
                'especes',
                'AirtelMoney', // AirtelMoney, 
                'OrangeMoney', 
                'MVola',
                'banque',       // Virement, chèque
            ])->default('especes');

            $table->decimal('reste_a_payer', 15, 2)->default(0);
            
            $table->enum('statut', ['attente', 'confirme', 'annule'])->default('confirme');
            
            // Informations complémentaires
            $table->decimal('quantite', 10, 2)->nullable(); // Si lié à des produits
            $table->string('unite')->nullable(); // sacs, kg, tonnes
            $table->decimal('prix_unitaire_mga', 12, 2)->nullable(); // Prix par unité
            
            $table->text('observation')->nullable(); // Français + Malagasy
            $table->timestamps();
            $table->softDeletes();
            
            // Index pour performance
            $table->index(['date', 'type']);
            $table->index(['from_nom', 'to_nom']); // Index sur noms libres
            $table->index('reference');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};

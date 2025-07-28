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
        Schema::create('dechargements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('voyage_id')->constrained('voyages')->cascadeOnDelete();
            $table->foreignId('chargement_id')->constrained('chargements')->onDelete('cascade');
            $table->string('reference'); // OP001, OP002
            $table->enum('type', ['vente', 'retour', 'depot', 'transfert']);
            $table->string('pointeur_nom')->nullable();
            $table->string('pointeur_contact')->nullable();
            $table->string('interlocuteur_nom')->nullable();
            $table->string('interlocuteur_contact')->nullable();
            $table->foreignId('lieu_livraison_id')->nullable()->constrained('lieux');
            
            // Quantités arrivée (nullable car parfois non pesé)
            $table->integer('sacs_pleins_arrivee')->nullable();
            $table->integer('sacs_demi_arrivee')->default(0);
            $table->decimal('poids_arrivee_kg', 10, 2)->nullable();
            
            // Informations commerciales
            $table->decimal('prix_unitaire_mga', 12, 2)->nullable(); // Prix en MGA
            $table->decimal('montant_total_mga', 15, 2)->nullable();
            $table->decimal('paiement_mga', 15, 2)->nullable();
            $table->decimal('reste_mga', 15, 2)->nullable();
            
            $table->enum('statut_commercial', ['en_attente', 'vendu', 'retourne', 'transfere'])->default('en_attente');
            $table->text('observation')->nullable(); // Français + Malagasy
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['reference', 'deleted_at'], 'dechargements_reference_deleted_at_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dechargements');
    }
};

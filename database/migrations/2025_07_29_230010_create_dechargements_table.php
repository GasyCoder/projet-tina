<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('dechargements', function (Blueprint $table) {
            $table->id();

            // Relations
            $table->foreignId('voyage_id')->constrained()->onDelete('cascade');
            $table->foreignId('chargement_id')->constrained()->onDelete('cascade');
            $table->foreignId('lieu_livraison_id')->nullable()->constrained('lieux')->onDelete('set null');

            // Informations générales
            $table->string('reference')->nullable();
            $table->enum('type', ['vente', 'transit', 'don'])->default('vente');

            // Interlocuteurs
            $table->string('interlocuteur_nom')->nullable();
            $table->string('interlocuteur_contact')->nullable();
            $table->string('pointeur_nom')->nullable();
            $table->string('pointeur_contact')->nullable();

            // Détails de livraison
            $table->integer('sacs_pleins_arrivee')->default(0);
            $table->integer('sacs_demi_arrivee')->default(0);
            $table->decimal('poids_arrivee_kg', 15, 2)->default(0);

            // Paiement
            $table->decimal('prix_unitaire_mga', 15, 2)->default(0);
            $table->decimal('montant_total_mga', 15, 2)->default(0);
            $table->decimal('paiement_mga', 15, 2)->default(0);
            $table->decimal('reste_mga', 15, 2)->default(0);

            // Divers
            $table->string('statut_commercial')->nullable(); // ex: "payé", "en attente", etc.
            $table->text('observation')->nullable();

            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dechargements');
    }
};

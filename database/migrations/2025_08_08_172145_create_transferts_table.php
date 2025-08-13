<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


class CreateTransfertsTable extends Migration
{
    public function up()
    {
        Schema::create('transferts', function (Blueprint $table) {
            $table->id();
            $table->string('numero_transfert')->unique();
            $table->date('date_creation');
            $table->date('date_prevue_expedition');
            $table->date('date_prevue_reception');

            // Dépôts
            $table->foreignId('depot_origine_id')->constrained('lieux')->onDelete('cascade');
            $table->foreignId('depot_destination_id')->constrained('lieux')->onDelete('cascade');
            $table->foreignId('responsable_origine_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('responsable_destination_id')->constrained('users')->onDelete('cascade');

            // Transport
            $table->foreignId('vehicule_id')->nullable()->constrained()->onDelete('set null');
            $table->string('chauffeur_nom')->nullable();
            $table->string('chauffeur_contact')->nullable();
            $table->decimal('distance_km', 8, 2)->default(0);
            $table->decimal('cout_transport_prevu_mga', 12, 2)->default(0);
            $table->decimal('cout_transport_reel_mga', 12, 2)->default(0);

            // Statuts et suivi
            $table->enum('statut_transfert', ['planifie', 'en_preparation', 'en_transit', 'livre', 'receptionne', 'annule']);
            $table->enum('priorite', ['basse', 'normale', 'haute', 'urgente'])->default('normale');
            $table->integer('progression_pourcent')->default(0);

            // Dates réelles
            $table->datetime('date_expedition_reelle')->nullable();
            $table->datetime('date_reception_reelle')->nullable();
            $table->datetime('date_validation_finale')->nullable();

            // Motifs et observations
            $table->enum('motif_transfert', ['reequilibrage_stock', 'commande_client', 'optimisation', 'urgence', 'autre']);
            $table->text('description_motif')->nullable();
            $table->text('observations_expedition')->nullable();
            $table->text('observations_reception')->nullable();

            // Validation et conformité
            $table->boolean('controle_expedition_fait')->default(false);
            $table->boolean('controle_reception_fait')->default(false);
            $table->foreignId('controleur_expedition_id')->nullable()->constrained('users');
            $table->foreignId('controleur_reception_id')->nullable()->constrained('users');

            // Assurance et risques
            $table->boolean('assurance_souscrite')->default(false);
            $table->string('numero_police_assurance')->nullable();
            $table->decimal('valeur_assuree_mga', 15, 2)->default(0);

            // Conditions particulières
            $table->text('conditions_transport')->nullable();
            $table->boolean('temperature_controlee')->default(false);
            $table->decimal('temperature_min', 5, 2)->nullable();
            $table->decimal('temperature_max', 5, 2)->nullable();

            // Traçabilité
            $table->foreignId('user_creation_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('user_validation_id')->nullable()->constrained('users')->onDelete('set null');

            $table->timestamps();
            $table->softDeletes();

            $table->index(['statut_transfert', 'priorite']);
            $table->index(['depot_origine_id', 'depot_destination_id']);
            $table->index(['date_creation', 'date_prevue_expedition']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('transferts');
    }
}
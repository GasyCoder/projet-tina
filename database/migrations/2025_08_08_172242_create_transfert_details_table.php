<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransfertDetailsTable extends Migration
{
    public function up()
    {
        Schema::create('transfert_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transfert_id')->constrained()->onDelete('cascade');
            $table->foreignId('produit_id')->constrained()->onDelete('cascade');

            // Attention : bonne table référencée ici
            $table->unsignedBigInteger('stock_origine_id');
            $table->foreign('stock_origine_id')->references('id')->on('stock_depots')->onDelete('cascade');

            // Quantités prévues
            $table->decimal('quantite_prevue_kg', 10, 2);
            $table->integer('sacs_pleins_prevus')->default(0);
            $table->integer('sacs_demi_prevus')->default(0);

            // Quantités expédiées
            $table->decimal('quantite_expedie_kg', 10, 2)->default(0);
            $table->integer('sacs_pleins_expedies')->default(0);
            $table->integer('sacs_demi_expedies')->default(0);

            // Quantités reçues
            $table->decimal('quantite_recu_kg', 10, 2)->default(0);
            $table->integer('sacs_pleins_recus')->default(0);
            $table->integer('sacs_demi_recus')->default(0);

            // Écarts et pertes
            $table->decimal('ecart_expedition_kg', 10, 2)->default(0);
            $table->decimal('ecart_reception_kg', 10, 2)->default(0);
            $table->decimal('perte_transport_kg', 10, 2)->default(0);
            $table->decimal('perte_transport_pourcent', 5, 2)->default(0);

            // Informations financières
            $table->decimal('prix_unitaire_mga', 12, 2);
            $table->decimal('valeur_prevue_mga', 15, 2);
            $table->decimal('valeur_expedie_mga', 15, 2)->default(0);
            $table->decimal('valeur_recu_mga', 15, 2)->default(0);

            // Qualité
            $table->enum('qualite_expedition', ['excellent', 'bon', 'moyen', 'mauvais'])->default('bon');
            $table->enum('qualite_reception', ['excellent', 'bon', 'moyen', 'mauvais'])->nullable();
            $table->text('observations_qualite')->nullable();

            // Statuts
            $table->enum('statut_detail', ['en_attente', 'expedie', 'en_transit', 'recu', 'refuse', 'perdu']);
            $table->text('motif_refus')->nullable();

            $table->timestamps();

            $table->index(['transfert_id', 'produit_id']);
            $table->index(['statut_detail']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('transfert_details');
    }
}

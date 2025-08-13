<?php

// Migration 1: Table des ventes
// database/migrations/xxxx_create_ventes_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVentesTable extends Migration
{
    public function up()
    {
        Schema::create('ventes', function (Blueprint $table) {
            $table->id();
            $table->string('numero_vente')->unique();
            $table->date('date_vente');
            $table->foreignId('chargement_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('produit_id')->constrained()->onDelete('cascade');
            $table->foreignId('lieu_livraison_id')->constrained('lieux')->onDelete('cascade');

            // Informations client
            $table->string('client_nom');
            $table->string('client_contact')->nullable();
            $table->string('client_adresse')->nullable();
            $table->string('client_type')->default('particulier'); // particulier, entreprise

            // Informations produit
            $table->decimal('quantite_kg', 10, 2);
            $table->integer('sacs_pleins')->default(0);
            $table->integer('sacs_demi')->default(0);
            $table->decimal('prix_unitaire_mga', 12, 2);
            $table->decimal('prix_total_mga', 15, 2);

            // Informations paiement
            $table->decimal('montant_paye_mga', 15, 2)->default(0);
            $table->decimal('montant_restant_mga', 15, 2)->default(0);
            $table->enum('statut_paiement', ['impaye', 'partiel', 'paye', 'rembourse'])->default('impaye');
            $table->enum('mode_paiement', ['especes', 'cheque', 'virement', 'mobile_money'])->nullable();

            // Logistique
            $table->string('transporteur_nom')->nullable();
            $table->string('vehicule_immatriculation')->nullable();
            $table->decimal('frais_transport_mga', 10, 2)->default(0);
            $table->datetime('date_livraison_prevue')->nullable();
            $table->datetime('date_livraison_reelle')->nullable();

            // Statuts
            $table->enum('statut_vente', ['brouillon', 'confirmee', 'en_preparation', 'expediee', 'livree', 'annulee'])->default('brouillon');
            $table->enum('statut_livraison', ['en_attente', 'en_cours', 'livree', 'echec'])->default('en_attente');

            // Suivi qualité
            $table->enum('qualite_produit', ['excellent', 'bon', 'moyen', 'mauvais'])->default('bon');
            $table->text('observations')->nullable();
            $table->text('remarques_client')->nullable();

            // Traçabilité
            $table->foreignId('user_creation_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('user_validation_id')->nullable()->constrained('users')->onDelete('set null');
            $table->datetime('date_validation')->nullable();

            // Comptabilité
            $table->string('numero_facture')->nullable();
            $table->date('date_facture')->nullable();
            $table->boolean('facture_envoyee')->default(false);
            $table->decimal('tva_taux', 5, 2)->default(0);
            $table->decimal('tva_montant_mga', 12, 2)->default(0);

            $table->timestamps();
            $table->softDeletes();

            // Index pour optimiser les recherches
            $table->index(['date_vente', 'statut_vente']);
            $table->index(['client_nom', 'statut_paiement']);
            $table->index(['produit_id', 'lieu_livraison_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('ventes');
    }
}
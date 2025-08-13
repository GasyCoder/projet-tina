<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;




class CreateRetoursTable extends Migration
{
    public function up()
    {
        Schema::create('retours', function (Blueprint $table) {
            $table->id();
            $table->string('numero_retour')->unique();
            $table->date('date_retour');
            $table->foreignId('vente_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('produit_id')->constrained()->onDelete('cascade');
            $table->foreignId('lieu_stockage_id')->constrained('lieux')->onDelete('cascade');

            // Informations retour
            $table->string('client_nom');
            $table->string('client_contact')->nullable();
            $table->decimal('quantite_retour_kg', 10, 2);
            $table->integer('sacs_pleins_retour')->default(0);
            $table->integer('sacs_demi_retour')->default(0);

            // Motifs et raisons
            $table->enum('motif_retour', ['defaut_qualite', 'erreur_livraison', 'annulation_client', 'surplus', 'autre']);
            $table->text('description_motif');
            $table->enum('responsabilite', ['client', 'transporteur', 'vendeur', 'produit'])->default('client');

            // Évaluation
            $table->enum('etat_produit', ['excellent', 'bon', 'moyen', 'mauvais', 'inutilisable']);
            $table->boolean('produit_revendable')->default(true);
            $table->decimal('valeur_recuperable_mga', 12, 2)->default(0);
            $table->decimal('perte_estimee_mga', 12, 2)->default(0);

            // Traitement
            $table->enum('statut_retour', ['en_attente', 'accepte', 'refuse', 'en_cours_traitement', 'traite', 'archive']);
            $table->enum('action_prise', ['remboursement', 'echange', 'avoir', 'destruction', 'revente_reduite'])->nullable();
            $table->decimal('montant_rembourse_mga', 12, 2)->default(0);
            $table->date('date_traitement')->nullable();

            // Logistique retour
            $table->string('transporteur_retour')->nullable();
            $table->decimal('frais_retour_mga', 10, 2)->default(0);
            $table->enum('prise_charge_frais', ['client', 'vendeur', 'transporteur', 'partage']);

            // Photos et documents
            $table->json('photos_produit')->nullable();
            $table->json('documents_justificatifs')->nullable();

            // Suivi
            $table->foreignId('user_reception_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('user_traitement_id')->nullable()->constrained('users')->onDelete('set null');
            $table->text('observations')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index(['date_retour', 'statut_retour']);
            $table->index(['motif_retour', 'etat_produit']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('retours');
    }
}


<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHistoriqueMouvementsTable extends Migration
{
    public function up()
    {
        Schema::create('historique_mouvements', function (Blueprint $table) {
            $table->id();
            $table->string('numero_mouvement');
            $table->enum('type_operation', ['vente', 'retour', 'entree_depot', 'sortie_depot', 'transfert', 'ajustement', 'inventaire']);
            $table->date('date_operation');

            $table->foreignId('produit_id')->constrained()->onDelete('cascade');
            $table->foreignId('depot_id')->constrained('lieux')->onDelete('cascade');
            $table->morphs('operation'); // Crée operation_id + operation_type avec index unique automatiquement

            $table->decimal('quantite_avant_kg', 10, 2);
            $table->decimal('quantite_mouvement_kg', 10, 2);
            $table->decimal('quantite_apres_kg', 10, 2);

            $table->enum('sens_mouvement', ['entree', 'sortie']);
            $table->decimal('prix_unitaire_mga', 12, 2)->default(0);
            $table->decimal('valeur_mouvement_mga', 15, 2)->default(0);

            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('user_nom');
            $table->text('observations')->nullable();

            $table->timestamps();

            $table->index(['type_operation', 'date_operation']);
            $table->index(['produit_id', 'depot_id']);
            // Supprimé l'index manuel sur operation_type et operation_id
        });
    }

    public function down()
    {
        Schema::dropIfExists('historique_mouvements');
    }
}

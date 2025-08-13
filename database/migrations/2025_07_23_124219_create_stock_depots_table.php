<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('stock_depots', function (Blueprint $table) {
            $table->id();
            $table->date('date_entree');
            $table->string('origine'); // 'Retour', 'Achat', 'Transfert'
            $table->foreignId('produit_id')->constrained('produits');
            $table->foreignId('depot_id')->constrained('lieux');
            $table->foreignId('proprietaire_id')->constrained('users'); // Propriétaire des marchandises

            // Colonne stock_origine_id ajoutée avant la clé étrangère
            $table->unsignedBigInteger('stock_origine_id')->nullable();
            $table->foreign('stock_origine_id')->references('id')->on('stock_depots')->onDelete('cascade');

            // Quantités entrée
            $table->integer('sacs_pleins');
            $table->integer('sacs_demi')->default(0);
            $table->decimal('poids_entree_kg', 10, 2);

            // Informations sortie
            $table->date('date_sortie')->nullable();
            $table->foreignId('vehicule_sortie_id')->nullable()->constrained('vehicules');
            $table->decimal('poids_sortie_kg', 10, 2)->nullable();
            $table->decimal('reste_kg', 10, 2)->default(0);

            // Gestion commerciale
            $table->enum('statut', ['en_stock', 'vendu', 'transfere', 'retourne'])->default('en_stock');
            $table->decimal('prix_marche_actuel_mga', 12, 2)->nullable(); // Prix du marché en MGA
            $table->enum('decision_proprietaire', ['vendre', 'attendre', 'transferer'])->nullable();

            $table->text('observation')->nullable(); // Français + Malagasy
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_depots');
    }
};

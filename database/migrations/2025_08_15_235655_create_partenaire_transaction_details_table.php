<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    
    public function up()
    {
        Schema::create('partenaire_transaction_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_id')->constrained('partenaire_transactions')->cascadeOnDelete();
            $table->foreignId('produit_id')->nullable()->constrained('produits')->nullOnDelete();
            $table->string('description');
            $table->decimal('quantite', 10, 2)->default(1);
            $table->string('unite', 10)->nullable();
            $table->decimal('prix_unitaire_mga', 15, 2)->default(0);
            $table->decimal('montant_mga', 15, 2);
            $table->enum('type_detail', ['achat_produit', 'credit', 'frais', 'autre'])->default('autre');
            $table->timestamps();

            // Index pour les performances
            $table->index('transaction_id');
            $table->index('produit_id');
            $table->index('type_detail');
            $table->index('unite');
        });
    }

    public function down()
    {
        Schema::dropIfExists('partenaire_transaction_details');
    }
};
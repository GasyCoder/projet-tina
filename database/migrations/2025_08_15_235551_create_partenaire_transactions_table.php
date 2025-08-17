<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    
    public function up()
    {
        Schema::create('partenaire_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('partenaire_id')->constrained('partenaires')->cascadeOnDelete();
            $table->string('reference', 50)->unique();
            $table->enum('type', ['entree', 'sortie']);
            $table->decimal('montant_mga', 15, 2);
            $table->string('motif');
            $table->string('mode_paiement')->nullable(); // Pour les entrées
            $table->string('sous_type_compte')->nullable();
            $table->foreignId('entree_source_id')->nullable()->constrained('partenaire_transactions')->cascadeOnDelete();
            $table->boolean('statut')->default(true); 
            $table->datetime('date_transaction');
            $table->text('observation')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Index pour les performances
            $table->index(['partenaire_id', 'type']);
            $table->index(['date_transaction']);
            $table->index(['type', 'statut']);
            $table->index('sous_type_compte');
            $table->index('reference');
        });
    }

    public function down()
    {
        Schema::dropIfExists('partenaire_transactions');
    }
};
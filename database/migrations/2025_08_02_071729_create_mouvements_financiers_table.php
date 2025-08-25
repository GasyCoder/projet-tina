<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mouvements_financiers', function (Blueprint $table) {
            $table->id();
            $table->date('date_mouvement');
            $table->foreignId('compte_id')->constrained('comptes')->onDelete('cascade');
            $table->enum('type_mouvement', ['entree', 'sortie']);
            $table->string('description'); // libre: loyer, salaire, etc.
            $table->decimal('montant', 15, 2);
            $table->text('commentaire')->nullable();
            $table->string('lieu')->default('antananarivo');
            $table->boolean('valide')->default(false);
            $table->timestamps();
            
            // Index pour performance
            $table->index(['date_mouvement', 'lieu']);
            $table->index(['compte_id', 'type_mouvement']);
            $table->index(['date_mouvement', 'compte_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mouvements_financiers');
    }
};

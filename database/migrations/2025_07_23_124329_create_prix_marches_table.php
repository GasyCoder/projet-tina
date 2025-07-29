<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('prix_marche', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produit_id')->constrained('produits');
            $table->foreignId('lieu_id')->constrained('lieux');
            $table->date('date');
            $table->decimal('prix_mga', 12, 2); // Prix en MGA
            $table->enum('unite', ['kg', 'sacs', 'tonnes'])->default('kg');
            $table->string('source')->nullable(); // Source de l'information
            $table->text('observation')->nullable();
            $table->timestamps();
            
            // Index pour recherche rapide
            $table->index(['produit_id', 'lieu_id', 'date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prix_marches');
    }
};

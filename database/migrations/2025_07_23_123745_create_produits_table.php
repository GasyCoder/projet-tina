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
        Schema::create('produits', function (Blueprint $table) {
            $table->id();
            $table->string('nom'); // TSOROKO, BEB, LOJY MENA, Haricot, Maïs, etc.
            $table->string('variete')->nullable(); // Variété spécifique (Rouge, Blanc, Premium, etc.)
            $table->enum('unite', ['sacs', 'kg', 'tonnes', 'boites', 'cartons'])->default('sacs');
            $table->decimal('poids_moyen_sac_kg_max', 8, 2)->default(120.00);
            $table->decimal('qte_variable', 8, 2)->default(0);
            $table->decimal('prix_reference_mga', 12, 2)->nullable(); // Prix de référence en MGA
            $table->text('description')->nullable(); // Description détaillée
            $table->boolean('actif')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produits');
    }
};

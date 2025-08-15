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
        Schema::create('chargements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('voyage_id')->constrained('voyages')->cascadeOnDelete();
            $table->string('reference'); // CH001, CH002
            $table->date('date');
            $table->string('chargeur_nom')->nullable();
            $table->string('chargeur_contact')->nullable();
            $table->foreignId('origine_id')->nullable()->constrained('lieux');
            $table->string('proprietaire_nom')->nullable();
            $table->string('proprietaire_contact')->nullable();
            $table->foreignId('produit_id')->constrained('produits');
            $table->integer('sacs_pleins_depart')->nullable();
            $table->integer('sacs_demi_depart')->default(0);
            $table->decimal('poids_depart_kg', 10, 2)->nullable();
            $table->text('observation')->nullable(); // Français + Malagasy
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['reference', 'deleted_at'], 'chargements_reference_deleted_at_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chargements');
    }
};

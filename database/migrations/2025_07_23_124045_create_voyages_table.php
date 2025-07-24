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
        Schema::create('voyages', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique(); // V020/25
            $table->date('date');
            $table->foreignId('origine_id')->constrained('lieux');
            $table->foreignId('vehicule_id')->constrained('vehicules');
            $table->foreignId('chauffeur_id')->nullable()->constrained('users'); // Peut être oublié
            $table->enum('statut', ['en_cours', 'termine', 'annule'])->default('en_cours');
            $table->integer('ecart_sacs_pleins')->default(0);
            $table->integer('ecart_sacs_demi')->default(0);
            $table->decimal('ecart_poids_kg', 10, 2)->default(0);
            $table->text('observation')->nullable(); // Français + Malagasy
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('voyages');
    }
};

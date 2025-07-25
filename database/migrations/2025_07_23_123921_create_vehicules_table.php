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
       Schema::create('vehicules', function (Blueprint $table) {
            $table->id();
            $table->string('immatriculation')->unique(); // S-2676MF, P-7710MB
            $table->enum('type', ['camion', 'semi-remorque', 'pick-up', 'tracteur', 'autre']);
            $table->string('marque')->nullable();
            $table->string('modele')->nullable();
            $table->string('chauffeur')->nullable();
            $table->integer('capacite_max_kg')->nullable();
            $table->enum('statut', ['actif', 'maintenance', 'inactif'])->default('actif');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicules');
    }
};

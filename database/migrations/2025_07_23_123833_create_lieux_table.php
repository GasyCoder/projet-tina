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
        Schema::create('lieux', function (Blueprint $table) {
            $table->id();
            $table->string('nom'); // ANKIRIRIKA, VISHAL, etc.
            $table->enum('type', ['origine', 'depot', 'magasin', 'boutique'])->index(); // 👈 index
            $table->string('region')->nullable()->index(); // 👈 index
            $table->string('telephone')->nullable();
            $table->text('adresse')->nullable();
            $table->boolean('actif')->default(true)->index(); // 👈 index
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lieux');
    }
};

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
        Schema::create('partenaires', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('telephone')->nullable();
            $table->string('adresse')->nullable();
            $table->enum('type', ['fournisseur', 'client'])->default('fournisseur');
            $table->boolean('is_active')->default(true);
            $table->decimal('solde_actuel_mga', 15, 2)->default(0);
            $table->foreignId('compte_id')->nullable()->constrained('comptes')->nullOnDelete();
            $table->timestamps();

            // Index utiles
            $table->index(['is_active', 'type']);
            $table->index('solde_actuel_mga');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('partenaires', function (Blueprint $table) {
            $table->dropForeign(['compte_id']);
            $table->dropIndex(['is_active', 'type']);
            $table->dropIndex(['solde_actuel_mga']);
        });

        Schema::dropIfExists('partenaires');
    }
};

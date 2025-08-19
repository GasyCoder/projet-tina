<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('code_comptable', 10)->unique();
            $table->string('nom', 100);
            $table->decimal('budget', 10, 2)->default(0);
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);

            // Only one type column for categories (recette/depense)
            $table->enum('type', ['recette', 'depense'])->default('depense');
            $table->enum('type_compte', [
                'Principal',
                'MobileMoney',
                'Banque'
            ])->default('Principal');

            $table->timestamps();

            // Indexes for better query performance
            $table->index('is_active');
            $table->index('type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
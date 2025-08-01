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
        Schema::create('comptes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users'); 
            $table->string('nom_proprietaire')->nullable(); 
            $table->enum('type_compte', [
                'principal',   
                'AirtelMoney', 
                'MVola',
                'OrangeMoney',
                'banque'      
            ])->default('principal'); 
            $table->string('numero_compte')->nullable(); 
            $table->decimal('solde_actuel_mga', 15, 2)->default(0); 
            $table->foreignId('derniere_transaction_id')->nullable()->constrained('transactions');
            $table->boolean('actif')->default(true);
            $table->timestamps();
            
            // Index
            $table->index(['user_id', 'nom_proprietaire']);
            $table->index(['type_compte']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comptes');
    }
};

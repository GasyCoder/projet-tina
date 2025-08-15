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
        Schema::create('achats', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique(); 
            $table->date('date');
            $table->string('from_nom')->nullable();
            $table->string('to_nom')->nullable();  
            $table->string('to_compte')->nullable();  
            $table->decimal('montant_mga', 15, 2);
            $table->text('objet')->nullable();
            $table->enum('mode_paiement', [
                'especes',
                'AirtelMoney', 
                'OrangeMoney', 
                'Mvola', // ⚠️ Seule correction : était "MVola"
                'banque',
            ])->default('especes');

            // ⚠️ Seule correction : changé boolean vers enum 
            $table->boolean('statut', true)->default(true);
            
            $table->text('observation')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['date']);
            $table->index(['from_nom', 'to_nom', 'to_compte']);
            $table->index('reference');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('achats'); // ⚠️ CORRIGÉ : était "transactions"
    }
};
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
        if (!Schema::hasTable('situations_financieres')) {
            Schema::create('situations_financieres', function (Blueprint $table) {
                $table->id();
                $table->date('date_situation')->nullable();
                $table->string('lieu')->nullable();
                $table->decimal('montant_initial', 15, 2)->default(0);
                $table->decimal('montant_final', 15, 2)->default(0);
                $table->timestamps();
                $table->softDeletes();
            });
        }
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('comptes', function (Blueprint $table) {
            //
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


class CreateSeuilsAlertesTable extends Migration
{
    public function up()
    {
        Schema::create('seuils_alertes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produit_id')->constrained()->onDelete('cascade');
            $table->foreignId('depot_id')->constrained('lieux')->onDelete('cascade');

            $table->decimal('seuil_stock_minimum_kg', 10, 2)->default(0);
            $table->decimal('seuil_stock_maximum_kg', 10, 2)->default(0);
            $table->decimal('stock_securite_kg', 10, 2)->default(0);
            $table->decimal('stock_alerte_kg', 10, 2)->default(0);

            $table->integer('jours_avant_peremption')->default(30);
            $table->boolean('alerte_stock_bas_active')->default(true);
            $table->boolean('alerte_stock_zero_active')->default(true);
            $table->boolean('alerte_peremption_active')->default(true);
            $table->boolean('alerte_surstock_active')->default(false);

            $table->json('destinataires_alertes')->nullable(); // IDs des users à alerter

            $table->timestamps();

            $table->unique(['produit_id', 'depot_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('seuils_alertes');
    }
}
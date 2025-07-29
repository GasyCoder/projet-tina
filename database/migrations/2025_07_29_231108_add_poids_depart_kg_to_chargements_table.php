<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('chargements', function (Blueprint $table) {
            $table->decimal('poids_depart_kg', 15, 2)->default(0);
        });
    }

    public function down()
    {
        Schema::table('chargements', function (Blueprint $table) {
            $table->dropColumn('poids_depart_kg');
        });
    }

};

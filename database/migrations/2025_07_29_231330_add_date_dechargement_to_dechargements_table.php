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
        Schema::table('dechargements', function (Blueprint $table) {
            $table->date('date_dechargement')->nullable()->after('observation');
            // Ajuste la position selon ta table
        });
    }

    public function down()
    {
        Schema::table('dechargements', function (Blueprint $table) {
            $table->dropColumn('date_dechargement');
        });
    }

};

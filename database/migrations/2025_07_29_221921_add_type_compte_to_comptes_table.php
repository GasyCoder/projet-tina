<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::table('comptes', function (Blueprint $table) {
        $table->string('type_compte')->nullable()->after('nom_proprietaire');
    });
}

public function down()
{
    Schema::table('comptes', function (Blueprint $table) {
        $table->dropColumn('type_compte');
    });
}

};

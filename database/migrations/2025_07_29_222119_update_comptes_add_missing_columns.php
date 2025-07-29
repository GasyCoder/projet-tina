<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('comptes', function (Blueprint $table) {
            if (!Schema::hasColumn('comptes', 'nom_proprietaire')) {
                $table->string('nom_proprietaire')->nullable()->after('id');
            }
            if (!Schema::hasColumn('comptes', 'type_compte')) {
                $table->string('type_compte')->nullable()->after('nom_proprietaire');
            }
            if (!Schema::hasColumn('comptes', 'nom_compte')) {
                $table->string('nom_compte')->nullable()->after('type_compte');
            }
            if (!Schema::hasColumn('comptes', 'numero_compte')) {
                $table->string('numero_compte')->nullable()->after('nom_compte');
            }
            if (!Schema::hasColumn('comptes', 'solde_actuel_mga')) {
                $table->decimal('solde_actuel_mga', 15, 2)->default(0)->after('numero_compte');
            }
            if (!Schema::hasColumn('comptes', 'actif')) {
                $table->boolean('actif')->default(true)->after('solde_actuel_mga');
            }
            // created_at, updated_at sont souvent déjà présents avec timestamps()
            if (!Schema::hasColumn('comptes', 'created_at') && !Schema::hasColumn('comptes', 'updated_at')) {
                $table->timestamps();
            }
        });
    }

    public function down()
    {
        Schema::table('comptes', function (Blueprint $table) {
            if (Schema::hasColumn('comptes', 'nom_proprietaire')) {
                $table->dropColumn('nom_proprietaire');
            }
            if (Schema::hasColumn('comptes', 'type_compte')) {
                $table->dropColumn('type_compte');
            }
            if (Schema::hasColumn('comptes', 'nom_compte')) {
                $table->dropColumn('nom_compte');
            }
            if (Schema::hasColumn('comptes', 'numero_compte')) {
                $table->dropColumn('numero_compte');
            }
            if (Schema::hasColumn('comptes', 'solde_actuel_mga')) {
                $table->dropColumn('solde_actuel_mga');
            }
            if (Schema::hasColumn('comptes', 'actif')) {
                $table->dropColumn('actif');
            }
            if (Schema::hasColumn('comptes', 'created_at') && Schema::hasColumn('comptes', 'updated_at')) {
                $table->dropTimestamps();
            }
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('situations_financieres', function (Blueprint $table) {
            if (!Schema::hasColumn('situations_financieres', 'montant_initial')) {
                $table->decimal('montant_initial', 15, 2)->default(0)->after('lieu');
            }
            if (!Schema::hasColumn('situations_financieres', 'montant_final')) {
                $table->decimal('montant_final', 15, 2)->default(0)->after('montant_initial');
            }
        });
    }

    public function down(): void
    {
        Schema::table('situations_financieres', function (Blueprint $table) {
            if (Schema::hasColumn('situations_financieres', 'montant_initial')) {
                $table->dropColumn('montant_initial');
            }
            if (Schema::hasColumn('situations_financieres', 'montant_final')) {
                $table->dropColumn('montant_final');
            }
        });
    }
};

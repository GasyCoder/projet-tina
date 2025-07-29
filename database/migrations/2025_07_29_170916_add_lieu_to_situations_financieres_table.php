<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('situations_financieres', function (Blueprint $table) {
            if (!Schema::hasColumn('situations_financieres', 'lieu')) {
                $table->string('lieu')->nullable()->after('date_situation');
            }
        });
    }

    public function down(): void
    {
        Schema::table('situations_financieres', function (Blueprint $table) {
            if (Schema::hasColumn('situations_financieres', 'lieu')) {
                $table->dropColumn('lieu');
            }
        });
    }
};

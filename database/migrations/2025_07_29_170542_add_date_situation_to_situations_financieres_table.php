<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('situations_financieres', function (Blueprint $table) {
            if (!Schema::hasColumn('situations_financieres', 'date_situation')) {
                $table->date('date_situation')->nullable()->after('id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('situations_financieres', function (Blueprint $table) {
            if (Schema::hasColumn('situations_financieres', 'date_situation')) {
                $table->dropColumn('date_situation');
            }
        });
    }
};

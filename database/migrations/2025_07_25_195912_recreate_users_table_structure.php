<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Ces lignes ne sont exécutées QUE si les colonnes n'existent pas déjà.
            if (!Schema::hasColumn('users', 'name')) {
                $table->string('name');
            }

            if (!Schema::hasColumn('users', 'email')) {
                $table->string('email')->unique();
            }

            if (!Schema::hasColumn('users', 'type')) {
                $table->string('type')->default('user'); // admin / user
            }

            if (!Schema::hasColumn('users', 'password')) {
                $table->string('password');
            }

            if (!Schema::hasColumn('users', 'created_at')) {
                $table->timestamps(); // ajoute created_at + updated_at
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['name', 'email', 'type', 'password', 'created_at', 'updated_at']);
        });
    }
};

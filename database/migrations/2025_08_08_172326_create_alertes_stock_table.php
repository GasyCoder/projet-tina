<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAlertesStockTable extends Migration
{
    public function up()
    {
        Schema::create('alertes_stock', function (Blueprint $table) {
            $table->id();
            $table->enum('type_alerte', ['stock_bas', 'stock_zero', 'peremption_proche', 'qualite_degradee', 'mouvement_suspect']);
            $table->enum('niveau_urgence', ['info', 'attention', 'urgent', 'critique']);

            $table->foreignId('produit_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('depot_id')->nullable()->constrained('lieux')->onDelete('cascade');
            $table->foreignId('stock_id')->nullable()->constrained('stock_depots')->onDelete('cascade'); // <-- corrigé ici

            $table->string('titre_alerte');
            $table->text('message_alerte');
            $table->decimal('seuil_defini', 10, 2)->nullable();
            $table->decimal('valeur_actuelle', 10, 2)->nullable();

            $table->boolean('alerte_active')->default(true);
            $table->boolean('alerte_vue')->default(false);
            $table->boolean('alerte_traitee')->default(false);

            $table->datetime('date_alerte');
            $table->datetime('date_vue')->nullable();
            $table->datetime('date_traitement')->nullable();

            $table->foreignId('user_destinataire_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('user_traitement_id')->nullable()->constrained('users')->onDelete('set null');
            $table->text('action_prise')->nullable();

            $table->timestamps();

            $table->index(['type_alerte', 'niveau_urgence', 'alerte_active']);
            $table->index(['user_destinataire_id', 'alerte_vue']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('alertes_stock');
    }
}

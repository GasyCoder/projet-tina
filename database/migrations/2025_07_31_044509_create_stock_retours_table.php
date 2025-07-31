<?php

// 1. Migration simple pour le stock de retour
// create_stock_retours_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('stock_retours', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dechargement_id')->constrained('dechargements')->onDelete('cascade');
            $table->string('reference'); // Référence du retour
            $table->date('date_retour');

            // Info produit (copié du déchargement)
            $table->foreignId('produit_id')->nullable()->constrained('produits');
            $table->string('produit_nom'); // Au cas où le produit est supprimé

            // Info lieu (c'est le stock de retour)
            $table->foreignId('lieu_stockage_id')->constrained('lieux');

            // Quantités (comme une vente)
            $table->decimal('quantite_kg', 10, 2);
            $table->integer('sacs_pleins')->default(0);
            $table->integer('sacs_demi')->default(0);

            // Prix (comme une vente)
            $table->decimal('prix_unitaire_mga', 12, 2);
            $table->decimal('valeur_totale_mga', 15, 2);

            // Info client/fournisseur
            $table->string('client_nom');
            $table->string('client_contact')->nullable();

            // Statut du stock
            $table->enum('statut', ['en_stock', 'vendu', 'transfere'])->default('en_stock');
            $table->text('observation')->nullable();

            $table->timestamps();

            $table->index(['produit_id', 'lieu_stockage_id']);
            $table->index(['statut', 'date_retour']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_retours');
    }
};


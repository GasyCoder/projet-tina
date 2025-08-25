<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('transactions_comptables', function (Blueprint $table) {
            $table->id();

            // Relations
            $table->foreignId('categorie_id')->constrained('categories')->onDelete('cascade');
            $table->foreignId('partenaire_id')->nullable()->constrained('partenaires')->onDelete('set null');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');

            // Informations de base
            $table->string('reference', 50)->unique();
            $table->string('description');
            $table->decimal('montant', 15, 2);
            $table->date('date_transaction');

            // Types et statuts
            $table->enum('type', ['recette', 'depense']);
            $table->enum('statut', ['entrer', 'sortie', 'en_attente', 'validee', 'annulee'])->default('entrer');

            // Mode de paiement cohérent
            $table->enum('mode_paiement', ['especes', 'MobileMoney', 'Banque'])->default('especes');
            $table->string('type_compte_mobilemoney_or_banque')->nullable();

            // Informations complémentaires
            $table->string('justificatif')->nullable();
            $table->text('notes')->nullable();

            // Index pour optimisation - AVEC NOMS PERSONNALISÉS
            $table->index(['categorie_id', 'type'], 'trans_cat_type_idx');
            $table->index(['mode_paiement', 'type_compte_mobilemoney_or_banque'], 'trans_mode_type_idx');
            $table->index(['date_transaction', 'type'], 'trans_date_type_idx');
            $table->index('statut', 'trans_statut_idx');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions_comptables');
    }
};
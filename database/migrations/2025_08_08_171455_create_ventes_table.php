<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('ventes', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique();
            $table->date('date');
            $table->text('objet')->nullable();

            // Lieu (dépôt / boutique / magasin)
            $table->foreignId('depot_id')
                ->nullable()
                ->constrained('lieux')
                ->onDelete('cascade');

            $table->string('vendeur_nom')->nullable();

            // Paiement
            $table->decimal('montant_paye_mga', 15, 2)->default(0);
            $table->decimal('montant_restant_mga', 15, 2)->default(0);
            $table->enum('statut_paiement', ['paye','partiel'])->default('paye');

            // ✅ Nouveau modèle de comptes
            // Principal = espèces/caisse ; MobileMoney (Mvola, OrangeMoney, AirtelMoney) ; Banque (BNI, BFV, …)
            $table->enum('type_paiement', ['Principal','MobileMoney','Banque'])->default('Principal');
            $table->string('sous_type_paiement')->nullable(); // Mvola, OrangeMoney, AirtelMoney, BNI, BFV...
            $table->foreignId('compte_id')->nullable()->constrained('comptes')->nullOnDelete();

            $table->text('observation')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Index
            $table->index(['date', 'statut_paiement', 'vendeur_nom']);
            $table->index(['depot_id']);
            $table->index(['type_paiement', 'sous_type_paiement']);
            $table->index(['compte_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ventes');
    }
};

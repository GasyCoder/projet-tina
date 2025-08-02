<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // 1. TABLE DECHARGEMENTS
        if (Schema::hasTable('dechargements')) {
            Schema::table('dechargements', function (Blueprint $table) {
                if (!Schema::hasColumn('dechargements', 'type')) {
                    $table->enum('type', ['vente', 'retour', 'depot', 'transfert'])->default('vente')->after('id');
                }
                if (!Schema::hasColumn('dechargements', 'priorite')) {
                    $table->tinyInteger('priorite')->default(1)->after('type');
                }
                if (!Schema::hasColumn('dechargements', 'numero_sequence')) {
                    $table->string('numero_sequence', 20)->nullable()->after('priorite');
                }
                if (!Schema::hasColumn('dechargements', 'statut_stock')) {
                    $table->enum('statut_stock', ['en_attente', 'confirme', 'livre', 'annule'])->default('en_attente')->after('numero_sequence');
                }
                if (!Schema::hasColumn('dechargements', 'tags')) {
                    $table->json('tags')->nullable()->after('statut_stock');
                }
                if (!Schema::hasColumn('dechargements', 'metadata')) {
                    $table->json('metadata')->nullable()->after('tags');
                }
                $table->index(['type', 'date'], 'idx_type_date');
                $table->index(['statut_commercial'], 'idx_statut_commercial');
                $table->index(['numero_sequence'], 'idx_numero_sequence');
            });
        }

        // 2. TABLE TRANSFERT_STOCKS
        if (Schema::hasTable('transfert_stocks')) {
            Schema::table('transfert_stocks', function (Blueprint $table) {
                if (!Schema::hasColumn('transfert_stocks', 'numero_transfert')) {
                    $table->string('numero_transfert', 50)->unique()->nullable();
                }
                if (!Schema::hasColumn('transfert_stocks', 'priorite')) {
                    $table->enum('priorite', ['basse', 'normale', 'haute', 'urgente'])->default('normale');
                }
                if (!Schema::hasColumn('transfert_stocks', 'date_prevue')) {
                    $table->dateTime('date_prevue')->nullable();
                }
                if (!Schema::hasColumn('transfert_stocks', 'date_depart')) {
                    $table->dateTime('date_depart')->nullable();
                }
                if (!Schema::hasColumn('transfert_stocks', 'date_arrivee')) {
                    $table->dateTime('date_arrivee')->nullable();
                }
                if (!Schema::hasColumn('transfert_stocks', 'chauffeur_nom')) {
                    $table->string('chauffeur_nom')->nullable();
                }
                if (!Schema::hasColumn('transfert_stocks', 'chauffeur_contact')) {
                    $table->string('chauffeur_contact', 20)->nullable();
                }
                if (!Schema::hasColumn('transfert_stocks', 'produits')) {
                    $table->json('produits')->nullable();
                }
                if (!Schema::hasColumn('transfert_stocks', 'progression')) {
                    $table->integer('progression')->default(0);
                }
                if (!Schema::hasColumn('transfert_stocks', 'user_creation_id')) {
                    $table->unsignedBigInteger('user_creation_id')->nullable();
                }
                if (!Schema::hasColumn('transfert_stocks', 'user_validation_id')) {
                    $table->unsignedBigInteger('user_validation_id')->nullable();
                }
                DB::statement("ALTER TABLE transfert_stocks MODIFY COLUMN statut ENUM('en_preparation', 'en_transit', 'recu', 'annule') DEFAULT 'en_preparation'");
                $table->index(['statut', 'date_transfert'], 'idx_statut_date');
                $table->index(['depot_origine_id', 'depot_destination_id'], 'idx_depots');
            });
        }

        // 3. TABLE DEPOTS
        if (Schema::hasTable('depots')) {
            Schema::table('depots', function (Blueprint $table) {
                if (!Schema::hasColumn('depots', 'numero_entree')) {
                    $table->string('numero_entree', 50)->unique()->nullable();
                }
                if (!Schema::hasColumn('depots', 'type_entree')) {
                    $table->enum('type_entree', ['achat', 'retour', 'transfert', 'production'])->default('achat');
                }
                if (!Schema::hasColumn('depots', 'fournisseur_nom')) {
                    $table->string('fournisseur_nom')->nullable();
                }
                if (!Schema::hasColumn('depots', 'fournisseur_contact')) {
                    $table->string('fournisseur_contact')->nullable();
                }
                if (!Schema::hasColumn('depots', 'bon_livraison')) {
                    $table->string('bon_livraison', 100)->nullable();
                }
                if (!Schema::hasColumn('depots', 'qualite_produit')) {
                    $table->enum('qualite_produit', ['excellent', 'bon', 'moyen', 'mauvais'])->default('bon');
                }
                if (!Schema::hasColumn('depots', 'temperature_stockage')) {
                    $table->decimal('temperature_stockage', 5, 2)->nullable();
                }
                if (!Schema::hasColumn('depots', 'humidite_stockage')) {
                    $table->decimal('humidite_stockage', 5, 2)->nullable();
                }
                if (!Schema::hasColumn('depots', 'user_reception_id')) {
                    $table->unsignedBigInteger('user_reception_id')->nullable();
                }
                if (!Schema::hasColumn('depots', 'photos')) {
                    $table->json('photos')->nullable();
                }
                if (!Schema::hasColumn('depots', 'controle_qualite')) {
                    $table->json('controle_qualite')->nullable();
                }
                DB::statement("ALTER TABLE depots MODIFY COLUMN statut ENUM('en_stock', 'sorti', 'en_attente', 'reserve', 'quarantaine') DEFAULT 'en_stock'");
                $table->index(['statut', 'produit_id'], 'idx_statut_produit');
                $table->index(['type_entree'], 'idx_type_entree');
            });
        }

        // 4. TABLE HISTORIQUE_STOCKS
        if (!Schema::hasTable('historique_stocks')) {
            Schema::create('historique_stocks', function (Blueprint $table) {
                $table->id();
                $table->enum('operation_type', ['vente', 'retour', 'depot', 'transfert_sortie', 'transfert_entree']);
                $table->unsignedBigInteger('operation_id');
                $table->unsignedBigInteger('produit_id');
                $table->unsignedBigInteger('lieu_id');
                $table->decimal('quantite_avant', 10, 2)->default(0);
                $table->decimal('quantite_mouvement', 10, 2);
                $table->decimal('quantite_apres', 10, 2);
                $table->decimal('prix_unitaire', 10, 2)->nullable();
                $table->decimal('valeur_mouvement', 12, 2)->nullable();
                $table->string('reference_operation', 100)->nullable();
                $table->unsignedBigInteger('user_id')->nullable();
                $table->dateTime('date_operation');
                $table->json('metadata')->nullable();
                $table->timestamps();

                $table->index(['operation_type', 'operation_id'], 'idx_operation');
                $table->index(['produit_id', 'lieu_id'], 'idx_produit_lieu');
                $table->index(['date_operation'], 'idx_date_operation');

                $table->foreign('produit_id')->references('id')->on('produits');
                $table->foreign('lieu_id')->references('id')->on('lieux');
                $table->foreign('user_id')->references('id')->on('users');
            });
        }

        // 5. TABLE ALERTES_STOCK
        if (!Schema::hasTable('alertes_stocks')) {
            Schema::create('alertes_stocks', function (Blueprint $table) {
                $table->id();
                $table->enum('type_alerte', ['stock_bas', 'stock_zero', 'expiration_proche', 'mouvement_suspect']);
                $table->unsignedBigInteger('produit_id');
                $table->unsignedBigInteger('lieu_id');
                $table->decimal('seuil_critique', 10, 2)->nullable();
                $table->decimal('quantite_actuelle', 10, 2)->nullable();
                $table->text('message')->nullable();
                $table->enum('niveau_urgence', ['info', 'warning', 'critical'])->default('warning');
                $table->enum('statut', ['active', 'resolue', 'ignoree'])->default('active');
                $table->unsignedBigInteger('user_assign_id')->nullable();
                $table->dateTime('date_detection');
                $table->dateTime('date_resolution')->nullable();
                $table->timestamps();

                $table->index(['statut', 'niveau_urgence'], 'idx_statut_urgence');
                $table->index(['produit_id', 'lieu_id'], 'idx_produit_lieu');

                $table->foreign('produit_id')->references('id')->on('produits');
                $table->foreign('lieu_id')->references('id')->on('lieux');
                $table->foreign('user_assign_id')->references('id')->on('users');
            });
        }

        // 6. VUE vue_stocks_actuels
        DB::statement("CREATE OR REPLACE VIEW vue_stocks_actuels AS
            SELECT 
                p.id AS produit_id,
                p.nom AS produit_nom,
                l.id AS lieu_id,
                l.nom AS lieu_nom,
                COALESCE(SUM(CASE 
                    WHEN h.operation_type IN ('depot', 'retour', 'transfert_entree') THEN h.quantite_mouvement
                    WHEN h.operation_type IN ('vente', 'transfert_sortie') THEN -h.quantite_mouvement
                    ELSE 0
                END), 0) AS stock_actuel,
                COALESCE(AVG(h.prix_unitaire), 0) AS prix_moyen,
                MAX(h.date_operation) AS derniere_operation,
                COUNT(h.id) AS nb_mouvements
            FROM produits p
            CROSS JOIN lieux l
            LEFT JOIN historique_stocks h ON p.id = h.produit_id AND l.id = h.lieu_id
            GROUP BY p.id, l.id
        ");

        // 7. PROCÉDURES stockées
        DB::unprepared("
            CREATE OR REPLACE PROCEDURE CalculerStockReel(IN p_produit_id BIGINT, IN p_lieu_id BIGINT)
            BEGIN
                DECLARE stock_calcule DECIMAL(10,2) DEFAULT 0;
                SELECT COALESCE(SUM(CASE 
                    WHEN operation_type IN ('depot', 'retour', 'transfert_entree') THEN quantite_mouvement
                    WHEN operation_type IN ('vente', 'transfert_sortie') THEN -quantite_mouvement
                    ELSE 0
                END), 0) INTO stock_calcule
                FROM historique_stocks 
                WHERE produit_id = p_produit_id AND lieu_id = p_lieu_id;
                SELECT stock_calcule AS stock_reel;
            END
        ");

        DB::unprepared("
            CREATE OR REPLACE PROCEDURE VerifierAlertes()
            BEGIN
                INSERT INTO alertes_stocks (type_alerte, produit_id, lieu_id, quantite_actuelle, message, niveau_urgence, date_detection)
                SELECT 
                    'stock_bas',
                    v.produit_id,
                    v.lieu_id,
                    v.stock_actuel,
                    CONCAT('Stock faible pour ', v.produit_nom, ' à ', v.lieu_nom, ': ', v.stock_actuel, ' unités'),
                    CASE 
                        WHEN v.stock_actuel <= 0 THEN 'critical'
                        WHEN v.stock_actuel <= 10 THEN 'warning'
                        ELSE 'info'
                    END,
                    NOW()
                FROM vue_stocks_actuels v
                WHERE v.stock_actuel <= 20 
                AND NOT EXISTS (
                    SELECT 1 FROM alertes_stocks a 
                    WHERE a.produit_id = v.produit_id 
                    AND a.lieu_id = v.lieu_id 
                    AND a.type_alerte = 'stock_bas' 
                    AND a.statut = 'active'
                );
            END
        ");
    }

    public function down(): void
    {
        // Pas de rollback automatique ici (manuellement si nécessaire)
    }
};

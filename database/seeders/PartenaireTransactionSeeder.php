<?php


use Illuminate\Database\Seeder;
use App\Models\Partenaire;
use App\Models\PartenaireTransaction;
use App\Models\PartenaireTransactionDetail;
use App\Models\Produit;
use App\Models\Compte;

class PartenaireTransactionSeeder extends Seeder
{
    public function run()
    {
        // Récupérer un partenaire existant ou en créer un
        $partenaire = Partenaire::first() ?? Partenaire::factory()->create([
            'nom' => 'Partenaire Test',
            'type' => 'fournisseur',
            'is_active' => true,
            'solde_actuel_mga' => 0
        ]);

        // Récupérer des comptes
        $compteSource = Compte::where('type_compte', 'principal')->first();
        $compteDestination = Compte::where('nom_proprietaire', 'like', '%' . $partenaire->nom . '%')->first()
            ?? $compteSource;

        // Créer quelques produits de test si ils n'existent pas
        $produits = Produit::take(3)->get();
        if ($produits->count() < 3) {
            $produits = collect([
                Produit::create(['nom' => 'Riz', 'variete' => 'Blanc', 'unite' => 'kg', 'prix_reference_mga' => 2500, 'actif' => true]),
                Produit::create(['nom' => 'Haricot', 'variete' => 'Rouge', 'unite' => 'kg', 'prix_reference_mga' => 3000, 'actif' => true]),
                Produit::create(['nom' => 'Maïs', 'variete' => 'Jaune', 'unite' => 'kg', 'prix_reference_mga' => 2000, 'actif' => true]),
            ]);
        }

        // Créer des transactions d'entrée
        for ($i = 1; $i <= 3; $i++) {
            $montant = rand(50000, 200000);
            $transaction = PartenaireTransaction::create([
                'partenaire_id' => $partenaire->id,
                'reference' => 'ENT-2025-' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'type' => 'entree',
                'montant_mga' => $montant,
                'motif' => 'Transfert d\'argent #' . $i,
                'mode_paiement' => ['especes', 'AirtelMoney', 'Mvola'][rand(0, 2)],
                'compte_source_id' => $compteSource?->id,
                'compte_destination_id' => $compteDestination?->id,
                'statut' => true,
                'date_transaction' => now()->subDays(rand(1, 30)),
                'observation' => 'Transaction de test'
            ]);

            // Mettre à jour le solde du partenaire
            $partenaire->increment('solde_actuel_mga', $montant);
        }

        // Créer des transactions de sortie avec détails
        for ($i = 1; $i <= 2; $i++) {
            $montantTotal = 0;
            $details = [];

            // Générer des détails aléaoires
            $nbDetails = rand(2, 4);
            for ($j = 1; $j <= $nbDetails; $j++) {
                $type = ['achat_produit', 'credit', 'frais'][rand(0, 2)];
                $montant = rand(10000, 50000);
                $montantTotal += $montant;

                $detail = [
                    'type_detail' => $type,
                    'description' => $type === 'achat_produit' ? 'Achat ' . $produits->random()->nom : ucfirst($type) . ' #' . $j,
                    'quantite' => $type === 'achat_produit' ? rand(10, 100) : 0,
                    'prix_unitaire_mga' => $type === 'achat_produit' ? rand(1000, 3000) : 0,
                    'montant_mga' => $montant,
                    'produit_id' => $type === 'achat_produit' ? $produits->random()->id : null,
                ];

                $details[] = $detail;
            }

            $transaction = PartenaireTransaction::create([
                'partenaire_id' => $partenaire->id,
                'reference' => 'SORT-2025-' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'type' => 'sortie',
                'montant_mga' => $montantTotal,
                'motif' => 'Dépenses diverses #' . $i,
                'statut' => true,
                'date_transaction' => now()->subDays(rand(1, 15)),
                'observation' => 'Sortie de test avec détails'
            ]);

            // Créer les détails
            foreach ($details as $detail) {
                PartenaireTransactionDetail::create([
                    'transaction_id' => $transaction->id,
                    'produit_id' => $detail['produit_id'],
                    'description' => $detail['description'],
                    'quantite' => $detail['quantite'],
                    'prix_unitaire_mga' => $detail['prix_unitaire_mga'],
                    'montant_mga' => $detail['montant_mga'],
                    'type_detail' => $detail['type_detail']
                ]);
            }

            // Mettre à jour le solde du partenaire
            $partenaire->decrement('solde_actuel_mga', $montantTotal);
        }

        // Calculer le solde final
        $partenaire->calculerSolde();
    }
}
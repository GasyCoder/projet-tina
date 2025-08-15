<?php

namespace App\Services;

use App\Models\Achat;
use App\Models\Compte;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class TransactionService
{
    public function generateReference(): string
    {
        $count = Achat::withTrashed()
            ->whereDate('created_at', Carbon::today())
            ->count() + 1;

        do {
            $reference = 'ACH' . date('Ymd') . str_pad($count, 3, '0', STR_PAD_LEFT);
            $count++;
        } while (Achat::withTrashed()->where('reference', $reference)->exists());

        return $reference;
    }

    public function verifierSolde(string $modePaiement, float $montant): array
    {
        if ($modePaiement === 'especes') {
            return ['success' => true];
        }

        $typeCompte = match($modePaiement) {
            'AirtelMoney' => 'AirtelMoney',
            'Mvola' => 'Mvola',
            'OrangeMoney' => 'OrangeMoney',
            'banque' => 'banque',
            default => null
        };

        if (!$typeCompte) {
            return ['success' => false, 'message' => 'Mode de paiement invalide'];
        }

        $compte = Compte::where('type_compte', $typeCompte)
            ->where('actif', true)
            ->first();

        if (!$compte) {
            return [
                'success' => false,
                'message' => "Aucun compte actif pour {$modePaiement}"
            ];
        }

        $solde = (float) $compte->solde_actuel_mga;
        if ($solde < $montant) {
            return [
                'success' => false,
                'message' => "Solde insuffisant. Disponible: " . number_format($solde, 0, ',', ' ') . " MGA"
            ];
        }

        return ['success' => true];
    }

    public function creerTransaction(array $data): Achat
    {
        // Vérifier le solde si confirmé
        if ($data['statut'] && $data['mode_paiement'] !== 'especes') {
            $verification = $this->verifierSolde($data['mode_paiement'], $data['montant_mga']);
            if (!$verification['success']) {
                throw new \Exception($verification['message']);
            }
        }

        return Achat::create($data);
    }

    public function modifierTransaction(Achat $achat, array $data): bool
    {
        // Vérifier le solde si confirmé
        if ($data['statut'] && $data['mode_paiement'] !== 'especes') {
            $verification = $this->verifierSolde($data['mode_paiement'], $data['montant_mga']);
            if (!$verification['success']) {
                throw new \Exception($verification['message']);
            }
        }

        return $achat->update($data);
    }

    public function confirmerTransaction(int $achatId): bool
    {
        $achat = Achat::findOrFail($achatId);
        
        if ($achat->mode_paiement !== 'especes') {
            $verification = $this->verifierSolde($achat->mode_paiement, $achat->montant_mga);
            if (!$verification['success']) {
                throw new \Exception($verification['message']);
            }
        }

        return $achat->update(['statut' => true]);
    }

    public function getStatistiques(?string $dateDebut = null, ?string $dateFin = null): array
    {
        $query = Achat::query();
        
        if ($dateDebut && $dateFin) {
            $query->whereBetween('date', [$dateDebut, $dateFin]);
        }

        $totalSorties = (clone $query)->where('statut', true)->sum('montant_mga');
        $transactionsEnAttente = (clone $query)->where('statut', false)->count();
        $nombreTransactions = (clone $query)->count();

        $repartitionParStatut = (clone $query)
            ->selectRaw('statut, COUNT(*) as count')
            ->groupBy('statut')
            ->pluck('count', 'statut');

        $repartitionParType = (clone $query)
            ->selectRaw('mode_paiement, COUNT(*) as count, SUM(montant_mga) as total')
            ->groupBy('mode_paiement')
            ->get()
            ->mapWithKeys(fn($item) => [
                $item->mode_paiement => [
                    'count' => (int)$item->count,
                    'total' => (float)$item->total
                ]
            ]);

        return compact(
            'totalSorties',
            'transactionsEnAttente', 
            'nombreTransactions',
            'repartitionParStatut',
            'repartitionParType'
        );
    }
}
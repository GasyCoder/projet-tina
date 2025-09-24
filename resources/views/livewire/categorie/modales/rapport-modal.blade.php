<div>
    @if($showRapportModal)
        <!-- Modal -->
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-2/3 shadow-lg rounded-md bg-white dark:bg-gray-800">
                <!-- En-tête -->
                <div class="flex justify-between items-center pb-3 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">
                        📊 Rapport Mensuel - {{ $categorie->nom }}
                    </h3>
                    <button wire:click="closeRapportModal" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <!-- Contrôles -->
                <div class="my-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Mois à analyser :</label>
                    <div class="flex space-x-4">
                        <input type="month" wire:model="moisRapport" class="form-input rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300">
                        <button wire:click="genererRapport" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                            Générer
                        </button>
                        <button wire:click="exportRapport" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors">
                            Exporter
                        </button>
                    </div>
                </div>

                <!-- Données du rapport -->
                @if(!empty($rapportData))
                <div class="space-y-6">
                    <!-- Résumé -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="bg-blue-50 dark:bg-blue-900/20 p-4 rounded-lg">
                            <div class="text-blue-600 dark:text-blue-400 font-semibold">Total du mois</div>
                            <div class="text-2xl font-bold dark:text-white">{{ number_format($rapportData['total_mois'] ?? 0, 0, ',', ' ') }} Ar</div>
                            <div class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ $rapportData['mois'] ?? '' }}</div>
                        </div>
                        <div class="bg-green-50 dark:bg-green-900/20 p-4 rounded-lg">
                            <div class="text-green-600 dark:text-green-400 font-semibold">Transactions</div>
                            <div class="text-2xl font-bold dark:text-white">{{ $rapportData['nombre_transactions'] ?? 0 }}</div>
                            <div class="text-sm text-gray-600 dark:text-gray-400 mt-1">Total des opérations</div>
                        </div>
                        <div class="bg-purple-50 dark:bg-purple-900/20 p-4 rounded-lg">
                            <div class="text-purple-600 dark:text-purple-400 font-semibold">Moyenne</div>
                            <div class="text-2xl font-bold dark:text-white">{{ number_format($rapportData['moyenne'] ?? 0, 0, ',', ' ') }} Ar</div>
                            <div class="text-sm text-gray-600 dark:text-gray-400 mt-1">Par transaction</div>
                        </div>
                    </div>

                    <!-- Détails par type -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                            <h4 class="font-semibold text-gray-700 dark:text-gray-300 mb-3">📈 Entrées</h4>
                            <div class="space-y-2">
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Nombre :</span>
                                    <span class="font-medium dark:text-white">{{ $rapportData['entrees']['nombre'] ?? 0 }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Montant total :</span>
                                    <span class="font-medium text-green-600 dark:text-green-400">{{ number_format($rapportData['entrees']['montant'] ?? 0, 0, ',', ' ') }} Ar</span>
                                </div>
                            </div>
                        </div>

                        <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                            <h4 class="font-semibold text-gray-700 dark:text-gray-300 mb-3">📉 Sorties</h4>
                            <div class="space-y-2">
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Nombre :</span>
                                    <span class="font-medium dark:text-white">{{ $rapportData['sorties']['nombre'] ?? 0 }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Montant total :</span>
                                    <span class="font-medium text-red-600 dark:text-red-400">{{ number_format($rapportData['sorties']['montant'] ?? 0, 0, ',', ' ') }} Ar</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Transactions récentes -->
                    @if(!empty($rapportData['transactions_recentes']))
                    <div>
                        <h4 class="font-semibold text-gray-700 dark:text-gray-300 mb-3">🕒 10 transactions récentes</h4>
                        <div class="border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Date</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Description</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Montant</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($rapportData['transactions_recentes'] as $transaction)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">{{ $transaction['date'] }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">{{ $transaction['description'] }}</td>
                                        <td class="px-4 py-3 text-sm font-medium {{ $transaction['type'] === 'recette' ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                            {{ number_format($transaction['montant'], 0, ',', ' ') }} Ar
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @else
                    <div class="text-center py-4 text-gray-500 dark:text-gray-400">
                        <p>Aucune transaction pour ce mois.</p>
                    </div>
                    @endif
                </div>
                @else
                <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                    <svg class="w-12 h-12 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <p class="mt-2">Sélectionnez un mois et cliquez sur "Générer" pour voir le rapport.</p>
                </div>
                @endif

                <!-- Pied de modal -->
                <div class="flex justify-end pt-4 border-t border-gray-200 dark:border-gray-700 mt-6">
                    <button wire:click="closeRapportModal" class="px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-md hover:bg-gray-400 dark:hover:bg-gray-500 transition-colors">
                        Fermer
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
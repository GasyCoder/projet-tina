{{-- Modal aperçu rapide d'une catégorie (sans type) --}}
@if($showDetail && $detail)
<div class="fixed inset-0 z-50 overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <!-- Overlay -->
        <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75 dark:bg-gray-900 dark:bg-opacity-75 backdrop-blur-sm"
             wire:click="closeDetail"></div>

        <!-- Modal container -->
        <div class="inline-block w-full max-w-3xl my-8 overflow-hidden text-left align-middle transition-all transform bg-white dark:bg-gray-800 shadow-2xl rounded-xl">
            <!-- En-tête -->
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-900 dark:to-gray-800">
                <div class="flex items-center">
                    <div class="w-12 h-12 rounded-xl bg-gray-100 dark:bg-gray-900 flex items-center justify-center mr-4">
                        <span class="text-lg font-bold text-gray-700 dark:text-gray-300">
                            {{ $detail['code_comptable'] }}
                        </span>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">{{ $detail['nom'] }}</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            {{ $detail['is_active'] ? 'Active' : 'Inactive' }}
                        </p>
                    </div>
                </div>
                <button wire:click="closeDetail"
                        class="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <!-- Contenu -->
            <div class="px-6 py-6">
                <!-- Statistiques -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                    <div class="bg-blue-50 dark:bg-blue-900/20 p-4 rounded-lg border border-blue-200 dark:border-blue-700">
                        <div class="text-center">
                            <p class="text-xs font-semibold text-blue-700 dark:text-blue-300 uppercase">Total</p>
                            <p class="text-xl font-bold text-blue-900 dark:text-blue-100 mt-1">
                                {{ number_format($detail['montant_total'], 0, ',', ' ') }} Ar
                            </p>
                        </div>
                    </div>

                    <div class="bg-green-50 dark:bg-green-900/20 p-4 rounded-lg border border-green-200 dark:border-green-700">
                        <div class="text-center">
                            <p class="text-xs font-semibold text-green-700 dark:text-green-300 uppercase">Ce mois</p>
                            <p class="text-xl font-bold text-green-900 dark:text-green-100 mt-1">
                                {{ number_format($detail['transactions_mois'], 0, ',', ' ') }} Ar
                            </p>
                        </div>
                    </div>

                    <div class="bg-purple-50 dark:bg-purple-900/20 p-4 rounded-lg border border-purple-200 dark:border-purple-700">
                        <div class="text-center">
                            <p class="text-xs font-semibold text-purple-700 dark:text-purple-300 uppercase">Transactions</p>
                            <p class="text-xl font-bold text-purple-900 dark:text-purple-100 mt-1">
                                {{ $detail['nombre_transactions'] }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Transactions récentes -->
                @if(!empty($detail['recent_transactions']))
                <div class="mb-6">
                    <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-3">Transactions récentes</h4>
                    <div class="space-y-2">
                        @foreach($detail['recent_transactions'] as $transaction)
                        <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <div>
                                <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $transaction['description'] }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ $transaction['date'] }}
                                    @if($transaction['partenaire'])
                                        • {{ $transaction['partenaire'] }}
                                    @endif
                                </p>
                            </div>
                            <p class="text-sm font-bold text-gray-900 dark:text-gray-100">
                                {{ number_format($transaction['montant'], 0, ',', ' ') }} Ar
                            </p>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Actions -->
                <div class="flex items-center space-x-3">
                    <button wire:click="show({{ $detail['id'] }})"
                            class="flex-1 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                        Voir toutes les transactions
                    </button>

                    <button wire:click="openTransactionModal({{ $detail['id'] }})"
                            class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors">
                        <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        Ajouter transaction
                    </button>

                    <button wire:click="editModal({{ $detail['id'] }})"
                            class="px-4 py-2 bg-orange-600 hover:bg-orange-700 text-white font-medium rounded-lg transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
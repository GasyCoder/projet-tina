@if($showDetail)
<div class="fixed inset-0 overflow-y-auto z-50" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <!-- Background overlay -->
        <div class="fixed inset-0 bg-gray-500 dark:bg-gray-900 bg-opacity-75 dark:bg-opacity-75 transition-opacity" 
             aria-hidden="true"
             wire:click="closeDetail"></div>

        <!-- Modal content -->
        <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                        <!-- Header -->
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white" id="modal-title">
                                    {{ $detail['nom'] }}
                                </h3>
                                <div class="mt-1 flex items-center">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        {{ $detail['type'] === 'recette' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' }}">
                                        {{ ucfirst($detail['type']) }}
                                    </span>
                                    <span class="ml-2 px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        {{ $detail['is_active'] ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300' }}">
                                        {{ $detail['is_active'] ? 'Active' : 'Inactive' }}
                                    </span>
                                </div>
                            </div>
                            <button wire:click="closeDetail" class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        <!-- Details -->
                        <div class="mt-4 space-y-4">
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Code comptable</p>
                                <p class="mt-1 text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $detail['code_comptable'] }}
                                </p>
                            </div>

                            @if($detail['description'])
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Description</p>
                                <p class="mt-1 text-sm text-gray-900 dark:text-white">
                                    {{ $detail['description'] }}
                                </p>
                            </div>
                            @endif

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Total transactions</p>
                                    <p class="mt-1 text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $detail['nombre_transactions'] }}
                                    </p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Montant total</p>
                                    <p class="mt-1 text-sm font-medium 
                                        {{ $detail['type'] === 'recette' ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                        {{ number_format($detail['montant_total'], 0, ',', ' ') }} Ar
                                    </p>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Créé le</p>
                                    <p class="mt-1 text-sm text-gray-900 dark:text-white">
                                        {{ $detail['created_at'] }}
                                    </p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Modifié le</p>
                                    <p class="mt-1 text-sm text-gray-900 dark:text-white">
                                        {{ $detail['updated_at'] ?? 'Jamais' }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Recent Transactions -->
                        @if(count($detail['recent_transactions']) > 0)
                        <div class="mt-6">
                            <h4 class="text-sm font-medium text-gray-900 dark:text-white mb-2">
                                Dernières transactions
                            </h4>
                            <div class="space-y-2">
                                @foreach($detail['recent_transactions'] as $transaction)
                                <div class="flex items-center justify-between p-2 bg-gray-50 dark:bg-gray-700 rounded">
                                    <div>
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $transaction['reference'] }}
                                        </p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ $transaction['date'] }}
                                        </p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm 
                                            {{ $detail['type'] === 'recette' ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                            {{ number_format($transaction['montant'], 0, ',', ' ') }} Ar
                                        </p>
                                        @if($transaction['partenaire'])
                                        <p class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ $transaction['partenaire'] }}
                                        </p>
                                        @endif
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" wire:click="editModal({{ $detail['id'] }})"
                    class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                    Modifier
                </button>
                <button type="button" wire:click="toggle({{ $detail['id'] }})"
                    class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-600 text-base font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                    {{ $detail['is_active'] ? 'Désactiver' : 'Activer' }}
                </button>
                <button type="button" wire:click="openTransactionModal({{ $detail['id'] }})"
                    class="mt-3 w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                    Ajouter transaction
                </button>
            </div>
        </div>
    </div>
</div>
@endif
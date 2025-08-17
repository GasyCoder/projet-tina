{{-- Modal de création/édition des catégories --}}
@if($showFormModal)
<div class="fixed inset-0 z-50 overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <!-- Overlay -->
        <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75 dark:bg-gray-900 dark:bg-opacity-75 backdrop-blur-sm"
             wire:click="closeModal"></div>

        <!-- Modal container -->
        <div class="inline-block w-full max-w-2xl my-8 overflow-hidden text-left align-middle transition-all transform bg-white dark:bg-gray-800 shadow-2xl rounded-xl">
            <!-- En-tête -->
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-gray-900 dark:to-gray-800">
                <div class="flex items-center">
                    <div class="p-2 bg-blue-100 dark:bg-blue-900 rounded-lg mr-3">
                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            @if($editingId)
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            @else
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            @endif
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                            {{ $editingId ? 'Modifier la catégorie' : 'Nouvelle catégorie' }}
                        </h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            {{ $editingId ? 'Modifiez les informations de la catégorie' : 'Créez une nouvelle catégorie comptable' }}
                        </p>
                    </div>
                </div>
                <button wire:click="closeModal"
                        class="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <!-- Formulaire -->
            <div class="px-6 py-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Code comptable -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            <span class="flex items-center">
                                <svg class="w-4 h-4 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/>
                                </svg>
                                Code comptable
                                <span class="text-red-500 ml-1">*</span>
                            </span>
                        </label>
                        <input type="text"
                               wire:model.defer="code_comptable"
                               placeholder="Ex: 05, 10A, 625..."
                               maxlength="10"
                               class="w-full px-3 py-2.5 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white font-mono">
                        @error('code_comptable')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Nom -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            <span class="flex items-center">
                                <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                </svg>
                                Nom de la catégorie
                                <span class="text-red-500 ml-1">*</span>
                            </span>
                        </label>
                        <input type="text"
                               wire:model.defer="nom"
                               placeholder="Ex: Carburant, Frais de transport, Ventes produits..."
                               class="w-full px-3 py-2.5 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                        @error('nom')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Budget (Ar) -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            <span class="flex items-center">
                                <svg class="w-4 h-4 mr-2 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-10v.01M12 6v.01" />
                                </svg>
                                Budget Max. (Ar)
                                <span class="text-red-500 ml-1">*</span>
                            </span>
                        </label>
                        <input type="number"
                               wire:model.defer="budget"
                               placeholder="0,00"
                               step="0.01"
                               min="0"
                               inputmode="decimal"
                               class="w-full px-3 py-2.5 text-sm border border-gray-300 dark:border-gray-600 rounded-lg
                                      focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700
                                      text-gray-900 dark:text-white">
                        @error('budget')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Description -->
                <div class="mt-6">
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                        <span class="flex items-center">
                            <svg class="w-4 h-4 mr-2 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Description (optionnel)
                        </span>
                    </label>
                    <textarea
                        wire:model.defer="description"
                        rows="3"
                        placeholder="Description détaillée de cette catégorie..."
                        class="w-full px-3 py-2.5 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white resize-none"></textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Statut -->
                <div class="mt-6 bg-gray-50 dark:bg-gray-700/50 p-4 rounded-lg">
                    <label class="flex items-center space-x-3 cursor-pointer">
                        <input type="checkbox"
                               wire:model.defer="is_active"
                               class="w-4 h-4 text-blue-600 bg-white border-2 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <div>
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Catégorie active</span>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Si activée, cette catégorie sera disponible pour les nouvelles transactions.</p>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-between px-6 py-4 bg-gray-50 dark:bg-gray-900 border-t border-gray-200 dark:border-gray-700">
                <div class="text-xs text-gray-500 dark:text-gray-400">
                    Les champs marqués d'un <span class="text-red-500 font-medium">*</span> sont obligatoires
                </div>

                <div class="flex items-center space-x-3">
                    <button wire:click="closeModal"
                            type="button"
                            class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white font-medium rounded-lg transition-all duration-200">
                        Annuler
                    </button>

                    <button wire:click="save"
                            class="px-6 py-2 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-semibold rounded-lg transition-all duration-200 shadow-sm hover:shadow-md">
                        {{ $editingId ? 'Mettre à jour' : 'Créer la catégorie' }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

{{-- Modal aperçu rapide d'une catégorie (affiche Budget) --}}
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
                <!-- Budget -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div class="bg-amber-50 dark:bg-amber-900/20 p-4 rounded-lg border border-amber-200 dark:border-amber-700">
                        <div class="text-center">
                            <p class="text-xs font-semibold text-amber-700 dark:text-amber-300 uppercase">Budget</p>
                            <p class="text-xl font-bold text-amber-900 dark:text-amber-100 mt-1">
                                {{ number_format($detail['budget'] ?? 0, 0, ',', ' ') }} Ar
                            </p>
                        </div>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-900/20 p-4 rounded-lg border border-gray-200 dark:border-gray-700">
                        <div class="text-center">
                            <p class="text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase">Statut</p>
                            <p class="text-xl font-bold text-gray-900 dark:text-gray-100 mt-1">
                                {{ $detail['is_active'] ? 'Active' : 'Inactive' }}
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

{{-- Modal transaction rapide --}}
@if($showTransactionModal)
<div class="fixed inset-0 z-50 overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <!-- Overlay -->
        <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75 dark:bg-gray-900 dark:bg-opacity-75 backdrop-blur-sm"
             wire:click="closeTransactionModal"></div>

        <!-- Modal container -->
        <div class="inline-block w-full max-w-md my-8 overflow-hidden text-left align-middle transition-all transform bg-white dark:bg-gray-800 shadow-2xl rounded-xl">
            <!-- En-tête -->
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Nouvelle transaction</h3>
                <button wire:click="closeTransactionModal"
                        class="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <!-- Formulaire -->
            <div class="px-6 py-6 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Description *</label>
                    <input type="text"
                           wire:model.defer="newTransaction.description"
                           placeholder="Description de la transaction..."
                           class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                    @error('newTransaction.description')
                        <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Montant *</label>
                    <input type="number"
                           wire:model.defer="newTransaction.montant"
                           placeholder="0"
                           step="0.01"
                           min="0"
                           class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                    @error('newTransaction.montant')
                        <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Date</label>
                    <input type="date"
                           wire:model.defer="newTransaction.date_transaction"
                           class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                    @error('newTransaction.date_transaction')
                        <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-end space-x-3 px-6 py-4 bg-gray-50 dark:bg-gray-900 border-t border-gray-200 dark:border-gray-700">
                <button wire:click="closeTransactionModal"
                        type="button"
                        class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white font-medium rounded-lg transition-all duration-200">
                    Annuler
                </button>

                <button wire:click="saveTransaction"
                        class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-all duration-200">
                    Créer
                </button>
            </div>
        </div>
    </div>
</div>
@endif

{{-- resources/views/livewire/finance/modals/transaction-modal.blade.php --}}
@if($showTransactionModal)
    <div class="fixed inset-0 z-50 overflow-y-auto" x-data="{ step: 1 }">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!-- Overlay avec animation -->
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 dark:bg-gray-900 dark:bg-opacity-75 transition-opacity" 
                 wire:click="closeTransactionModal"></div>
            
            <!-- Modal Container avec animation moderne -->
            <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-5xl sm:w-full border border-gray-200 dark:border-gray-700">
                
                <!-- Header avec gradient -->
                <div class="bg-gradient-to-r from-blue-600 to-purple-700 px-6 py-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center">
                                    @if($editingTransaction)
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    @else
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                        </svg>
                                    @endif
                                </div>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-white">
                                    {{ $editingTransaction ? 'Modifier' : 'Nouvelle' }} Transaction
                                </h3>
                                <p class="text-blue-100 text-sm">
                                    {{ $editingTransaction ? 'Modifiez les chats des produits' : 'Achat des produits' }}
                                </p>
                            </div>
                        </div>
                        <button wire:click="closeTransactionModal" 
                                class="text-white/80 hover:text-white hover:bg-white/10 rounded-lg p-2 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </div>
                <form wire:submit="saveTransaction">
                    <div class="bg-gray-50 dark:bg-gray-700 px-6 py-6">
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                            <!-- Colonne gauche - Informations principales -->
                            <div class="lg:col-span-2 space-y-6">
                                <!-- 1. INFORMATIONS DE BASE -->
                                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/30 dark:to-indigo-900/30 px-6 py-4 border-b border-blue-100 dark:border-blue-900">
                                        <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100 flex items-center">
                                            <span class="w-8 h-8 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center mr-3">
                                                📋
                                            </span>
                                            Informations de base
                                        </h4>
                                    </div>
                                    <div class="p-6 space-y-4">
                                        <input wire:model="reference" type="hidden">
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div>
                                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                                    <span class="flex items-center">
                                                        🏷️ Type de transaction *
                                                        <span class="ml-1 text-red-500">*</span>
                                                    </span>
                                                </label>
                                                <select wire:model.live="type" 
                                                        class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors dark:bg-gray-700 dark:text-gray-300">
                                                    <option value="">🔹 Sélectionner le type</option>
                                                    <option value="achat">🛒 Achat de produits</option>
                                                </select>
                                                @error('type') 
                                                    <div class="mt-2 flex items-center text-red-600 text-sm">
                                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                                        </svg>
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                            <div>
                                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                                    <span class="flex items-center">
                                                        📅 Date de transaction *
                                                        <span class="ml-1 text-red-500">*</span>
                                                    </span>
                                                </label>
                                                <input wire:model="date" type="date" 
                                                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors dark:bg-gray-700 dark:text-gray-300">
                                                @error('date') 
                                                    <div class="mt-2 flex items-center text-red-600 text-sm">
                                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                                        </svg>
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Product Selection -->
                                    @if(in_array($type, ['achat']))
                                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden mt-6">
                                            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/30 dark:to-indigo-900/30 px-6 py-4 border-b border-blue-100 dark:border-blue-900">
                                                <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100 flex items-center">
                                                    <span class="w-8 h-8 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center mr-3">
                                                        📦
                                                    </span>
                                                    Détails du produit
                                                </h4>
                                            </div>
                                            <div class="p-6 space-y-6">
                                                <div>
                                                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                                        <span class="flex items-center">
                                                            🛍️ Produit*
                                                            <span class="ml-1 text-red-500">*</span>
                                                        </span>
                                                    </label>
                                                    <select wire:model.live="produit_id" 
                                                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors dark:bg-gray-700 dark:text-gray-300">
                                                        <option value="">🔍 Sélectionner un produit</option>
                                                        @forelse($produitsDisponibles as $produit)
                                                            <option value="{{ $produit->id }}">
                                                                {{ $produit->nom_complet }} ({{ $produit->unite }})
                                                                @if($type === 'vente')
                                                                    - Stock: {{ number_format($produit->poids_moyen_sac_kg_max, 2) }} {{ $produit->unite }}
                                                                @endif
                                                            </option>
                                                        @empty
                                                            <option disabled>❌ Aucun produit disponible</option>
                                                        @endforelse
                                                    </select>
                                                    @error('produit_id') 
                                                        <div class="mt-2 flex items-center text-red-600 text-sm">
                                                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                                            </svg>
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>

                                            </div>
                                        </div>
                                    @endif
                                </div>
                               <!-- Partenaires -->
                                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                                    <div class="bg-gradient-to-r from-yellow-50 to-orange-50 dark:from-yellow-900/30 dark:to-orange-900/30 px-6 py-4 border-b border-yellow-100 dark:border-yellow-900">
                                        <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100 flex items-center">
                                            <span class="w-8 h-8 bg-yellow-100 dark:bg-yellow-900 rounded-lg flex items-center justify-center mr-3">
                                                👥
                                            </span>
                                            Partenaires
                                        </h4>
                                    </div>
                                    <div class="p-6 space-y-4">
                                        <div>
                                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                                <span class="flex items-center justify-between">
                                                    <span>Choisir partenaires</span>
                                                </span>
                                            </label>
                                              <select wire:model.live="partenaire_id" 
                                                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors dark:bg-gray-700 dark:text-gray-300">
                                                <option value="">🔍 Sélectionner un partenaire</option>
                                                @forelse($partenaires ?? [] as $partenaire)
                                                    <option value="{{ $partenaire->id }}">
                                                        {{ $partenaire->nom }} 
                                                    </option>
                                                @empty
                                                    <option disabled>❌ Aucun partenaire disponible</option>
                                                @endforelse
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Colonne droite - Résumé et finalisation -->
                            <div class="space-y-6">
                                <!-- MONTANT -->
                                <div class="overflow-hidden">
                                <!-- MODALITÉS DE PAIEMENT -->
                                <div class="mb-4 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                                    <div class="bg-gradient-to-r from-purple-50 to-indigo-50 dark:from-purple-900/30 dark:to-indigo-900/30 px-6 py-4 border-b border-purple-100 dark:border-purple-900">
                                        <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100 flex items-center">
                                            <span class="w-8 h-8 bg-purple-100 dark:bg-purple-900 rounded-lg flex items-center justify-center mr-3">
                                                💳
                                            </span>
                                            Modalités
                                        </h4>
                                    </div>
                                    <div class="p-6 space-y-4">
                                        <div>
                                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Mode de paiement *</label>
                                            <select wire:model.live="mode_paiement" 
                                                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors dark:bg-gray-700 dark:text-gray-300">
                                                <option value="especes">💵 Espèces</option>
                                                <option value="AirtelMoney">📱 AirtelMoney</option>
                                                <option value="Mvola">📱 MVola</option>
                                                <option value="OrangeMoney">📱 OrangeMoney</option>
                                                <option value="banque">🏦 Banque</option>
                                            </select>
                                            @error('mode_paiement') 
                                                <div class="mt-2 flex items-center text-red-600 text-sm">
                                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                                    </svg>
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                        <!-- COMPTE DE DESTINATION - SEULEMENT SI PAS ESPÈCES -->
                                        @if($mode_paiement !== 'especes')
                                            <div>
                                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                                    🏦 Compte de destination
                                                </label>
                                                <input wire:model="to_compte" type="text" placeholder="Ex: 034 12 345 67, nom du compte..." 
                                                        class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 transition-colors dark:bg-gray-700 dark:text-gray-300">
                                                <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                                                    @if($mode_paiement === 'banque')
                                                        Numéro de compte bancaire ou nom de la banque
                                                    @elseif($mode_paiement === 'AirtelMoney')
                                                        Numéro AirtelMoney de destination
                                                    @elseif($mode_paiement === 'MVola')
                                                        Numéro MVola de destination
                                                    @elseif($mode_paiement === 'OrangeMoney')
                                                        Numéro OrangeMoney de destination
                                                    @else
                                                        Informations du compte de destination
                                                    @endif
                                                </p>
                                            </div>
                                        @endif

                                    </div>
                                </div>
                                <!-- OBSERVATIONS -->
                                <div class="mb-4 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                                    <div class="bg-gradient-to-r from-gray-50 to-slate-50 dark:from-gray-900/30 dark:to-slate-900/30 px-6 py-4 border-b border-gray-100 dark:border-gray-700">
                                        <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100 flex items-center">
                                            <span class="w-8 h-8 bg-gray-100 dark:bg-gray-900 rounded-lg flex items-center justify-center mr-3">
                                                📝
                                            </span>
                                            Notes
                                        </h4>
                                    </div>
                                    <div class="p-6">
                                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Observations</label>
                                        <textarea wire:model="observation" rows="4" 
                                                    placeholder="Notes supplémentaires, commentaires en français ou malagasy..."
                                                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-gray-500 focus:border-gray-500 transition-colors resize-none dark:bg-gray-700 dark:text-gray-300"></textarea>
                                        <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">Informations complémentaires sur cette transaction</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Footer avec actions -->
                    <div class="bg-gray-50 dark:bg-gray-700 px-6 py-4 border-t border-gray-200 dark:border-gray-600">
                        <div class="flex flex-col sm:flex-row-reverse sm:space-x-reverse sm:space-x-3 space-y-3 sm:space-y-0">
                            <button type="submit" 
                                    class="w-full sm:w-auto inline-flex justify-center items-center px-6 py-3 border border-transparent text-base font-medium rounded-lg text-white bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 shadow-lg transition-all">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                {{ $editingTransaction ? '💾 Enregistrer les modifications' : '✨ Créer la transaction' }}
                            </button>
                            <button type="button" wire:click="closeTransactionModal" 
                                    class="w-full sm:w-auto inline-flex justify-center items-center px-6 py-3 border border-gray-300 dark:border-gray-600 text-base font-medium rounded-lg text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                                Annuler
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endif
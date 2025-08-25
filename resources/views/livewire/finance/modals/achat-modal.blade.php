{{-- resources/views/livewire/finance/modals/achat-modal.blade.php --}}
{{-- PURE LIVEWIRE + TAILWIND - SANS ALPINE.JS --}}

@if($showModal)
<div class="fixed inset-0 z-50 overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        
        <!-- Overlay -->
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 dark:bg-gray-900 dark:bg-opacity-75 transition-opacity" 
             wire:click="closeModal"></div>
        
        <!-- Modal Container -->
        <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full border border-gray-200 dark:border-gray-700">
            
            <!-- Header -->
            <div class="bg-gradient-to-r from-blue-600 to-purple-700 px-6 py-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center">
                            @if($editingAchat)
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            @else
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                </svg>
                            @endif
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-white">
                                {{ $editingAchat ? 'Modifier l\'achat' : 'Nouvel achat' }}
                            </h3>
                            <p class="text-blue-100 text-sm">Gestion des achats et dépenses</p>
                        </div>
                    </div>
                    <button wire:click="closeModal" 
                            class="text-white/80 hover:text-white hover:bg-white/10 rounded-lg p-2 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Message de solde -->
            @if($soldeMessage)
            <div class="mx-6 mt-4 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-red-500 dark:text-red-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="text-sm text-red-600 dark:text-red-400">{{ $soldeMessage }}</p>
                </div>
            </div>
            @endif

            <!-- Formulaire - PURE LIVEWIRE -->
            <form wire:submit.prevent="saveAchat">
                <div class="bg-gray-50 dark:bg-gray-700 px-6 py-6">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        
                        <!-- COLONNE GAUCHE -->
                        <div class="space-y-6">
                            
                            <!-- Informations de base -->
                            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                                <h4 class="flex items-center font-semibold text-gray-900 dark:text-gray-100 mb-4">
                                    <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center mr-3">
                                        📋
                                    </div>
                                    Informations de base
                                </h4>
                                
                                <div class="space-y-4">
                                    <!-- Référence (caché) -->
                                    <input wire:model="form.reference" type="hidden">

                                    <!-- Objet -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            Objet de la transaction *
                                        </label>
                                        <input wire:model="form.objet" 
                                               type="text" 
                                               placeholder="Description de l'achat..."
                                               class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors dark:bg-gray-700 dark:text-gray-300">
                                        @error('form.objet') 
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p> 
                                        @enderror
                                    </div>

                                    <!-- Date -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            Date de transaction *
                                        </label>
                                        <input wire:model="form.date" 
                                               type="date" 
                                               class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors dark:bg-gray-700 dark:text-gray-300">
                                        @error('form.date') 
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p> 
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Participants -->
                            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                                <h4 class="flex items-center font-semibold text-gray-900 dark:text-gray-100 mb-4">
                                    <div class="w-8 h-8 bg-purple-100 dark:bg-purple-900 rounded-lg flex items-center justify-center mr-3">
                                        👥
                                    </div>
                                    Participants
                                </h4>
                                
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            Émetteur (qui paye)
                                        </label>
                                        <input wire:model="form.from_nom" 
                                               type="text" 
                                               placeholder="Nom de l'émetteur..."
                                               class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors dark:bg-gray-700 dark:text-gray-300">
                                        @error('form.from_nom') 
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p> 
                                        @enderror
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            Bénéficiaire (qui reçoit)
                                        </label>
                                        <input wire:model="form.to_nom" 
                                               type="text" 
                                               placeholder="Nom du bénéficiaire..."
                                               class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors dark:bg-gray-700 dark:text-gray-300">
                                        @error('form.to_nom') 
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p> 
                                        @enderror
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            Compte/Numéro destination
                                        </label>
                                        <input wire:model="form.to_compte" 
                                               type="text" 
                                               placeholder="Numéro de compte ou mobile money..."
                                               class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors dark:bg-gray-700 dark:text-gray-300">
                                        @error('form.to_compte') 
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p> 
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- COLONNE DROITE -->
                        <div class="space-y-6">
                            
                            <!-- Montant -->
                            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                                <h4 class="flex items-center font-semibold text-gray-900 dark:text-gray-100 mb-4">
                                    <div class="w-8 h-8 bg-emerald-100 dark:bg-emerald-900 rounded-lg flex items-center justify-center mr-3">
                                        💰
                                    </div>
                                    Montant
                                </h4>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Montant en MGA *
                                    </label>
                                    <div class="relative">
                                        <input wire:model.live="form.montant" 
                                               type="number" 
                                               step="0.01" 
                                               min="0" 
                                               placeholder="0"
                                               class="w-full px-4 py-3 pr-16 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors dark:bg-gray-700 dark:text-gray-300">
                                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                            <span class="text-gray-500 dark:text-gray-400 text-sm font-medium">MGA</span>
                                        </div>
                                    </div>
                                    @error('form.montant') 
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p> 
                                    @enderror
                                </div>
                            </div>

                            <!-- Mode de paiement et statut -->
                            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                                <h4 class="flex items-center font-semibold text-gray-900 dark:text-gray-100 mb-4">
                                    <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center mr-3">
                                        💳
                                    </div>
                                    Mode de paiement
                                </h4>
                                
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            Mode de paiement *
                                        </label>
                                        <select wire:model.live="form.mode_paiement" 
                                                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors dark:bg-gray-700 dark:text-gray-300">
                                            <option value="especes">💵 Espèces</option>
                                            <option value="AirtelMoney">📱 AirtelMoney</option>
                                            <option value="Mvola">📱 MVola</option>
                                            <option value="OrangeMoney">📱 OrangeMoney</option>
                                            <option value="banque">🏦 Banque</option>
                                        </select>
                                        @error('form.mode_paiement') 
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p> 
                                        @enderror
                                    </div>

                                    <!-- Statut -->
                                    <div>
                                        <label class="flex items-center space-x-3 p-3 bg-gray-50 dark:bg-gray-700 rounded-lg cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                                            <input wire:model.live="form.statut" 
                                                   type="checkbox" 
                                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                                Transaction confirmée
                                            </span>
                                        </label>
                                        @error('form.statut') 
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p> 
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Observations -->
                            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                                <h4 class="flex items-center font-semibold text-gray-900 dark:text-gray-100 mb-4">
                                    <div class="w-8 h-8 bg-yellow-100 dark:bg-yellow-900 rounded-lg flex items-center justify-center mr-3">
                                        📝
                                    </div>
                                    Notes et observations
                                </h4>
                                
                                <div>
                                    <textarea wire:model="form.observation" 
                                              rows="4" 
                                              placeholder="Notes, commentaires, détails additionnels..."
                                              class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 transition-colors resize-none dark:bg-gray-700 dark:text-gray-300"></textarea>
                                    @error('form.observation') 
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p> 
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="bg-gray-50 dark:bg-gray-700 px-6 py-4 border-t border-gray-200 dark:border-gray-600">
                    <div class="flex flex-col sm:flex-row-reverse gap-3">
                        <button type="submit" 
                                class="flex-1 sm:flex-none inline-flex justify-center items-center px-6 py-3 border border-transparent text-base font-medium rounded-lg text-white bg-gradient-to-r from-emerald-600 to-green-600 hover:from-emerald-700 hover:to-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 shadow-lg transition-all disabled:opacity-50 disabled:cursor-not-allowed"
                                {{ $soldeMessage ? 'disabled' : '' }}
                                wire:loading.attr="disabled"
                                wire:target="saveAchat">
                            
                            <span wire:loading.remove wire:target="saveAchat" class="flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                {{ $editingAchat ? 'Modifier' : 'Créer' }}
                            </span>
                            
                            <span wire:loading wire:target="saveAchat" class="flex items-center">
                                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Sauvegarde...
                            </span>
                        </button>
                        
                        <button type="button" 
                                wire:click="closeModal" 
                                class="flex-1 sm:flex-none inline-flex justify-center items-center px-6 py-3 border border-gray-300 dark:border-gray-600 text-base font-medium rounded-lg text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                            Annuler
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
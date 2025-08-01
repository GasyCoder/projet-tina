<!-- ✅ MODAL COMPTE MODERNE ET SIMPLE -->
@if($showCompteModal)
<div class="fixed inset-0 z-50 overflow-y-auto"
     x-data="{ show: true }"
     x-show="show"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0 scale-95"
     x-transition:enter-end="opacity-100 scale-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100 scale-100"
     x-transition:leave-end="opacity-0 scale-95">
    
    <!-- Overlay -->
    <div class="flex items-center justify-center min-h-screen px-4 py-6">
        <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" 
             wire:click="closeCompteModal"></div>
        
        <!-- Modal Content -->
        <div class="bg-white rounded-2xl max-w-md w-full shadow-2xl relative z-10 transform transition-all">
            
            <!-- Header -->
            <div class="flex items-center justify-between p-6 border-b border-gray-100">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center">
                        @if($editingCompte)
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        @else
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                        @endif
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">
                            {{ $editingCompte ? 'Modifier le compte' : 'Nouveau compte' }}
                        </h2>
                        <p class="text-sm text-gray-500">
                            {{ $editingCompte ? 'Modifiez les informations du compte' : 'Créez un nouveau compte financier' }}
                        </p>
                    </div>
                </div>
                
                <button wire:click="closeCompteModal" 
                        class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Form -->
            <form wire:submit.prevent="saveCompte" class="p-6">
                <div class="space-y-5">
                    
                    <!-- Type de compte -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            🏷️ Type de compte *
                        </label>
                        <select wire:model.live="type_compte" 
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all text-sm">
                            <option value="principal">💰 Principal (Espèces/Caisse)</option>
                            <option value="AirtelMoney">📱AirtelMoney</option>
                            <option value="Mvola">📱MVola</option>
                            <option value="OrangeMoney">📱OrangeMoney</option>
                            <option value="banque">🏦 Compte Bancaire</option>
                        </select>
                        @error('type_compte') 
                            <p class="mt-1 text-xs text-red-600 flex items-center">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Propriétaire -->
                    <input wire:model="nom_proprietaire" 
                        type="hidden" 
                        class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all text-sm">

                    <!-- Numéro de compte -->
                    @if($type_compte !== 'principal')
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                🔢 Numéro de compte
                            </label>
                            <input wire:model="numero_compte" 
                                type="text" 
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all text-sm" 
                                placeholder="Ex: 123456789 ou 034 12 345 67">
                            @error('numero_compte') 
                                <p class="mt-1 text-xs text-red-600 flex items-center">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    @endif

                    <!-- Solde actuel -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            💰 Solde actuel (MGA)
                        </label>
                        <div class="relative">
                            <input wire:model="solde_actuel_mga" 
                                   type="number" 
                                   step="0.01" 
                                   class="w-full px-4 py-3 pr-16 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all text-sm" 
                                   placeholder="0">
                            <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                                <span class="text-gray-500 text-sm font-medium">MGA</span>
                            </div>
                        </div>
                        @if($solde_actuel_mga)
                            <p class="mt-1 text-xs text-gray-600">
                                ≈ {{ number_format($solde_actuel_mga, 0, ',', ' ') }} Ariary
                            </p>
                        @endif
                        @error('solde_actuel_mga') 
                            <p class="mt-1 text-xs text-red-600 flex items-center">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Compte actif -->
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl">
                        <div>
                            <label class="text-sm font-semibold text-gray-700">✅ Compte actif</label>
                            <p class="text-xs text-gray-500">Le compte peut être utilisé pour les transactions</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input wire:model="compte_actif" type="checkbox" class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                        </label>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex justify-end space-x-3 mt-8 pt-6 border-t border-gray-100">
                    <button type="button" 
                            wire:click="closeCompteModal"
                            class="px-6 py-3 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-xl transition-colors focus:outline-none focus:ring-2 focus:ring-gray-300">
                        Annuler
                    </button>
                    <button type="submit" 
                            class="px-6 py-3 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-xl transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 shadow-sm">
                        @if($editingCompte)
                            ✏️ Modifier le compte
                        @else
                            ➕ Créer le compte
                        @endif
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
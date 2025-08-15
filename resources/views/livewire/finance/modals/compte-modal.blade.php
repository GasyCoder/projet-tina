{{-- resources/views/livewire/finance/modals/compte-modal.blade.php --}}
{{-- PURE LIVEWIRE + TAILWIND - SANS ALPINE.JS --}}

@if($showCompteModal)
<div class="fixed inset-0 z-50 overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4 py-6">
        
        <!-- Overlay -->
        <div class="fixed inset-0 bg-black bg-opacity-50 dark:bg-opacity-70 transition-opacity" 
             wire:click="closeCompteModal"></div>
        
        <!-- Modal Content -->
        <div class="bg-white dark:bg-gray-800 rounded-lg max-w-md w-full shadow-xl relative z-10">
            
            <!-- Header -->
            <div class="flex items-center justify-between p-6 border-b border-gray-100 dark:border-gray-700">
                <div>
                    <h2 class="text-xl font-bold text-gray-900 dark:text-gray-100">
                        {{ $editingCompte ? 'Modifier le compte' : 'Nouveau compte' }}
                    </h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        {{ $editingCompte ? 'Modifiez les informations du compte' : 'Créez un nouveau compte financier' }}
                    </p>
                </div>
                
                <button wire:click="closeCompteModal" 
                        class="text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-300 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Form -->
            <form wire:submit.prevent="saveCompte" class="p-6">
                <div class="space-y-5">
                    
                    <!-- Type de compte -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Type de compte *
                        </label>
                        <select wire:model.live="type_compte" 
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-gray-300">
                            <option value="principal">💰 Principal (Espèces/Caisse)</option>
                            <option value="AirtelMoney">📱 AirtelMoney</option>
                            <option value="Mvola">📱 Mvola</option>
                            <option value="OrangeMoney">📱 OrangeMoney</option>
                            <option value="banque">🏦 Compte Bancaire</option>
                        </select>
                        @error('type_compte') 
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Propriétaire (caché pour simplifier) -->
                    <input wire:model="nom_proprietaire" type="hidden">

                    <!-- Numéro de compte - CONDITIONNEL -->
                    @if($type_compte !== 'principal')
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Numéro de compte
                            </label>
                            <input wire:model="numero_compte" 
                                type="text" 
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-gray-300" 
                                placeholder="Ex: 123456789 ou 034 12 345 67">
                            @error('numero_compte') 
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    @endif

                    <!-- Solde actuel -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Solde actuel (MGA)
                        </label>
                        <input wire:model="solde_actuel_mga" 
                               type="number" 
                               step="0.01" 
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-gray-300" 
                               placeholder="0">
                        @if($solde_actuel_mga)
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                ≈ {{ number_format($solde_actuel_mga, 0, ',', ' ') }} Ariary
                            </p>
                        @endif
                        @error('solde_actuel_mga') 
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Compte actif -->
                    <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-md">
                        <div>
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Compte actif</label>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Le compte peut être utilisé pour les transactions</p>
                        </div>
                        <input wire:model="compte_actif" 
                               type="checkbox" 
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 dark:border-gray-600 rounded">
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex justify-end space-x-3 mt-6 pt-4 border-t border-gray-100 dark:border-gray-700">
                    <button type="button" 
                            wire:click="closeCompteModal"
                            class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-600 hover:bg-gray-200 dark:hover:bg-gray-500 rounded-md transition-colors focus:outline-none focus:ring-2 focus:ring-gray-300">
                        Annuler
                    </button>
                    
                    <button type="submit" 
                            class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-md transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 disabled:opacity-50"
                            wire:loading.attr="disabled"
                            wire:target="saveCompte">
                        
                        <span wire:loading.remove wire:target="saveCompte">
                            @if($editingCompte)
                                Modifier le compte
                            @else
                                Créer le compte
                            @endif
                        </span>
                        
                        <span wire:loading wire:target="saveCompte">
                            Sauvegarde...
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
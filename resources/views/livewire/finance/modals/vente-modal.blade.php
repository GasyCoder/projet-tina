{{-- resources/views/livewire/finance/modals/vente-modal.blade.php --}}
{{-- DESIGN TAILWIND SIMPLE + CONDITIONS --}}

@if($showModal)
<div class="fixed inset-0 z-50 overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        
        <!-- Overlay -->
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 dark:bg-gray-900 dark:bg-opacity-75 transition-opacity" 
             wire:click="closeModal"></div>
        
        <!-- Modal Container -->
        <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
            
            <!-- Header -->
            <div class="bg-green-600 px-6 py-4">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-medium text-white">
                        {{ $editingVente ? 'Modifier la vente' : 'Nouvelle vente' }}
                    </h3>
                    <button wire:click="closeModal" 
                            class="text-green-200 hover:text-white">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Message de solde -->
            @if($soldeMessage)
            <div class="mx-6 mt-4 p-3 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-md">
                <p class="text-sm text-yellow-700 dark:text-yellow-400">{{ $soldeMessage }}</p>
            </div>
            @endif

            <!-- Formulaire - DESIGN SIMPLE -->
            <form wire:submit.prevent="saveVente">
                <div class="px-6 py-6 space-y-6">
                    
                    <!-- Référence (caché) -->
                    <input wire:model="form.reference" type="hidden">

                    <!-- Première ligne : Objet + Date -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Objet -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Objet de la vente
                            </label>
                            <input wire:model="form.objet" 
                                   type="text" 
                                   placeholder="Description de la vente..."
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-green-500 focus:border-green-500 dark:bg-gray-700 dark:text-gray-300">
                            @error('form.objet') 
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p> 
                            @enderror
                        </div>

                        <!-- Date -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Date de vente *
                            </label>
                            <input wire:model="form.date" 
                                   type="date" 
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-green-500 focus:border-green-500 dark:bg-gray-700 dark:text-gray-300">
                            @error('form.date') 
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p> 
                            @enderror
                        </div>
                    </div>

                    <!-- Deuxième ligne : Vendeur + Dépôt -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Vendeur -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Nom du vendeur
                            </label>
                            <input wire:model="form.vendeur_nom" 
                                   type="text" 
                                   placeholder="Nom du vendeur..."
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-green-500 focus:border-green-500 dark:bg-gray-700 dark:text-gray-300">
                            @error('form.vendeur_nom') 
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p> 
                            @enderror
                        </div>

                        <!-- Dépôt -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Lieu de vente
                            </label>
                            <select wire:model="form.depot_id" 
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-green-500 focus:border-green-500 dark:bg-gray-700 dark:text-gray-300">
                                <option value="">Sélectionner un lieu...</option>
                                @foreach($depots as $depot)
                                    <option value="{{ $depot->id }}">{{ $depot->nom }} ({{ ucfirst($depot->type) }})</option>
                                @endforeach
                            </select>
                            @error('form.depot_id') 
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p> 
                            @enderror
                        </div>
                    </div>

                    <!-- Troisième ligne : Montant payé + Mode de paiement -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Montant payé -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Montant payé (MGA) *
                            </label>
                            <input wire:model.live="form.montant_paye" 
                                   type="number" 
                                   step="0.01" 
                                   min="0" 
                                   placeholder="0"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-green-500 focus:border-green-500 dark:bg-gray-700 dark:text-gray-300">
                            @error('form.montant_paye') 
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p> 
                            @enderror
                        </div>

                        <!-- Mode de paiement -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Mode de paiement *
                            </label>
                            <select wire:model.live="form.mode_paiement" 
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-green-500 focus:border-green-500 dark:bg-gray-700 dark:text-gray-300">
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
                    </div>

                    <!-- Statut de paiement -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Statut de paiement *
                        </label>
                        <div class="space-y-2">
                            <label class="flex items-center">
                                <input wire:model.live="form.statut_paiement" 
                                       type="radio" 
                                       name="statut_paiement"
                                       value="paye" 
                                       class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 dark:border-gray-600">
                                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">✅ Payé complètement</span>
                            </label>
                            <label class="flex items-center">
                                <input wire:model.live="form.statut_paiement" 
                                       type="radio" 
                                       name="statut_paiement"
                                       value="partiel" 
                                       class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 dark:border-gray-600">
                                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">⏳ Paiement partiel</span>
                            </label>
                        </div>
                        @error('form.statut_paiement') 
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p> 
                        @enderror
                    </div>

                    <!-- Montant restant - CONDITIONNEL -->
                    @if($this->form['statut_paiement'] === 'partiel')
                    <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-700 rounded-md p-4">
                        <label class="block text-sm font-medium text-yellow-800 dark:text-yellow-300 mb-2">
                            Montant restant à payer (MGA)
                        </label>
                        <input wire:model="form.montant_restant" 
                               type="number" 
                               step="0.01" 
                               min="0" 
                               placeholder="0"
                               class="w-full px-3 py-2 border border-yellow-300 dark:border-yellow-600 rounded-md focus:ring-yellow-500 focus:border-yellow-500 bg-yellow-50 dark:bg-yellow-900/20 dark:text-yellow-300">
                        <p class="mt-1 text-xs text-yellow-700 dark:text-yellow-400">
                            Ce montant reste à encaisser ultérieurement
                        </p>
                        @error('form.montant_restant') 
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p> 
                        @enderror
                    </div>
                    @endif

                    <!-- Observations -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Notes et observations
                        </label>
                        <textarea wire:model="form.observation" 
                                  rows="3" 
                                  placeholder="Notes, commentaires, détails additionnels..."
                                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-green-500 focus:border-green-500 resize-none dark:bg-gray-700 dark:text-gray-300"></textarea>
                        @error('form.observation') 
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p> 
                        @enderror
                    </div>
                </div>

                <!-- Footer -->
                <div class="bg-gray-50 dark:bg-gray-700 px-6 py-3 flex justify-end space-x-3">
                    <button type="button" 
                            wire:click="closeModal" 
                            class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-600 border border-gray-300 dark:border-gray-500 rounded-md hover:bg-gray-50 dark:hover:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        Annuler
                    </button>
                    
                    <button type="submit" 
                            class="px-4 py-2 text-sm font-medium text-white bg-green-600 border border-transparent rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 disabled:opacity-50"
                            wire:loading.attr="disabled"
                            wire:target="saveVente">
                        
                        <span wire:loading.remove wire:target="saveVente">
                            {{ $editingVente ? 'Modifier' : 'Créer' }}
                        </span>
                        
                        <span wire:loading wire:target="saveVente">
                            Sauvegarde...
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
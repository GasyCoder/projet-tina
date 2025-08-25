{{-- resources/views/livewire/categorie/modales/budget-modal.blade.php --}}
@if($showBudgetModal)
    <div class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center p-4 z-50">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl w-full max-w-4xl max-h-[90vh] overflow-hidden">
            
            <!-- En-tête -->
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-gray-900 dark:to-gray-800">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="p-2 bg-blue-100 dark:bg-blue-900 rounded-lg">
                            <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white">Gestion Budget & Objectifs</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ $categorie->nom }} ({{ $categorie->code_comptable }})</p>
                        </div>
                    </div>
                    <button wire:click="closeBudgetModal" 
                            class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Contenu -->
            <div class="p-6 space-y-8">
                
                <!-- État actuel du budget -->
                <div class="bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-800 dark:to-gray-700 p-6 rounded-xl">
                    <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">État actuel</h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Budget alloué -->
                        <div class="text-center">
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Budget alloué</p>
                            <p class="text-2xl font-bold text-blue-600 dark:text-blue-400">
                                {{ number_format($categorie->budget, 0, ',', ' ') }} Ar
                            </p>
                        </div>
                        
                        <!-- Montant consommé -->
                        <div class="text-center">
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Montant consommé</p>
                            <p class="text-2xl font-bold {{ ($dashboardData['budget']['budget_depasse'] ?? false) ? 'text-red-600 dark:text-red-400' : 'text-green-600 dark:text-green-400' }}">
                                {{ number_format($dashboardData['budget']['montant_consomme'] ?? 0, 0, ',', ' ') }} Ar
                            </p>
                        </div>
                        
                        <!-- Pourcentage d'utilisation -->
                        <div class="text-center">
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Utilisation</p>
                            <p class="text-2xl font-bold {{ ($dashboardData['budget']['budget_depasse'] ?? false) ? 'text-red-600 dark:text-red-400' : (($dashboardData['budget']['seuil_alerte_atteint'] ?? false) ? 'text-yellow-600 dark:text-yellow-400' : 'text-green-600 dark:text-green-400') }}">
                                {{ number_format($dashboardData['budget']['pourcentage_utilise'] ?? 0, 1) }}%
                            </p>
                        </div>
                    </div>

                    <!-- Barre de progression -->
                    <div class="mt-6">
                        <div class="flex justify-between text-sm text-gray-600 dark:text-gray-400 mb-2">
                            <span>Progression du budget</span>
                            <span>{{ number_format($dashboardData['budget']['montant_restant'] ?? 0, 0, ',', ' ') }} Ar restants</span>
                        </div>
                        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-3">
                            <div class="h-3 rounded-full transition-all duration-500 {{ ($dashboardData['budget']['budget_depasse'] ?? false) ? 'bg-red-500' : (($dashboardData['budget']['seuil_alerte_atteint'] ?? false) ? 'bg-yellow-500' : 'bg-green-500') }}" 
                                 style="width: {{ min(100, $dashboardData['budget']['pourcentage_utilise'] ?? 0) }}%"></div>
                        </div>
                        
                        @if($dashboardData['budget']['budget_depasse'] ?? false)
                            <p class="text-red-600 dark:text-red-400 text-sm mt-2 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.5 0L4.346 15.5c-.77.833.192 2.5 1.732 2.5z" />
                                </svg>
                                Budget dépassé de {{ number_format(abs($dashboardData['budget']['montant_restant'] ?? 0), 0, ',', ' ') }} Ar
                            </p>
                        @elseif($dashboardData['budget']['seuil_alerte_atteint'] ?? false)
                            <p class="text-yellow-600 dark:text-yellow-400 text-sm mt-2 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01" />
                                </svg>
                                Seuil d'alerte atteint ({{ $categorie->seuil_alerte }}%)
                            </p>
                        @endif
                    </div>
                </div>

                <!-- Projection fin de mois -->
                @if(!empty($dashboardData['budget']['projection_fin_mois'] ?? []))
                    <div class="bg-blue-50 dark:bg-blue-900/20 p-6 rounded-xl border border-blue-200 dark:border-blue-700">
                        <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                            Projection fin de mois
                        </h4>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-blue-600 dark:text-blue-400 mb-1">Montant projeté</p>
                                <p class="text-xl font-bold {{ ($dashboardData['budget']['projection_fin_mois']['depassement'] ?? false) ? 'text-red-600 dark:text-red-400' : 'text-blue-600 dark:text-blue-400' }}">
                                    {{ ($dashboardData['budget']['projection_fin_mois']['projection'] ?? 0) >= 0 ? '+' : '' }}{{ number_format($dashboardData['budget']['projection_fin_mois']['projection'] ?? 0, 0, ',', ' ') }} Ar
                                </p>
                            </div>
                            <div>
                                <p class="text-sm text-blue-600 dark:text-blue-400 mb-1">Pourcentage projection</p>
                                <p class="text-xl font-bold {{ ($dashboardData['budget']['projection_fin_mois']['depassement'] ?? false) ? 'text-red-600 dark:text-red-400' : 'text-blue-600 dark:text-blue-400' }}">
                                    {{ number_format($dashboardData['budget']['projection_fin_mois']['pourcentage_projection'] ?? 0, 1) }}%
                                </p>
                            </div>
                        </div>
                        
                        @if($dashboardData['budget']['projection_fin_mois']['depassement'] ?? false)
                            <div class="mt-4 p-3 bg-red-100 dark:bg-red-900/30 rounded-lg border border-red-200 dark:border-red-700">
                                <p class="text-red-800 dark:text-red-200 text-sm flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.5 0L4.346 15.5c-.77.833.192 2.5 1.732 2.5z" />
                                    </svg>
                                    <strong>Attention:</strong> La projection indique un dépassement de budget en fin de mois
                                </p>
                            </div>
                        @endif
                    </div>
                @endif

                <!-- Formulaire de mise à jour -->
                <form wire:submit.prevent="updateBudget" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        
                        <!-- Budget principal -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Budget total (Ar) *
                                </span>
                            </label>
                            <input type="number" wire:model.defer="nouveauBudget" 
                                   step="0.01" min="0" placeholder="Ex: 500000"
                                   class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                            @error('nouveauBudget')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Objectif mensuel -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                                    </svg>
                                    Objectif mensuel (Ar) *
                                </span>
                            </label>
                            <input type="number" wire:model.defer="objectifMensuel" 
                                   step="0.01" min="0" placeholder="Ex: 100000"
                                   class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                            @error('objectifMensuel')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Seuil d'alerte -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.5 0L4.346 15.5c-.77.833.192 2.5 1.732 2.5z" />
                                    </svg>
                                    Seuil d'alerte (%) *
                                </span>
                            </label>
                            <select wire:model.defer="seuilAlerte" 
                                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                                <option value="50">50% - Très tôt</option>
                                <option value="70">70% - Tôt</option>
                                <option value="80">80% - Normal</option>
                                <option value="90">90% - Tard</option>
                                <option value="95">95% - Très tard</option>
                            </select>
                            @error('seuilAlerte')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Informations complémentaires -->
                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                        <h5 class="font-medium text-gray-900 dark:text-white mb-2">💡 Conseils pour bien gérer votre budget</h5>
                        <ul class="text-sm text-gray-600 dark:text-gray-400 space-y-1">
                            <li>• Définissez un budget réaliste basé sur vos données historiques</li>
                            <li>• L'objectif mensuel devrait être environ 1/12 du budget annuel</li>
                            <li>• Un seuil d'alerte à 80% vous laisse une marge de manœuvre</li>
                            <li>• Surveillez régulièrement la projection de fin de mois</li>
                        </ul>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center justify-end space-x-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <button type="button" wire:click="closeBudgetModal" 
                                class="px-6 py-3 bg-gray-500 hover:bg-gray-600 text-white font-medium rounded-lg transition-colors">
                            Annuler
                        </button>
                        <button type="submit" 
                                class="px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-colors flex items-center"
                                wire:loading.attr="disabled" wire:target="updateBudget">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span wire:loading.remove wire:target="updateBudget">Mettre à jour</span>
                            <span wire:loading wire:target="updateBudget">Mise à jour...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endif
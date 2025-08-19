{{-- resources/views/livewire/categorie/modales/categories-transaction-modales.blade.php --}}
@if ($showNewTransactionModal)
    @php
        $isRecette = $newTransaction['type'] === 'recette';
        $modalTitle = $isRecette ? 'Nouvelle Recette' : 'Nouvelle Dépense';
        $modalIcon = $isRecette ? 'M12 4v16m8-8H4' : 'M20 12H4';
        $modalColor = $isRecette ? 'green' : 'red';
    @endphp

    <div class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center p-4 z-50">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl w-full max-w-4xl max-h-[90vh] overflow-hidden">
            
            <!-- En-tête -->
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <div class="flex justify-between items-center">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-lg {{ $isRecette ? 'bg-green-100 dark:bg-green-900/50' : 'bg-red-100 dark:red-900/50' }} flex items-center justify-center">
                            <svg class="w-5 h-5 {{ $isRecette ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $modalIcon }}" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-white">{{ $modalTitle }}</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                {{ $categorie->nom }} ({{ $categorie->code_comptable }})
                            </p>
                        </div>
                    </div>
                    <button wire:click="closeNewTransactionModal"
                        class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300 transition-colors p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Contenu scrollable -->
            <div class="overflow-y-auto max-h-[calc(90vh-180px)]">
                <form wire:submit.prevent="addToTransactionList" class="p-6">
                    
                    <div class="space-y-6">
                        
                        <!-- Suggestions de descriptions -->
                        @if(!empty($suggestions['descriptions']))
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Descriptions récentes
                                </label>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($suggestions['descriptions'] as $desc)
                                        <button type="button" 
                                                wire:click="selectDescriptionTemplate('{{ $desc }}')"
                                                class="px-3 py-1 text-xs rounded-lg border border-gray-200 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                            {{ $desc }}
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Description -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Description *
                            </label>
                            <input type="text" 
                                   wire:model.live.debounce.300ms="newTransaction.description" 
                                   placeholder="{{ $isRecette ? 'Ex: Vente produit XYZ' : 'Ex: Achat fournitures bureau' }}"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-{{ $modalColor }}-500 focus:border-{{ $modalColor }}-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                            @error('newTransaction.description')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Montant -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Montant (Ar) *</label>
                            <input type="number" 
                                   wire:model.live.debounce.400ms="newTransaction.montant" 
                                   step="0.01" min="0" 
                                   placeholder="Ex: 120 000"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-{{ $modalColor }}-500 focus:border-{{ $modalColor }}-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                            @error('newTransaction.montant')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Mode de paiement -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Mode de paiement *</label>
                            <div class="grid grid-cols-3 gap-3">
                                @foreach(['especes' => '💵 Espèces', 'MobileMoney' => '📱 Mobile Money', 'Banque' => '🏦 Banque'] as $mode => $label)
                                    <button type="button" 
                                            wire:click="$set('newTransaction.mode_paiement', '{{ $mode }}')"
                                            class="p-3 text-sm rounded-lg border transition-all {{ ($newTransaction['mode_paiement'] ?? 'especes') === $mode ? 'border-blue-500 bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-300' : 'border-gray-300 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                                        {{ $label }}
                                    </button>
                                @endforeach
                            </div>
                        </div>

                        <!-- Sous-type selon mode de paiement -->
                        @if(($newTransaction['mode_paiement'] ?? 'especes') === 'MobileMoney')
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Opérateur Mobile Money *</label>
                                <select wire:model.live="newTransaction.type_compte_mobilemoney_or_banque"
                                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-{{ $modalColor }}-500 focus:border-{{ $modalColor }}-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                                    <option value="">— Sélectionner —</option>
                                    <option value="Mvola">📱 Mvola</option>
                                    <option value="OrangeMoney">🟠 Orange Money</option>
                                    <option value="AirtelMoney">🔴 Airtel Money</option>
                                </select>
                                @error('newTransaction.type_compte_mobilemoney_or_banque') 
                                    <span class="text-red-500 text-xs">{{ $message }}</span> 
                                @enderror
                            </div>
                        @elseif(($newTransaction['mode_paiement'] ?? 'especes') === 'Banque')
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Banque *</label>
                                <select wire:model.live="newTransaction.type_compte_mobilemoney_or_banque"
                                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-{{ $modalColor }}-500 focus:border-{{ $modalColor }}-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                                    <option value="">— Sélectionner —</option>
                                    <option value="BNI">🏦 BNI</option>
                                    <option value="BFV">🏦 BFV-SG</option>
                                    <option value="BOA">🏦 BOA</option>
                                    <option value="BMOI">🏦 BMOI</option>
                                    <option value="SBM">🏦 SBM</option>
                                </select>
                                @error('newTransaction.type_compte_mobilemoney_or_banque') 
                                    <span class="text-red-500 text-xs">{{ $message }}</span> 
                                @enderror
                            </div>
                        @endif

                        <!-- Partenaires fréquents -->
                        @if(!empty($suggestions['partenaires_frequents']))
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Partenaires fréquents</label>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($suggestions['partenaires_frequents'] as $id => $nom)
                                        <button type="button" 
                                                wire:click="selectPartenaire({{ $id }})"
                                                class="px-3 py-1 text-xs rounded-lg border border-gray-200 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                            {{ $nom }}
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Partenaire -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nom du partenaire</label>
                            <input type="text" 
                                   wire:model.live.debounce.300ms="newTransaction.partenaire_nom"
                                   placeholder="{{ $isRecette ? 'Client, donateur...' : 'Fournisseur, prestataire...' }}"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-{{ $modalColor }}-500 focus:border-{{ $modalColor }}-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                        </div>

                    
                        <!-- Notes -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Notes complémentaires</label>
                            <textarea wire:model.live.debounce.300ms="newTransaction.notes" 
                                      rows="3" 
                                      placeholder="Informations supplémentaires, contexte..."
                                      class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-{{ $modalColor }}-500 focus:border-{{ $modalColor }}-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white"></textarea>
                        </div>

                        <!-- Solde disponible -->
                        @if(!$isRecette && !empty($newTransaction['montant']))
                            <div class="p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                                <div class="text-sm font-medium text-blue-800 dark:text-blue-200">
                                    Solde disponible
                                </div>
                                <div class="text-xs text-blue-600 dark:text-blue-400 mt-1">
                                  @php
    $soldeCompte = 0;
    $modePaiement = $newTransaction['mode_paiement'] ?? 'especes';
    $sousType = $newTransaction['type_compte_mobilemoney_or_banque'] ?? '';
    
    if ($modePaiement === 'especes' && isset($comptes['principal'])) {
        $soldeCompte = $comptes['principal']->solde_actuel_mga ?? 0; // ✅ Bon nom
    } elseif ($modePaiement === 'MobileMoney' && $sousType) {
        $compte = $comptes['mobile_money']->where('type_compte_mobilemoney_or_banque', $sousType)->first();
        $soldeCompte = $compte->solde_actuel_mga ?? 0; // ✅ Bon nom
    } elseif ($modePaiement === 'Banque' && $sousType) {
        $compte = $comptes['banque']->where('type_compte_mobilemoney_or_banque', $sousType)->first();
        $soldeCompte = $compte->solde_actuel_mga ?? 0; // ✅ Bon nom
    }
@endphp
                                    {{ number_format($soldeCompte, 0, ',', ' ') }} Ar
                                    @if($this->insuffisantTransaction)
                                        <span class="text-red-500 font-medium"> - Insuffisant</span>
                                    @endif
                                </div>
                            </div>
                        @endif

                        <!-- Alerte solde insuffisant -->
                        @if($this->insuffisantTransaction)
                            <div class="p-4 rounded-lg border border-red-200 bg-red-50 dark:bg-red-900/20">
                                <div class="flex items-center space-x-3">
                                    <svg class="w-5 h-5 text-red-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.5 0L4.346 15.5c-.77.833.192 2.5 1.732 2.5z" />
                                    </svg>
                                    <div class="text-red-800 dark:text-red-200">
                                        <div class="font-medium">Solde insuffisant</div>
                                        <div class="text-sm">Le montant dépasse le solde disponible.</div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Bouton Ajouter à la liste -->
                    <div class="mt-6 flex justify-end">
                        <button type="submit"
                                class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors inline-flex items-center {{ $this->insuffisantTransaction ? 'opacity-50 cursor-not-allowed' : '' }}"
                                wire:loading.attr="disabled" 
                                wire:target="addToTransactionList"
                                {{ $this->insuffisantTransaction ? 'disabled' : '' }}>
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            <span wire:loading.remove wire:target="addToTransactionList">Ajouter à la liste</span>
                            <span wire:loading wire:target="addToTransactionList">Ajout...</span>
                        </button>
                    </div>
                </form>

                <!-- Liste des transactions en cours -->
                @if(count($transactionsEnCours) > 0)
                    <div class="border-t border-gray-200 dark:border-gray-700 mt-6 pt-6 px-6">
                        <h4 class="font-medium text-gray-900 dark:text-white mb-4">Transactions à enregistrer</h4>
                        
                        <div class="space-y-3 mb-4">
                            @foreach($transactionsEnCours as $index => $transaction)
                                <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                    <div class="flex-1">
                                        <div class="font-medium text-gray-900 dark:text-white">{{ $transaction['description'] }}</div>
                                        <div class="text-sm text-gray-600 dark:text-gray-400">
                                            {{ number_format($transaction['montant'], 0, ',', ' ') }} Ar • 
                                            {{ $transaction['mode_paiement'] }}
                                            @if($transaction['type_compte_mobilemoney_or_banque'] ?? false)
                                                ({{ $transaction['type_compte_mobilemoney_or_banque'] }})
                                            @endif
                                        </div>
                                    </div>
                                    <button wire:click="removeFromTransactionList({{ $index }})" 
                                            class="ml-2 p-1 text-red-600 hover:text-red-800 dark:hover:text-red-400">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                            @endforeach
                        </div>

                        <!-- Total -->
                        <div class="flex justify-between items-center p-3 bg-{{ $modalColor }}-50 dark:bg-{{ $modalColor }}-900/20 rounded-lg">
                            <span class="font-medium text-gray-900 dark:text-white">Total:</span>
                            <span class="font-bold text-{{ $modalColor }}-600 dark:text-{{ $modalColor }}-400">
                                {{ number_format($totalTransactions, 0, ',', ' ') }} Ar
                            </span>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-between px-6 py-4 bg-gray-50 dark:bg-gray-900 border-t border-gray-200 dark:border-gray-700">
                <div class="text-sm text-gray-500 dark:text-gray-400">
                    {{ $isRecette ? 'Ces recettes seront ajoutées au solde' : 'Ces dépenses seront déduites du solde' }}
                </div>
                
                <div class="flex space-x-3">
                    <button type="button" 
                            wire:click="closeNewTransactionModal"
                            class="px-6 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                        Annuler
                    </button>
                    
                    @if(count($transactionsEnCours) > 0)
                        <button type="button"
                                wire:click="saveAllTransactions"
                                class="px-6 py-2 bg-{{ $modalColor }}-600 text-white rounded-lg hover:bg-{{ $modalColor }}-700 transition-colors inline-flex items-center"
                                wire:loading.attr="disabled" 
                                wire:target="saveAllTransactions">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span wire:loading.remove wire:target="saveAllTransactions">Enregistrer tout ({{ count($transactionsEnCours) }})</span>
                            <span wire:loading wire:target="saveAllTransactions">Enregistrement...</span>
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endif

{{-- Modal détail transaction --}}
@if ($showTransactionDetailModal && !empty($transactionDetails))
    <div class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <!-- Overlay -->
            <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75 dark:bg-gray-900 dark:bg-opacity-75 backdrop-blur-sm"
                wire:click="closeTransactionDetailModal"></div>

            <!-- Modal container -->
            <div class="inline-block w-full max-w-2xl my-8 overflow-hidden text-left align-middle transition-all transform bg-white dark:bg-gray-800 shadow-2xl rounded-xl">
                
                <!-- En-tête -->
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                            {{ $transactionDetails['reference'] ?? 'Détail Transaction' }}
                        </h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            {{ $transactionDetails['date'] ?? '' }}
                        </p>
                    </div>
                    <button wire:click="closeTransactionDetailModal"
                        class="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Contenu -->
                <div class="px-6 py-6 space-y-4">
                    <!-- Informations principales -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Description</label>
                            <p class="mt-1 text-sm text-gray-900 dark:text-white">
                                {{ $transactionDetails['description'] ?? '' }}
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Montant</label>
                            <p class="mt-1 text-lg font-bold {{ ($transactionDetails['type'] ?? '') === 'recette' ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                {{ $transactionDetails['montant_formate'] ?? '' }}
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Type</label>
                            <span class="mt-1 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ ($transactionDetails['type'] ?? '') === 'recette' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' }}">
                                {{ ucfirst($transactionDetails['type'] ?? '') }}
                            </span>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Mode de paiement</label>
                            <p class="mt-1 text-sm text-gray-900 dark:text-white">
                                {{ $transactionDetails['mode_paiement'] ?? '' }}
                                @if($transactionDetails['type_compte_mobilemoney_or_banque'] ?? false)
                                    - {{ $transactionDetails['type_compte_mobilemoney_or_banque'] }}
                                @endif
                            </p>
                        </div>
                        @if ($transactionDetails['partenaire'] ?? false)
                            <div>
                                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Partenaire</label>
                                <p class="mt-1 text-sm text-gray-900 dark:text-white">
                                    {{ $transactionDetails['partenaire'] }}
                                </p>
                            </div>
                        @endif
                    </div>

            
                    @if ($transactionDetails['notes'] ?? false)
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Notes</label>
                            <p class="mt-1 text-sm text-gray-900 dark:text-white whitespace-pre-line">
                                {{ $transactionDetails['notes'] }}
                            </p>
                        </div>
                    @endif

                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Créé le</label>
                        <p class="mt-1 text-sm text-gray-900 dark:text-white">
                            {{ $transactionDetails['created_at'] ?? '' }}
                        </p>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex items-center justify-end space-x-3 px-6 py-4 bg-gray-50 dark:bg-gray-900 border-t border-gray-200 dark:border-gray-700">
                    <button wire:click="deleteTransaction({{ $transactionDetails['id'] ?? 0 }})"
                        onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette transaction ?')"
                        class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition-colors">
                        Supprimer
                    </button>
                </div>
            </div>
        </div>
    </div>
@endif
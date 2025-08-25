{{-- resources/views/livewire/categorie/modales/categories-transaction-modales.blade.php --}}
@if ($showNewTransactionModal)
    @php
        $isRecette = $newTransaction['type'] === 'recette';
        $modalTitle = $isRecette ? 'Nouvelle Recette' : 'Nouvelle Dépense';
        $modalIcon = $isRecette ? 'M12 4v16m8-8H4' : 'M20 12H4';
        $modalColor = $isRecette ? 'green' : 'red';
        $transactionCount = count($transactionsEnCours);
    @endphp

    <div class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center p-4 z-50">
        <div
            class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl w-full max-w-4xl max-h-[90vh] overflow-hidden flex flex-col">

            <!-- En-tête -->
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <div class="flex justify-between items-center">
                    <div class="flex items-center space-x-3">
                        <div
                            class="w-10 h-10 rounded-lg {{ $isRecette ? 'bg-green-100 dark:bg-green-900/50' : 'bg-red-100 dark:red-900/50' }} flex items-center justify-center">
                            <svg class="w-5 h-5 {{ $isRecette ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="{{ $modalIcon }}" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-white">{{ $modalTitle }}</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                {{ $categorie->nom }} ({{ $categorie->code_comptable }})
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2">
                        @if ($transactionCount > 0)
                            <span
                                class="px-2 py-1 bg-{{ $modalColor }}-100 dark:bg-{{ $modalColor }}-900 text-{{ $modalColor }}-800 dark:text-{{ $modalColor }}-200 text-xs font-medium rounded-full">
                                {{ $transactionCount }} en attente
                            </span>
                        @endif
                        <button wire:click="closeNewTransactionModal"
                            class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300 transition-colors p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Contenu principal avec onglets -->
            <div class="flex-1 overflow-hidden flex flex-col">
                <!-- Onglets -->
                <div class="border-b border-gray-200 dark:border-gray-700">
                    <nav class="flex -mb-px">
                        <button
                            :class="{ 'border-{{ $modalColor }}-500 text-{{ $modalColor }}-600 dark:text-{{ $modalColor }}-400 border-b-2 font-medium': true }"
                            class="py-4 px-6 text-sm font-medium text-center whitespace-nowrap border-transparent">
                            Nouvelle transaction
                        </button>
                        @if ($transactionCount > 0)
                            <button
                                :class="{ 'border-{{ $modalColor }}-500 text-{{ $modalColor }}-600 dark:text-{{ $modalColor }}-400 border-b-2 font-medium': false }"
                                class="py-4 px-6 text-sm font-medium text-center whitespace-nowrap border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300">
                                Transactions en attente ({{ $transactionCount }})
                            </button>
                        @endif
                    </nav>
                </div>

                <!-- Contenu scrollable -->
                <div class="flex-1 overflow-y-auto">
                    <!-- Formulaire de nouvelle transaction -->
                    <form wire:submit.prevent="addToTransactionList" class="p-6">
                        <div class="space-y-6">

                            <!-- Suggestions de descriptions -->
                            @if (!empty($suggestions['descriptions']))
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        <span class="flex items-center">
                                            <svg class="w-4 h-4 mr-1 text-blue-500" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M13 10V3L4 14h7v7l9-11h-7z" />
                                            </svg>
                                            Suggestions de descriptions
                                        </span>
                                    </label>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach ($suggestions['descriptions'] as $desc)
                                            <button type="button"
                                                wire:click="selectDescriptionTemplate('{{ $desc }}')"
                                                class="px-3 py-1.5 text-sm rounded-lg border border-gray-200 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors flex items-center">
                                                <svg class="w-4 h-4 mr-1 text-gray-400" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                </svg>
                                                {{ $desc }}
                                            </button>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <!-- Description -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    <span class="flex items-center">
                                        <svg class="w-4 h-4 mr-1 text-blue-500" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        Description *
                                    </span>
                                </label>
                                <input type="text" wire:model.live.debounce.300ms="newTransaction.description"
                                    placeholder="{{ $isRecette ? 'Ex: Vente produit XYZ' : 'Ex: Achat fournitures bureau' }}"
                                    class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-{{ $modalColor }}-500 focus:border-{{ $modalColor }}-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400">
                                @error('newTransaction.description')
                                    <span class="text-red-500 text-xs mt-1 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.5 0L4.346 15.5c-.77.833.192 2.5 1.732 2.5z" />
                                        </svg>
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>

                            <!-- Montant et Date -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Montant -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        <span class="flex items-center">
                                            <svg class="w-4 h-4 mr-1 text-blue-500" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            Montant (Ar) *
                                        </span>
                                    </label>
                                    <div class="relative">
                                        <input type="number" wire:model.live.debounce.400ms="newTransaction.montant"
                                            step="0.01" min="0" placeholder="Ex: 120 000"
                                            class="w-full pl-8 pr-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-{{ $modalColor }}-500 focus:border-{{ $modalColor }}-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400">
                                        <div
                                            class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <span class="text-gray-500 dark:text-gray-400">Ar</span>
                                        </div>
                                    </div>
                                    @error('newTransaction.montant')
                                        <span class="text-red-500 text-xs mt-1 flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.5 0L4.346 15.5c-.77.833.192 2.5 1.732 2.5z" />
                                            </svg>
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>

                                <!-- Date -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        <span class="flex items-center">
                                            <svg class="w-4 h-4 mr-1 text-blue-500" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            Date *
                                        </span>
                                    </label>
                                    <input type="date" wire:model="newTransaction.date_transaction"
                                        class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-{{ $modalColor }}-500 focus:border-{{ $modalColor }}-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                                </div>
                            </div>

                            <!-- Mode de paiement -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    <span class="flex items-center">
                                        <svg class="w-4 h-4 mr-1 text-blue-500" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                        </svg>
                                        Mode de paiement *
                                    </span>
                                </label>
                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                    @foreach (['especes' => ['💵 Espèces', 'bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200'], 'MobileMoney' => ['📱 Mobile Money', 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200'], 'Banque' => ['🏦 Banque', 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200']] as $mode => [$label, $colors])
                                        <button type="button"
                                            wire:click="$set('newTransaction.mode_paiement', '{{ $mode }}')"
                                            class="p-3 text-sm rounded-lg border transition-all flex items-center justify-center 
        {{ ($newTransaction['mode_paiement'] ?? 'especes') === $mode
            ? 'border-' . $modalColor . '-500 ring-2 ring-' . $modalColor . '-500 ring-opacity-50 ' . $colors
            : 'border-gray-300 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                                            {{ $label }}
                                        </button>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Sous-type selon mode de paiement -->
                            @if (($newTransaction['mode_paiement'] ?? 'especes') === 'MobileMoney')
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Opérateur Mobile Money *
                                    </label>
                                    <select wire:model.live="newTransaction.type_compte_mobilemoney_or_banque"
                                        class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-{{ $modalColor }}-500 focus:border-{{ $modalColor }}-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                                        <option value="">— Sélectionner —</option>
                                        <option value="Mvola">📱 Mvola</option>
                                        <option value="OrangeMoney">🟠 Orange Money</option>
                                        <option value="AirtelMoney">🔴 Airtel Money</option>
                                    </select>
                                    @error('newTransaction.type_compte_mobilemoney_or_banque')
                                        <span class="text-red-500 text-xs mt-1 flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.5 0L4.346 15.5c-.77.833.192 2.5 1.732 2.5z" />
                                            </svg>
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                            @elseif(($newTransaction['mode_paiement'] ?? 'especes') === 'Banque')
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Banque *
                                    </label>
                                    <select wire:model.live="newTransaction.type_compte_mobilemoney_or_banque"
                                        class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-{{ $modalColor }}-500 focus:border-{{ $modalColor }}-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                                        <option value="">— Sélectionner —</option>
                                        <option value="BNI">🏦 BNI</option>
                                        <option value="BFV">🏦 BFV-SG</option>
                                        <option value="BOA">🏦 BOA</option>
                                        <option value="BMOI">🏦 BMOI</option>
                                        <option value="SBM">🏦 SBM</option>
                                    </select>
                                    @error('newTransaction.type_compte_mobilemoney_or_banque')
                                        <span class="text-red-500 text-xs mt-1 flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.5 0L4.346 15.5c-.77.833.192 2.5 1.732 2.5z" />
                                            </svg>
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                            @endif

                            <!-- Partenaires fréquents -->
                            @if (!empty($suggestions['partenaires_frequents']))
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        <span class="flex items-center">
                                            <svg class="w-4 h-4 mr-1 text-blue-500" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                            </svg>
                                            Partenaires fréquents
                                        </span>
                                    </label>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach ($suggestions['partenaires_frequents'] as $id => $nom)
                                            <button type="button" wire:click="selectPartenaire({{ $id }})"
                                                class="px-3 py-1.5 text-sm rounded-lg border border-gray-200 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors flex items-center">
                                                <svg class="w-4 h-4 mr-1 text-gray-400" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                </svg>
                                                {{ $nom }}
                                            </button>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <!-- Partenaire -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Nom du partenaire
                                </label>
                                <input type="text" wire:model.live.debounce.300ms="newTransaction.partenaire_nom"
                                    placeholder="{{ $isRecette ? 'Client, donateur...' : 'Fournisseur, prestataire...' }}"
                                    class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-{{ $modalColor }}-500 focus:border-{{ $modalColor }}-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400">
                            </div>

                            <!-- Notes -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    <span class="flex items-center">
                                        <svg class="w-4 h-4 mr-1 text-blue-500" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                                        </svg>
                                        Notes complémentaires
                                    </span>
                                </label>
                                <textarea wire:model.live.debounce.300ms="newTransaction.notes" rows="3"
                                    placeholder="Informations supplémentaires, contexte..."
                                    class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-{{ $modalColor }}-500 focus:border-{{ $modalColor }}-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400"></textarea>
                            </div>

                            <!-- Solde disponible -->
                            @if (!$isRecette && !empty($newTransaction['montant']))
                                <div
                                    class="p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-200 dark:border-blue-700">
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 mr-2" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                        </svg>
                                        <span class="text-sm font-medium text-blue-800 dark:text-blue-200">
                                            Solde disponible
                                        </span>
                                    </div>
                                    <div class="text-lg font-semibold text-blue-900 dark:text-blue-100 mt-1">
                                        @php
                                            $soldeCompte = 0;
                                            $modePaiement = $newTransaction['mode_paiement'] ?? 'especes';
                                            $sousType = $newTransaction['type_compte_mobilemoney_or_banque'] ?? '';

                                            if ($modePaiement === 'especes' && isset($comptes['principal'])) {
                                                $soldeCompte = $comptes['principal']->solde_actuel_mga ?? 0;
                                            } elseif ($modePaiement === 'MobileMoney' && $sousType) {
                                                $compte = $comptes['mobile_money']
                                                    ->where('type_compte_mobilemoney_or_banque', $sousType)
                                                    ->first();
                                                $soldeCompte = $compte->solde_actuel_mga ?? 0;
                                            } elseif ($modePaiement === 'Banque' && $sousType) {
                                                $compte = $comptes['banque']
                                                    ->where('type_compte_mobilemoney_or_banque', $sousType)
                                                    ->first();
                                                $soldeCompte = $compte->solde_actuel_mga ?? 0;
                                            }
                                        @endphp
                                        {{ number_format($soldeCompte, 0, ',', ' ') }} Ar
                                        @if ($this->insuffisantTransaction)
                                            <span class="text-red-500 font-medium ml-2">- Solde insuffisant</span>
                                        @endif
                                    </div>
                                </div>
                            @endif

                            <!-- Alerte solde insuffisant -->
                            @if ($this->insuffisantTransaction)
                                <div class="p-4 rounded-lg border border-red-200 bg-red-50 dark:bg-red-900/20">
                                    <div class="flex items-center space-x-3">
                                        <svg class="w-6 h-6 text-red-500 flex-shrink-0" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.5 0L4.346 15.5c-.77.833.192 2.5 1.732 2.5z" />
                                        </svg>
                                        <div class="text-red-800 dark:text-red-200">
                                            <div class="font-medium">Solde insuffisant</div>
                                            <div class="text-sm">Le montant dépasse le solde disponible sur ce compte.
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Bouton Ajouter à la liste -->
                        <div class="mt-6 flex justify-end">
                            <button type="submit"
                                class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors inline-flex items-center shadow-md hover:shadow-lg {{ $this->insuffisantTransaction ? 'opacity-50 cursor-not-allowed' : '' }}"
                                wire:loading.attr="disabled" wire:target="addToTransactionList"
                                {{ $this->insuffisantTransaction ? 'disabled' : '' }}>
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                                <span wire:loading.remove wire:target="addToTransactionList">Ajouter à la liste</span>
                                <span wire:loading wire:target="addToTransactionList">
                                    <span class="inline-flex items-center">
                                        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white"
                                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10"
                                                stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor"
                                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                            </path>
                                        </svg>
                                        Ajout...
                                    </span>
                                </span>
                            </button>
                        </div>
                    </form>

                    <!-- Liste des transactions en cours -->
                    @if ($transactionCount > 0)
                        <div class="border-t border-gray-200 dark:border-gray-700 mt-6 pt-6 px-6 pb-6">
                            <h4 class="font-medium text-gray-900 dark:text-white mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-{{ $modalColor }}-500" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                                Transactions à enregistrer
                            </h4>

                            <div class="space-y-3 mb-4 max-h-60 overflow-y-auto">
                                @foreach ($transactionsEnCours as $index => $transaction)
                                    <div
                                        class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600 hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                                        <div class="flex-1">
                                            <div class="font-medium text-gray-900 dark:text-white flex items-center">
                                                @if ($transaction['type'] === 'recette')
                                                    <svg class="w-4 h-4 mr-2 text-green-500" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                @else
                                                    <svg class="w-4 h-4 mr-2 text-red-500" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                    </svg>
                                                @endif
                                                {{ $transaction['description'] }}
                                            </div>
                                            <div class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                                <span
                                                    class="font-semibold {{ $transaction['type'] === 'recette' ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                                    {{ number_format($transaction['montant'], 0, ',', ' ') }} Ar
                                                </span>
                                                •
                                                {{ $transaction['mode_paiement'] }}
                                                @if ($transaction['type_compte_mobilemoney_or_banque'] ?? false)
                                                    ({{ $transaction['type_compte_mobilemoney_or_banque'] }})
                                                @endif
                                                •
                                                {{ \Carbon\Carbon::parse($transaction['date_transaction'])->format('d/m/Y') }}
                                            </div>
                                            @if ($transaction['partenaire_nom'] ?? false)
                                                <div
                                                    class="text-xs text-gray-500 dark:text-gray-400 mt-1 flex items-center">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                    </svg>
                                                    {{ $transaction['partenaire_nom'] }}
                                                </div>
                                            @endif
                                        </div>
                                        <button wire:click="removeFromTransactionList({{ $index }})"
                                            class="ml-3 p-1.5 text-red-500 hover:text-red-700 dark:hover:text-red-400 hover:bg-red-100 dark:hover:bg-red-900/30 rounded-lg transition-colors"
                                            title="Supprimer cette transaction">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Total -->
                            <div
                                class="flex justify-between items-center p-4 bg-{{ $modalColor }}-50 dark:bg-{{ $modalColor }}-900/20 rounded-lg border border-{{ $modalColor }}-200 dark:border-{{ $modalColor }}-700">
                                <span class="font-medium text-gray-900 dark:text-white flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-{{ $modalColor }}-600 dark:text-{{ $modalColor }}-400"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                    </svg>
                                    Total:
                                </span>
                                <span
                                    class="font-bold text-lg text-{{ $modalColor }}-600 dark:text-{{ $modalColor }}-400">
                                    {{ number_format($totalTransactions, 0, ',', ' ') }} Ar
                                </span>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Actions -->
            <div
                class="flex items-center justify-between px-6 py-4 bg-gray-50 dark:bg-gray-900 border-t border-gray-200 dark:border-gray-700">
                <div class="text-sm text-gray-500 dark:text-gray-400 flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    {{ $isRecette ? 'Ces recettes seront ajoutées au solde' : 'Ces dépenses seront déduites du solde' }}
                </div>

                <div class="flex space-x-3">
                    <button type="button" wire:click="closeNewTransactionModal"
                        class="px-5 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        Annuler
                    </button>

                    @if ($transactionCount > 0)
                        <button type="button" wire:click="saveAllTransactions"
                            class="px-6 py-2 bg-{{ $modalColor }}-600 hover:bg-{{ $modalColor }}-700 text-white rounded-lg transition-colors inline-flex items-center shadow-md hover:shadow-lg"
                            wire:loading.attr="disabled" wire:target="saveAllTransactions">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                            <span wire:loading.remove wire:target="saveAllTransactions">
                                Enregistrer tout ({{ $transactionCount }})
                            </span>
                            <span wire:loading wire:target="saveAllTransactions">
                                <span class="inline-flex items-center">
                                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10"
                                            stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor"
                                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                        </path>
                                    </svg>
                                    Enregistrement...
                                </span>
                            </span>
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
            <div
                class="inline-block w-full max-w-2xl my-8 overflow-hidden text-left align-middle transition-all transform bg-white dark:bg-gray-800 shadow-2xl rounded-xl">

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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Contenu -->
                <div class="px-6 py-6 space-y-4">
                    <!-- Informations principales -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-500 dark:text-gray-400">Description</label>
                            <p class="mt-1 text-sm text-gray-900 dark:text-white">
                                {{ $transactionDetails['description'] ?? '' }}
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Montant</label>
                            <p
                                class="mt-1 text-lg font-bold {{ ($transactionDetails['type'] ?? '') === 'recette' ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                {{ $transactionDetails['montant_formate'] ?? '' }}
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Type</label>
                            <span
                                class="mt-1 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ ($transactionDetails['type'] ?? '') === 'recette' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' }}">
                                {{ ucfirst($transactionDetails['type'] ?? '') }}
                            </span>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Mode de
                                paiement</label>
                            <p class="mt-1 text-sm text-gray-900 dark:text-white">
                                {{ $transactionDetails['mode_paiement'] ?? '' }}
                                @if ($transactionDetails['type_compte_mobilemoney_or_banque'] ?? false)
                                    - {{ $transactionDetails['type_compte_mobilemoney_or_banque'] }}
                                @endif
                            </p>
                        </div>
                        @if ($transactionDetails['partenaire'] ?? false)
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-500 dark:text-gray-400">Partenaire</label>
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
                <div
                    class="flex items-center justify-end space-x-3 px-6 py-4 bg-gray-50 dark:bg-gray-900 border-t border-gray-200 dark:border-gray-700">
                    <button wire:click="deleteTransaction({{ $transactionDetails['id'] ?? 0 }})"
                        onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette transaction ?')"
                        class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition-colors flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Supprimer
                    </button>
                </div>
            </div>
        </div>
    </div>
@endif

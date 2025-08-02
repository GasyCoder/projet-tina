<div class="space-y-4">
    <!-- Total général et bouton créer -->
    <div class="flex justify-between items-center">
        <div class="bg-gray-100 p-4 rounded-lg">
            <h3 class="text-sm font-medium text-gray-700">💰 Total général</h3>
            <p class="text-2xl font-bold text-gray-900">
                {{ number_format($comptes->sum('solde_actuel_mga'), 0) }} MGA
            </p>
            <p class="text-xs text-gray-600">{{ $comptes->count() }} compte(s) total</p>
        </div>
    </div>

    <!-- Résumé détaillé par type de paiement -->
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
        <div class="bg-blue-50 dark:bg-blue-900/20 p-4 rounded-lg">
            <h3 class="text-sm font-medium text-blue-900 dark:text-blue-200">💰 Principal (Espèces)</h3>
            <p class="text-xl font-bold text-blue-600 dark:text-blue-400">
                {{ number_format($comptes->where('type_compte', 'principal')->sum('solde_actuel_mga'), 0) }} MGA
            </p>
            <p class="text-xs text-blue-700 dark:text-blue-300">{{ $comptes->where('type_compte', 'principal')->count() }} compte(s)</p>
        </div>
        
        <div class="bg-red-50 dark:bg-red-900/20 p-4 rounded-lg">
            <h3 class="text-sm font-medium text-red-900 dark:text-red-200">📱 Airtel Money</h3>
            <p class="text-xl font-bold text-red-600 dark:text-red-400">
                {{ number_format($comptes->where('type_compte', 'AirtelMoney')->sum('solde_actuel_mga'), 0) }} MGA
            </p>
            <p class="text-xs text-red-700 dark:text-red-300">{{ $comptes->where('type_compte', 'AirtelMoney')->count() }} compte(s)</p>
        </div>
        
        <div class="bg-green-50 dark:bg-green-900/20 p-4 rounded-lg">
            <h3 class="text-sm font-medium text-green-900 dark:text-green-200">📱 MVola</h3>
            <p class="text-xl font-bold text-green-600 dark:text-green-400">
                {{ number_format($comptes->where('type_compte', 'Mvola')->sum('solde_actuel_mga'), 0) }} MGA
            </p>
            <p class="text-xs text-green-700 dark:text-green-300">{{ $comptes->where('type_compte', 'Mvola')->count() }} compte(s)</p>
        </div>
        
        <div class="bg-orange-50 dark:bg-orange-900/20 p-4 rounded-lg">
            <h3 class="text-sm font-medium text-orange-900 dark:text-orange-200">📱 Orange Money</h3>
            <p class="text-xl font-bold text-orange-600 dark:text-orange-400">
                {{ number_format($comptes->where('type_compte', 'OrangeMoney')->sum('solde_actuel_mga'), 0) }} MGA
            </p>
            <p class="text-xs text-orange-700 dark:text-orange-300">{{ $comptes->where('type_compte', 'OrangeMoney')->count() }} compte(s)</p>
        </div>
        
        <div class="bg-purple-50 dark:bg-purple-900/20 p-4 rounded-lg">
            <h3 class="text-sm font-medium text-purple-900 dark:text-purple-200">🏦 Banques</h3>
            <p class="text-xl font-bold text-purple-600 dark:text-purple-400">
                {{ number_format($comptes->where('type_compte', 'banque')->sum('solde_actuel_mga'), 0) }} MGA
            </p>
            <p class="text-xs text-purple-700 dark:text-purple-300">{{ $comptes->where('type_compte', 'banque')->count() }} compte(s)</p>
        </div>
    </div>

    <!-- Liste des comptes par type -->
    @if($comptes->count() > 0)
        <div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-md">
            <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                @foreach($comptes->groupBy('type_compte') as $type => $comptesGroup)
                    <li class="bg-gray-50 dark:bg-gray-700 px-6 py-3">
                        <h3 class="text-sm font-medium text-gray-900 dark:text-gray-100">
                            @switch($type)
                                @case('principal') 💰 Espèces @break
                                @case('AirtelMoney') 📱 Airtel Money @break
                                @case('Mvola') 📱 MVola @break
                                @case('OrangeMoney') 📱 Orange Money @break
                                @case('banque') 🏦 Comptes Bancaires @break
                                @default {{ ucfirst($type) }}
                            @endswitch
                            <span class="text-xs text-gray-500 ml-2">
                                ({{ $comptesGroup->count() }} compte(s) - {{ number_format($comptesGroup->sum('solde_actuel_mga'), 0) }} MGA)
                            </span>
                        </h3>
                    </li>
                    @foreach($comptesGroup as $compte)
                        <li class="px-6 py-4 hover:bg-gray-50 dark:hover:bg-gray-700">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center space-x-2">
                                        <h4 class="text-sm font-medium text-gray-900">
                                            @switch($compte->type_compte)
                                                @case('principal') 💰 Espèces @break
                                                @case('AirtelMoney') 📱 Airtel Money @break
                                                @case('Mvola') 📱 MVola @break
                                                @case('OrangeMoney') 📱 Orange Money @break
                                                @case('banque') 🏦 Banque @break
                                                @default {{ ucfirst($compte->type_compte) }}
                                            @endswitch
                                            - {{ $compte->nom_proprietaire ?: 'Compte' }}
                                        </h4>
                                        @if(!$compte->actif)
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200">
                                                Inactif
                                            </span>
                                        @endif
                                        {{-- Indicateur de mise à jour automatique --}}
                                        @if($compte->derniere_transaction_id)
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800" title="Solde mis à jour automatiquement">
                                                🔄 Auto
                                            </span>
                                        @endif
                                    </div>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2 mt-2">
                                        <div>
                                            @if($compte->nom_proprietaire)
                                                <p class="text-sm text-gray-600 dark:text-gray-400">👤 {{ $compte->nom_proprietaire }}</p>
                                            @endif
                                            @if($compte->numero_compte && in_array($compte->type_compte, ['AirtelMoney', 'Mvola', 'OrangeMoney', 'banque']))
                                                <p class="text-xs text-gray-500">📋 N° {{ $compte->numero_compte }}</p>
                                            @endif
                                        </div>
                                        
                                        <div>
                                            @if($compte->derniere_transaction_id)
                                                <p class="text-xs text-blue-600">
                                                    🔗 Dernière transaction: #{{ $compte->derniere_transaction_id }}
                                                </p>
                                            @else
                                                <p class="text-xs text-gray-400">
                                                    ⏸️ Aucune transaction
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="flex items-center space-x-4">
                                    <div class="text-right">
                                        <p class="text-lg font-semibold {{ $compte->solde_actuel_mga >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                            {{ $compte->solde_formatted }}
                                        </p>
                                        
                                        @if($compte->solde_actuel_mga < 0)
                                            <p class="text-xs text-red-500">⚠️ Solde négatif</p>
                                        @elseif($compte->solde_actuel_mga == 0)
                                            <p class="text-xs text-gray-500">💰 Solde nul</p>
                                        @else
                                            <p class="text-xs text-green-500">✅ Solde positif</p>
                                        @endif
                                        
                                        <p class="text-xs text-gray-400 mt-1">
                                            📅 Créé: {{ $compte->created_at->format('d/m/Y') }}
                                        </p>
                                    </div>
                                    
                                    <div class="flex flex-col space-y-1">
                                        <button wire:click="editCompte({{ $compte->id }})" 
                                                class="text-blue-600 hover:text-blue-900 p-1" title="Modifier le compte">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </button>
                                        
                                        <button wire:click="deleteCompte({{ $compte->id }})" 
                                                wire:confirm="Supprimer ce compte ? Attention : cette action est irréversible."
                                                class="text-red-600 hover:text-red-900 p-1" title="Supprimer le compte">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </li>
                    @endforeach
                @endforeach
            </ul>
        </div>
    @else
        <div class="text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3 3v8a3 3 0 003 3z"/>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">Aucun compte trouvé</h3>
            <p class="mt-1 text-sm text-gray-500">Commencez par créer votre premier compte.</p>
            <div class="mt-4">
                <button wire:click="createCompte" class="inline-flex items-center px-4 py-2 bg-blue-600 dark:bg-blue-700 border border-transparent rounded-md text-sm font-medium text-white hover:bg-blue-700 dark:hover:bg-blue-600">
                    🏦 Créer un compte
                </button>
            </div>
        </div>
    @endif

    <!-- Modal moderne -->
    <div x-data="{ compteModal: @entangle('showCompteModal') }">
        @include('livewire.finance.modals.compte-modal')
    </div>
</div>
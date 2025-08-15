{{-- Vue comptes - TAILLE NORMALE + COMPACT --}}
<div class="min-h-screen bg-gray-100 dark:bg-gray-900 pt-20 pb-6 md:px-6">
    
    <!-- Header -->
    <div class="px-3 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <div class="flex items-center justify-between">
                <!-- Titre -->
                <div class="flex items-center space-x-4">
                    <h1 class="text-base sm:text-2xl lg:text-3xl font-semibold text-gray-900 dark:text-gray-100 tracking-tight">
                        Gestion des Comptes
                    </h1>
                </div>

                <!-- Bouton créer -->
                <button wire:click="createCompte" 
                        class="w-10 h-10 sm:w-auto sm:h-auto inline-flex items-center justify-center p-0 sm:px-4 sm:py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white text-xs sm:text-sm font-medium rounded-full sm:rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-150">
                    <!-- Icône mobile -->
                    <svg class="w-5 h-5 sm:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    <!-- Texte + icône desktop -->
                    <span class="hidden sm:inline-flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Nouveau compte
                    </span>
                </button>
            </div>
        </div>
    </div>

    <!-- Contenu principal -->
    <div class="px-0 sm:px-6 lg:px-8 py-4 sm:py-6">
        <div class="max-w-7xl mx-auto">
            
            <!-- Container principal -->
            <div class="bg-white dark:bg-gray-800 shadow-sm mx-0 sm:mx-0 sm:rounded-xl overflow-hidden">
                
                <div class="p-3 sm:p-6 space-y-6">
                    
                    <!-- Total général -->
                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 p-4 rounded-lg border border-blue-200 dark:border-blue-800">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-sm font-medium text-blue-900 dark:text-blue-200">💰 Total général</h3>
                                <p class="text-2xl font-bold text-blue-600 dark:text-blue-400">
                                    {{ number_format($comptes->sum('solde_actuel_mga'), 0, ',', ' ') }} MGA
                                </p>
                                <p class="text-xs text-blue-700 dark:text-blue-300">{{ $comptes->count() }} compte(s) total</p>
                            </div>
                            <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Résumé par type - COMPACT -->
                    <div class="grid grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-3">
                        <div class="bg-blue-50 dark:bg-blue-900/20 p-3 rounded-lg border border-blue-200 dark:border-blue-800">
                            <h4 class="text-xs font-medium text-blue-900 dark:text-blue-200">💰 Espèces</h4>
                            <p class="text-lg font-bold text-blue-600 dark:text-blue-400">
                                {{ number_format($comptes->where('type_compte', 'principal')->sum('solde_actuel_mga'), 0, ',', ' ') }}
                            </p>
                            <p class="text-xs text-blue-700 dark:text-blue-300">{{ $comptes->where('type_compte', 'principal')->count() }} compte(s)</p>
                        </div>
                        
                        <div class="bg-red-50 dark:bg-red-900/20 p-3 rounded-lg border border-red-200 dark:border-red-800">
                            <h4 class="text-xs font-medium text-red-900 dark:text-red-200">📱 Airtel</h4>
                            <p class="text-lg font-bold text-red-600 dark:text-red-400">
                                {{ number_format($comptes->where('type_compte', 'AirtelMoney')->sum('solde_actuel_mga'), 0, ',', ' ') }}
                            </p>
                            <p class="text-xs text-red-700 dark:text-red-300">{{ $comptes->where('type_compte', 'AirtelMoney')->count() }} compte(s)</p>
                        </div>
                        
                        <div class="bg-green-50 dark:bg-green-900/20 p-3 rounded-lg border border-green-200 dark:border-green-800">
                            <h4 class="text-xs font-medium text-green-900 dark:text-green-200">📱 MVola</h4>
                            <p class="text-lg font-bold text-green-600 dark:text-green-400">
                                {{ number_format($comptes->where('type_compte', 'Mvola')->sum('solde_actuel_mga'), 0, ',', ' ') }}
                            </p>
                            <p class="text-xs text-green-700 dark:text-green-300">{{ $comptes->where('type_compte', 'Mvola')->count() }} compte(s)</p>
                        </div>
                        
                        <div class="bg-orange-50 dark:bg-orange-900/20 p-3 rounded-lg border border-orange-200 dark:border-orange-800">
                            <h4 class="text-xs font-medium text-orange-900 dark:text-orange-200">📱 Orange</h4>
                            <p class="text-lg font-bold text-orange-600 dark:text-orange-400">
                                {{ number_format($comptes->where('type_compte', 'OrangeMoney')->sum('solde_actuel_mga'), 0, ',', ' ') }}
                            </p>
                            <p class="text-xs text-orange-700 dark:text-orange-300">{{ $comptes->where('type_compte', 'OrangeMoney')->count() }} compte(s)</p>
                        </div>
                        
                        <div class="bg-purple-50 dark:bg-purple-900/20 p-3 rounded-lg border border-purple-200 dark:border-purple-800">
                            <h4 class="text-xs font-medium text-purple-900 dark:text-purple-200">🏦 Banques</h4>
                            <p class="text-lg font-bold text-purple-600 dark:text-purple-400">
                                {{ number_format($comptes->where('type_compte', 'banque')->sum('solde_actuel_mga'), 0, ',', ' ') }}
                            </p>
                            <p class="text-xs text-purple-700 dark:text-purple-300">{{ $comptes->where('type_compte', 'banque')->count() }} compte(s)</p>
                        </div>
                    </div>

                    <!-- Liste des comptes -->
                    @if($comptes->count() > 0)
                        <div class="space-y-4">
                            @foreach($comptes->groupBy('type_compte') as $type => $comptesGroup)
                                <!-- En-tête de groupe -->
                                <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 rounded-lg">
                                    <h3 class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                        @switch($type)
                                            @case('principal') 💰 Comptes Espèces @break
                                            @case('AirtelMoney') 📱 Airtel Money @break
                                            @case('Mvola') 📱 MVola @break
                                            @case('OrangeMoney') 📱 Orange Money @break
                                            @case('banque') 🏦 Comptes Bancaires @break
                                            @default {{ ucfirst($type) }}
                                        @endswitch
                                        <span class="text-xs text-gray-500 dark:text-gray-400 ml-2">
                                            ({{ $comptesGroup->count() }} • {{ number_format($comptesGroup->sum('solde_actuel_mga'), 0, ',', ' ') }} MGA)
                                        </span>
                                    </h3>
                                </div>

                                <!-- Comptes du groupe -->
                                <div class="space-y-2">
                                    @foreach($comptesGroup as $compte)
                                        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-4 hover:shadow-sm transition-shadow">
                                            <div class="flex items-center justify-between">
                                                <div class="flex-1">
                                                    <div class="flex items-center space-x-2">
                                                        <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                            @switch($compte->type_compte)
                                                                @case('principal') 💰 @break
                                                                @case('AirtelMoney') 📱 @break
                                                                @case('Mvola') 📱 @break
                                                                @case('OrangeMoney') 📱 @break
                                                                @case('banque') 🏦 @break
                                                            @endswitch
                                                            {{ $compte->nom_proprietaire ?: 'Compte Principal' }}
                                                        </h4>
                                                        
                                                        @if(!$compte->actif)
                                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200">
                                                                Inactif
                                                            </span>
                                                        @endif
                                                        
                                                        @if($compte->derniere_transaction_id)
                                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200" title="Solde mis à jour automatiquement">
                                                                🔄
                                                            </span>
                                                        @endif
                                                    </div>
                                                    
                                                    <div class="mt-1 flex items-center space-x-4 text-xs text-gray-500 dark:text-gray-400">
                                                        @if($compte->numero_compte && in_array($compte->type_compte, ['AirtelMoney', 'Mvola', 'OrangeMoney', 'banque']))
                                                            <span>📋 {{ $compte->numero_compte }}</span>
                                                        @endif
                                                        
                                                        @if($compte->derniere_transaction_id)
                                                            <span class="text-blue-600 dark:text-blue-400">🔗 Transaction #{{ $compte->derniere_transaction_id }}</span>
                                                        @endif
                                                        
                                                        <span>📅 {{ $compte->created_at->format('d/m/Y') }}</span>
                                                    </div>
                                                </div>
                                                
                                                <div class="flex items-center space-x-3">
                                                    <!-- Solde -->
                                                    <div class="text-right">
                                                        <p class="text-lg font-semibold {{ $compte->solde_actuel_mga >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                                            {{ number_format($compte->solde_actuel_mga, 0, ',', ' ') }} MGA
                                                        </p>
                                                        
                                                        @if($compte->solde_actuel_mga < 0)
                                                            <p class="text-xs text-red-500 dark:text-red-400">⚠️ Négatif</p>
                                                        @elseif($compte->solde_actuel_mga == 0)
                                                            <p class="text-xs text-gray-500 dark:text-gray-400">💰 Nul</p>
                                                        @else
                                                            <p class="text-xs text-green-500 dark:text-green-400">✅ Positif</p>
                                                        @endif
                                                    </div>
                                                    
                                                    <!-- Actions -->
                                                    <div class="flex items-center space-x-1">
                                                        <button wire:click="editCompte({{ $compte->id }})" 
                                                                class="p-2 text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-lg transition-colors" 
                                                                title="Modifier">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                            </svg>
                                                        </button>
                                                        
                                                        <button wire:click="deleteCompte({{ $compte->id }})" 
                                                                wire:confirm="Supprimer ce compte ?"
                                                                class="p-2 text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors" 
                                                                title="Supprimer">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                            </svg>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endforeach
                        </div>
                    @else
                        <!-- État vide -->
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                            </svg>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">Aucun compte trouvé</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Commencez par créer votre premier compte.</p>
                            <button wire:click="createCompte" 
                                    class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                                Créer un compte
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    @include('livewire.finance.modals.compte-modal')

    <!-- Loading States -->
    <div wire:loading.flex wire:target="editCompte,deleteCompte,createCompte,saveCompte" 
         class="fixed inset-0 bg-gray-500 bg-opacity-75 z-50 items-center justify-center">
        <div class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-xl">
            <div class="flex items-center space-x-3">
                <svg class="animate-spin h-6 w-6 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span class="text-gray-900 dark:text-gray-100 font-medium">Traitement en cours...</span>
            </div>
        </div>
    </div>
</div>
<div class="space-y-4">
    <!-- Résumé détaillé par type de paiement -->
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
        <div class="bg-blue-50 p-4 rounded-lg">
            <h3 class="text-sm font-medium text-blue-900">💰 Principal (Espèces)</h3>
            <p class="text-xl font-bold text-blue-600">
                {{ number_format($comptes->where('type_compte', 'principal')->sum('solde_actuel_mga'), 0) }} MGA
            </p>
            <p class="text-xs text-blue-700">{{ $comptes->where('type_compte', 'principal')->count() }} compte(s)</p>
        </div>
        
        <div class="bg-red-50 p-4 rounded-lg">
            <h3 class="text-sm font-medium text-red-900">📱 Airtel Money</h3>
            <p class="text-xl font-bold text-red-600">
                {{ number_format($comptes->where('type_compte', 'AirtelMoney')->sum('solde_actuel_mga'), 0) }} MGA
            </p>
            <p class="text-xs text-red-700">{{ $comptes->where('type_compte', 'AirtelMoney')->count() }} compte(s)</p>
        </div>
        
        <div class="bg-green-50 p-4 rounded-lg">
            <h3 class="text-sm font-medium text-green-900">📱 MVola</h3>
            <p class="text-xl font-bold text-green-600">
                {{ number_format($comptes->where('type_compte', 'Mvola')->sum('solde_actuel_mga'), 0) }} MGA
            </p>
            <p class="text-xs text-green-700">{{ $comptes->where('type_compte', 'Mvola')->count() }} compte(s)</p>
        </div>
        
        <div class="bg-orange-50 p-4 rounded-lg">
            <h3 class="text-sm font-medium text-orange-900">📱 Orange Money</h3>
            <p class="text-xl font-bold text-orange-600">
                {{ number_format($comptes->where('type_compte', 'OrangeMoney')->sum('solde_actuel_mga'), 0) }} MGA
            </p>
            <p class="text-xs text-orange-700">{{ $comptes->where('type_compte', 'OrangeMoney')->count() }} compte(s)</p>
        </div>
        
        <div class="bg-purple-50 p-4 rounded-lg">
            <h3 class="text-sm font-medium text-purple-900">🏦 Banques</h3>
            <p class="text-xl font-bold text-purple-600">
                {{ number_format($comptes->where('type_compte', 'banque')->sum('solde_actuel_mga'), 0) }} MGA
            </p>
            <p class="text-xs text-purple-700">{{ $comptes->where('type_compte', 'banque')->count() }} compte(s)</p>
        </div>
    </div>

    <!-- Liste des comptes par type -->
    @if($comptes->count() > 0)
        <div class="bg-white shadow overflow-hidden sm:rounded-md">
            <ul class="divide-y divide-gray-200">
                @foreach($comptes->groupBy('type_compte') as $type => $comptesGroup)
                    <li class="bg-gray-50 px-6 py-3">
                        <h3 class="text-sm font-medium text-gray-900">
                            @switch($type)
                                @case('principal') 💰 Espèces @break
                                @case('AirtelMoney') 📱 Airtel Money @break
                                @case('Mvola') 📱 MVola @break
                                @case('OrangeMoney') 📱 Orange Money @break
                                @case('banque') 🏦 Comptes Bancaires @break
                                @default {{ ucfirst($type) }}
                            @endswitch
                        </h3>
                    </li>
                    @foreach($comptesGroup as $compte)
                        <li class="px-6 py-4 hover:bg-gray-50">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center space-x-2">
                                        {{-- ✅ CHANGÉ : Remplacé nom_compte par type_compte + proprietaire --}}
                                        <h4 class="text-sm font-medium text-gray-900">
                                            {{ ucfirst($compte->type_compte) }} - {{ $compte->nom_proprietaire ?: 'Compte' }}
                                        </h4>
                                        @if(!$compte->actif)
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                                Inactif
                                            </span>
                                        @endif
                                    </div>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2 mt-2">
                                        <div>
                                            @if($compte->nom_proprietaire)
                                                <p class="text-sm text-gray-600">👤 {{ $compte->nom_proprietaire }}</p>
                                            @endif
                                        </div>
                                        
                                        <div>
                                            <p class="text-xs text-gray-500">
                                                🏷️ {{ ucfirst(str_replace('_', ' ', $compte->type_compte)) }}
                                            </p>
                                            
                                            @if($compte->type_compte == 'principal')
                                                <p class="text-xs text-green-600">
                                                    Espèces
                                                </p>
                                            @elseif(in_array($compte->type_compte, ['OrangeMoney', 'AirtelMoney', 'Mvola']))
                                                @if($compte->numero_compte)
                                                    <p class="text-xs text-gray-500">📋 N° {{ $compte->numero_compte }}</p>
                                                @endif
                                            @elseif($compte->type_compte == 'banque')
                                                <p class="text-xs text-green-600">
                                                    Banque
                                                </p>
                                            @else
                                                <p class="text-xs text-gray-400">
                                                    {{ $compte->numero_compte ?? '-' }}
                                                </p>
                                            @endif
                                        </div>

                                    </div>
                                </div>
                                
                                <div class="flex items-center space-x-4">
                                    <div class="text-right">
                                        <p class="text-lg font-semibold {{ $compte->solde_actuel_mga >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                            {{ $compte->solde_formatted }}
                                        </p>
                                        
                                        @if($compte->solde_actuel_mga < 0)
                                            <p class="text-xs text-red-500">⚠️ Solde négatif</p>
                                        @endif
                                        
                                        <!-- Date de création du compte -->
                                        <p class="text-xs text-gray-400 mt-1">
                                            📅 Créé: {{ $compte->created_at->format('d/m/Y') }}
                                        </p>
                                    </div>
                                    
                                    <div class="flex flex-col space-y-1">
                                        {{-- ✅ CHANGÉ : Noms des types de comptes corrigés --}}
                                        <button wire:click="editCompte({{ $compte->id }})" 
                                                class="text-blue-600 hover:text-blue-900 p-1" title="Modifier">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </button>
                                        
                                        <button wire:click="deleteCompte({{ $compte->id }})" 
                                                wire:confirm="Supprimer ce compte ?"
                                                class="text-red-600 hover:text-red-900 p-1" title="Supprimer">
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
            <h3 class="mt-2 text-sm font-medium text-gray-900">Aucun compte</h3>
            <p class="mt-1 text-sm text-gray-500">Commencez par ajouter un nouveau compte.</p>
            <div class="mt-4">
                <button wire:click="createCompte" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md text-sm font-medium text-white hover:bg-blue-700">
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
{{-- resources/views/livewire/finance/modals/transaction-modal.blade.php --}}
@if($showTransactionModal)
    <div class="fixed inset-0 z-50 overflow-y-auto" x-data="{ step: 1 }">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!-- Overlay avec animation -->
            <div class="fixed inset-0 " 
                 wire:click="closeTransactionModal"></div>
            
            <!-- Modal Container avec animation moderne -->
            <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-6xl sm:w-full border border-gray-200">
                
                <!-- Header avec gradient -->
                <div class="bg-gradient-to-r from-blue-600 to-purple-700 px-6 py-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center">
                                    @if($editingTransaction)
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    @else
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                        </svg>
                                    @endif
                                </div>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-white">
                                    {{ $editingTransaction ? 'Modifier' : 'Nouvelle' }} Transaction
                                </h3>
                                <p class="text-blue-100 text-sm">
                                    {{ $editingTransaction ? 'Modifiez les détails de la transaction' : 'Créez une nouvelle transaction financière' }}
                                </p>
                            </div>
                        </div>
                        <button wire:click="closeTransactionModal" 
                                class="text-white/80 hover:text-white hover:bg-white/10 rounded-lg p-2 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <form wire:submit="saveTransaction">
                    <div class="bg-gray-50 px-6 py-6">
                        
                        <!-- Progress Steps -->
                        <div class="mb-8">
                            <div class="flex items-center justify-center space-x-8">
                                <div class="flex items-center space-x-2">
                                    <div class="w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center text-sm font-medium">1</div>
                                    <span class="text-sm font-medium text-blue-600">Type & Date</span>
                                </div>
                                <div class="w-16 h-1 bg-gray-200 rounded"></div>
                                <div class="flex items-center space-x-2">
                                    <div class="w-8 h-8 rounded-full bg-gray-300 text-gray-600 flex items-center justify-center text-sm font-medium">2</div>
                                    <span class="text-sm font-medium text-gray-500">Éléments</span>
                                </div>
                                <div class="w-16 h-1 bg-gray-200 rounded"></div>
                                <div class="flex items-center space-x-2">
                                    <div class="w-8 h-8 rounded-full bg-gray-300 text-gray-600 flex items-center justify-center text-sm font-medium">3</div>
                                    <span class="text-sm font-medium text-gray-500">Finalisation</span>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                            
                            <!-- Colonne gauche - Informations principales -->
                            <div class="lg:col-span-2 space-y-6">
                                
                                <!-- 1. INFORMATIONS DE BASE -->
                                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 px-6 py-4 border-b border-blue-100">
                                        <h4 class="text-lg font-semibold text-gray-900 flex items-center">
                                            <span class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                                📋
                                            </span>
                                            Informations de base
                                        </h4>
                                    </div>
                                    
                                    <!-- Inside the modal form, under "Informations de base" section -->
                                    <div class="p-6 space-y-4">
                                        <input wire:model="reference" type="hidden">
                                        
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div>
                                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                                    <span class="flex items-center">
                                                        🏷️ Type de transaction *
                                                        <span class="ml-1 text-red-500">*</span>
                                                    </span>
                                                </label>
                                                <select wire:model.live="type" 
                                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                                    <option value="">🔹 Sélectionner le type</option>
                                                    <option value="vente">💰 Vente de produits</option>
                                                    <option value="achat">🛒 Achat de produits</option>
                                                    <option value="autre">✨ Autre</option>
                                                </select>
                                                @error('type') 
                                                    <div class="mt-2 flex items-center text-red-600 text-sm">
                                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                                        </svg>
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                            
                                            <div>
                                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                                    <span class="flex items-center">
                                                        📅 Date de transaction *
                                                        <span class="ml-1 text-red-500">*</span>
                                                    </span>
                                                </label>
                                                <input wire:model="date" type="date" 
                                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                                @error('date') 
                                                    <div class="mt-2 flex items-center text-red-600 text-sm">
                                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                                        </svg>
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>

                                        <!-- CHAMP OBJET - SEULEMENT POUR ACHAT ET AUTRE -->
                                        @if(in_array($type, ['achat', 'autre']))
                                            <div>
                                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                                    <span class="flex items-center">
                                                        📝 Objet de la transaction *
                                                        <span class="ml-1 text-red-500">*</span>
                                                    </span>
                                                </label>
                                                <input wire:model="objet" type="text" 
                                                    placeholder="Ex: Achat matières premières, Frais de transport..."
                                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                                @error('objet') 
                                                    <div class="mt-2 flex items-center text-red-600 text-sm">
                                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                                        </svg>
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        @endif

                                        <!-- Type-specific info -->
                                        @if($type)
                                            <div class="p-4 rounded-lg border-l-4 
                                                        {{ $type === 'vente' ? 'bg-green-50 border-green-400' : ($type === 'achat' ? 'bg-blue-50 border-blue-400' : 'bg-gray-50 border-gray-400') }}">
                                                <div class="text-sm font-medium 
                                                            {{ $type === 'vente' ? 'text-green-800' : ($type === 'achat' ? 'text-blue-800' : 'text-gray-800') }}">
                                                    @if($type === 'vente')
                                                        💰 Transaction de vente : Vous recevez un paiement pour des produits livrés
                                                    @elseif($type === 'achat')
                                                        🛒 Transaction d'achat : Sélectionnez un produit et saisissez les détails
                                                    @else
                                                        ✨ Autre type de transaction : Saisie libre
                                                    @endif
                                                </div>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Product Selection -->
                                    @if(in_array($type, ['achat']))
                                        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mt-6">
                                            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 px-6 py-4 border-b border-blue-100">
                                                <h4 class="text-lg font-semibold text-gray-900 flex items-center">
                                                    <span class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                                        📦
                                                    </span>
                                                    Détails du produit
                                                </h4>
                                            </div>
                                            
                                            <div class="p-6 space-y-6">
                                                <div>
                                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                                        <span class="flex items-center">
                                                            🛍️ Produit *
                                                            <span class="ml-1 text-red-500">*</span>
                                                        </span>
                                                    </label>
                                                    <select wire:model.live="produit_id" 
                                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                                        <option value="">🔍 Sélectionner un produit</option>
                                                        @forelse($produitsDisponibles as $produit)
                                                            <option value="{{ $produit->id }}">
                                                                {{ $produit->nom_complet }} ({{ $produit->unite }})
                                                                @if($type === 'vente')
                                                                    - Stock: {{ number_format($produit->poids_moyen_sac_kg, 2) }} {{ $produit->unite }}
                                                                @endif
                                                            </option>
                                                        @empty
                                                            <option disabled>❌ Aucun produit disponible</option>
                                                        @endforelse
                                                    </select>
                                                    @error('produit_id') 
                                                        <div class="mt-2 flex items-center text-red-600 text-sm">
                                                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                                            </svg>
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>

                                                <!-- Dynamic Fields for Product Details -->
                                                @if($produit_id && $produitSelectionne)
                                                    <!-- Informations de référence du produit -->
                                                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                                        <h5 class="text-sm font-semibold text-blue-900 mb-3">📊 Informations de référence</h5>
                                                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 text-sm">
                                                            <div class="bg-white rounded-lg p-3 border border-blue-100">
                                                                <div class="text-blue-600 font-medium">📦 Produit</div>
                                                                <div class="text-blue-900 font-bold">{{ $produitSelectionne->nom_complet }}</div>
                                                            </div>
                                                            <div class="bg-white rounded-lg p-3 border border-blue-100">
                                                                <div class="text-blue-600 font-medium">📏 Unité</div>
                                                                <div class="text-blue-900 font-bold">{{ $produitSelectionne->unite }}</div>
                                                            </div>
                                                            <div class="bg-white rounded-lg p-3 border border-blue-100 
                                                                    {{ $type === 'vente' ? 'border-green-200 bg-green-50' : '' }}">
                                                                <div class="text-blue-600 font-medium {{ $type === 'vente' ? 'text-green-600' : '' }}">
                                                                    {{ $type === 'vente' ? '📦 Stock actuel' : '⚖️ Stock disponible' }}
                                                                </div>
                                                                <div class="text-blue-900 font-bold {{ $type === 'vente' ? 'text-green-900 text-lg' : '' }}">
                                                                    {{ number_format($stock_actuel, 2, ',', ' ') }} {{ $produitSelectionne->unite }}
                                                                </div>
                                                                @if($type === 'vente')
                                                                    <div class="text-xs text-green-600 mt-1">Disponible pour la vente</div>
                                                                @endif
                                                            </div>
                                                            <div class="bg-white rounded-lg p-3 border border-blue-100">
                                                                <div class="text-blue-600 font-medium">💰 Prix référence</div>
                                                                <div class="text-blue-900 font-bold">{{ $produitSelectionne->prix_reference_mga_formatted }}</div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Saisie des détails -->
                                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                        <!-- Quantité -->
                                                        <div>
                                                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                                                <span class="flex items-center">
                                                                    📏 Quantité {{ $type === 'achat' ? 'à acheter' : 'à vendre' }} *
                                                                    <span class="ml-1 text-red-500">*</span>
                                                                    @if($type === 'vente')
                                                                        <span class="ml-2 text-xs text-orange-600">
                                                                            (Max: {{ number_format($stock_actuel, 2, ',', ' ') }} {{ $unite }})
                                                                        </span>
                                                                    @endif
                                                                </span>
                                                            </label>
                                                            <div class="relative">
                                                                <input wire:model.live="quantite" 
                                                                    type="number" 
                                                                    step="0.01" 
                                                                    placeholder="0"
                                                                    @if($type === 'vente') max="{{ $stock_actuel }}" @endif
                                                                    class="w-full px-4 py-3 pr-16 border 
                                                                            {{ $errors->has('quantite') ? 'border-red-300 bg-red-50' : 'border-gray-300' }} 
                                                                            rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                                                @if($unite)
                                                                    <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                                                                        <span class="text-blue-600 text-sm font-medium">{{ $unite }}</span>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                            
                                                            <!-- Messages d'erreur et d'information -->
                                                            @error('quantite') 
                                                                <div class="mt-2 p-3 bg-red-50 border border-red-200 rounded-lg">
                                                                    <div class="flex items-start">
                                                                        <svg class="w-5 h-5 text-red-400 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                                                        </svg>
                                                                        <div>
                                                                            <div class="text-red-800 font-medium text-sm">⚠️ Quantité non valide</div>
                                                                            <div class="text-red-700 text-sm mt-1">{{ $message }}</div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @enderror

                                                            <!-- Indicateur visuel pour les ventes -->
                                                            @if($type === 'vente' && $quantite && $stock_actuel && !$errors->has('quantite'))
                                                                @php
                                                                    $quantiteSaisie = floatval($quantite);
                                                                    $stockDisponible = floatval($stock_actuel);
                                                                    $pourcentageUtilise = $stockDisponible > 0 ? ($quantiteSaisie / $stockDisponible) * 100 : 0;
                                                                @endphp
                                                                
                                                                <div class="mt-3 p-3 rounded-lg border-l-4 
                                                                            {{ $quantiteSaisie > $stockDisponible ? 'bg-red-50 border-red-400' : 
                                                                            ($pourcentageUtilise > 80 ? 'bg-yellow-50 border-yellow-400' : 'bg-green-50 border-green-400') }}">
                                                                    <div class="flex items-center justify-between">
                                                                        <div>
                                                                            <div class="text-sm font-medium 
                                                                                        {{ $quantiteSaisie > $stockDisponible ? 'text-red-800' : 
                                                                                        ($pourcentageUtilise > 80 ? 'text-yellow-800' : 'text-green-800') }}">
                                                                                @if($quantiteSaisie > $stockDisponible)
                                                                                    🚫 Quantité excessive
                                                                                @elseif($pourcentageUtilise > 80)
                                                                                    ⚠️ Stock bientôt épuisé
                                                                                @else
                                                                                    ✅ Quantité valide
                                                                                @endif
                                                                            </div>
                                                                            <div class="text-xs mt-1 
                                                                                        {{ $quantiteSaisie > $stockDisponible ? 'text-red-700' : 
                                                                                        ($pourcentageUtilise > 80 ? 'text-yellow-700' : 'text-green-700') }}">
                                                                                Utilisation: {{ number_format($pourcentageUtilise, 1) }}% du stock
                                                                                ({{ number_format($stockDisponible - $quantiteSaisie, 2) }} {{ $unite }} restant)
                                                                            </div>
                                                                        </div>
                                                                        <div class="text-right">
                                                                            @if($quantiteSaisie > $stockDisponible)
                                                                                <span class="text-red-600 font-bold text-xl">❌</span>
                                                                            @elseif($pourcentageUtilise > 80)
                                                                                <span class="text-yellow-600 font-bold text-xl">⚠️</span>
                                                                            @else
                                                                                <span class="text-green-600 font-bold text-xl">✅</span>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        </div>

                                                        <!-- Purchase/Sale Price -->
                                                        <div>
                                                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                                                <span class="flex items-center">
                                                                    💵 Prix {{ $type === 'achat' ? 'd\'achat' : 'de vente' }} (unitaire) *
                                                                    <span class="ml-1 text-red-500">*</span>
                                                                </span>
                                                            </label>
                                                            <div class="relative">
                                                                <input wire:model.live="prix_unitaire_mga" type="number" step="0.01" placeholder="0"
                                                                    class="w-full px-4 py-3 pr-16 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                                                <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                                                                    <span class="text-blue-600 text-sm font-medium">MGA/{{ $unite ?? 'unité' }}</span>
                                                                </div>
                                                            </div>
                                                            @error('prix_unitaire_mga') 
                                                                <div class="mt-2 flex items-center text-red-600 text-sm">
                                                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                                                    </svg>
                                                                    {{ $message }}
                                                                </div>
                                                            @enderror
                                                        </div>
                                                    </div>

                                                    <!-- Calcul automatique du montant -->
                                                    @if($quantite && $prix_unitaire_mga)
                                                        @php
                                                            $montantCalcule = floatval($quantite) * floatval($prix_unitaire_mga);
                                                        @endphp
                                                        <div class="mt-4 p-4 bg-green-50 border border-green-200 rounded-lg">
                                                            <div class="flex items-center justify-between">
                                                                <div>
                                                                    <div class="font-semibold text-green-900">💰 Montant total calculé</div>
                                                                    <div class="text-sm text-green-700">{{ $quantite }} {{ $unite }} × {{ number_format($prix_unitaire_mga, 0) }} MGA</div>
                                                                </div>
                                                                <div class="text-right">  
                                                                    <div class="text-xl font-bold text-green-900">{{ number_format($montantCalcule, 0) }} MGA</div>
                                                                </div>
                                                            </div>

                                                        </div>
                                                    @endif
                                                @endif
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <!-- 2. SÉLECTION VOYAGE ET ÉLÉMENTS - SEULEMENT POUR VENTE -->
                                @if($type === 'vente')
                                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                                        <div class="bg-gradient-to-r from-purple-50 to-pink-50 px-6 py-4 border-b border-purple-100">
                                            <h4 class="text-lg font-semibold text-gray-900 flex items-center">
                                                <span class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center mr-3">
                                                    🚚
                                                </span>
                                                Voyage et Déchargements
                                            </h4>
                                        </div>
                                        
                                        <div class="p-6 space-y-6">
                                            <!-- VOYAGE SELECTION -->
                                            <div wire:key="voyage-select-{{ $type }}">
                                                <label class="block text-sm font-semibold text-gray-700 mb-3">
                                                    <span class="flex items-center">
                                                        🚗 Voyage concerné *
                                                        <span class="ml-1 text-red-500">*</span>
                                                    </span>
                                                </label>
                                                
                                                <select wire:model.live="voyage_id" 
                                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors">
                                                    <option value="">🔍 Sélectionner un voyage</option>
                                                    @forelse($voyagesDisponibles ?? [] as $voyage)
                                                        <option value="{{ $voyage->id }}">
                                                            🚛 {{ $voyage->reference }} 
                                                            ({{ $voyage->date->format('d/m/Y') }})
                                                            - ✅ {{ $voyage->dechargements->count() }} déchargement(s) disponible(s)
                                                        </option>
                                                    @empty
                                                        <option disabled>❌ Aucun voyage disponible</option>
                                                    @endforelse
                                                </select>
                                                
                                                @if(empty($voyagesDisponibles) || $voyagesDisponibles->isEmpty())
                                                    <div class="mt-4 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                                                        <div class="flex items-start">
                                                            <div class="flex-shrink-0">
                                                                <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                                                </svg>
                                                            </div>
                                                            <div class="ml-3">
                                                                <h3 class="text-sm font-medium text-yellow-800">
                                                                    🚫 Aucun voyage avec déchargements disponibles
                                                                </h3>
                                                                <div class="mt-2 text-sm text-yellow-700">
                                                                    <ul class="list-disc list-inside space-y-1">
                                                                        <li>Les voyages doivent avoir des déchargements (livraisons effectuées)</li>
                                                                        <li>Les déchargements ne doivent pas déjà être utilisés</li>
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                                
                                                @error('voyage_id') 
                                                    <div class="mt-2 flex items-center text-red-600 text-sm">
                                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                                        </svg>
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>

                                            <!-- DECHARGEMENTS - Pour vente seulement -->
                                            @if($voyage_id)
                                                <div wire:key="dechargements-{{ $voyage_id }}">
                                                    <label class="block text-sm font-semibold text-gray-700 mb-3">
                                                        <span class="flex items-center">
                                                            📦 Déchargements concernés *
                                                            <span class="ml-1 text-red-500">*</span>
                                                        </span>
                                                    </label>
                                                    
                                                    @php
                                                        $dechargementsDisponibles = $this->dechargementsDisponibles ?? collect();
                                                        $totalMontantVenteSelectionne = 0;
                                                        foreach($dechargement_ids as $id) {
                                                            $dech = $dechargementsDisponibles->firstWhere('id', $id);
                                                            if($dech) {
                                                                $totalMontantVenteSelectionne += $dech->montant_total_mga;
                                                            }
                                                        }
                                                    @endphp
                                                    
                                                    @if($dechargementsDisponibles->isNotEmpty())
                                                        <div class="max-h-40 overflow-y-auto border border-gray-200 rounded-lg bg-gray-50 p-4">
                                                            <div class="grid grid-cols-1 gap-3">
                                                                @foreach($dechargementsDisponibles as $dechargement)
                                                                    <label class="flex items-start space-x-3 p-3 bg-white rounded-lg border border-gray-200 hover:border-green-300 hover:bg-green-50 cursor-pointer transition-all">
                                                                        <input type="checkbox" wire:model.live="dechargement_ids" value="{{ $dechargement->id }}" 
                                                                               class="mt-1 w-4 h-4 text-green-600 border-gray-300 rounded focus:ring-green-500">
                                                                        <div class="flex-1">
                                                                            <div class="flex items-center justify-between">
                                                                                <div>
                                                                                    <div class="font-medium text-gray-900">{{ $dechargement->reference }}</div>
                                                                                    <div class="text-sm text-gray-600">{{ $dechargement->chargement->produit->nom_complet ?? $dechargement->chargement->produit->nom ?? 'N/A' }}</div>
                                                                                    @if($dechargement->interlocuteur_nom)
                                                                                        <div class="text-sm text-blue-600 flex items-center mt-1">
                                                                                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                                                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                                                                            </svg>
                                                                                            {{ $dechargement->interlocuteur_nom }}
                                                                                        </div>
                                                                                    @endif
                                                                                </div>
                                                                                <div class="text-right">
                                                                                    <div class="font-medium text-green-600">{{ number_format($dechargement->montant_total_mga, 0) }} MGA</div>
                                                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                                                        ✅ Disponible
                                                                                    </span>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </label>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                        
                                                        @if(!empty($dechargement_ids))
                                                            <div class="mt-4 p-4 bg-green-50 border border-green-200 rounded-lg">
                                                                <div class="flex items-center justify-between">
                                                                    <div>
                                                                        <div class="font-semibold text-green-900">Sélection actuelle</div>
                                                                        <div class="text-sm text-green-700">{{ count($dechargement_ids) }} déchargement(s) sélectionné(s)</div>
                                                                    </div>
                                                                    <div class="text-right">
                                                                        <div class="font-bold text-lg text-green-900">{{ number_format($totalMontantVenteSelectionne, 0) }} MGA</div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    @else
                                                        <div class="p-6 bg-red-50 border border-red-200 rounded-lg text-center">
                                                            <svg class="w-12 h-12 text-red-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                                                            </svg>
                                                            <p class="text-red-800 font-medium">❌ Aucun déchargement disponible</p>
                                                            <p class="text-red-600 text-sm mt-1">
                                                                Ce voyage n'a pas encore de déchargements ou ils sont déjà utilisés.
                                                            </p>
                                                        </div>
                                                    @endif
                                                    
                                                    @error('dechargement_ids') 
                                                        <div class="mt-2 flex items-center text-red-600 text-sm">
                                                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                                            </svg>
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                            @endif

                                            <!-- LIEUX AFFICHES - Auto-calculé pour ventes -->
                                            @if($lieux_display)
                                                <div>
                                                    <label class="block text-sm font-semibold text-gray-700 mb-3">
                                                        📍 Lieux de livraison (auto-calculé)
                                                    </label>
                                                    <div class="p-4 bg-gradient-to-r from-blue-50 to-purple-50 border border-blue-200 rounded-lg">
                                                        <div class="flex items-center">
                                                            <svg class="w-5 h-5 text-blue-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                                                            </svg>
                                                            <span class="font-medium text-blue-900">{{ $lieux_display }}</span>
                                                        </div>
                                                        <p class="text-sm text-blue-700 mt-1">
                                                            Lieux de destination des déchargements sélectionnés
                                                        </p>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            </div>
                            
                            <!-- Colonne droite - Résumé et finalisation -->
                            <div class="space-y-6">
                                <!-- MONTANT -->
                                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                                    <div class="bg-gradient-to-r from-green-50 to-emerald-50 px-6 py-4 border-b border-green-100">
                                        <h4 class="text-lg font-semibold text-gray-900 flex items-center">
                                            <span class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                                                💰
                                            </span>
                                            Montant
                                        </h4>
                                    </div>
                                    
                                    <div class="p-6">
                                        <label class="block text-sm font-semibold text-gray-700 mb-3">
                                            <span class="flex items-center justify-between">
                                                <span>Montant total (MGA) *</span>
                                                @if(in_array($type, ['vente', 'achat']) && !$editingTransaction)
                                                    <span class="text-xs text-blue-500 bg-blue-100 px-2 py-1 rounded-full">Auto-calculé</span>
                                                @else
                                                    <span class="text-xs text-orange-500 bg-orange-100 px-2 py-1 rounded-full">Saisie libre</span>
                                                @endif
                                            </span>
                                        </label>
                                        
                                        <div class="relative">
                                            <!-- Pour vente : affichage auto-calculé en lecture seule -->
                                            @if($type === 'vente' && !$editingTransaction)
                                                <div class="w-full px-4 py-3 text-xl font-bold border border-gray-300 rounded-lg bg-gray-50 text-green-700 min-h-[50px] flex items-center justify-between">
                                                    <span>{{ $montant_mga ? number_format($montant_mga, 0, ',', ' ') : '0' }}</span>
                                                    <span class="text-green-600 text-sm font-medium">MGA</span>
                                                </div>
                                                <input type="hidden" wire:model.live="montant_mga">
                                            @else
                                                <!-- Pour achats et autres types : champ éditable avec calcul automatique -->
                                                <input wire:model.live="montant_mga" 
                                                    type="number" 
                                                    step="0.01" 
                                                    placeholder="0"
                                                    @if($type === 'achat') 
                                                        class="w-full px-4 py-3 text-xl font-bold border border-green-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors bg-green-50"
                                                    @else
                                                        class="w-full px-4 py-3 text-xl font-bold border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors"
                                                    @endif>
                                                
                                                @if($montant_mga)
                                                    <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                                                        <span class="text-green-600 text-sm font-medium">MGA</span>
                                                    </div>
                                                @endif
                                            @endif
                                        </div>
                                        
                                        @if($montant_mga)
                                            <div class="mt-4 p-4 rounded-lg border-l-4 border-green-400 bg-green-50">
                                                <div class="text-sm text-green-800">
                                                    <div class="font-semibold">💰 Montant de la transaction</div>
                                                    <div class="mt-1">
                                                        <span class="font-bold text-green-700">{{ number_format($montant_mga, 0, ',', ' ') }} MGA</span>
                                                    </div>
                                                </div>
                                                
                                                @if($type === 'vente')
                                                    <button type="button" wire:click="recalculerMontant" 
                                                            class="mt-3 w-full px-4 py-2 bg-green-100 text-green-700 rounded-lg hover:bg-green-200 transition-colors text-sm font-medium flex items-center justify-center">
                                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                                        </svg>
                                                        Recalculer le montant
                                                    </button>
                                                @elseif($type === 'achat')
                                                    <div class="mt-3 text-xs text-green-600 text-center">
                                                        ✅ Montant calculé automatiquement : {{ $quantite ?? '0' }} {{ $unite ?? 'unité' }} × {{ number_format($prix_unitaire_mga ?? 0, 0, ',', ' ') }} MGA
                                                    </div>
                                                @endif
                                            </div>
                                        @else
                                            <div class="mt-4 p-4 rounded-lg border border-gray-200 bg-gray-50">
                                                <div class="text-sm text-gray-600 text-center">
                                                    @if($type === 'vente')
                                                        🔄 Sélectionnez des déchargements pour calculer automatiquement le montant
                                                    @elseif($type === 'achat')
                                                        📦 Sélectionnez un produit, une quantité et un prix pour calculer automatiquement le montant
                                                    @else
                                                        💰 Saisissez le montant de la transaction
                                                    @endif
                                                </div>
                                            </div>
                                        @endif
                                        
                                        @error('montant_mga') 
                                            <div class="mt-2 flex items-center text-red-600 text-sm bg-red-50 p-2 rounded">
                                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                                </svg>
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- PARTICIPANTS -->
                                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                                    <div class="bg-gradient-to-r from-yellow-50 to-orange-50 px-6 py-4 border-b border-yellow-100">
                                        <h4 class="text-lg font-semibold text-gray-900 flex items-center">
                                            <span class="w-8 h-8 bg-yellow-100 rounded-lg flex items-center justify-center mr-3">
                                                👥
                                            </span>
                                            Participants
                                        </h4>
                                    </div>
                                    
                                    <div class="p-6 space-y-4">
                                        <div>
                                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                                <span class="flex items-center justify-between">
                                                    <span>💸 Qui donne l'argent</span>
                                                    @if($type === 'vente')
                                                        <span class="text-xs text-blue-500 bg-blue-100 px-2 py-1 rounded-full">Auto-rempli</span>
                                                    @else
                                                        <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded-full">Saisie libre</span>
                                                    @endif
                                                </span>
                                            </label>
                                            
                                            @if($type === 'vente')
                                                <div class="p-3 bg-gray-50 border border-gray-200 rounded-lg text-gray-900 min-h-[50px] flex items-center">
                                                    <span class="font-medium">{{ $from_nom ?: 'En attente de sélection...' }}</span>
                                                </div>
                                                <input type="hidden" wire:model="from_nom">
                                            @else
                                                <input wire:model.live="from_nom" type="text" placeholder="Ex: Jean Dupont, Société ABC..." 
                                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 transition-colors">
                                            @endif
                                            
                                            <p class="mt-2 text-xs text-gray-500">
                                                @if($type === 'vente')
                                                    Auto-rempli avec les interlocuteurs des déchargements
                                                @else
                                                    Saisie libre - peut être externe au système
                                                @endif
                                            </p>
                                        </div>

                                        <div>
                                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                                💰 Qui reçoit l'argent
                                            </label>
                                            <input wire:model="to_nom" type="text" placeholder="Ex: Marie Martin, Votre entreprise..." 
                                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 transition-colors">
                                            <p class="mt-2 text-xs text-gray-500">
                                                Saisie libre - peut être externe au système
                                            </p>
                                        </div>

                                        <!-- COMPTE DE DESTINATION - SEULEMENT SI PAS ESPÈCES -->
                                        @if($mode_paiement !== 'especes')
                                            <div>
                                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                                    🏦 Compte de destination
                                                </label>
                                                <input wire:model="to_compte" type="text" placeholder="Ex: 034 12 345 67, nom du compte..." 
                                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 transition-colors">
                                                <p class="mt-2 text-xs text-gray-500">
                                                    @if($mode_paiement === 'banque')
                                                        Numéro de compte bancaire ou nom de la banque
                                                    @elseif($mode_paiement === 'AirtelMoney')
                                                        Numéro AirtelMoney de destination
                                                    @elseif($mode_paiement === 'MVola')
                                                        Numéro MVola de destination
                                                    @elseif($mode_paiement === 'OrangeMoney')
                                                        Numéro OrangeMoney de destination
                                                    @else
                                                        Informations du compte de destination
                                                    @endif
                                                </p>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <!-- MODALITÉS DE PAIEMENT -->
                                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                                    <div class="bg-gradient-to-r from-purple-50 to-indigo-50 px-6 py-4 border-b border-purple-100">
                                        <h4 class="text-lg font-semibold text-gray-900 flex items-center">
                                            <span class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center mr-3">
                                                💳
                                            </span>
                                            Modalités
                                        </h4>
                                    </div>
                                    
                                    <div class="p-6 space-y-4">
                                        <div>
                                            <label class="block text-sm font-semibold text-gray-700 mb-2">Mode de paiement *</label>
                                            <select wire:model.live="mode_paiement" 
                                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors">
                                                <option value="especes">💵 Espèces</option>
                                                <option value="AirtelMoney">📱 AirtelMoney</option>
                                                <option value="MVola">📱 MVola</option>
                                                <option value="OrangeMoney">📱 OrangeMoney</option>
                                                <option value="banque">🏦 Banque</option>
                                            </select>
                                            @error('mode_paiement') 
                                                <div class="mt-2 flex items-center text-red-600 text-sm">
                                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                                    </svg>
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>

                                        <div>
                                            <label class="block text-sm font-semibold text-gray-700 mb-2">Statut de la transaction *</label>
                                            <select wire:model.live="statut" 
                                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors">
                                                <option value="confirme">✅ Confirmé</option>
                                                <option value="attente">⏳ En attente</option>
                                                <option value="partiellement_payee">⚠️ Paiement partiel</option>
                                                <option value="annule">❌ Annulé</option>
                                            </select>
                                            @error('statut') 
                                                <div class="mt-2 flex items-center text-red-600 text-sm">
                                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                                    </svg>
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>

                                        <!-- RESTE À PAYER - SEULEMENT SI PAIEMENT PARTIEL -->
                                        @if($statut === 'partiellement_payee')
                                            <div>
                                                <label class="block text-sm font-semibold text-gray-700 mb-2">💰 Reste à payer (MGA) *</label>
                                                <input wire:model="reste_a_payer" type="number" step="0.01" 
                                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors">
                                                @error('reste_a_payer') 
                                                    <div class="mt-2 flex items-center text-red-600 text-sm">
                                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                                        </svg>
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                                <p class="mt-2 text-xs text-gray-500">Montant restant à payer pour cette transaction</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <!-- OBSERVATIONS -->
                                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                                    <div class="bg-gradient-to-r from-gray-50 to-slate-50 px-6 py-4 border-b border-gray-100">
                                        <h4 class="text-lg font-semibold text-gray-900 flex items-center">
                                            <span class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center mr-3">
                                                📝
                                            </span>
                                            Notes
                                        </h4>
                                    </div>
                                    
                                    <div class="p-6">
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">Observations</label>
                                        <textarea wire:model="observation" rows="4" 
                                                    placeholder="Notes supplémentaires, commentaires en français ou malagasy..."
                                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-500 focus:border-gray-500 transition-colors resize-none"></textarea>
                                        <p class="mt-2 text-xs text-gray-500">Informations complémentaires sur cette transaction</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Footer avec actions -->
                    <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                        <div class="flex flex-col sm:flex-row-reverse sm:space-x-reverse sm:space-x-3 space-y-3 sm:space-y-0">
                            <button type="submit" 
                                    class="w-full sm:w-auto inline-flex justify-center items-center px-6 py-3 border border-transparent text-base font-medium rounded-lg text-white bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 shadow-lg transition-all">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                {{ $editingTransaction ? '💾 Enregistrer les modifications' : '✨ Créer la transaction' }}
                            </button>
                            <button type="button" wire:click="closeTransactionModal" 
                                    class="w-full sm:w-auto inline-flex justify-center items-center px-6 py-3 border border-gray-300 text-base font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                                Annuler
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endif
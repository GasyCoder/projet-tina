{{-- resources/views/livewire/voyage/modals/chargement-modal.blade.php --}}
@if($showChargementModal)
    <div class="fixed inset-0 z-50 overflow-y-auto" x-data="{ loading: false }">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!-- Overlay avec animation -->
            <div class="fixed inset-0 bg-gray-900 bg-opacity-50 dark:bg-opacity-70 backdrop-blur-sm transition-opacity" 
                 wire:click="closeChargementModal"></div>
            
            <!-- Modal Container -->
            <div class="inline-block align-middle bg-white dark:bg-gray-800 rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:max-w-4xl sm:w-full border border-gray-100 dark:border-gray-700">
                
                <!-- Header avec gradient -->
                <div class="bg-gradient-to-r from-blue-600 to-indigo-700 px-6 py-5">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 bg-white bg-opacity-20 rounded-xl flex items-center justify-center">
                                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                    </svg>
                                </div>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-white">
                                    {{ $editingChargement ? '✏️ Modifier le chargement' : '📦 Nouveau chargement' }}
                                </h3>
                                <p class="text-blue-100 text-sm mt-1">
                                    {{ $editingChargement ? 'Modifiez les détails du chargement' : 'Ajoutez un nouveau chargement au voyage' }}
                                </p>
                            </div>
                        </div>
                        <button wire:click="closeChargementModal" 
                                class="text-white hover:bg-white hover:bg-opacity-20 rounded-lg p-2 transition-all duration-200">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <form wire:submit="saveChargement" @submit="loading = true">
                    <div class="bg-gray-50 dark:bg-gray-700 px-6 py-6">
                        
                        <!-- Section 1: Informations générales -->
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden mb-6">
                            <div class="bg-gradient-to-r from-gray-50 dark:from-gray-700 to-blue-50 dark:to-gray-600 px-5 py-4 border-b border-gray-100 dark:border-gray-600">
                                <h4 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                                    <span class="w-8 h-8 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center mr-3">
                                        ℹ️
                                    </span>
                                    Informations générales
                                </h4>
                            </div>
                            
                            <div class="p-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <!-- Référence -->
                                    <div class="space-y-2">
                                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                                            🏷️ Référence *
                                        </label>
                                        <input wire:model="chargement_reference" 
                                               type="text" 
                                               class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors bg-gray-50 dark:bg-gray-700 dark:text-white"
                                               readonly>
                                        @error('chargement_reference') 
                                            <p class="mt-1 text-sm text-red-600 dark:text-red-400 flex items-center">
                                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                                </svg>
                                                {{ $message }}
                                            </p>
                                        @enderror
                                    </div>

                                    <!-- Date -->
                                    <div class="space-y-2">
                                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                                            📅 Date de chargement *
                                        </label>
                                        <input wire:model="date" 
                                               type="date" 
                                               class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors dark:bg-gray-700 dark:text-white">
                                        @error('date') 
                                            <p class="mt-1 text-sm text-red-600 dark:text-red-400 flex items-center">
                                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                                </svg>
                                                {{ $message }}
                                            </p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Section 2: Propriétaire -->
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden mb-6">
                            <div class="bg-gradient-to-r from-yellow-50 dark:from-yellow-900/20 to-orange-50 dark:to-orange-900/20 px-5 py-4 border-b border-yellow-100 dark:border-yellow-900/30">
                                <h4 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                                    <span class="w-8 h-8 bg-yellow-100 dark:bg-yellow-900 rounded-lg flex items-center justify-center mr-3">
                                        👤
                                    </span>
                                    Propriétaire des marchandises
                                </h4>
                            </div>
                            
                            <div class="p-6">
                                <!-- Radio buttons pour le type de propriétaire -->
                                <div class="flex flex-col sm:flex-row gap-4 mb-6">
                                    <label class="flex items-center cursor-pointer group">
                                        <input type="radio" 
                                               name="type_proprietaire"
                                               value="defaut"
                                               wire:click="setTypeProprietaire('defaut')"
                                               @if($type_proprietaire === 'defaut') checked @endif
                                               class="w-4 h-4 text-blue-600 border-gray-300 dark:border-gray-600 focus:ring-blue-500 dark:bg-gray-700">
                                        <div class="ml-3 flex items-center">
                                            <span class="inline-flex items-center px-3 py-2 rounded-full text-sm font-medium bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-200 group-hover:bg-yellow-200 dark:group-hover:bg-yellow-800/40 transition-colors">
                                                🏢 Propriétaire par défaut
                                            </span>
                                        </div>
                                    </label>
                                    
                                    <label class="flex items-center cursor-pointer group">
                                        <input type="radio" 
                                               name="type_proprietaire"
                                               value="autre"
                                               wire:click="setTypeProprietaire('autre')"
                                               @if($type_proprietaire === 'autre') checked @endif
                                               class="w-4 h-4 text-blue-600 border-gray-300 dark:border-gray-600 focus:ring-blue-500 dark:bg-gray-700">
                                        <div class="ml-3 flex items-center">
                                            <span class="inline-flex items-center px-3 py-2 rounded-full text-sm font-medium bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-200 group-hover:bg-green-200 dark:group-hover:bg-green-800/40 transition-colors">
                                                ✏️ Autre propriétaire
                                            </span>
                                        </div>
                                    </label>
                                </div>

                                <!-- Champs propriétaire conditionnels -->
                                @if($type_proprietaire === 'defaut')
                                    <div class="p-4 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0">
                                                <svg class="w-8 h-8 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                                </svg>
                                            </div>
                                            <div class="ml-3">
                                                <p class="text-sm font-medium text-yellow-800 dark:text-yellow-200">Mme TINAH</p>
                                                <p class="text-sm text-yellow-700 dark:text-yellow-300">📞 0349045769</p>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div class="space-y-2">
                                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                                                👤 Nom du propriétaire *
                                            </label>
                                            <input wire:model="proprietaire_nom"
                                                   type="text"
                                                   placeholder="Ex: Rakoto Jean, Société HERY..."
                                                   class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors dark:bg-gray-700 dark:text-white">
                                            @error('proprietaire_nom') 
                                                <p class="mt-1 text-sm text-red-600 dark:text-red-400 flex items-center">
                                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                                    </svg>
                                                    {{ $message }}
                                                </p>
                                            @enderror
                                        </div>

                                        <div class="space-y-2">
                                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                                                📞 Contact propriétaire
                                            </label>
                                            <input wire:model="proprietaire_contact"
                                                   type="text"
                                                   placeholder="Téléphone, email..."
                                                   class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors dark:bg-gray-700 dark:text-white">
                                            @error('proprietaire_contact') 
                                                <p class="mt-1 text-sm text-red-600 dark:text-red-400 flex items-center">
                                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                                    </svg>
                                                    {{ $message }}
                                                </p>
                                            @enderror
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Section 3: Produit et lieu -->
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden mb-6">
                            <div class="bg-gradient-to-r from-purple-50 dark:from-purple-900/20 to-pink-50 dark:to-pink-900/20 px-5 py-4 border-b border-purple-100 dark:border-purple-900/30">
                                <h4 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                                    <span class="w-8 h-8 bg-purple-100 dark:bg-purple-900 rounded-lg flex items-center justify-center mr-3">
                                        📦
                                    </span>
                                    Produit et lieu de chargement
                                </h4>
                            </div>
                            
                            <div class="p-6">
                                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                    <!-- Produit -->
                                    <div class="space-y-4">
                                        <div class="space-y-2">
                                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                                                🛍️ Produit à charger *
                                            </label>
                                            <select wire:model.live="produit_id" 
                                                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors dark:bg-gray-700 dark:text-white">
                                                <option value="">🔍 Sélectionner un produit</option>
                                                @forelse($this->produitsDisponibles as $produit)
                                                    <option value="{{ $produit->id }}">
                                                        {{ $produit->nom }}{{ $produit->variete ? ' (' . $produit->variete . ')' : '' }}
                                                        - Stock: {{ $produit->stock_formate }}
                                                    </option>
                                                @empty
                                                    <option disabled>❌ Aucun produit avec stock disponible</option>
                                                @endforelse
                                            </select>
                                            @error('produit_id') 
                                                <p class="mt-1 text-sm text-red-600 dark:text-red-400 flex items-center">
                                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                                    </svg>
                                                    {{ $message }}
                                                </p>
                                            @enderror
                                        </div>

                                        <!-- Info produit sélectionné -->
                                        @if($this->produitSelectionne)
                                            <div class="p-4 bg-purple-50 dark:bg-purple-900/20 border border-purple-200 dark:border-purple-800 rounded-lg">
                                                <h5 class="text-sm font-semibold text-purple-900 dark:text-purple-200 mb-3">📊 Informations du produit</h5>
                                                <div class="space-y-2 text-sm">
                                                    <div class="flex justify-between">
                                                        <span class="text-purple-600 dark:text-purple-400">Produit:</span>
                                                        <span class="font-medium text-purple-900 dark:text-purple-100">{{ $this->produitSelectionne->nom_complet }}</span>
                                                    </div>
                                                    <div class="flex justify-between">
                                                        <span class="text-purple-600 dark:text-purple-400">Stock disponible:</span>
                                                        <span class="font-bold text-purple-900 dark:text-purple-100">{{ $this->produitSelectionne->stock_formate }}</span>
                                                    </div>
                                                    <div class="flex justify-between">
                                                        <span class="text-purple-600 dark:text-purple-400">Unité:</span>
                                                        <span class="font-medium text-purple-900 dark:text-purple-100">{{ ucfirst($this->produitSelectionne->unite) }}</span>
                                                    </div>
                                                </div>
                                                <div class="mt-3 p-2 bg-purple-100 dark:bg-purple-800/40 rounded text-xs text-purple-800 dark:text-purple-200">
                                                    💡 <strong>Important:</strong> Le stock sera automatiquement déduit lors du chargement.
                                                </div>
                                            </div>
                                        @endif

                                        <!-- Message si aucun produit -->
                                        @if($this->produitsDisponibles->isEmpty())
                                            <div class="p-4 bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-lg">
                                                <div class="flex items-start">
                                                    <svg class="w-5 h-5 text-amber-400 mr-3 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                                    </svg>
                                                    <div>
                                                        <h3 class="text-sm font-medium text-amber-800 dark:text-amber-200">
                                                            🚫 Aucun produit disponible
                                                        </h3>
                                                        <div class="mt-1 text-sm text-amber-700 dark:text-amber-300">
                                                            Effectuez d'abord des achats pour avoir des produits à charger.
                                                        </div>
                                                        <div class="mt-2">
                                                            <a href="{{ route('finance.index', ['tab' => 'transactions']) }}" 
                                                               class="text-sm text-blue-600 dark:text-blue-400 hover:text-blue-500 font-medium">
                                                                → Aller aux transactions d'achat
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Lieu et chargeur -->
                                    <div class="space-y-4">
                                        <div class="space-y-2">
                                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                                                📍 Lieu de chargement *
                                            </label>
                                            <select wire:model="depart_id" 
                                                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors dark:bg-gray-700 dark:text-white">
                                                <option value="">🔍 Sélectionner un lieu</option>
                                                @foreach($departs as $depart)
                                                    <option value="{{ $depart->id }}">{{ $depart->nom }} ({{ $depart->region }})</option>
                                                @endforeach
                                            </select>
                                            @error('depart_id') 
                                                <p class="mt-1 text-sm text-red-600 dark:text-red-400 flex items-center">
                                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                                    </svg>
                                                    {{ $message }}
                                                </p>
                                            @enderror
                                        </div>

                                        <div class="space-y-2">
                                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                                                👷 Nom du chargeur *
                                            </label>
                                            <input wire:model="chargeur_nom"
                                                   type="text"
                                                   placeholder="Ex: Rakoto, SARL HERY..."
                                                   class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors dark:bg-gray-700 dark:text-white">
                                            @error('chargeur_nom') 
                                                <p class="mt-1 text-sm text-red-600 dark:text-red-400 flex items-center">
                                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                                    </svg>
                                                    {{ $message }}
                                                </p>
                                            @enderror
                                        </div>

                                        <div class="space-y-2">
                                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                                                📞 Contact chargeur
                                            </label>
                                            <input wire:model="chargeur_contact"
                                                   type="text"
                                                   placeholder="Téléphone, email..."
                                                   class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors dark:bg-gray-700 dark:text-white">
                                            @error('chargeur_contact') 
                                                <p class="mt-1 text-sm text-red-600 dark:text-red-400 flex items-center">
                                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                                    </svg>
                                                    {{ $message }}
                                                </p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Section 4: Quantités -->
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 mb-6">
                            <!-- Header -->
                            <div class="px-4 py-3 border-b border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-700">
                                <h4 class="text-base font-semibold text-gray-800 dark:text-white flex items-center">
                                    <span class="mr-2">⚖️</span>
                                    Quantités et poids
                                </h4>
                            </div>

                            <!-- Content -->
                            <div class="p-5">
                                <!-- Stock épuisé alert -->
                                @if($this->produitSelectionne && $this->limiteSacs && $this->limiteSacs['stock_disponible'] <= 0)
                                    <div class="mb-5 p-3 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-md">
                                        <div class="flex items-center">
                                            <svg class="w-5 h-5 text-red-500 dark:text-red-400 mr-2" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                            </svg>
                                            <p class="text-sm text-red-700 dark:text-red-300">
                                                Stock épuisé. Sélectionnez un autre produit ou approvisionnez le stock.
                                            </p>
                                        </div>
                                    </div>
                                @endif

                                <!-- Limite de stock -->
                                @if($this->produitSelectionne && $this->limiteSacs && $this->limiteSacs['stock_disponible'] > 0)
                                    <div class="mb-5 p-3 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-md">
                                        <h5 class="text-sm font-semibold text-blue-800 dark:text-blue-200">
                                            Limite pour {{ $this->produitSelectionne->nom_complet }}
                                        </h5>
                                        <div class="mt-2 grid grid-cols-1 sm:grid-cols-3 gap-3 text-sm">
                                            <div class="text-center p-2 bg-white dark:bg-gray-700 rounded border border-blue-100 dark:border-blue-800">
                                                <div class="font-semibold text-blue-700 dark:text-blue-300">{{ number_format($this->limiteSacs['stock_disponible'], 2) }}</div>
                                                <div class="text-blue-600 dark:text-blue-400 text-xs">{{ $this->limiteSacs['produit_unite'] ?: 'kg' }} en stock</div>
                                            </div>
                                            @if($this->produitSelectionne->unite === 'sacs')
                                                <div class="text-center p-2 bg-white dark:bg-gray-700 rounded border border-blue-100 dark:border-blue-800">
                                                    <div class="font-semibold text-blue-700 dark:text-blue-300">{{ number_format($this->limiteSacs['max_sacs'], 1) }}</div>
                                                    <div class="text-blue-600 dark:text-blue-400 text-xs">Sacs max</div>
                                                </div>
                                                <div class="text-center p-2 bg-white dark:bg-gray-700 rounded border border-blue-100 dark:border-blue-800">
                                                    <div class="font-semibold text-blue-700 dark:text-blue-300">{{ $this->limiteSacs['poids_moyen_sac'] }} kg</div>
                                                    <div class="text-blue-600 dark:text-blue-400 text-xs">Poids/sac</div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endif

                                <!-- Input fields -->
                                @if($this->produitSelectionne)
                                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                        @if($this->produitSelectionne->unite === 'sacs')
                                            <!-- Sacs pleins -->
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                                    Sacs pleins *
                                                    @if($this->limiteSacs && $this->limiteSacs['stock_disponible'] > 0)
                                                        <span class="text-xs text-gray-500 dark:text-gray-400">(Max: {{ number_format($this->limiteSacs['max_sacs'], 1) }})</span>
                                                    @endif
                                                </label>
                                                <div class="relative">
                                                    <input wire:model.live="sacs_pleins_depart" 
                                                        type="number" 
                                                        min="0"
                                                        @if($this->limiteSacs && $this->limiteSacs['stock_disponible'] > 0)
                                                            max="{{ floor($this->limiteSacs['max_sacs']) }}"
                                                        @else
                                                            disabled
                                                        @endif
                                                        step="1"
                                                        placeholder="0"
                                                        class="w-full px-3 py-2 border {{ $errors->has('sacs_pleins_depart') ? 'border-red-300 bg-red-50 dark:bg-red-900/20' : 'border-gray-300 dark:border-gray-600' }} rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 disabled:opacity-50 disabled:cursor-not-allowed transition-colors dark:bg-gray-700 dark:text-white"
                                                        aria-describedby="sacs-pleins-error">
                                                    @if($this->limiteSacs && $this->limiteSacs['stock_disponible'] > 0 && $sacs_pleins_depart)
                                                        @php
                                                            $totalSacsSaisis = floatval($sacs_pleins_depart ?: 0) + (floatval($sacs_demi_depart ?: 0) * 0.5);
                                                            $pourcentageUtilise = $this->limiteSacs['max_sacs'] > 0 ? ($totalSacsSaisis / $this->limiteSacs['max_sacs']) * 100 : 0;
                                                        @endphp
                                                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                                            @if($pourcentageUtilise > 100)
                                                                <span class="text-red-500" title="Dépassement">🚫</span>
                                                            @elseif($pourcentageUtilise > 90)
                                                                <span class="text-yellow-500" title="Proche de la limite">⚠️</span>
                                                            @else
                                                                <span class="text-green-500" title="Dans les limites">✅</span>
                                                            @endif
                                                        </div>
                                                    @endif
                                                </div>
                                                @error('sacs_pleins_depart')
                                                    <div class="mt-1 text-sm text-red-600 dark:text-red-400 flex items-center" id="sacs-pleins-error">
                                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                                        </svg>
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>

                                            <!-- Sacs demi -->
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                                    Sacs demi
                                                </label>
                                                <input wire:model.live="sacs_demi_depart" 
                                                    type="number" 
                                                    min="0"
                                                    max="1"
                                                    step="1"
                                                    placeholder="0"
                                                    @if(!$this->produitSelectionne || ($this->limiteSacs && $this->limiteSacs['stock_disponible'] <= 0)) disabled @endif
                                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 disabled:opacity-50 disabled:cursor-not-allowed transition-colors dark:bg-gray-700 dark:text-white"
                                                    aria-describedby="sacs-demi-error">
                                                @error('sacs_demi_depart')
                                                    <div class="mt-1 text-sm text-red-600 dark:text-red-400 flex items-center" id="sacs-demi-error">
                                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                                        </svg>
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        @else
                                            <!-- Poids total -->
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                                    Poids total (kg) *
                                                </label>
                                                <input wire:model.live="poids_depart_kg" 
                                                    type="number" 
                                                    step="0.01"
                                                    min="0"
                                                    placeholder="0.00"
                                                    @if(!$this->produitSelectionne || ($this->limiteSacs && $this->limiteSacs['stock_disponible'] <= 0)) disabled @endif
                                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 disabled:opacity-50 disabled:cursor-not-allowed transition-colors dark:bg-gray-700 dark:text-white"
                                                    aria-describedby="poids-depart-error">
                                                @error('poids_depart_kg')
                                                    <div class="mt-1 text-sm text-red-600 dark:text-red-400 flex items-center" id="poids-depart-error">
                                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                                        </svg>
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        @endif
                                    </div>
                                @endif

                                <!-- Progression indicator -->
                                @if($this->produitSelectionne && $this->produitSelectionne->unite === 'sacs' && $this->limiteSacs && $this->limiteSacs['stock_disponible'] > 0 && $sacs_pleins_depart && !$errors->has('sacs_pleins_depart'))
                                    @php
                                        $totalSacsSaisis = floatval($sacs_pleins_depart ?: 0) + (floatval($sacs_demi_depart ?: 0) * 0.5);
                                        $pourcentageUtilise = $this->limiteSacs['max_sacs'] > 0 ? ($totalSacsSaisis / $this->limiteSacs['max_sacs']) * 100 : 0;
                                        $sacRestants = $this->limiteSacs['max_sacs'] - $totalSacsSaisis;
                                    @endphp
                                    <div class="mt-4 p-3 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-md">
                                        <div class="flex items-center justify-between text-sm mb-2">
                                            <span class="text-gray-600 dark:text-gray-300">Utilisation: {{ number_format($pourcentageUtilise, 1) }}%</span>
                                            <span class="text-gray-600 dark:text-gray-300">Reste: {{ number_format($sacRestants, 1) }} sacs</span>
                                        </div>
                                        <div class="w-full bg-gray-200 dark:bg-gray-600 rounded-full h-2">
                                            <div class="h-2 rounded-full {{ $pourcentageUtilise > 90 ? 'bg-red-500' : ($pourcentageUtilise > 70 ? 'bg-yellow-500' : 'bg-blue-500') }}" 
                                                style="width: {{ min(100, $pourcentageUtilise) }}%"></div>
                                        </div>
                                        <div class="mt-1 text-xs text-gray-600 dark:text-gray-400">
                                            ≈ {{ number_format($totalSacsSaisis * $this->limiteSacs['poids_moyen_sac'], 2) }} kg / {{ number_format($this->limiteSacs['stock_disponible'], 2) }} {{ $this->limiteSacs['produit_unite'] }}
                                        </div>
                                    </div>
                                @endif

                                <!-- Résumé du chargement -->
                                @if(($sacs_pleins_depart || $sacs_demi_depart || $poids_depart_kg) && $this->produitSelectionne)
                                    @php
                                        $totalSacs = floatval($sacs_pleins_depart ?: 0) + (floatval($sacs_demi_depart ?: 0) * 0.5);
                                    @endphp
                                    <div class="mt-5 p-3 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-md">
                                        <h5 class="text-sm font-semibold text-gray-800 dark:text-white mb-3 flex items-center justify-between">
                                            Résumé du chargement
                                            @if($this->limiteSacs && $this->limiteSacs['stock_disponible'] > 0)
                                                <span class="text-xs {{ $this->produitSelectionne->unite === 'sacs' && $totalSacs <= $this->limiteSacs['max_sacs'] ? 'text-green-600 dark:text-green-400' : ($this->produitSelectionne->unite === 'sacs' ? 'text-red-600 dark:text-red-400' : '') }}">
                                                    Impact stock: -{{ number_format($this->produitSelectionne->unite === 'sacs' ? $totalSacs : $poids_depart_kg, 2) }} {{ $this->limiteSacs['produit_unite'] ?: 'kg' }}
                                                </span>
                                            @endif
                                        </h5>
                                        <div class="grid grid-cols-1 sm:grid-cols-4 gap-3 text-sm">
                                            @if($this->produitSelectionne->unite === 'sacs')
                                                <div class="text-center p-2 bg-white dark:bg-gray-800 rounded border border-gray-100 dark:border-gray-700">
                                                    <div class="font-semibold text-gray-700 dark:text-gray-300">{{ $sacs_pleins_depart ?: 0 }}</div>
                                                    <div class="text-gray-600 dark:text-gray-400 text-xs">Sacs pleins</div>
                                                </div>
                                                <div class="text-center p-2 bg-white dark:bg-gray-800 rounded border border-gray-100 dark:border-gray-700">
                                                    <div class="font-semibold text-gray-700 dark:text-gray-300">{{ $sacs_demi_depart ?: 0 }}</div>
                                                    <div class="text-gray-600 dark:text-gray-400 text-xs">Sacs demi</div>
                                                </div>
                                                <div class="text-center p-2 bg-white dark:bg-gray-800 rounded border border-gray-100 dark:border-gray-700">
                                                    <div class="font-semibold text-gray-700 dark:text-gray-300">{{ number_format($totalSacs, 1) }}</div>
                                                    <div class="text-gray-600 dark:text-gray-400 text-xs">Total sacs</div>
                                                </div>
                                            @endif
                                            <div class="text-center p-2 bg-white dark:bg-gray-800 rounded border border-gray-100 dark:border-gray-700">
                                                <div class="font-semibold text-gray-700 dark:text-gray-300">{{ $poids_depart_kg ? number_format($poids_depart_kg, 2) : '0.00' }}</div>
                                                <div class="text-gray-600 dark:text-gray-400 text-xs">Kg total</div>
                                            </div>
                                        </div>
                                        @if($this->produitSelectionne->unite === 'sacs' && $this->limiteSacs && $this->limiteSacs['stock_disponible'] > 0 && $totalSacs > $this->limiteSacs['max_sacs'])
                                            <div class="mt-3 p-2 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-md flex items-center justify-between">
                                                <div class="text-sm text-red-700 dark:text-red-300">
                                                    Dépassement de {{ number_format($totalSacs - $this->limiteSacs['max_sacs'], 1) }} sacs
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                @endif

                                <!-- Message when no product is selected -->
                                @if(!$this->produitSelectionne)
                                    <div class="mt-4 p-4 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-md text-center">
                                        <p class="text-sm text-gray-600 dark:text-gray-400">
                                            Sélectionnez un produit pour voir les limites de chargement et saisir les quantités.
                                        </p>
                                    </div>
                                @endif
                            </div>
                        </div>


                        <!-- Section 5: Observations -->
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                            <div class="bg-gradient-to-r from-gray-50 dark:from-gray-700 to-slate-50 dark:to-gray-600 px-5 py-4 border-b border-gray-100 dark:border-gray-600">
                                <h4 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                                    <span class="w-8 h-8 bg-gray-100 dark:bg-gray-700 rounded-lg flex items-center justify-center mr-3">
                                        📝
                                    </span>
                                    Observations (optionnel)
                                </h4>
                            </div>
                            
                            <div class="p-6">
                                <textarea wire:model="chargement_observation" 
                                          rows="4" 
                                          placeholder="Remarques, conditions particulières, qualité du produit..."
                                          class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-gray-500 focus:border-gray-500 transition-colors resize-none dark:bg-gray-700 dark:text-white"></textarea>
                                @error('chargement_observation') 
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <!-- Footer avec actions -->
                    <div class="bg-gray-100 dark:bg-gray-700 px-6 py-4 border-t border-gray-200 dark:border-gray-600">
                        <div class="flex flex-col sm:flex-row-reverse sm:space-x-reverse sm:space-x-3 space-y-3 sm:space-y-0">
                            <button type="submit" 
                                    wire:click="saveChargement"
                                    wire:loading.attr="disabled"
                                    wire:loading.class="opacity-50 cursor-not-allowed"
                                    class="w-full sm:w-auto inline-flex justify-center items-center px-6 py-3 border border-transparent text-base font-medium rounded-lg text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 shadow-lg transition-all">
                                <span wire:loading.remove class="flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    {{ $editingChargement ? '💾 Enregistrer les modifications' : '✨ Créer le chargement' }}
                                </span>
                                <span wire:loading class="flex items-center">
                                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Enregistrement...
                                </span>
                            </button>
                            <button type="button" 
                                    wire:click="closeChargementModal" 
                                    wire:loading.attr="disabled"
                                    wire:loading.class="opacity-50 cursor-not-allowed"
                                    class="w-full sm:w-auto inline-flex justify-center items-center px-6 py-3 border border-gray-300 dark:border-gray-600 text-base font-medium rounded-lg text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors">
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
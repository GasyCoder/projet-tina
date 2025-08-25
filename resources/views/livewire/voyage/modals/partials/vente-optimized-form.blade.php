{{-- resources/views/livewire/voyage/modals/partials/vente-optimized-form.blade.php --}}
<form wire:submit="saveDechargement">
    <div class="px-6 pt-6 pb-4">
        
        {{-- Étapes du formulaire --}}
        <div class="mb-6">
            <nav aria-label="Progress">
                <ol role="list" class="flex items-center">
                    <li class="relative flex-1">
                        <div class="flex items-center">
                            <div class="relative flex h-8 w-8 items-center justify-center rounded-full bg-blue-600">
                                <span class="text-sm font-medium text-white">1</span>
                            </div>
                            <div class="ml-4 min-w-0 flex-1">
                                <span class="text-sm font-medium text-blue-600">Informations client</span>
                            </div>
                        </div>
                    </li>
                    <li class="relative flex-1">
                        <div class="flex items-center">
                            <div class="relative flex h-8 w-8 items-center justify-center rounded-full {{ $chargement_id ? 'bg-blue-600' : 'bg-gray-300' }}">
                                <span class="text-sm font-medium {{ $chargement_id ? 'text-white' : 'text-gray-500' }}">2</span>
                            </div>
                            <div class="ml-4 min-w-0 flex-1">
                                <span class="text-sm font-medium {{ $chargement_id ? 'text-blue-600' : 'text-gray-500' }}">Produit</span>
                            </div>
                        </div>
                    </li>
                    <li class="relative flex-1">
                        <div class="flex items-center">
                            <div class="relative flex h-8 w-8 items-center justify-center rounded-full {{ $prix_unitaire_mga ? 'bg-blue-600' : 'bg-gray-300' }}">
                                <span class="text-sm font-medium {{ $prix_unitaire_mga ? 'text-white' : 'text-gray-500' }}">3</span>
                            </div>
                            <div class="ml-4 min-w-0 flex-1">
                                <span class="text-sm font-medium {{ $prix_unitaire_mga ? 'text-blue-600' : 'text-gray-500' }}">Prix & Paiement</span>
                            </div>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>

        {{-- Section 1: Informations de base et client --}}
        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg p-4 mb-6">
            <h4 class="text-md font-semibold text-blue-900 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                Informations client et référence
            </h4>
            
            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Référence vente *</label>
                    <input wire:model="dechargement_reference" type="text" 
                        class="block w-full border border-gray-300 rounded-lg shadow-sm py-3 px-4 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">
                    @error('dechargement_reference') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nom du client *</label>
                    <input wire:model="client_nom" type="text" 
                        class="block w-full border border-gray-300 rounded-lg shadow-sm py-3 px-4 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200"
                        placeholder="Ex: Rakoto Jean">
                    @error('client_nom') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Contact client</label>
                    <input wire:model="client_contact" type="text" 
                        class="block w-full border border-gray-300 rounded-lg shadow-sm py-3 px-4 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200"
                        placeholder="Ex: 034 12 345 67">
                    @error('client_contact') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 mt-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Lieu de livraison</label>
                    <select wire:model="lieu_livraison_id" 
                        class="block w-full border border-gray-300 rounded-lg shadow-sm py-3 px-4 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">
                        <option value="">Sélectionner un lieu</option>
                        @foreach($destinations ?? [] as $destination)
                            <option value="{{ $destination->id }}">📍 {{ $destination->nom }} ({{ $destination->region }})</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Pointeur</label>
                    <input wire:model="pointeur_nom" type="text" 
                        class="block w-full border border-gray-300 rounded-lg shadow-sm py-3 px-4 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200"
                        placeholder="Ex: Ndriana">
                </div>
            </div>
        </div>

        {{-- Section 2: Sélection du produit --}}
        <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-lg p-4 mb-6">
            <h4 class="text-md font-semibold text-green-900 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                </svg>
                Produit à vendre
            </h4>

            {{-- Choix rapide de produit ou chargement existant --}}
            <div class="mb-4">
                <div class="flex space-x-4 mb-4">
                    <label class="flex items-center">
                        <input type="radio" wire:model.live="product_selection_mode" value="stock" class="form-radio">
                        <span class="ml-2 text-sm font-medium text-gray-700">🏪 Du stock disponible</span>
                    </label>
                    <label class="flex items-center">
                        <input type="radio" wire:model.live="product_selection_mode" value="chargement" class="form-radio">
                        <span class="ml-2 text-sm font-medium text-gray-700">🚛 D'un chargement spécifique</span>
                    </label>
                </div>
            </div>

            @if(($product_selection_mode ?? 'stock') === 'chargement')
                {{-- Mode chargement spécifique --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Chargement source *</label>
                    <select wire:model.live="chargement_id" 
                        class="block w-full border border-gray-300 rounded-lg shadow-sm py-3 px-4 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">
                        <option value="">Sélectionner un chargement</option>
                        @if(isset($voyage))
                            @foreach($voyage->chargements ?? [] as $chargement)
                                @if(!$chargement->dechargement || ($editingDechargement && $editingDechargement->chargement_id == $chargement->id))
                                    <option value="{{ $chargement->id }}">
                                        {{ $chargement->reference }} - {{ $chargement->proprietaire_nom }} 
                                        ({{ $chargement->produit->nom ?? 'N/A' }}) - {{ number_format($chargement->poids_depart_kg, 0) }} kg
                                    </option>
                                @endif
                            @endforeach
                        @endif
                    </select>
                    @error('chargement_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
            @else
                {{-- Mode stock général --}}
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Type de produit *</label>
                        <select wire:model.live="product_type" 
                            class="block w-full border border-gray-300 rounded-lg shadow-sm py-3 px-4 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">
                            <option value="">Choisir un produit</option>
                            <option value="riz">🌾 Riz local</option>
                            <option value="mais">🌽 Maïs jaune</option>
                            <option value="haricot">🫘 Haricot rouge</option>
                            <option value="patate">🍠 Patate douce</option>
                            <option value="manioc">🥔 Manioc</option>
                        </select>
                        @error('product_type') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Stock disponible</label>
                        <div class="block w-full border border-gray-200 rounded-lg shadow-sm py-3 px-4 bg-gray-50 text-gray-700">
                            @if($product_type)
                                {{ $this->getStockDisponible($product_type) }} kg disponibles
                            @else
                                Sélectionnez un produit
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        </div>

        {{-- Aperçu du chargement/produit sélectionné --}}
        @if($chargement_id && isset($voyage))
            @php $selectedChargement = $voyage->chargements->find($chargement_id); @endphp
            @if($selectedChargement)
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                    <h4 class="font-semibold text-blue-900 mb-3 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                        Chargement {{ $selectedChargement->reference }}
                    </h4>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                        <div class="bg-white rounded-lg p-3">
                            <div class="text-blue-700 font-medium">Propriétaire</div>
                            <div class="text-gray-900">{{ $selectedChargement->proprietaire_nom }}</div>
                        </div>
                        <div class="bg-white rounded-lg p-3">
                            <div class="text-blue-700 font-medium">Produit</div>
                            <div class="text-gray-900">{{ $selectedChargement->produit->nom ?? 'N/A' }}</div>
                        </div>
                        <div class="bg-white rounded-lg p-3">
                            <div class="text-blue-700 font-medium">Poids chargé</div>
                            <div class="text-gray-900 font-semibold">{{ number_format($selectedChargement->poids_depart_kg, 0) }} kg</div>
                        </div>
                        <div class="bg-white rounded-lg p-3">
                            <div class="text-blue-700 font-medium">Sacs</div>
                            <div class="text-gray-900">
                                {{ $selectedChargement->sacs_pleins_depart }} pleins
                                @if($selectedChargement->sacs_demi_depart > 0)
                                    + {{ $selectedChargement->sacs_demi_depart }} demis
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        @endif

        {{-- Section 3: Quantités et prix --}}
        <div class="bg-gradient-to-r from-purple-50 to-pink-50 rounded-lg p-4 mb-6">
            <h4 class="text-md font-semibold text-purple-900 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                </svg>
                Quantités et tarification
            </h4>
            
            <div class="grid grid-cols-3 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Poids vendu (kg) *</label>
                    <input wire:model.live="poids_arrivee_kg" type="number" step="0.01" 
                        class="block w-full border border-gray-300 rounded-lg shadow-sm py-3 px-4 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors duration-200">
                    @error('poids_arrivee_kg') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Sacs pleins</label>
                    <input wire:model="sacs_pleins_arrivee" type="number" 
                        class="block w-full border border-gray-300 rounded-lg shadow-sm py-3 px-4 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors duration-200">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Sacs demi</label>
                    <input wire:model="sacs_demi_arrivee" type="number" 
                        class="block w-full border border-gray-300 rounded-lg shadow-sm py-3 px-4 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors duration-200">
                </div>
            </div>
        </div>

        {{-- Section 4: Informations financières --}}
        <div class="bg-gradient-to-r from-yellow-50 to-orange-50 rounded-lg p-4 mb-6">
            <h4 class="text-md font-semibold text-yellow-900 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                </svg>
                Informations financières
            </h4>
            
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Prix unitaire (MGA/kg) *</label>
                    <div class="relative">
                        <input wire:model.live="prix_unitaire_mga" type="number" step="0.01" 
                            class="block w-full border border-gray-300 rounded-lg shadow-sm py-3 px-4 pr-16 focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 transition-colors duration-200"
                            placeholder="Ex: 2500">
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                            <span class="text-gray-500 text-sm">MGA/kg</span>
                        </div>
                    </div>
                    @error('prix_unitaire_mga') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Montant total (MGA) 
                        <span class="text-xs text-yellow-600">✨ Calculé automatiquement</span>
                    </label>
                    <div class="relative">
                        <input wire:model="montant_total_mga" type="text" readonly 
                            class="block w-full border border-gray-200 rounded-lg shadow-sm py-3 px-4 pr-16 bg-yellow-50 text-yellow-900 font-bold cursor-not-allowed">
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                            <span class="text-yellow-600 text-sm">MGA</span>
                        </div>
                    </div>
                    @if($prix_unitaire_mga && $poids_arrivee_kg)
                        <p class="mt-1 text-xs text-yellow-700 bg-yellow-100 rounded px-2 py-1">
                            💡 {{ number_format($prix_unitaire_mga, 0) }} MGA/kg × {{ number_format($poids_arrivee_kg, 1) }} kg = {{ number_format($montant_total_mga ?? 0, 0) }} MGA
                        </p>
                    @endif
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Paiement reçu (MGA)
                        @if($montant_total_mga)
                            <button type="button" wire:click="setFullPayment" 
                                class="ml-2 text-xs bg-green-100 text-green-700 px-2 py-1 rounded-full hover:bg-green-200 transition-colors duration-200">
                                💰 Complet
                            </button>
                        @endif
                    </label>
                    <div class="relative">
                        <input wire:model.live="paiement_mga" type="number" step="0.01" 
                            class="block w-full border border-gray-300 rounded-lg shadow-sm py-3 px-4 pr-16 focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 transition-colors duration-200">
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                            <span class="text-gray-500 text-sm">MGA</span>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Reste à encaisser (MGA)
                        <span class="text-xs text-yellow-600">✨ Calculé automatiquement</span>
                    </label>
                    
                    @php
                        $reste_numerique = is_numeric($reste_mga) ? (float)$reste_mga : 0;
                    @endphp
                    
                    <div class="relative">
                        <div class="block w-full border rounded-lg shadow-sm py-3 px-4 pr-16 font-bold transition-colors duration-200
                            @if($reste_numerique > 0) bg-red-50 border-red-300 text-red-800
                            @elseif($reste_numerique < 0) bg-purple-50 border-purple-300 text-purple-800  
                            @else bg-green-50 border-green-300 text-green-800 @endif">
                            
                            @if($reste_numerique > 0)
                                {{ number_format($reste_numerique, 0) }}
                            @elseif($reste_numerique < 0)
                                {{ number_format($reste_numerique, 0) }}
                            @else
                                0
                            @endif
                        </div>
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                            <span class="text-gray-500 text-sm">MGA</span>
                        </div>
                    </div>
                    
                    @if($montant_total_mga)
                        <p class="mt-1 text-xs">
                            @if($reste_numerique > 0)
                                <span class="text-red-600 bg-red-100 rounded px-2 py-1">⚠️ Paiement partiel</span>
                            @elseif($reste_numerique < 0)
                                <span class="text-purple-600 bg-purple-100 rounded px-2 py-1">💡 Trop-perçu</span>
                            @else
                                <span class="text-green-600 bg-green-100 rounded px-2 py-1">✅ Paiement complet</span>
                            @endif
                        </p>
                    @endif
                </div>
            </div>

            {{-- Résumé financier visuel --}}
            @if($montant_total_mga)
                <div class="mt-6 bg-white border border-yellow-200 rounded-lg p-4">
                    <h5 class="text-sm font-semibold text-gray-900 mb-3 flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                        Résumé de la transaction
                    </h5>
                    <div class="grid grid-cols-3 gap-4 text-center">
                        <div class="bg-blue-50 rounded-lg p-3">
                            <div class="text-sm text-blue-600 font-medium">Montant dû</div>
                            <div class="text-xl font-bold text-blue-800">{{ number_format($montant_total_mga, 0) }}</div>
                            <div class="text-xs text-blue-600">MGA</div>
                        </div>
                        <div class="bg-green-50 rounded-lg p-3">
                            <div class="text-sm text-green-600 font-medium">Payé</div>
                            <div class="text-xl font-bold text-green-800">{{ number_format($paiement_mga ?: 0, 0) }}</div>
                            <div class="text-xs text-green-600">MGA</div>
                        </div>
                        <div class="bg-{{ $reste_mga > 0 ? 'red' : ($reste_mga < 0 ? 'purple' : 'gray') }}-50 rounded-lg p-3">
                            <div class="text-sm text-{{ $reste_mga > 0 ? 'red' : ($reste_mga < 0 ? 'purple' : 'gray') }}-600 font-medium">Reste</div>
                            <div class="text-xl font-bold text-{{ $reste_mga > 0 ? 'red' : ($reste_mga < 0 ? 'purple' : 'gray') }}-800">{{ number_format($reste_mga ?: 0, 0) }}</div>
                            <div class="text-xs text-{{ $reste_mga > 0 ? 'red' : ($reste_mga < 0 ? 'purple' : 'gray') }}-600">MGA</div>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        {{-- Section 5: Observations --}}
        <div class="bg-gray-50 rounded-lg p-4">
            <h4 class="text-md font-semibold text-gray-900 mb-3 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-1l-4 4z" />
                </svg>
                Observations et notes
            </h4>
            <textarea wire:model="observation" rows="3" 
                class="block w-full border border-gray-300 rounded-lg shadow-sm py-3 px-4 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200"
                placeholder="Notes sur la vente, conditions particulières, mode de livraison..."></textarea>
        </div>
    </div>
</form>
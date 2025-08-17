{{-- Modal Sortie depuis Entrée avec Auto-calcul FONCTIONNEL --}}
@if($showSortieDepuisEntreeModal && $entreeSource)
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl w-full max-w-3xl transform transition-all duration-300 max-h-screen overflow-y-auto">
            <div class="p-6">
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">Sortie depuis Entrée</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $entreeSource->reference }} - {{ $entreeSource->motif }}</p>
                    </div>
                    <button wire:click="fermerSortieDepuisEntreeModal" class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                
                {{-- Info entrée source --}}
                <div class="bg-blue-50 dark:bg-blue-900 dark:bg-opacity-20 p-4 rounded-lg mb-6">
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <p class="text-blue-600 dark:text-blue-400 font-medium">Montant Entrée:</p>
                            <p class="font-bold">{{ number_format((float)$entreeSource->montant_mga, 0, ',', ' ') }} Ar</p>
                        </div>
                        <div>
                            <p class="text-blue-600 dark:text-blue-400 font-medium">Disponible:</p>
                            <p class="font-bold text-green-600">{{ number_format((float)$this->getMontantDisponibleEntree($entreeSource->id), 0, ',', ' ') }} Ar</p>
                        </div>
                    </div>
                    
                    {{-- Info motif automatique --}}
                    <div class="mt-3 pt-3 border-t border-blue-200 dark:border-blue-700">
                        <p class="text-xs text-blue-600 dark:text-blue-400 flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Motif automatique : "Utilisation de {{ $entreeSource->motif }}"
                        </p>
                    </div>
                </div>

                {{-- Messages Flash pour les détails --}}
                @if (session()->has('detail_success'))
                    <div class="mb-4 p-3 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                        {{ session('detail_success') }}
                    </div>
                @endif

                @if (session()->has('detail_info'))
                    <div class="mb-4 p-3 bg-blue-100 border border-blue-400 text-blue-700 rounded-lg">
                        {{ session('detail_info') }}
                    </div>
                @endif
                
                <form wire:submit="creerSortieDepuisEntree" class="space-y-6">
                    {{-- Motif automatique (hidden) basé sur l'entrée source --}}
                    <input type="hidden" wire:model="sortieForm.motif">

                    {{-- Section Ajouter détail avec Auto-calcul FONCTIONNEL --}}
                    <div class="">
                        <h4 class="text-md font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Ajouter un détail de sortie
                        </h4>
                        
                        {{-- Description sur toute la largeur --}}
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Description *</label>
                            <input type="text" wire:model.live="newDetail.description" 
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                                    placeholder="Description du détail (ex: Achat matériel, Frais transport...)">
                            @error('newDetail.description') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        {{-- Ligne avec auto-calcul FONCTIONNEL + Unité (Montant caché) --}}
                        <div class="grid grid-cols-2 md:grid-cols-6 gap-3 items-end">
                            {{-- Quantité --}}
                            <div class="col-span-1">
                                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Qté *
                                </label>
                                <input type="number" 
                                       wire:model.live="newDetail.quantite" 
                                       step="0.01" 
                                       min="0"
                                       class="w-full px-2 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                                       placeholder="1">
                                @error('newDetail.quantite') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            {{-- ✅ Unité --}}
                            <div class="col-span-1">
                                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Unité
                                </label>
                                <select wire:model.live="newDetail.unite" 
                                        class="w-full px-2 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                                    @foreach($this->getUnitesDisponibles() as $value => $label)
                                        <option value="{{ $value }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                                @error('newDetail.unite') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            {{-- Prix Unitaire --}}
                            <div class="col-span-1 md:col-span-2">
                                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Prix Unit. (Ar) *
                                    @if(!empty($newDetail['unite']))
                                        <span class="text-gray-500">/{{ $newDetail['unite'] }}</span>
                                    @endif
                                </label>
                                <input type="number" 
                                       wire:model.live="newDetail.prix_unitaire_mga" 
                                       step="0.01" 
                                       min="0"
                                       class="w-full px-2 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                                       placeholder="0">
                                @error('newDetail.prix_unitaire_mga') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            {{-- 🔥 BOUTON CALCUL + AFFICHAGE MONTANT --}}
                            <div class="col-span-1">
                                @if(!empty($newDetail['quantite']) && !empty($newDetail['prix_unitaire_mga']) && is_numeric($newDetail['quantite']) && is_numeric($newDetail['prix_unitaire_mga']))
                                    <div class="text-center">
                                        <div class="text-xs text-gray-500 dark:text-gray-400 mb-1">Montant</div>
                                        <div class="px-2 py-2 bg-green-100 dark:bg-green-800 text-green-800 dark:text-green-200 text-sm font-bold rounded-lg border-2 border-green-300 dark:border-green-600">
                                            {{ number_format((float)$newDetail['quantite'] * (float)$newDetail['prix_unitaire_mga'], 0, ',', ' ') }} Ar
                                        </div>
                                    </div>
                                @else
                                    <div class="text-center">
                                        <div class="text-xs text-gray-500 dark:text-gray-400 mb-1">Montant</div>
                                        <div class="px-2 py-2 bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400 text-sm rounded-lg border-2 border-gray-300 dark:border-gray-600">
                                            0 Ar
                                        </div>
                                    </div>
                                @endif
                            </div>

                            {{-- Bouton Ajouter --}}
                            <div class="col-span-2 md:col-span-1">
                                <button type="button" 
                                        wire:click="ajouterDetail" 
                                        class="w-full px-3 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700 transition-colors flex items-center justify-center disabled:opacity-50 disabled:cursor-not-allowed"
                                        {{ empty($newDetail['description']) || empty($newDetail['quantite']) || empty($newDetail['prix_unitaire_mga']) ? 'disabled' : '' }}>
                                    <svg class="w-4 h-4 md:mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                    </svg>
                                    <span class="hidden md:inline">Ajouter</span>
                                </button>
                            </div>
                        </div>

                        {{-- ✅ CHAMP MONTANT CACHÉ (pour Livewire) --}}
                        <input type="hidden" wire:model.live="newDetail.montant_mga">
                        @error('newDetail.montant_mga') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror

                        {{-- Aide et informations --}}
                        <div class="flex justify-between items-center mt-3">
                            <div class="flex items-center space-x-4">
                                <p class="text-xs text-gray-500 flex items-center">
                                    <svg class="w-4 h-4 mr-1 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Calcul automatique : Qté × Prix = Montant
                                </p>
                                
                                {{-- Calcul en temps réel affiché --}}
                                @if(!empty($newDetail['quantite']) && !empty($newDetail['prix_unitaire_mga']) && is_numeric($newDetail['quantite']) && is_numeric($newDetail['prix_unitaire_mga']))
                                    <p class="text-xs text-green-600 font-medium">
                                        {{ $newDetail['quantite'] }}
                                        @if(!empty($newDetail['unite']))
                                            <span class="text-blue-600">{{ $newDetail['unite'] }}</span>
                                        @endif
                                        × {{ number_format((float)$newDetail['prix_unitaire_mga'], 0, ',', ' ') }} Ar
                                        @if(!empty($newDetail['unite']))
                                            <span class="text-gray-500">/{{ $newDetail['unite'] }}</span>
                                        @endif
                                        = {{ number_format((float)($newDetail['quantite'] * $newDetail['prix_unitaire_mga']), 0, ',', ' ') }} Ar
                                    </p>
                                @endif
                            </div>
                            
                            <div class="flex space-x-2">
                                {{-- Reset --}}
                                <button type="button" 
                                        wire:click="resetNewDetail" 
                                        class="px-3 py-1 bg-gray-400 text-white text-xs rounded hover:bg-gray-500 transition-colors">
                                    Reset
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- Liste des détails ajoutés --}}
                    @if(!empty($sortieDetails))
                        <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                            <h4 class="text-md font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Détails ajoutés ({{ count($sortieDetails) }})
                            </h4>
                            
                            {{-- En-tête tableau desktop --}}
                            <div class="hidden md:grid grid-cols-12 gap-3 mb-2 px-3 py-2 bg-gray-50 dark:bg-gray-700 rounded-lg text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase">
                                <div class="col-span-4">Description</div>
                                <div class="col-span-2 text-center">Quantité & Unité</div>
                                <div class="col-span-2 text-right">Prix Unit.</div>
                                <div class="col-span-3 text-right">Montant</div>
                                <div class="col-span-1 text-center">Actions</div>
                            </div>

                            <div class="space-y-2">
                                @foreach($sortieDetails as $index => $detail)
                                    {{-- Version Desktop --}}
                                    <div class="hidden md:grid grid-cols-12 gap-3 items-center p-3 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-600 rounded-lg hover:shadow-md transition-all">
                                        <div class="col-span-4 font-medium text-gray-900 dark:text-white">
                                            {{ $detail['description'] }}
                                        </div>
                                        <div class="col-span-2 text-center text-gray-600 dark:text-gray-300">
                                            @if(!empty($detail['quantite']))
                                                {{ $detail['quantite'] }}
                                                @if(!empty($detail['unite']))
                                                    <span class="text-blue-600 dark:text-blue-400 font-medium">{{ $detail['unite'] }}</span>
                                                @endif
                                            @else
                                                -
                                            @endif
                                        </div>
                                        <div class="col-span-2 text-right text-gray-600 dark:text-gray-300">
                                            @if(!empty($detail['prix_unitaire_mga']) && is_numeric($detail['prix_unitaire_mga']))
                                                {{ number_format((float)$detail['prix_unitaire_mga'], 0, ',', ' ') }} Ar
                                                @if(!empty($detail['unite']))
                                                    <span class="text-gray-500">{{ $detail['unite'] }}</span>
                                                @endif
                                            @else
                                                -
                                            @endif
                                        </div>
                                        <div class="col-span-3 text-right font-bold text-gray-900 dark:text-white">
                                            {{ number_format((float)($detail['montant_mga'] ?? 0), 0, ',', ' ') }} Ar
                                        </div>
                                        <div class="col-span-1 text-center flex justify-center space-x-1">
                                            {{-- Recalculer --}}
                                            @if(!empty($detail['quantite']) && !empty($detail['prix_unitaire_mga']) && is_numeric($detail['quantite']) && is_numeric($detail['prix_unitaire_mga']))
                                                <button type="button" 
                                                        wire:click="recalculerDetail({{ $index }})" 
                                                        class="text-blue-500 hover:text-blue-700 transition-colors p-1" 
                                                        title="Recalculer: {{ $detail['quantite'] }} × {{ $detail['prix_unitaire_mga'] }}">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                                    </svg>
                                                </button>
                                            @endif
                                            
                                            {{-- Supprimer --}}
                                            <button type="button" 
                                                    wire:click="supprimerDetail({{ $index }})" 
                                                    class="text-red-500 hover:text-red-700 transition-colors p-1"
                                                    onclick="return confirm('Supprimer ce détail ?')">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>

                                    {{-- Version Mobile --}}
                                    <div class="md:hidden p-4 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-600 rounded-lg">
                                        <div class="flex justify-between items-start mb-2">
                                            <div class="flex-1">
                                                <h5 class="font-medium text-gray-900 dark:text-white">{{ $detail['description'] }}</h5>
                                                @if(!empty($detail['quantite']) && !empty($detail['prix_unitaire_mga']) && is_numeric($detail['prix_unitaire_mga']))
                                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                                        {{ $detail['quantite'] }}
                                                        @if(!empty($detail['unite']))
                                                            <span class="text-blue-600 dark:text-blue-400 font-medium">{{ $detail['unite'] }}</span>
                                                        @endif
                                                        × {{ number_format((float)$detail['prix_unitaire_mga'], 0, ',', ' ') }} Ar
                                                        @if(!empty($detail['unite']))
                                                            <span class="text-gray-500">/{{ $detail['unite'] }}</span>
                                                        @endif
                                                        
                                                        {{-- Recalculer mobile --}}
                                                        <button type="button" 
                                                                wire:click="recalculerDetail({{ $index }})" 
                                                                class="ml-2 text-blue-500 hover:text-blue-700">
                                                            <svg class="w-3 h-3 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                                            </svg>
                                                        </button>
                                                    </p>
                                                @endif
                                            </div>
                                            <button type="button" 
                                                    wire:click="supprimerDetail({{ $index }})" 
                                                    class="text-red-500 hover:text-red-700 transition-colors p-1 ml-2"
                                                    onclick="return confirm('Supprimer ce détail ?')">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </div>
                                        <div class="text-right">
                                            <span class="text-lg font-bold text-gray-900 dark:text-white">
                                                {{ number_format((float)($detail['montant_mga'] ?? 0), 0, ',', ' ') }} Ar
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            {{-- Total de la sortie --}}
                            <div class="mt-4 p-4 bg-gradient-to-r from-red-50 to-red-100 dark:from-red-900 dark:from-opacity-20 dark:to-red-800 dark:to-opacity-30 rounded-lg border border-red-200 dark:border-red-700">
                                <div class="flex justify-between items-center">
                                    <span class="font-semibold text-red-900 dark:text-red-100">TOTAL DE LA SORTIE :</span>
                                    <span class="text-2xl font-bold text-red-600 dark:text-red-400">
                                        {{ number_format((float)($sortieForm['montant_total'] ?? 0), 0, ',', ' ') }} Ar
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Boutons d'action --}}
                    <div class="flex flex-col sm:flex-row justify-end space-y-2 sm:space-y-0 sm:space-x-3 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <button type="button" 
                                wire:click="fermerSortieDepuisEntreeModal" 
                                class="px-6 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                            Annuler
                        </button>
                        <button type="submit" 
                                class="px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors flex items-center justify-center disabled:opacity-50 disabled:cursor-not-allowed"
                                {{ empty($sortieDetails) ? 'disabled' : '' }}>
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                            </svg>
                            Créer la Sortie ({{ number_format((float)($sortieForm['montant_total'] ?? 0), 0, ',', ' ') }} Ar)
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endif
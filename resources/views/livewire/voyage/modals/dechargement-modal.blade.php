{{-- resources/views/livewire/voyage/modals/dechargement-modal.blade.php --}}
@if($showDechargementModal)
<div class="fixed inset-0 z-50 overflow-y-auto">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 dark:bg-gray-900 bg-opacity-75 dark:bg-opacity-75 transition-opacity" wire:click="closeDechargementModal"></div>
        <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-5xl sm:w-full">
            
            <!-- En-tête avec étapes -->
            <div class="bg-blue-50 dark:bg-blue-900 dark:bg-opacity-20 px-6 py-4 border-b border-blue-200 dark:border-blue-700">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                        {{ $editingDechargement ? 'Modifier' : 'Créer' }} un déchargement
                    </h3>
                    <div class="flex items-center space-x-4">
                        <div class="flex items-center">
                            <span class="flex items-center justify-center w-8 h-8 rounded-full text-xs font-medium {{ $dechargement_step >= 1 ? 'bg-blue-600 text-white' : 'bg-gray-300 dark:bg-gray-700 text-gray-600 dark:text-gray-300' }}">1</span>
                            <span class="ml-2 {{ $dechargement_step >= 1 ? 'text-blue-600 dark:text-blue-400 font-medium' : 'text-gray-500 dark:text-gray-400' }}">Voyage</span>
                            <div class="w-8 h-0.5 mx-2 {{ $dechargement_step >= 2 ? 'bg-blue-600' : 'bg-gray-300 dark:bg-gray-600' }}"></div>
                            <span class="flex items-center justify-center w-8 h-8 rounded-full text-xs font-medium {{ $dechargement_step >= 2 ? 'bg-blue-600 text-white' : 'bg-gray-300 dark:bg-gray-700 text-gray-600 dark:text-gray-300' }}">2</span>
                            <span class="ml-2 {{ $dechargement_step >= 2 ? 'text-blue-600 dark:text-blue-400 font-medium' : 'text-gray-500 dark:text-gray-400' }}">Chargement</span>
                            <div class="w-8 h-0.5 mx-2 {{ $dechargement_step >= 3 ? 'bg-blue-600' : 'bg-gray-300 dark:bg-gray-600' }}"></div>
                            <span class="flex items-center justify-center w-8 h-8 rounded-full text-xs font-medium {{ $dechargement_step >= 3 ? 'bg-blue-600 text-white' : 'bg-gray-300 dark:bg-gray-700 text-gray-600 dark:text-gray-300' }}">3</span>
                            <span class="ml-2 {{ $dechargement_step >= 3 ? 'text-blue-600 dark:text-blue-400 font-medium' : 'text-gray-500 dark:text-gray-400' }}">Détails</span>
                        </div>
                    </div>
                </div>
            </div>

            <form wire:submit="saveDechargement">
                <div class="bg-white dark:bg-gray-800 px-6 pt-6 pb-4">

                    {{-- ÉTAPE 1: VOYAGE --}}
                    @if($dechargement_step == 1)
                    <div class="space-y-6">
                        <div class="text-center">
                            <h4 class="text-lg font-medium text-gray-900 dark:text-white mb-2">🚛 Sélectionner le voyage</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Choisissez le voyage pour créer un déchargement</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Voyages disponibles *</label>
                            <select wire:model.live="selected_voyage_id" class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-3 px-4 focus:ring-blue-500 focus:border-blue-500 text-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                                <option value="">Sélectionner un voyage...</option>
                                @if($available_voyages)
                                    @foreach($available_voyages as $v)
                                    <option value="{{ $v->id }}">
                                        {{ $v->reference }} - {{ $v->date->format('d/m/Y') }} ({{ $v->vehicule->immatriculation ?? 'N/A' }}) - {{ $v->chargements->count() }} chargement(s)
                                    </option>
                                    @endforeach
                                @endif
                            </select>
                            @error('selected_voyage_id') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                        </div>
                        @if($selected_voyage)
                        <div class="bg-blue-50 dark:bg-blue-900 dark:bg-opacity-20 border border-blue-200 dark:border-blue-700 rounded-lg p-4">
                            <h5 class="font-medium text-blue-900 dark:text-blue-200 mb-3">📋 Voyage {{ $selected_voyage->reference }}</h5>
                            <div class="grid grid-cols-2 gap-4 text-sm">
                                <div><span class="text-blue-700 dark:text-blue-300">Date:</span> {{ $selected_voyage->date->format('d/m/Y') }}</div>
                                <div><span class="text-blue-700 dark:text-blue-300">Véhicule:</span> {{ $selected_voyage->vehicule->immatriculation ?? 'N/A' }}</div>
                            </div>
                        </div>
                        @endif
                    </div>
                    @endif

                    {{-- ÉTAPE 2: CHARGEMENT --}}
                    @if($dechargement_step == 2)
                    <div class="space-y-6">
                        <div class="text-center">
                            <h4 class="text-lg font-medium text-gray-900 dark:text-white mb-2">📦 Sélectionner le chargement</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Voyage: <strong>{{ $selected_voyage->reference }}</strong></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Date du déchargement *</label>
                            <input wire:model="date" type="date" class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-3 px-4 focus:ring-blue-500 focus:border-blue-500 text-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                            @error('date') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                        </div>
                        @if($available_chargements && $available_chargements->count() > 0)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Chargements disponibles *</label>
                            <select wire:model.live="chargement_id" class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-3 px-4 focus:ring-blue-500 focus:border-blue-500 text-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                                <option value="">Sélectionner un chargement...</option>
                                @foreach($available_chargements as $chargement)
                                <option value="{{ $chargement->id }}">
                                    {{ $chargement->reference }} - {{ $chargement->proprietaire_nom }} ({{ $chargement->produit->nom ?? 'N/A' }}) - {{ $this->formatNumber($chargement->poids_depart_kg, 0) }} kg
                                </option>
                                @endforeach
                            </select>
                            @error('chargement_id') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                        </div>
                        @if($chargement_id && $selectedChargement = $available_chargements->find($chargement_id))
                        <div class="bg-green-50 dark:bg-green-900 dark:bg-opacity-20 border border-green-200 dark:border-green-700 rounded-lg p-4">
                            <h5 class="font-medium text-green-900 dark:text-green-200 mb-3">📦 Chargement {{ $selectedChargement->reference }}</h5>
                            <div class="grid grid-cols-2 gap-4 text-sm">
                                <div><span class="text-green-700 dark:text-green-300">Propriétaire:</span> {{ $selectedChargement->proprietaire_nom }}</div>
                                <div><span class="text-green-700 dark:text-green-300">Produit:</span> {{ $selectedChargement->produit->nom ?? 'N/A' }}</div>
                                <div><span class="text-green-700 dark:text-green-300">Poids chargé:</span> {{ $this->formatNumber($selectedChargement->poids_depart_kg, 1) }} kg</div>
                                <div><span class="text-green-700 dark:text-green-300">Sacs chargés:</span> {{ $this->formatNumber($selectedChargement->sacs_pleins_depart, 0) }}</div>
                            </div>
                            
                            @if($selectedChargement->dechargements->count() > 0)
                            <div class="mt-3 p-3 bg-yellow-50 dark:bg-yellow-900 dark:bg-opacity-20 border border-yellow-200 dark:border-yellow-700 rounded">
                                <div class="flex">
                                    <div class="text-yellow-400 dark:text-yellow-300 mr-2">⚠️</div>
                                    <div class="text-sm text-yellow-800 dark:text-yellow-200">
                                        <strong>Mode édition :</strong> Ce chargement a déjà {{ $selectedChargement->dechargements->count() }} déchargement(s).
                                        Vous pouvez modifier le déchargement existant.
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                        @endif
                        @else
                        <div class="text-center py-8">
                            <div class="text-gray-400 dark:text-gray-500 text-6xl mb-4">📭</div>
                            <h5 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Aucun chargement disponible</h5>
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                @if($this->editingDechargement)
                                    Mode édition du déchargement {{ $this->editingDechargement->reference }}
                                @else
                                    Tous les chargements de ce voyage ont déjà été déchargés.
                                @endif
                            </p>
                            <button type="button" wire:click="goToStep(1)" class="mt-4 text-blue-600 dark:text-blue-400 hover:text-blue-500 dark:hover:text-blue-300">← Choisir un autre voyage</button>
                        </div>
                        @endif
                    </div>
                    @endif

                    {{-- ÉTAPE 3: DÉTAILS --}}
                    @if($dechargement_step == 3)
                    <div class="space-y-6">
                        <div class="bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg p-4">
                            <h4 class="text-lg font-medium text-gray-900 dark:text-white mb-3">📋 Récapitulatif</h4>
                            <div class="grid grid-cols-3 gap-4 text-sm">
                                <div>
                                    <span class="text-gray-600 dark:text-gray-400">Voyage:</span>
                                    <div class="font-medium dark:text-gray-200">{{ $selected_voyage->reference }}</div>
                                </div>
                                <div>
                                    <span class="text-gray-600 dark:text-gray-400">Chargement:</span>
                                    @if($chargement_id && $selectedChargement = $available_chargements->find($chargement_id))
                                    <div class="font-medium dark:text-gray-200">{{ $selectedChargement->reference }}</div>
                                    @endif
                                </div>
                                <div>
                                    <span class="text-gray-600 dark:text-gray-400">Poids:</span>
                                    @if($selectedChargement)
                                    <div class="font-medium dark:text-gray-200">{{ number_format($selectedChargement->poids_depart_kg, 1) }} kg</div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <!-- Référence -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Référence *</label>
                                <input wire:model="dechargement_reference" type="text" class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                                @error('dechargement_reference') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                            </div>
                            <!-- Type -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Type *</label>
                                <select wire:model="type_dechargement" class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                                    <option value="vente">Vente</option>
                                    <option value="retour">Retour</option>
                                    <option value="depot">Dépôt</option>
                                    <option value="transfert">Transfert</option>
                                </select>
                                @error('type_dechargement') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                            </div>

                            <!-- Interlocuteurs -->
                            <div class="col-span-2">
                                <h4 class="text-md font-medium text-gray-900 dark:text-white mb-2 border-t pt-4 border-gray-200 dark:border-gray-700">👥 Informations des interlocuteurs</h4>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nom de l'interlocuteur <span class="text-xs text-gray-500 dark:text-gray-400">(Client/Acheteur)</span></label>
                                <input wire:model="interlocuteur_nom" type="text" class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white" placeholder="Ex: M. Rakoto Jean, Société FITIAVANA">
                                @error('interlocuteur_nom') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Contact de l'interlocuteur</label>
                                <input wire:model="interlocuteur_contact" type="text" class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white" placeholder="Ex: 032 12 345 67 ou 020 22 123 45">
                                @error('interlocuteur_contact') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nom du pointeur <span class="text-xs text-gray-500 dark:text-gray-400">(Superviseur déchargement)</span></label>
                                <input wire:model="pointeur_nom" type="text" class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white" placeholder="Ex: Ndriana, Ravo, Hery">
                                @error('pointeur_nom') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Contact du pointeur</label>
                                <input wire:model="pointeur_contact" type="text" class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white" placeholder="Ex: 033 98 765 43">
                                @error('pointeur_contact') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                            </div>

                            <!-- Lieu livraison -->
                            <div class="col-span-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Lieu de livraison / Dépôt de destination</label>
                                <select wire:model="lieu_livraison_id" class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                                    <option value="">Sélectionner le lieu de destination...</option>
                                    @foreach($destinations as $destination)
                                    <option value="{{ $destination->id }}">{{ $destination->nom }} ({{ $destination->region }})</option>
                                    @endforeach
                                </select>
                                @error('lieu_livraison_id') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                            </div>

                            <!-- Quantités -->
                            <div class="col-span-2">
                                <h4 class="text-md font-medium text-gray-900 dark:text-white mb-2 border-t pt-4 border-gray-200 dark:border-gray-700">📊 Quantités reçues à destination</h4>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Sacs pleins reçus</label>
                                <input wire:model="sacs_pleins_arrivee" type="number" class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white" placeholder="Ex: 25">
                                @error('sacs_pleins_arrivee') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Sacs demi reçus</label>
                                <input wire:model="sacs_demi_arrivee" type="number" class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white" placeholder="Ex: 2">
                                @error('sacs_demi_arrivee') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Poids total reçu (kg)</label>
                                <input wire:model="poids_arrivee_kg" type="number" step="0.01" class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white" placeholder="Ex: 3250.50">
                                @error('poids_arrivee_kg') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Statut commercial *</label>
                                <select wire:model="statut_commercial" class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                                    <option value="en_attente">En attente</option>
                                    <option value="vendu">Vendu</option>
                                    <option value="retourne">Retourné</option>
                                    <option value="transfere">Transféré</option>
                                </select>
                                @error('statut_commercial') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                            </div>

                            {{-- Calcul d'écarts avec explications --}}
                            @if($chargement_id && ($poids_arrivee_kg || $sacs_pleins_arrivee) && $selectedChargement = $available_chargements->find($chargement_id))
                            <div class="col-span-2 bg-blue-50 dark:bg-blue-900 dark:bg-opacity-20 border border-blue-200 dark:border-blue-700 rounded-lg p-4">
                                <h5 class="font-medium text-blue-900 dark:text-blue-200 mb-3">📊 Analyse des écarts (chargement → réception)</h5>
                                @php
                                    $ecart_poids = $selectedChargement->poids_depart_kg - ($poids_arrivee_kg ?: 0);
                                    $ecart_sacs_pleins = $selectedChargement->sacs_pleins_depart - ($sacs_pleins_arrivee ?: 0);
                                    $ecart_sacs_demi = $selectedChargement->sacs_demi_depart - ($sacs_demi_arrivee ?: 0);
                                    $pourcentage_ecart = $selectedChargement->poids_depart_kg > 0 ? ($ecart_poids / $selectedChargement->poids_depart_kg) * 100 : 0;
                                @endphp
                                
                                <div class="grid grid-cols-3 gap-4 text-sm">
                                    <div>
                                        <span class="text-blue-700 dark:text-blue-300">Écart poids:</span>
                                        <div class="font-medium {{ $ecart_poids > 0 ? 'text-orange-600 dark:text-orange-400' : ($ecart_poids < 0 ? 'text-green-600 dark:text-green-400' : 'text-blue-600 dark:text-blue-400') }}">
                                            {{ $ecart_poids > 0 ? '-' : ($ecart_poids < 0 ? '+' : '') }}{{ number_format(abs($ecart_poids), 1) }} kg
                                        </div>
                                    </div>
                                    <div>
                                        <span class="text-blue-700 dark:text-blue-300">Écart sacs pleins:</span>
                                        <div class="font-medium {{ $ecart_sacs_pleins > 0 ? 'text-orange-600 dark:text-orange-400' : ($ecart_sacs_pleins < 0 ? 'text-green-600 dark:text-green-400' : 'text-blue-600 dark:text-blue-400') }}">
                                            {{ $ecart_sacs_pleins > 0 ? '-' : ($ecart_sacs_pleins < 0 ? '+' : '') }}{{ abs($ecart_sacs_pleins) }} sacs
                                        </div>
                                    </div>
                                    <div>
                                        <span class="text-blue-700 dark:text-blue-300">Pourcentage:</span>
                                        <div class="font-medium {{ abs($pourcentage_ecart) > 5 ? 'text-orange-600 dark:text-orange-400' : 'text-blue-600 dark:text-blue-400' }}">
                                            {{ number_format(abs($pourcentage_ecart), 1) }}%
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mt-3 p-2 rounded text-xs {{ abs($pourcentage_ecart) > 10 ? 'bg-orange-100 dark:bg-orange-900 dark:bg-opacity-20 text-orange-800 dark:text-orange-200' : (abs($pourcentage_ecart) > 5 ? 'bg-yellow-100 dark:bg-yellow-900 dark:bg-opacity-20 text-yellow-800 dark:text-yellow-200' : 'bg-green-100 dark:bg-green-900 dark:bg-opacity-20 text-green-800 dark:text-green-200') }}">
                                    @if(abs($pourcentage_ecart) > 10)
                                        ⚠️ <strong>Écart significatif ({{ number_format(abs($pourcentage_ecart), 1) }}%)</strong> - Peut être dû à la qualité, humidité, tassement, ou autres facteurs. Vérifiez les conditions de transport.
                                    @elseif(abs($pourcentage_ecart) > 5)
                                        ℹ️ <strong>Écart modéré ({{ number_format(abs($pourcentage_ecart), 1) }}%)</strong> - Variation normale due aux conditions de transport, pesée, ou qualité du produit.
                                    @else
                                        ✅ <strong>Écart faible ({{ number_format(abs($pourcentage_ecart), 1) }}%)</strong> - Variation normale et acceptable dans les opérations de transport.
                                    @endif
                                </div>
                                
                                <div class="mt-2 text-xs text-blue-600 dark:text-blue-400">
                                    <strong>Note:</strong> Les écarts ne signifient pas automatiquement des "pertes". Ils peuvent être dus à la qualité, l'humidité, le tassement, les conditions de pesée, etc. 
                                    Les pertes réelles (disparition physique) sont rares et doivent être déclarées séparément.
                                </div>
                            </div>
                            @endif

                            {{-- SECTION FINANCIÈRE --}}
                            @if($type_dechargement === 'vente')
                            <div class="col-span-2">
                                <h4 class="text-md font-medium text-gray-900 dark:text-white mb-2 border-t pt-4 border-gray-200 dark:border-gray-700">💰 Informations financières</h4>
                                @if($selected_product)
                                <div class="mb-4 p-3 bg-blue-50 dark:bg-blue-900 dark:bg-opacity-20 border border-blue-200 dark:border-blue-700 rounded-lg">
                                    <div class="flex items-center justify-between text-sm">
                                        <div>
                                            <span class="text-blue-700 dark:text-blue-300">Produit:</span>
                                            <span class="font-medium dark:text-gray-200">{{ $selected_product->nom_complet }}</span>
                                        </div>
                                        <div>
                                            <span class="text-blue-700 dark:text-blue-300">Unité de vente:</span>
                                            <span class="font-bold px-2 py-1 bg-blue-100 dark:bg-blue-800 dark:bg-opacity-30 text-blue-800 dark:text-blue-300 rounded text-xs uppercase">{{ $product_unite }}</span>
                                        </div>
                                    </div>
                                    @if($product_unite === 'sacs')
                                    <div class="mt-2 text-xs text-blue-600 dark:text-blue-400">
                                        ℹ️ Poids moyen par sac: {{ number_format($product_poids_moyen_sac, 0) }} kg
                                    </div>
                                    @endif
                                </div>
                                @endif
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Prix unitaire 
                                    @if($selected_product)
                                    <span class="text-blue-600 dark:text-blue-400">(MGA/{{ $product_unite === 'kg' ? 'kg' : ($product_unite === 'sacs' ? 'sac' : $product_unite) }})</span>
                                    @endif
                                    *
                                </label>
                                <div class="relative">
                                    <input wire:model.live="prix_unitaire_mga" type="number" step="0.01" class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 pr-20 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white" 
                                           placeholder="@if($product_prix_reference > 0){{ number_format($product_prix_reference, 0) }}@else{{ $product_unite === 'kg' ? 'Ex: 3200' : ($product_unite === 'sacs' ? 'Ex: 140000' : 'Ex: 2500') }}@endif">
                                    @if($product_prix_reference > 0)
                                    <button type="button" wire:click="$set('prix_unitaire_mga', {{ $product_prix_reference }})" class="absolute inset-y-0 right-0 px-3 py-2 text-xs bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 border-l border-gray-300 dark:border-gray-600 rounded-r-md hover:bg-gray-200 dark:hover:bg-gray-600" title="Utiliser prix de référence">📋 Réf</button>
                                    @endif
                                </div>
                                @if($product_prix_reference > 0)
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                    💡 Prix de référence: {{ number_format($product_prix_reference, 0) }} MGA/{{ $product_unite === 'kg' ? 'kg' : ($product_unite === 'sacs' ? 'sac' : $product_unite) }}
                                </p>
                                @endif
                                @error('prix_unitaire_mga') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Quantité à facturer
                                    @if($selected_product)
                                    <span class="text-blue-600 dark:text-blue-400">({{ $product_unite === 'kg' ? 'kg' : ($product_unite === 'sacs' ? 'sacs' : $product_unite) }})</span>
                                    @endif
                                    <span class="text-xs text-blue-600 dark:text-blue-400">✨ Calculé selon unité produit</span>
                                </label>
                                <input wire:model="quantite_vendue" type="text" readonly class="mt-1 block w-full border border-gray-200 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 bg-green-50 dark:bg-green-900 dark:bg-opacity-20 text-green-900 dark:text-green-200 font-medium cursor-not-allowed">
                                <p class="mt-1 text-xs text-green-600 dark:text-green-400">
                                    @if($selected_product && $quantite_vendue > 0)
                                        @if($product_unite === 'kg')
                                            📊 {{ number_format($quantite_vendue, 1) }} kg (basé sur le poids total reçu)
                                        @elseif($product_unite === 'sacs')
                                            📊 {{ number_format($quantite_vendue, 1) }} sacs ({{ $sacs_pleins_arrivee ?: 0 }} pleins + {{ ($sacs_demi_arrivee ?: 0) }} × 0.5 demi)
                                        @elseif($product_unite === 'tonnes')
                                            📊 {{ number_format($quantite_vendue, 3) }} tonnes ({{ number_format($poids_arrivee_kg ?: 0, 0) }} kg ÷ 1000)
                                        @else
                                            📊 {{ number_format($quantite_vendue, 1) }} {{ $product_unite }}
                                        @endif
                                    @else
                                        @if($selected_product)
                                            @if($product_unite === 'kg')
                                                Remplissez le "Poids total reçu (kg)"
                                            @elseif($product_unite === 'sacs')
                                                Remplissez "Sacs pleins reçus" et "Sacs demi reçus"
                                            @else
                                                Remplissez les quantités reçues
                                            @endif
                                        @else
                                            Sélectionnez d'abord un chargement
                                        @endif
                                    @endif
                                </p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Montant total (MGA) <span class="text-xs text-blue-600 dark:text-blue-400">✨ Calculé automatiquement</span></label>
                                <input wire:model="montant_total_mga" type="text" readonly class="mt-1 block w-full border border-gray-200 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 bg-blue-50 dark:bg-blue-900 dark:bg-opacity-20 text-blue-900 dark:text-blue-200 font-medium cursor-not-allowed" placeholder="Sera calculé automatiquement">
                                <p class="mt-1 text-xs text-blue-600 dark:text-blue-400">
                                @if($prix_unitaire_mga && $quantite_vendue && $selected_product)
                                    💰 {{ number_format(floatval($prix_unitaire_mga), 0) }} MGA/{{ $product_unite === 'kg' ? 'kg' : ($product_unite === 'sacs' ? 'sac' : $product_unite) }} × {{ number_format(floatval($quantite_vendue), $product_unite === 'tonnes' ? 3 : 1) }} {{ $product_unite === 'kg' ? 'kg' : ($product_unite === 'sacs' ? 'sacs' : $product_unite) }} = {{ number_format(floatval($montant_total_mga), 0) }} MGA
                                @else
                                    Remplissez le prix unitaire et les quantités correspondant à l'unité du produit
                                @endif
                                </p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Paiement reçu (MGA)
                                    <button type="button" wire:click="setFullPayment" class="ml-2 text-xs bg-green-100 dark:bg-green-800 dark:bg-opacity-30 text-green-700 dark:text-green-300 px-2 py-1 rounded hover:bg-green-200 dark:hover:bg-green-700" @if(!$montant_total_mga) disabled @endif title="Remplir automatiquement le montant total">💰 Paiement complet</button>
                                </label>
                                <input wire:model.live="paiement_mga" type="number" step="0.01" class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white" placeholder="Ex: 750000 (montant effectivement payé par le client)">
                                @error('paiement_mga') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Reste à encaisser (MGA) <span class="text-xs text-blue-600 dark:text-blue-400">✨ Calculé automatiquement</span></label>
                                @php
                                    $reste_numerique = is_numeric($reste_mga) ? (float)$reste_mga : 0;
                                @endphp
                                <div class="mt-1 block w-full border rounded-md shadow-sm py-2 px-3 font-medium @if($reste_numerique > 0) bg-yellow-50 dark:bg-yellow-900 dark:bg-opacity-20 border-yellow-300 dark:border-yellow-700 text-yellow-800 dark:text-yellow-200 @elseif($reste_numerique < 0) bg-red-50 dark:bg-red-900 dark:bg-opacity-20 border-red-300 dark:border-red-700 text-red-800 dark:text-red-200 @else bg-green-50 dark:bg-green-900 dark:bg-opacity-20 border-green-300 dark:border-green-700 text-green-800 dark:text-green-200 @endif">
                                    @if($reste_numerique > 0)
                                    ⚠️ {{ number_format($reste_numerique, 0) }} MGA (Paiement partiel - créance client)
                                    @elseif($reste_numerique < 0)
                                    ❌ {{ number_format(abs($reste_numerique), 0) }} MGA (Trop-perçu - vérifiez les montants)
                                    @else
                                    ✅ 0 MGA (Transaction soldée - paiement complet)
                                    @endif
                                </div>
                                @if($montant_total_mga || $paiement_mga)
                                <p class="mt-1 text-xs text-gray-600 dark:text-gray-400">
                                    📊 Calcul: {{ number_format($montant_total_mga ?: 0, 0) }} MGA (montant dû) - {{ number_format($paiement_mga ?: 0, 0) }} MGA (payé) = {{ number_format($reste_numerique, 0) }} MGA
                                </p>
                                @endif
                            </div>
                            
                            {{-- Résumé visuel adapté avec unités --}}
                            @if($montant_total_mga && $selected_product)
                            <div class="col-span-2 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg p-4">
                                <h5 class="text-sm font-medium text-gray-900 dark:text-white mb-3">💼 Résumé de la transaction commerciale</h5>
                                <div class="grid grid-cols-4 gap-4 text-sm text-center">
                                    <div>
                                        <div class="text-gray-600 dark:text-gray-400">Quantité facturée</div>
                                        <div class="font-bold text-lg text-blue-600 dark:text-blue-400">{{ number_format($quantite_vendue, $product_unite === 'tonnes' ? 3 : 1) }}</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">{{ $product_unite === 'kg' ? 'kg' : ($product_unite === 'sacs' ? 'sacs' : $product_unite) }}</div>
                                    </div>
                                    <div>
                                        <div class="text-gray-600 dark:text-gray-400">Prix unitaire</div>
                                        <div class="font-bold text-lg text-blue-600 dark:text-blue-400">{{ number_format($prix_unitaire_mga ?: 0, 0) }}</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">MGA/{{ $product_unite === 'kg' ? 'kg' : ($product_unite === 'sacs' ? 'sac' : $product_unite) }}</div>
                                    </div>
                                    <div>
                                        <div class="text-gray-600 dark:text-gray-400">Montant facturé</div>
                                        <div class="font-bold text-xl text-green-600 dark:text-green-400">{{ number_format($montant_total_mga, 0) }}</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">MGA</div>
                                    </div>
                                    <div>
                                        <div class="text-gray-600 dark:text-gray-400">Solde</div>
                                        <div class="font-bold text-lg {{ $reste_mga > 0 ? 'text-yellow-600 dark:text-yellow-400' : ($reste_mga < 0 ? 'text-red-600 dark:text-red-400' : 'text-green-600 dark:text-green-400') }}">{{ number_format($reste_mga ?: 0, 0) }}</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                            @if($reste_mga > 0) créance @elseif($reste_mga < 0) surplus @else soldé @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                            @endif

                            <div class="col-span-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Observations / Remarques</label>
                                <textarea wire:model="dechargement_observation" rows="3" class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white" placeholder="Ex: Qualité bonne, quelques sacs légèrement humides, livraison en bon état, client satisfait..."></textarea>
                                @error('dechargement_observation') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>
                    @endif

                </div>

                <!-- Boutons -->
                <div class="bg-gray-50 dark:bg-gray-700 px-6 py-4 sm:flex sm:flex-row-reverse">
                    @if($dechargement_step == 1)
                    <button type="button" wire:click="$set('dechargement_step', 2)" @if(!$selected_voyage_id) disabled @endif class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 disabled:bg-gray-400 dark:disabled:bg-gray-600 sm:ml-3 sm:w-auto sm:text-sm">Suivant →</button>
                    @elseif($dechargement_step == 2)
                    @if($chargement_id)
                    <span class="w-full inline-flex justify-center rounded-md border border-green-300 dark:border-green-600 shadow-sm px-4 py-2 bg-green-50 dark:bg-green-900 dark:bg-opacity-20 text-base font-medium text-green-700 dark:text-green-300 sm:ml-3 sm:w-auto sm:text-sm">✅ Sélectionné</span>
                    @endif
                    <button type="button" wire:click="goToStep(1)" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-600 text-base font-medium text-gray-700 dark:text-white hover:bg-gray-50 dark:hover:bg-gray-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">← Retour</button>
                    @elseif($dechargement_step == 3)
                    <button type="button" wire:click="showPreview" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 sm:ml-3 sm:w-auto sm:text-sm">🔍 Aperçu</button>
                    <button type="button" wire:click="goToStep(2)" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-600 text-base font-medium text-gray-700 dark:text-white hover:bg-gray-50 dark:hover:bg-gray-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">← Retour</button>
                    @endif
                    <button type="button" wire:click="closeDechargementModal" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-600 text-base font-medium text-gray-700 dark:text-white hover:bg-gray-50 dark:hover:bg-gray-500 sm:mt-0 sm:w-auto sm:text-sm">Annuler</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
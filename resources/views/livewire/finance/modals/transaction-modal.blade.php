{{-- resources/views/livewire/finance/modals/transaction-modal.blade.php --}}
@if($showTransactionModal)
    <div class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="closeTransactionModal"></div>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
                <form wire:submit="saveTransaction">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">
                            {{ $editingTransaction ? 'Modifier' : 'Ajouter' }} une transaction
                        </h3>

                        <div class="grid grid-cols-2 gap-4">
                            
                            <!-- 1. INFORMATIONS DE BASE -->
                            <div class="col-span-2">
                                <h4 class="text-md font-medium text-gray-900 mb-3 border-b pb-2">📋 Informations de base</h4>
                            </div>

                            <input wire:model="reference" type="hidden">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Type de transaction *</label>
                                <select wire:model.live="type" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">Sélectionner</option>
                                    <option value="achat">🛒 Achat de produits</option>
                                    <option value="vente">💰 Vente de produits</option>
                                    <option value="depot">📥 Dépôt d'argent</option>
                                    <option value="Autre">✨ Autre</option>
                                </select>
                                @error('type') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Date *</label>
                                <input wire:model="date" type="date" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500">
                                @error('date') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <!-- VOYAGE - Pour achat et vente -->
                            @if(in_array($type, ['achat', 'vente']))
                                <div class="col-span-2" wire:key="voyage-select-{{ $type }}">
                                    <label class="block text-sm font-medium text-gray-700">🚗 Voyage *</label>
                                    <select wire:model.live="voyage_id" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500">
                                        <option value="">Sélectionner un voyage</option>
                                        @foreach($voyages as $voyage)
                                            <option value="{{ $voyage->id }}">{{ $voyage->reference }}</option>
                                        @endforeach
                                    </select>
                                    <p class="mt-1 text-xs text-gray-500">Obligatoire pour les achats et ventes</p>
                                </div>
                            @endif

                            <!-- CHARGEMENTS - Pour achat seulement -->
                            @if($type === 'achat' && $voyage_id)
                                <div class="col-span-2" wire:key="chargements-{{ $voyage_id }}">
                                    <label class="block text-sm font-medium text-gray-700">📦 Chargements liés *</label>
                                    @php
                                        $chargementsVoyage = collect($chargements)->where('voyage_id', $voyage_id);
                                        $totalPoidsSelectionne = 0;
                                        $totalMontantSelectionne = 0;
                                        foreach($chargement_ids as $id) {
                                            $charg = $chargementsVoyage->firstWhere('id', $id);
                                            if($charg) {
                                                $totalPoidsSelectionne += $charg->poids_depart_kg;
                                                $totalMontantSelectionne += $charg->poids_depart_kg * 1500;
                                            }
                                        }
                                    @endphp
                                    <div class="mt-2 max-h-32 overflow-y-auto border border-gray-200 rounded p-2">
                                        <div class="flex flex-wrap gap-2">
                                            @forelse($chargementsVoyage as $chargement)
                                                <label class="flex items-center bg-gray-50 px-3 py-2 rounded-lg border border-gray-300 hover:bg-blue-50 cursor-pointer">
                                                    <input type="checkbox" wire:model.live="chargement_ids" value="{{ $chargement->id }}" class="mr-2">
                                                    <span class="text-sm whitespace-nowrap">{{ $chargement->reference }} - {{ $chargement->produit->nom_complet ?? $chargement->produit->nom ?? 'N/A' }} ({{ number_format($chargement->poids_depart_kg, 0) }}kg)</span>
                                                </label>
                                            @empty
                                                <p class="text-sm text-gray-500">Aucun chargement pour ce voyage</p>
                                            @endforelse
                                        </div>
                                    </div>
                                    @if(!empty($chargement_ids))
                                        <div class="mt-2 p-2 bg-blue-50 rounded text-xs">
                                            <strong>Sélection:</strong> {{ number_format($totalPoidsSelectionne, 0) }}kg → {{ number_format($totalMontantSelectionne, 0) }} MGA
                                        </div>
                                    @endif
                                    <p class="mt-1 text-xs text-gray-500">Sélectionner les chargements concernés - Le montant sera calculé automatiquement</p>
                                </div>
                            @endif

                            <!-- DECHARGEMENTS - Pour vente seulement -->
                            @if($type === 'vente' && $voyage_id)
                                <div class="col-span-2" wire:key="dechargements-{{ $voyage_id }}">
                                    <label class="block text-sm font-medium text-gray-700">📦 Déchargements liés *</label>
                                    @php
                                        $dechargementsVoyage = collect($dechargements)->where('voyage_id', $voyage_id);
                                        $totalMontantVenteSelectionne = 0;
                                        foreach($dechargement_ids as $id) {
                                            $dech = $dechargementsVoyage->firstWhere('id', $id);
                                            if($dech) {
                                                $totalMontantVenteSelectionne += $dech->montant_total_mga;
                                            }
                                        }
                                    @endphp
                                    <div class="mt-2 max-h-32 overflow-y-auto border border-gray-200 rounded p-2">
                                        <div class="flex flex-wrap gap-2">
                                            @forelse($dechargementsVoyage as $dechargement)
                                                <label class="flex items-center bg-gray-50 px-3 py-2 rounded-lg border border-gray-300 hover:bg-green-50 cursor-pointer">
                                                    <input type="checkbox" wire:model.live="dechargement_ids" value="{{ $dechargement->id }}" class="mr-2">
                                                    <span class="text-sm whitespace-nowrap">{{ $dechargement->reference }} - {{ $dechargement->chargement->produit->nom_complet ?? $dechargement->chargement->produit->nom ?? 'N/A' }} ({{ number_format($dechargement->montant_total_mga, 0) }} MGA)</span>
                                                </label>
                                            @empty
                                                <p class="text-sm text-gray-500">Aucun déchargement pour ce voyage</p>
                                            @endforelse
                                        </div>
                                    </div>
                                    @if(!empty($dechargement_ids))
                                        <div class="mt-2 p-2 bg-green-50 rounded text-xs">
                                            <strong>Sélection:</strong> {{ number_format($totalMontantVenteSelectionne, 0) }} MGA
                                        </div>
                                    @endif
                                    <p class="mt-1 text-xs text-gray-500">Sélectionner les déchargements concernés - Le montant sera calculé automatiquement</p>
                                </div>
                            @endif

                            <!-- LIEUX AFFICHES - Auto-calculé -->
                            @if($lieux_display)
                                <div class="col-span-2">
                                    <label class="block text-sm font-medium text-gray-700">
                                        📍 
                                        @if($type === 'achat')
                                            Lieux de chargement
                                        @elseif($type === 'vente')
                                            Lieux de livraison
                                        @else
                                            Lieux concernés
                                        @endif
                                    </label>
                                    <div class="mt-1 p-2 bg-gray-50 border border-gray-200 rounded-md">
                                        <span class="text-sm font-medium text-blue-600">{{ $lieux_display }}</span>
                                    </div>
                                    <p class="mt-1 text-xs text-gray-500">
                                        @if($type === 'achat')
                                            Lieux de départ des chargements sélectionnés
                                        @elseif($type === 'vente')
                                            Lieux de destination des déchargements sélectionnés
                                        @else
                                            Auto-calculé selon les éléments sélectionnés
                                        @endif
                                    </p>
                                </div>
                            @endif
                            
                            <!-- MONTANT - Auto-calculé ou manuel -->
                            <div class="col-span-2">
                                <label class="block text-sm font-medium text-gray-700">
                                    💰 Montant (MGA) *
                                    @if(in_array($type, ['achat', 'vente']))
                                        <span class="text-xs text-blue-500">(Auto-calculé)</span>
                                        <button type="button" wire:click="recalculerMontant" class="ml-2 text-xs bg-blue-100 text-blue-700 px-2 py-1 rounded">Recalculer</button>
                                    @endif
                                </label>
                                <div class="relative">
                                    <input wire:model="montant_mga" type="number" step="0.01" 
                                           @if(in_array($type, ['achat', 'vente'])) readonly @endif
                                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500 @if(in_array($type, ['achat', 'vente'])) bg-gray-50 @endif">
                                    @if(in_array($type, ['achat', 'vente']) && $montant_mga)
                                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                            <span class="text-green-600 text-sm font-medium">{{ number_format($montant_mga, 0) }}</span>
                                        </div>
                                    @endif
                                </div>
                                @error('montant_mga') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <!-- 2. PARTICIPANTS -->
                            <div class="col-span-2">
                                <h4 class="text-md font-medium text-gray-900 mb-3 border-b pb-2 mt-4">👥 Participants</h4>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">
                                    💸 Qui donne l'argent
                                    @if($type === 'achat') <span class="text-xs text-blue-500">(Auto-rempli)</span> @endif
                                </label>
                                <input wire:model="from_nom" type="text" placeholder="Ex: Jean Dupont, Société ABC, Client externe..." 
                                       @if($type === 'achat') readonly @endif
                                       class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500 @if($type === 'achat') bg-gray-50 @endif">
                                <p class="mt-1 text-xs text-gray-500">
                                    @if($type === 'achat')
                                        Auto-rempli avec les lieux de chargement (départ)
                                    @else
                                        Saisie libre - peut être externe au système
                                    @endif
                                </p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">
                                    💰 Qui reçoit l'argent
                                    @if($type === 'vente') <span class="text-xs text-blue-500">(Auto-rempli)</span> @endif
                                </label>
                                <input wire:model="to_nom" type="text" placeholder="Ex: Marie Martin, Fournisseur XYZ, Chauffeur..." 
                                       @if($type === 'vente') readonly @endif
                                       class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500 @if($type === 'vente') bg-gray-50 @endif">
                                <p class="mt-1 text-xs text-gray-500">
                                    @if($type === 'vente')
                                        Auto-rempli avec les lieux de livraison (destination)
                                    @else
                                        Saisie libre - peut être externe au système
                                    @endif
                                </p>
                            </div>

                            <!-- 3. MODALITÉS DE PAIEMENT -->
                            <div class="col-span-2">
                                <h4 class="text-md font-medium text-gray-900 mb-3 border-b pb-2 mt-4">💳 Modalités de paiement</h4>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Mode de paiement *</label>
                                <select wire:model="mode_paiement" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="especes">💵 Espèces</option>
                                    <option value="mobile_money">📱 Mobile Money</option>
                                    <option value="banque">🏦 Banque</option>
                                    <option value="credit">💳 À crédit</option>
                                </select>
                                @error('mode_paiement') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Statut *</label>
                                <select wire:model.live="statut" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="payee">✅ Payée</option>
                                    <option value="partiellement_payee">⚠️ Partiellement payée</option>
                                    <option value="attente">⏳ En attente</option>
                                    <option value="annule">❌ Annulé</option>
                                </select>
                                @error('statut') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <!-- Reste à payer - Conditionnel -->
                            @if($statut === 'partiellement_payee')
                                <div class="col-span-2">
                                    <label class="block text-sm font-medium text-gray-700">💰 Reste à payer (MGA) *</label>
                                    <input wire:model="reste_a_payer" type="number" step="0.01" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500">
                                    @error('reste_a_payer') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                    <p class="mt-1 text-xs text-gray-500">Ce champ apparaît car le statut est "Partiellement payée"</p>
                                </div>
                            @endif

                            <!-- 4. INFORMATIONS COMPLÉMENTAIRES -->
                            <div class="col-span-2">
                                <h4 class="text-md font-medium text-gray-900 mb-3 border-b pb-2 mt-4">📝 Informations complémentaires</h4>
                            </div>

                            <div class="col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Observation</label>
                                <textarea wire:model="observation" rows="2" placeholder="Notes supplémentaires (français + malagasy)..." class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 sm:ml-3 sm:w-auto sm:text-sm">
                            {{ $editingTransaction ? 'Modifier' : 'Ajouter' }}
                        </button>
                        <button type="button" wire:click="closeTransactionModal" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Annuler
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endif
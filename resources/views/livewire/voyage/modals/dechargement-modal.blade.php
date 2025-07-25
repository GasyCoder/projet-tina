{{-- resources/views/livewire/voyage/modals/dechargement-modal.blade.php - VERSION CORRIGÉE --}}
@if($showDechargementModal)
    <div class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="closeDechargementModal"></div>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
                <form wire:submit="saveDechargement">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">
                            {{ $editingDechargement ? 'Modifier' : 'Ajouter' }} un déchargement
                        </h3>

                        <div class="grid grid-cols-2 gap-4">
                            <!-- Référence et Type -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Référence *</label>
                                <input wire:model="dechargement_reference" type="text" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500">
                                @error('dechargement_reference') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Type *</label>
                                <select wire:model="type_dechargement" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="vente">Vente</option>
                                    <option value="retour">Retour</option>
                                    <option value="depot">Dépôt</option>
                                    <option value="transfert">Transfert</option>
                                </select>
                                @error('type_dechargement') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <!-- SÉLECTION DU CHARGEMENT SOURCE -->
                            <div class="col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Chargement à décharger *</label>
                                <select wire:model="chargement_id" {{ $editingDechargement ? '' : 'wire:change="updatedChargementId"' }} class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">Sélectionner un chargement</option>
                                    @foreach($voyage->chargements as $chargement)
                                        @php
                                            $hasDechargement = \App\Models\Dechargement::where('chargement_id', $chargement->id)->exists();
                                        @endphp
                                        @if(!$hasDechargement || ($editingDechargement && $editingDechargement->chargement_id == $chargement->id))
                                            <option value="{{ $chargement->id }}">
                                                {{ $chargement->reference }} - {{ $chargement->proprietaire_nom }} 
                                                ({{ $chargement->produit->nom ?? 'N/A' }}) 
                                                - {{ number_format($chargement->poids_depart_kg, 0) }} kg
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                                @error('chargement_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <!-- Aperçu du chargement sélectionné -->
                            @if($chargement_id && $selectedChargement = $voyage->chargements->find($chargement_id))
                                <div class="col-span-2 bg-blue-50 border border-blue-200 rounded-lg p-4">
                                    <h4 class="font-medium text-blue-900 mb-2">📦 Chargement {{ $selectedChargement->reference }}</h4>
                                    <div class="grid grid-cols-2 gap-4 text-sm">
                                        <div>
                                            <span class="text-blue-700">Propriétaire:</span> {{ $selectedChargement->proprietaire_nom }}
                                        </div>
                                        <div>
                                            <span class="text-blue-700">Produit:</span> {{ $selectedChargement->produit->nom ?? 'N/A' }}
                                        </div>
                                        <div>
                                            <span class="text-blue-700">Poids chargé:</span> {{ number_format($selectedChargement->poids_depart_kg, 0) }} kg
                                        </div>
                                        <div>
                                            <span class="text-blue-700">Sacs chargés:</span> {{ $selectedChargement->sacs_pleins_depart }}
                                            @if($selectedChargement->sacs_demi_depart > 0)
                                                + {{ $selectedChargement->sacs_demi_depart }}/2
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- Pointeur et dépot-->
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Nom du pointeur</label>
                                <input 
                                    wire:model="pointeur_nom"
                                    type="text"
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="Ex: Ndriana, Ravo..."
                                >
                                @error('pointeur_nom') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Lieu de livraison (Dépot)</label>
                                <select wire:model="lieu_livraison_id" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">Sélectionner un lieu</option>
                                    @foreach($destinations as $destination)
                                        <option value="{{ $destination->id }}">{{ $destination->nom }} ({{ $destination->region }})</option>
                                    @endforeach
                                </select>
                                @error('lieu_livraison_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <!-- SEULEMENT quantités reçues à l'arrivée -->
                            <div class="col-span-2">
                                <h4 class="text-md font-medium text-gray-900 mb-2 border-t pt-4">Quantités reçues à destination</h4>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Sacs pleins reçus</label>
                                <input wire:model="sacs_pleins_arrivee" type="number" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500">
                                @error('sacs_pleins_arrivee') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Sacs demi reçus</label>
                                <input wire:model="sacs_demi_arrivee" type="number" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500">
                                @error('sacs_demi_arrivee') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Poids reçu (kg)</label>
                                <input wire:model="poids_arrivee_kg" type="number" step="0.01" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500">
                                @error('poids_arrivee_kg') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Statut commercial *</label>
                                <select wire:model="statut_commercial" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="en_attente">En attente</option>
                                    <option value="vendu">Vendu</option>
                                    <option value="retourne">Retourné</option>
                                    <option value="transfere">Transféré</option>
                                </select>
                                @error('statut_commercial') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <!-- Calcul automatique des écarts -->
                            @if($chargement_id && $poids_arrivee_kg && $selectedChargement = $voyage->chargements->find($chargement_id))
                                <div class="col-span-2 bg-yellow-50 border border-yellow-200 rounded-lg p-3">
                                    @php
                                        $ecart = $selectedChargement->poids_depart_kg - $poids_arrivee_kg;
                                        $pourcentage = $selectedChargement->poids_depart_kg > 0 ? ($ecart / $selectedChargement->poids_depart_kg) * 100 : 0;
                                    @endphp
                                    <p class="text-sm text-yellow-800">
                                        <strong>Écart :</strong> {{ number_format($ecart, 1) }} kg 
                                        ({{ number_format($pourcentage, 1) }}% {{ $ecart > 0 ? 'de perte' : 'de gain' }})
                                    </p>
                                </div>
                            @endif

                            {{-- Section Informations financières avec calculs automatiques --}}
                            @if($type_dechargement === 'vente')
                                <div class="col-span-2">
                                    <h4 class="text-md font-medium text-gray-900 mb-2 border-t pt-4">💰 Informations financières</h4>
                                </div>

                                {{-- Prix unitaire (saisie manuelle) --}}
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Prix unitaire (MGA/kg) *</label>
                                    <input 
                                        wire:model.live="prix_unitaire_mga" 
                                        type="number" 
                                        step="0.01" 
                                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500"
                                        placeholder="Ex: 2500"
                                    >
                                    @error('prix_unitaire_mga') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>

                                {{-- Montant total (calculé automatiquement) --}}
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">
                                        Montant total (MGA) 
                                        <span class="text-xs text-blue-600">✨ Calculé automatiquement</span>
                                    </label>
                                    <input 
                                        wire:model="montant_total_mga" 
                                        type="text" 
                                        readonly 
                                        class="mt-1 block w-full border border-gray-200 rounded-md shadow-sm py-2 px-3 bg-blue-50 text-blue-900 font-medium cursor-not-allowed"
                                        placeholder="Sera calculé automatiquement"
                                    >
                                    <p class="mt-1 text-xs text-blue-600">
                                        @if($prix_unitaire_mga && $poids_arrivee_kg)
                                            {{ number_format($prix_unitaire_mga, 0) }} MGA/kg × {{ number_format($poids_arrivee_kg, 1) }} kg = {{ number_format($montant_total_mga, 0) }} MGA
                                        @else
                                            Remplissez le prix unitaire et le poids reçu
                                        @endif
                                    </p>
                                </div>

                                {{-- Paiement reçu (saisie manuelle ou auto) --}}
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">
                                        Paiement reçu (MGA)
                                        <button 
                                            type="button" 
                                            wire:click="setFullPayment" 
                                            class="ml-2 text-xs bg-green-100 text-green-700 px-2 py-1 rounded hover:bg-green-200"
                                            @if(!$montant_total_mga) disabled @endif
                                        >
                                            💰 Paiement complet
                                        </button>
                                    </label>
                                    <input 
                                        wire:model.live="paiement_mga" 
                                        type="number" 
                                        step="0.01" 
                                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500"
                                        placeholder="Montant payé par le client"
                                    >
                                    @error('paiement_mga') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>

                                {{-- Reste à encaisser (calculé automatiquement) --}}
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">
                                        Reste à encaisser (MGA)
                                        <span class="text-xs text-blue-600">✨ Calculé automatiquement</span>
                                    </label>
                                    
                                    @php
                                        // ✅ Conversion sécurisée des valeurs
                                        $reste_numerique = is_numeric($reste_mga) ? (float)$reste_mga : 0;
                                        $montant_total_numerique = is_numeric($montant_total_mga) ? (float)$montant_total_mga : 0;
                                        $paiement_numerique = is_numeric($paiement_mga) ? (float)$paiement_mga : 0;
                                    @endphp
                                    
                                    <div class="mt-1 block w-full border rounded-md shadow-sm py-2 px-3 font-medium
                                        @if($reste_numerique > 0) bg-yellow-50 border-yellow-300 text-yellow-800
                                        @elseif($reste_numerique < 0) bg-red-50 border-red-300 text-red-800  
                                        @else bg-green-50 border-green-300 text-green-800 @endif">
                                        
                                        @if($reste_numerique > 0)
                                            <span class="flex items-center">
                                                ⚠️ {{ number_format($reste_numerique, 0) }} MGA 
                                                <span class="ml-2 text-xs">(Paiement partiel)</span>
                                            </span>
                                        @elseif($reste_numerique < 0)
                                            <span class="flex items-center">
                                                ❌ {{ number_format(abs($reste_numerique), 0) }} MGA 
                                                <span class="ml-2 text-xs">(Trop-perçu - Vérifiez les montants)</span>
                                            </span>
                                        @else
                                            <span class="flex items-center">
                                                ✅ 0 MGA 
                                                <span class="ml-2 text-xs">(Paiement complet)</span>
                                            </span>
                                        @endif
                                    </div>
                                    
                                    {{-- Aide contextuelle --}}
                                    @if($montant_total_numerique > 0 || $paiement_numerique > 0)
                                        <p class="mt-1 text-xs text-gray-600">
                                            {{ number_format($montant_total_numerique, 0) }} - {{ number_format($paiement_numerique, 0) }} = {{ number_format($reste_numerique, 0) }} MGA
                                        </p>
                                    @endif
                                </div>

                                {{-- Résumé visuel rapide --}}
                                @if($montant_total_mga)
                                    <div class="col-span-2 bg-gray-50 border border-gray-200 rounded-lg p-3">
                                        <div class="grid grid-cols-3 gap-4 text-sm text-center">
                                            <div>
                                                <div class="text-gray-600">Montant dû</div>
                                                <div class="font-bold text-lg text-blue-600">{{ number_format($montant_total_mga, 0) }} MGA</div>
                                            </div>
                                            <div>
                                                <div class="text-gray-600">Payé</div>
                                                <div class="font-bold text-lg text-green-600">{{ number_format($paiement_mga ?: 0, 0) }} MGA</div>
                                            </div>
                                            <div>
                                                <div class="text-gray-600">Reste</div>
                                                <div class="font-bold text-lg {{ $reste_mga > 0 ? 'text-yellow-600' : ($reste_mga < 0 ? 'text-red-600' : 'text-green-600') }}">
                                                    {{ number_format($reste_mga ?: 0, 0) }} MGA
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endif

                            <div class="col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Observation</label>
                                <textarea wire:model="dechargement_observation" rows="3" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500"></textarea>
                                @error('dechargement_observation') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <!-- ✅ NOUVEAU : Bouton Aperçu au lieu de Ajouter directement -->
                        <button type="button" wire:click="showPreview" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 sm:ml-3 sm:w-auto sm:text-sm">
                            🔍 Aperçu avant {{ $editingDechargement ? 'modification' : 'ajout' }}
                        </button>
                        <button type="button" wire:click="closeDechargementModal" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Annuler
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endif
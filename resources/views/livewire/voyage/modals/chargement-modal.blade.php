{{-- resources/views/livewire/voyage/modals/chargement-modal.blade.php --}}
@if($showChargementModal)
    <div class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="closeChargementModal"></div>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
                <form wire:submit="saveChargement">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">
                            {{ $editingChargement ? 'Modifier' : 'Ajouter' }} un chargement
                        </h3>

                        <div class="grid grid-cols-2 gap-4">
                            <!-- Référence -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Référence *</label>
                                <input wire:model="chargement_reference" type="text" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500">
                                @error('chargement_reference') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <!-- Date -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Date *</label>
                                <input wire:model="date" type="date" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500">
                                @error('date') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <!-- Sélection du type de propriétaire -->
                            <div class="col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-3">Propriétaire *</label>
                                <div class="flex flex-row space-x-6">
                                    <div class="flex items-center">
                                        <input id="proprietaire_defaut" 
                                               name="type_proprietaire"
                                               type="radio" 
                                               value="defaut"
                                               wire:click="setTypeProprietaire('defaut')"
                                               @if($type_proprietaire === 'defaut') checked @endif
                                               class="h-4 w-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                                        <label for="proprietaire_defaut" class="ml-2 text-sm text-gray-700 cursor-pointer"
                                               wire:click="setTypeProprietaire('defaut')">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                Par défaut (Mme TINAH)
                                            </span>
                                        </label>
                                    </div>
                                    
                                    <div class="flex items-center">
                                        <input id="proprietaire_autre" 
                                               name="type_proprietaire"
                                               type="radio" 
                                               value="autre"
                                               wire:click="setTypeProprietaire('autre')"
                                               @if($type_proprietaire === 'autre') checked @endif
                                               class="h-4 w-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                                        <label for="proprietaire_autre" class="ml-2 text-sm text-gray-700 cursor-pointer"
                                               wire:click="setTypeProprietaire('autre')">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                Autre propriétaire
                                            </span>
                                        </label>
                                    </div>
                                </div>
                                @error('type_proprietaire') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <!-- Champs propriétaire (affichage conditionnel) -->
                            @if($type_proprietaire === 'autre')
                                <!-- Champs éditables pour autre propriétaire -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Nom du propriétaire *</label>
                                    <input 
                                        wire:model="proprietaire_nom"
                                        type="text"
                                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500"
                                        placeholder="Ex: Rabe, Société Hery..."
                                    >
                                    @error('proprietaire_nom') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Contact propriétaire</label>
                                    <input 
                                        wire:model="proprietaire_contact"
                                        type="text"
                                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500"
                                        placeholder="Téléphone, email..."
                                    >
                                    @error('proprietaire_contact') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>
                            @endif

                            <!-- Produit -->
                            <div class="col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Produit *</label>
                                <select wire:model="produit_id" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">Sélectionner un produit</option>
                                    @foreach($produits as $produit)
                                        <option value="{{ $produit->id }}">{{ $produit->nom }} {{ $produit->variete ? '(' . $produit->variete . ')' : '' }}</option>
                                    @endforeach
                                </select>
                                @error('produit_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <!-- Point de chargement -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Point chargement *</label>
                                <select wire:model="depart_id" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">Sélectionner un départ</option>
                                    @foreach($departs as $depart)
                                        <option value="{{ $depart->id }}">{{ $depart->nom }} ({{ $depart->region }})</option>
                                    @endforeach
                                </select>
                                @error('depart_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <!-- Nom du chargeur -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Nom du chargeur *</label>
                                <input 
                                    wire:model="chargeur_nom"
                                    type="text"
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="Ex: Rakoto, SARL Vato..."
                                >
                                @error('chargeur_nom') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <!-- Contact chargeur -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Contact chargeur (optionnel)</label>
                                <input 
                                    wire:model="chargeur_contact"
                                    type="text"
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="Téléphone, email..."
                                >
                                @error('chargeur_contact') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <!-- Sacs pleins -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Sacs pleins *</label>
                                <input wire:model="sacs_pleins_depart" type="number" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500">
                                @error('sacs_pleins_depart') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <!-- Sacs demi -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Sacs demi</label>
                                <input wire:model="sacs_demi_depart" type="number" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500">
                                @error('sacs_demi_depart') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <!-- Poids -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Poids (kg) *</label>
                                <input wire:model="poids_depart_kg" type="number" step="0.01" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500">
                                @error('poids_depart_kg') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <!-- Observation -->
                            <div class="col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Observation</label>
                                <textarea wire:model="chargement_observation" rows="3" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500"></textarea>
                                @error('chargement_observation') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 sm:ml-3 sm:w-auto sm:text-sm">
                            {{ $editingChargement ? 'Modifier' : 'Ajouter' }}
                        </button>
                        <button type="button" wire:click="closeChargementModal" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Annuler
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endif
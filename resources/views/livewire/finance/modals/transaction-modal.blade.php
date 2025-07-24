{{-- resources/views/livewire/finance/modals/transaction-modal.blade.php - CORRIGÉ --}}
@if($showTransactionModal)
    <div class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="closeTransactionModal"></div>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-3xl sm:w-full">
                <form wire:submit="saveTransaction">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">
                            {{ $editingTransaction ? 'Modifier' : 'Ajouter' }} une transaction
                        </h3>

                        <div class="grid grid-cols-2 gap-4">
                            <!-- Référence et Date -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Référence *</label>
                                <input wire:model="reference" type="text" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500">
                                @error('reference') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Date *</label>
                                <input wire:model="date" type="date" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500">
                                @error('date') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <!-- Type selon VOS vraies tables -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Type de transaction *</label>
                                <select wire:model="type" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">Sélectionner</option>
                                    <option value="achat">🛒 Achat de produits</option>
                                    <option value="vente">💰 Vente de produits</option>
                                    <option value="transfert">🔄 Transfert (NAKA VOLA)</option>
                                    <option value="frais">🧾 Frais (transport, péage...)</option>
                                    <option value="commission">💼 Commission</option>
                                    <option value="paiement">💳 Paiement facture/dette</option>
                                    <option value="avance">💸 Avance d'argent</option>
                                    <option value="depot">📥 Dépôt d'argent</option>
                                    <option value="retrait">📤 Retrait d'argent</option>
                                </select>
                                @error('type') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Montant (MGA) *</label>
                                <input wire:model="montant_mga" type="number" step="0.01" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500">
                                @error('montant_mga') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <!-- QUI DONNE L'ARGENT - CHAMPS LIBRES -->
                            <div class="col-span-2">
                                <h4 class="text-md font-medium text-gray-900 mb-2 border-t pt-4">💸 Qui donne l'argent (CHAMPS LIBRES)</h4>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Nom (saisie libre)</label>
                                <input wire:model="from_nom" type="text" placeholder="Ex: Jean Dupont, Société ABC, Client externe..." class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500">
                                <p class="mt-1 text-xs text-gray-500">💡 Nom libre - peut être quelqu'un d'externe au système</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Compte source</label>
                                <input wire:model="from_compte" type="text" placeholder="Ex: especes, airtel_money, boa_207142800027..." class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500">
                                <p class="mt-1 text-xs text-gray-500">💡 Nom libre du compte (espèces, mobile money, banque...)</p>
                            </div>

                            <!-- QUI REÇOIT L'ARGENT - CHAMPS LIBRES -->
                            <div class="col-span-2">
                                <h4 class="text-md font-medium text-gray-900 mb-2 border-t pt-4">💰 Qui reçoit l'argent (CHAMPS LIBRES)</h4>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Nom (saisie libre)</label>
                                <input wire:model="to_nom" type="text" placeholder="Ex: Marie Martin, Fournisseur XYZ, Chauffeur..." class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500">
                                <p class="mt-1 text-xs text-gray-500">💡 Nom libre - peut être quelqu'un d'externe au système</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Compte destination</label>
                                <input wire:model="to_compte" type="text" placeholder="Ex: especes, airtel_money, boa_207142800027..." class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500">
                                <p class="mt-1 text-xs text-gray-500">💡 Nom libre du compte (espèces, mobile money, banque...)</p>
                            </div>

                            <!-- Détails -->
                            <div class="col-span-2">
                                <h4 class="text-md font-medium text-gray-900 mb-2 border-t pt-4">📋 Détails de la transaction</h4>
                            </div>

                            <div class="col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Objet/Description *</label>
                                <textarea wire:model="objet" rows="2" placeholder="Description détaillée de la transaction..." class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500"></textarea>
                                @error('objet') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Voyage lié</label>
                                <select wire:model="voyage_id" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">Aucun voyage</option>
                                    @foreach($voyages as $voyage)
                                        <option value="{{ $voyage->id }}">{{ $voyage->reference }}</option>
                                    @endforeach
                                </select>
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
                                <select wire:model="statut" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="attente">⏳ En attente</option>
                                    <option value="confirme">✅ Confirmé</option>
                                    <option value="annule">❌ Annulé</option>
                                </select>
                                @error('statut') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
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

{{-- Modal compte corrigé aussi --}}
@if($showCompteModal)
    <div class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="closeCompteModal"></div>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                <form wire:submit="saveCompte">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">
                            {{ $editingCompte ? 'Modifier' : 'Ajouter' }} un compte
                        </h3>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Type de compte *</label>
                                <select wire:model="type_compte" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="principal">💰 Principal (espèces)</option>
                                    <option value="mobile_money">📱 Mobile Money</option>
                                    <option value="banque">🏦 Banque</option>
                                    <option value="credit">💳 Crédit/Dette</option>
                                </select>
                                @error('type_compte') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Nom du compte *</label>
                                <input wire:model="nom_compte" type="text" placeholder="Ex: Espèces, Airtel Money, BOA 207142800027..." class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500">
                                @error('nom_compte') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div class="col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Propriétaire (CHAMP LIBRE)</label>
                                <input wire:model="nom_proprietaire" type="text" placeholder="Ex: Jean Dupont, Société Transport, Caissier principal..." class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500">
                                <p class="mt-1 text-xs text-gray-500">💡 Nom libre du propriétaire - peut être externe au système</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Numéro de compte</label>
                                <input wire:model="numero_compte" type="text" placeholder="N° de compte, RIB, numéro mobile..." class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Solde initial (MGA) *</label>
                                <input wire:model="solde_actuel_mga" type="number" step="0.01" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500">
                                @error('solde_actuel_mga') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div class="col-span-2">
                                <label class="flex items-center">
                                    <input wire:model="compte_actif" type="checkbox" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                    <span class="ml-2 text-sm text-gray-700">Compte actif</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 sm:ml-3 sm:w-auto sm:text-sm">
                            {{ $editingCompte ? 'Modifier' : 'Ajouter' }}
                        </button>
                        <button type="button" wire:click="closeCompteModal" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Annuler
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endif
    <!-- Modal -->
    @if($showModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 dark:bg-gray-900 bg-opacity-75 dark:bg-opacity-75 transition-opacity" wire:click="closeModal"></div>

                <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <form wire:submit="save">
                        <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="flex items-center mb-4">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                                    {{ $editingLieu ? 'Modifier' : 'Créer' }} un lieu
                                </h3>
                            </div>

                            <div class="space-y-4">
                                <!-- Nom -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nom *</label>
                                    <input 
                                        wire:model="nom"
                                        type="text"
                                        class="mt-1 block w-full bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500 text-gray-900 dark:text-white text-sm"
                                        placeholder="BORIZINY, VISHAL, Dépôt Mounaf..."
                                    >
                                    @error('nom') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <!-- Type (corrigé) -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Type *</label>
                                        <select 
                                            wire:model="type" 
                                            class="mt-1 block w-full bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500 text-gray-900 dark:text-white text-sm"
                                        >
                                            <option value="">— Sélectionner —</option>
                                            <option value="origine">Origine</option>
                                            <option value="depot">Dépôt</option>
                                            <option value="magasin">Magasin</option>
                                            <option value="boutique">Boutique</option>
                                        </select>
                                        @error('type') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                                    </div>

                                    <!-- Région -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Région</label>
                                        <input 
                                            wire:model="region"
                                            type="text"
                                            class="mt-1 block w-full bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500 text-gray-900 dark:text-white text-sm"
                                            placeholder="SOFIA, ANALAMANGA..."
                                        >
                                        @error('region') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                                    </div>
                                </div>

                                <!-- Téléphone -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Téléphone</label>
                                    <input 
                                        wire:model="telephone"
                                        type="text"
                                        class="mt-1 block w-full bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500 text-gray-900 dark:text-white text-sm"
                                        placeholder="Ex: 034 12 34 56"
                                    >
                                    @error('telephone') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                                </div>  

                                <!-- Adresse -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Adresse</label>
                                    <textarea 
                                        wire:model="adresse"
                                        rows="3"
                                        class="mt-1 block w-full bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500 text-gray-900 dark:text-white text-sm"
                                        placeholder="Adresse complète du lieu..."
                                    ></textarea>
                                    @error('adresse') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                                </div>

                                <!-- Actif -->
                                <div class="flex items-center">
                                    <input 
                                        wire:model="actif"
                                        type="checkbox"
                                        class="h-4 w-4 text-blue-600 dark:text-blue-400 focus:ring-blue-500 border-gray-300 dark:border-gray-600 rounded bg-white dark:bg-gray-700"
                                    >
                                    <label class="ml-2 block text-sm text-gray-900 dark:text-gray-300">Lieu actif</label>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-offset-gray-800 sm:ml-3 sm:w-auto sm:text-sm">
                                {{ $editingLieu ? 'Modifier' : 'Créer' }}
                            </button>
                            <button type="button" wire:click="closeModal" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-600 text-base font-medium text-gray-700 dark:text-white hover:bg-gray-50 dark:hover:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-offset-gray-800 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                Annuler
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
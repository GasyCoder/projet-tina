{{-- Modal de création/édition des catégories --}}
@if ($showFormModal)
    <div class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <!-- Overlay avec animation -->
            <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75 dark:bg-gray-900 dark:bg-opacity-75 backdrop-blur-sm"
                wire:click="closeModal"></div>

            <!-- Modal container - AGRANDIE -->
            <div
                class="inline-block w-full max-w-4xl my-8 overflow-hidden text-left align-middle transition-all transform bg-white dark:bg-gray-800 shadow-2xl rounded-xl">
                <!-- En-tête de la modal - PLUS SPACIEUSE -->
                <div
                    class="flex items-center justify-between px-8 py-6 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-gray-900 dark:to-gray-800">
                    <div class="flex items-center">
                        <div class="p-3 bg-blue-100 dark:bg-blue-900 rounded-xl mr-4 shadow-lg">
                            <svg class="w-8 h-8 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                @if ($editingId)
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                @else
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                @endif
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900 dark:text-white">
                                {{ $editingId ? 'Modifier la catégorie' : 'Nouvelle catégorie' }}
                            </h3>
                            <p class="text-base text-gray-600 dark:text-gray-400 mt-1">
                                {{ $editingId ? 'Modifiez les informations de la catégorie existante' : 'Créez une nouvelle catégorie comptable pour organiser vos transactions' }}
                            </p>
                        </div>
                    </div>
                    <button wire:click="closeModal"
                        class="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-all duration-200">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Formulaire - PLUS SPACIEUX -->
                <div class="px-8 py-8">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- Colonne gauche -->
                        <div class="space-y-6">
                            <!-- Code comptable -->
                            <div>
                                <label class="block text-base font-semibold text-gray-700 dark:text-gray-300 mb-3">
                                    <span class="flex items-center">
                                        <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14" />
                                        </svg>
                                        Code comptable
                                        <span class="text-red-500 ml-1">*</span>
                                    </span>
                                </label>
                                <input type="text" wire:model.defer="code_comptable"
                                    placeholder="Ex: 05, 10A, 625..." maxlength="10"
                                    class="w-full px-4 py-3 text-base border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white font-mono placeholder-gray-500 dark:placeholder-gray-400 transition-all duration-200">
                                @error('code_comptable')
                                    <p
                                        class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center bg-red-50 dark:bg-red-900/20 p-2 rounded-lg">
                                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- Nom -->
                            <div>
                                <label class="block text-base font-semibold text-gray-700 dark:text-gray-300 mb-3">
                                    <span class="flex items-center">
                                        <svg class="w-5 h-5 mr-2 text-green-500" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                        </svg>
                                        Nom de la catégorie
                                        <span class="text-red-500 ml-1">*</span>
                                    </span>
                                </label>
                                <input type="text" wire:model.defer="nom"
                                    placeholder="Ex: Carburant, Frais de transport, Ventes produits..."
                                    class="w-full px-4 py-3 text-base border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 transition-all duration-200">
                                @error('nom')
                                    <p
                                        class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center bg-red-50 dark:bg-red-900/20 p-2 rounded-lg">
                                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- Budget -->
                            <div>
                                <label class="block text-base font-semibold text-gray-700 dark:text-gray-300 mb-3">
                                    <span class="flex items-center">
                                        <svg class="w-5 h-5 mr-2 text-amber-500" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-10v.01M12 6v.01" />
                                        </svg>
                                        Budget Maximum (Ar)
                                        <span class="text-red-500 ml-1">*</span>
                                    </span>
                                </label>
                                <input type="number" wire:model.defer="budget" placeholder="0,00" step="0.01"
                                    min="0" inputmode="decimal"
                                    class="w-full px-4 py-3 text-base border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 transition-all duration-200">
                                @error('budget')
                                    <p
                                        class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center bg-red-50 dark:bg-red-900/20 p-2 rounded-lg">
                                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>

                        <!-- Colonne droite -->
                        <div class="space-y-6">
                            <!-- Description -->
                            <div>
                                <label class="block text-base font-semibold text-gray-700 dark:text-gray-300 mb-3">
                                    <span class="flex items-center">
                                        <svg class="w-5 h-5 mr-2 text-orange-500" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        Description (optionnel)
                                    </span>
                                </label>
                                <textarea wire:model.defer="description" rows="4" placeholder="Description détaillée de cette catégorie..."
                                    class="w-full px-4 py-3 text-base border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 transition-all duration-200 resize-none"></textarea>
                                @error('description')
                                    <p
                                        class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center bg-red-50 dark:bg-red-900/20 p-2 rounded-lg">
                                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- Statut de la catégorie -->
                            <div class="bg-gray-50 dark:bg-gray-700/50 p-6 rounded-xl">
                                <label class="block text-base font-semibold text-gray-700 dark:text-gray-300 mb-4">
                                    <span class="flex items-center">
                                        <svg class="w-5 h-5 mr-2 text-yellow-500" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        Statut de la catégorie
                                    </span>
                                </label>
                                <div class="flex items-start space-x-4">
                                    <label class="flex items-center space-x-3 cursor-pointer group">
                                        <input type="checkbox" wire:model.defer="is_active"
                                            class="w-5 h-5 text-blue-600 bg-white border-2 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600 transition-all duration-200">
                                        <div>
                                            <span
                                                class="text-base font-medium text-gray-700 dark:text-gray-300 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">
                                                Catégorie active
                                            </span>
                                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                                Si activée, cette catégorie sera disponible pour les nouvelles
                                                transactions et apparaîtra dans les listes actives.
                                            </p>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actions - PLUS SPACIEUSES -->
                <div
                    class="flex items-center justify-between px-8 py-6 bg-gray-50 dark:bg-gray-900 border-t border-gray-200 dark:border-gray-700">
                    <!-- Informations sur les champs requis -->
                    <div class="flex items-center text-sm text-gray-500 dark:text-gray-400">
                        <svg class="w-4 h-4 mr-2 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                clip-rule="evenodd" />
                        </svg>
                        Les champs marqués d'un <span class="text-red-500 font-medium">*</span> sont obligatoires
                    </div>

                    <!-- Boutons d'action -->
                    <div class="flex items-center space-x-4">
                        <button wire:click="closeModal" type="button"
                            class="inline-flex items-center px-6 py-3 bg-gray-500 hover:bg-gray-600 text-white font-medium rounded-xl transition-all duration-200 shadow-sm hover:shadow-md">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            Annuler
                        </button>

                        <button wire:click="save"
                            class="inline-flex items-center px-8 py-3 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-semibold rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-105">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                @if ($editingId)
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                @else
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                @endif
                            </svg>
                            {{ $editingId ? 'Mettre à jour la catégorie' : 'Créer la catégorie' }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

{{-- Modal de détails de la catégorie --}}
@if ($showDetail && $detail)
    <div class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <!-- Overlay -->
            <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75 dark:bg-gray-900 dark:bg-opacity-75 backdrop-blur-sm"
                wire:click="closeDetail"></div>

            <!-- Modal container - AGRANDIE -->
            <div
                class="inline-block w-full max-w-5xl my-8 overflow-hidden text-left align-middle transition-all transform bg-white dark:bg-gray-800 shadow-2xl rounded-xl">
                <!-- En-tête de la modal - PLUS SPACIEUSE -->
                <div
                    class="flex items-center justify-between px-8 py-6 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-900 dark:to-gray-800">
                    <div class="flex items-center">
                        <div class="p-3 bg-blue-100 dark:bg-blue-900 rounded-xl mr-4 shadow-lg">
                            <span class="text-xl font-bold text-blue-600 dark:text-blue-400">
                                {{ $detail['code_comptable'] }}
                            </span>
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900 dark:text-white">
                                {{ $detail['nom'] }}
                            </h3>
                            <p class="text-base text-gray-600 dark:text-gray-400 mt-1">
                                Détails de la catégorie comptable
                            </p>
                        </div>
                    </div>
                    <button wire:click="closeDetail"
                        class="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-all duration-200">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Contenu de la modal - PLUS SPACIEUX -->
                <div class="px-8 py-8">
                    <!-- Badge de statut - PLUS GROS -->
                    <div class="flex items-center gap-4 mb-8">
                        <span
                            class="inline-flex items-center px-4 py-2 rounded-full text-base font-semibold
                        {{ $detail['is_active'] ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300' }}">
                            <span
                                class="w-3 h-3 mr-2 rounded-full {{ $detail['is_active'] ? 'bg-green-400' : 'bg-red-400' }}"></span>
                            {{ $detail['is_active'] ? 'Catégorie Active' : 'Catégorie Inactive' }}
                        </span>
                    </div>

                    <!-- Informations principales - LAYOUT AMÉLIORÉ -->
                    <div class="grid grid-cols-1 xl:grid-cols-2 gap-10">
                        <!-- Informations générales -->
                        <div class="space-y-6">
                            <h4
                                class="text-xl font-bold text-gray-900 dark:text-white uppercase tracking-wide border-b-2 border-blue-200 dark:border-blue-700 pb-3">
                                <span class="flex items-center">
                                    <svg class="w-6 h-6 mr-2 text-blue-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Informations générales
                                </span>
                            </h4>

                            <div class="space-y-5">
                                <div class="flex items-start bg-gray-50 dark:bg-gray-700/50 p-4 rounded-xl">
                                    <svg class="w-6 h-6 text-gray-400 mr-4 mt-1 flex-shrink-0" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14" />
                                    </svg>
                                    <div>
                                        <dt
                                            class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">
                                            Code comptable</dt>
                                        <dd class="text-xl text-gray-900 dark:text-white font-mono font-bold mt-1">
                                            {{ $detail['code_comptable'] }}</dd>
                                    </div>
                                </div>

                                <div class="flex items-start bg-gray-50 dark:bg-gray-700/50 p-4 rounded-xl">
                                    <svg class="w-6 h-6 text-gray-400 mr-4 mt-1 flex-shrink-0" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                    </svg>
                                    <div>
                                        <dt
                                            class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">
                                            Nom de la catégorie</dt>
                                        <dd class="text-xl text-gray-900 dark:text-white font-bold mt-1">
                                            {{ $detail['nom'] }}</dd>
                                    </div>
                                </div>

                                <div class="flex items-start bg-gray-50 dark:bg-gray-700/50 p-4 rounded-xl">
                                    <svg class="w-6 h-6 text-gray-400 mr-4 mt-1 flex-shrink-0" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-10v.01M12 6v.01" />
                                    </svg>
                                    <div>
                                        <dt
                                            class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">
                                            Budget Maximum</dt>
                                        <dd class="text-lg text-gray-900 dark:text-white mt-1 font-bold">
                                            {{ number_format($detail['budget'] ?? 0, 0, ',', ' ') }} Ar
                                        </dd>
                                    </div>
                                </div>

                                @if ($detail['description'])
                                    <div class="flex items-start bg-gray-50 dark:bg-gray-700/50 p-4 rounded-xl">
                                        <svg class="w-6 h-6 text-gray-400 mr-4 mt-1 flex-shrink-0" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        <div>
                                            <dt
                                                class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">
                                                Description</dt>
                                            <dd class="text-lg text-gray-900 dark:text-white mt-1 leading-relaxed">
                                                {{ $detail['description'] }}</dd>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Statistiques et actions -->
                        <div class="space-y-6">
                            <h4
                                class="text-xl font-bold text-gray-900 dark:text-white uppercase tracking-wide border-b-2 border-green-200 dark:border-green-700 pb-3">
                                <span class="flex items-center">
                                    <svg class="w-6 h-6 mr-2 text-green-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                    </svg>
                                    Statistiques
                                </span>
                            </h4>

                            <!-- Statistiques en grille -->
                            <div class="grid grid-cols-2 gap-4">
                                <div
                                    class="bg-blue-50 dark:bg-blue-900/20 p-4 rounded-lg border border-blue-200 dark:border-blue-700">
                                    <div class="text-center">
                                        <p class="text-xs font-semibold text-blue-700 dark:text-blue-300 uppercase">
                                            Total</p>
                                        <p class="text-xl font-bold text-blue-900 dark:text-blue-100 mt-1">
                                            {{ number_format($detail['montant_total'] ?? 0, 0, ',', ' ') }} Ar
                                        </p>
                                    </div>
                                </div>

                                <div
                                    class="bg-green-50 dark:bg-green-900/20 p-4 rounded-lg border border-green-200 dark:border-green-700">
                                    <div class="text-center">
                                        <p class="text-xs font-semibold text-green-700 dark:text-green-300 uppercase">
                                            Ce mois</p>
                                        <p class="text-xl font-bold text-green-900 dark:text-green-100 mt-1">
                                            {{ number_format($detail['transactions_mois'] ?? 0, 0, ',', ' ') }} Ar
                                        </p>
                                    </div>
                                </div>

                                <div
                                    class="bg-purple-50 dark:bg-purple-900/20 p-4 rounded-lg border border-purple-200 dark:border-purple-700">
                                    <div class="text-center">
                                        <p
                                            class="text-xs font-semibold text-purple-700 dark:text-purple-300 uppercase">
                                            Transactions</p>
                                        <p class="text-xl font-bold text-purple-900 dark:text-purple-100 mt-1">
                                            {{ $detail['nombre_transactions'] ?? 0 }}
                                        </p>
                                    </div>
                                </div>

                                <div
                                    class="bg-amber-50 dark:bg-amber-900/20 p-4 rounded-lg border border-amber-200 dark:border-amber-700">
                                    <div class="text-center">
                                        <p class="text-xs font-semibold text-amber-700 dark:text-amber-300 uppercase">
                                            Budget</p>
                                        <p class="text-xl font-bold text-amber-900 dark:text-amber-100 mt-1">
                                            {{ number_format($detail['budget'] ?? 0, 0, ',', ' ') }} Ar
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Transactions récentes -->
                            @if (!empty($detail['recent_transactions']))
                                <div>
                                    <h5
                                        class="text-lg font-semibold text-gray-900 dark:text-white mb-3 flex items-center">
                                        <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                        </svg>
                                        Transactions récentes
                                    </h5>
                                    <div class="space-y-2">
                                        @foreach ($detail['recent_transactions'] as $transaction)
                                            <div
                                                class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg hover:shadow-md transition-all">
                                                <div>
                                                    <p class="text-sm font-medium text-gray-900 dark:text-white">
                                                        {{ $transaction['description'] }}</p>
                                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                                        {{ $transaction['date'] }}
                                                        @if ($transaction['partenaire'])
                                                            • {{ $transaction['partenaire'] }}
                                                        @endif
                                                    </p>
                                                </div>
                                                <p class="text-sm font-bold text-gray-900 dark:text-gray-100">
                                                    {{ number_format($transaction['montant'], 0, ',', ' ') }} Ar
                                                </p>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <!-- Section des actions rapides - PLUS GRANDE -->
                            <div
                                class="bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-gray-800 dark:to-gray-700 p-6 rounded-xl border border-blue-200 dark:border-gray-600">
                                <h4 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 10V3L4 14h7v7l9-11h-7z" />
                                    </svg>
                                    Actions rapides
                                </h4>
                                <div class="grid grid-cols-1 gap-3">
                                    <button wire:click="openTransactionModal({{ $detail['id'] }})"
                                        class="w-full inline-flex items-center justify-center px-4 py-3 text-base font-medium text-green-600 dark:text-green-400 bg-green-100 dark:bg-green-900/50 rounded-xl hover:bg-green-200 dark:hover:bg-green-800 transition-all duration-200">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                        </svg>
                                        Nouvelle transaction
                                    </button>

                                    <button wire:click="editModal({{ $detail['id'] }})"
                                        class="w-full inline-flex items-center justify-center px-4 py-3 text-base font-medium text-orange-600 dark:text-orange-400 bg-orange-100 dark:bg-orange-900/50 rounded-xl hover:bg-orange-200 dark:hover:bg-orange-800 transition-all duration-200">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                        Modifier la catégorie
                                    </button>

                                    <button wire:click="show({{ $detail['id'] }})"
                                        class="w-full inline-flex items-center justify-center px-4 py-3 text-base font-medium text-blue-600 dark:text-blue-400 bg-blue-100 dark:bg-blue-900/50 rounded-xl hover:bg-blue-200 dark:hover:bg-blue-800 transition-all duration-200">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        Voir toutes les transactions
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pied de la modal - PLUS SPACIEUX -->
                <div
                    class="flex items-center justify-end px-8 py-6 bg-gray-50 dark:bg-gray-900 border-t border-gray-200 dark:border-gray-700">
                    <button wire:click="closeDetail"
                        class="inline-flex items-center px-6 py-3 bg-gray-500 hover:bg-gray-600 text-white font-semibold rounded-xl transition-all duration-200 shadow-sm hover:shadow-md">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        Fermer
                    </button>
                </div>
            </div>
        </div>
    </div>
@endif


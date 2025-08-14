{{-- Modal de création/édition du partenaire --}}
@if($showFormModal)
<div class="fixed inset-0 z-50 overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <!-- Overlay avec animation -->
        <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75 dark:bg-gray-900 dark:bg-opacity-75 backdrop-blur-sm" 
             wire:click="closeModal"></div>
        
        <!-- Modal container - AGRANDIE -->
        <div class="inline-block w-full max-w-4xl my-8 overflow-hidden text-left align-middle transition-all transform bg-white dark:bg-gray-800 shadow-2xl rounded-xl">
            <!-- En-tête de la modal - PLUS SPACIEUSE -->
            <div class="flex items-center justify-between px-8 py-6 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-gray-900 dark:to-gray-800">
                <div class="flex items-center">
                    <div class="p-3 bg-blue-100 dark:bg-blue-900 rounded-xl mr-4 shadow-lg">
                        <svg class="w-8 h-8 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            @if($editingId)
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            @else
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            @endif
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white">
                            {{ $editingId ? 'Modifier le partenaire' : 'Nouveau partenaire' }}
                        </h3>
                        <p class="text-base text-gray-600 dark:text-gray-400 mt-1">
                            {{ $editingId ? 'Modifiez les informations du partenaire existant' : 'Ajoutez un nouveau partenaire à votre base de données' }}
                        </p>
                    </div>
                </div>
                <button wire:click="closeModal" 
                        class="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-all duration-200">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            
            <!-- Formulaire - PLUS SPACIEUX -->
            <div class="px-8 py-8">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Colonne gauche -->
                    <div class="space-y-6">
                        <!-- Nom du partenaire -->
                        <div>
                            <label class="block text-base font-semibold text-gray-700 dark:text-gray-300 mb-3">
                                <span class="flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                    Nom du partenaire
                                    <span class="text-red-500 ml-1">*</span>
                                </span>
                            </label>
                            <input type="text" 
                                   wire:model.defer="nom" 
                                   placeholder="Entrez le nom complet du partenaire"
                                   class="w-full px-4 py-3 text-base border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 transition-all duration-200">
                            @error('nom') 
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center bg-red-50 dark:bg-red-900/20 p-2 rounded-lg">
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                        
                        <!-- Téléphone -->
                        <div>
                            <label class="block text-base font-semibold text-gray-700 dark:text-gray-300 mb-3">
                                <span class="flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                    </svg>
                                    Numéro de téléphone
                                </span>
                            </label>
                            <input type="text" 
                                   wire:model.defer="telephone" 
                                   placeholder="Ex: +33 1 23 45 67 89"
                                   class="w-full px-4 py-3 text-base border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 transition-all duration-200">
                            @error('telephone') 
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center bg-red-50 dark:bg-red-900/20 p-2 rounded-lg">
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Type de partenaire -->
                        <div>
                            <label class="block text-base font-semibold text-gray-700 dark:text-gray-300 mb-3">
                                <span class="flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                    Type de partenaire
                                    <span class="text-red-500 ml-1">*</span>
                                </span>
                            </label>
                            
                            <div class="flex space-x-6">
                                <!-- Fournisseur -->
                                <label class="flex items-center cursor-pointer">
                                    <input type="radio" 
                                        name="type"
                                        wire:model.defer="type" 
                                        value="fournisseur"
                                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                    <span class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">
                                        🏭 Fournisseur
                                    </span>
                                </label>

                                <!-- Client -->
                                <label class="flex items-center cursor-pointer">
                                    <input type="radio" 
                                        name="type"
                                        wire:model.defer="type" 
                                        value="client"
                                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                    <span class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">
                                        👥 Client
                                    </span>
                                </label>
                            </div>
                            
                            @error('type') 
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center bg-red-50 dark:bg-red-900/20 p-2 rounded-lg">
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>

                    <!-- Colonne droite -->
                    <div class="space-y-6">
                        <!-- Adresse complète -->
                        <div>
                            <label class="block text-base font-semibold text-gray-700 dark:text-gray-300 mb-3">
                                <span class="flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    Adresse complète
                                </span>
                            </label>
                            <textarea 
                                wire:model.defer="adresse" 
                                rows="3"
                                placeholder="Adresse complète : rue, ville, code postal, pays..."
                                class="w-full px-4 py-3 text-base border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 transition-all duration-200 resize-none"></textarea>
                            @error('adresse') 
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center bg-red-50 dark:bg-red-900/20 p-2 rounded-lg">
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Statut du partenaire -->
                        <div class="bg-gray-50 dark:bg-gray-700/50 p-6 rounded-xl">
                            <label class="block text-base font-semibold text-gray-700 dark:text-gray-300 mb-4">
                                <span class="flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Statut du partenaire
                                </span>
                            </label>
                            <div class="flex items-start space-x-4">
                                <label class="flex items-center space-x-3 cursor-pointer group">
                                    <input type="checkbox" 
                                           wire:model.defer="is_active" 
                                           class="w-5 h-5 text-blue-600 bg-white border-2 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600 transition-all duration-200">
                                    <div>
                                        <span class="text-base font-medium text-gray-700 dark:text-gray-300 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">
                                            Partenaire actif
                                        </span>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                            Si activé, ce partenaire pourra effectuer des transactions et apparaîtra dans les listes actives.
                                        </p>
                                    </div>
                                </label>
                            </div>
                        </div>

                      
                    </div>
                </div>
            </div>
            
            <!-- Actions - PLUS SPACIEUSES -->
            <div class="flex items-center justify-between px-8 py-6 bg-gray-50 dark:bg-gray-900 border-t border-gray-200 dark:border-gray-700">
                <!-- Informations sur les champs requis -->
                <div class="flex items-center text-sm text-gray-500 dark:text-gray-400">
                    <svg class="w-4 h-4 mr-2 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    Les champs marqués d'un <span class="text-red-500 font-medium">*</span> sont obligatoires
                </div>

                <!-- Boutons d'action -->
                <div class="flex items-center space-x-4">
                    <button wire:click="closeModal" 
                            type="button"
                            class="inline-flex items-center px-6 py-3 bg-gray-500 hover:bg-gray-600 text-white font-medium rounded-xl transition-all duration-200 shadow-sm hover:shadow-md">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        Annuler
                    </button>
                    
                    <button wire:click="save" 
                            class="inline-flex items-center px-8 py-3 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-semibold rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-105">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            @if($editingId)
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                            @else
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            @endif
                        </svg>
                        {{ $editingId ? 'Mettre à jour le partenaire' : 'Créer le partenaire' }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

{{-- Modal de détails du partenaire --}}
@if($showDetail && $detail)
<div class="fixed inset-0 z-50 overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <!-- Overlay -->
        <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75 dark:bg-gray-900 dark:bg-opacity-75 backdrop-blur-sm" 
             wire:click="closeDetail"></div>
        
        <!-- Modal container - AGRANDIE -->
        <div class="inline-block w-full max-w-5xl my-8 overflow-hidden text-left align-middle transition-all transform bg-white dark:bg-gray-800 shadow-2xl rounded-xl">
            <!-- En-tête de la modal - PLUS SPACIEUSE -->
            <div class="flex items-center justify-between px-8 py-6 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-900 dark:to-gray-800">
                <div class="flex items-center">
                    <div class="p-3 bg-blue-100 dark:bg-blue-900 rounded-xl mr-4 shadow-lg">
                        <svg class="w-8 h-8 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white">
                            Détails du partenaire
                        </h3>
                        <p class="text-base text-gray-600 dark:text-gray-400 mt-1">
                            Informations complètes et actions disponibles
                        </p>
                    </div>
                </div>
                <button wire:click="closeDetail" 
                        class="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-all duration-200">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            
            <!-- Contenu de la modal - PLUS SPACIEUX -->
            <div class="px-8 py-8">
                <!-- Badge de type et statut - PLUS GROS -->
                <div class="flex items-center gap-4 mb-8">
                    <span class="inline-flex items-center px-4 py-2 rounded-full text-base font-semibold
                        {{ $detail['type'] == 'client' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-300' }}">
                        @if($detail['type'] == 'client')
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        @else
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
                            </svg>
                        @endif
                        {{ ucfirst($detail['type']) }}
                    </span>
                    
                    <span class="inline-flex items-center px-4 py-2 rounded-full text-base font-semibold
                        {{ $detail['is_active'] ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300' }}">
                        <span class="w-3 h-3 mr-2 rounded-full {{ $detail['is_active'] ? 'bg-green-400' : 'bg-red-400' }}"></span>
                        {{ $detail['is_active'] ? 'Actif' : 'Inactif' }}
                    </span>
                </div>

                <!-- Informations principales - LAYOUT AMÉLIORÉ -->
                <div class="grid grid-cols-1 xl:grid-cols-2 gap-10">
                    <!-- Informations générales -->
                    <div class="space-y-6">
                        <h4 class="text-xl font-bold text-gray-900 dark:text-white uppercase tracking-wide border-b-2 border-blue-200 dark:border-blue-700 pb-3">
                            <span class="flex items-center">
                                <svg class="w-6 h-6 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Informations générales
                            </span>
                        </h4>
                        
                        <div class="space-y-5">
                            <div class="flex items-start bg-gray-50 dark:bg-gray-700/50 p-4 rounded-xl">
                                <svg class="w-6 h-6 text-gray-400 mr-4 mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                </svg>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Identifiant</dt>
                                    <dd class="text-xl text-gray-900 dark:text-white font-mono font-bold mt-1">#{{ $detail['id'] }}</dd>
                                </div>
                            </div>
                            
                            <div class="flex items-start bg-gray-50 dark:bg-gray-700/50 p-4 rounded-xl">
                                <svg class="w-6 h-6 text-gray-400 mr-4 mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Nom du partenaire</dt>
                                    <dd class="text-xl text-gray-900 dark:text-white font-bold mt-1">{{ $detail['nom'] }}</dd>
                                </div>
                            </div>
                            
                            <div class="flex items-start bg-gray-50 dark:bg-gray-700/50 p-4 rounded-xl">
                                <svg class="w-6 h-6 text-gray-400 mr-4 mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                </svg>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Téléphone</dt>
                                    <dd class="text-lg text-gray-900 dark:text-white mt-1">
                                        @if($detail['telephone'])
                                            <a href="tel:{{ $detail['telephone'] }}" class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors font-medium">
                                                {{ $detail['telephone'] }}
                                            </a>
                                        @else
                                            <span class="text-gray-400 italic">Non renseigné</span>
                                        @endif
                                    </dd>
                                </div>
                            </div>
                            
                            <div class="flex items-start bg-gray-50 dark:bg-gray-700/50 p-4 rounded-xl">
                                <svg class="w-6 h-6 text-gray-400 mr-4 mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Adresse</dt>
                                    <dd class="text-lg text-gray-900 dark:text-white mt-1">
                                        @if($detail['adresse'])
                                            <span class="leading-relaxed">{{ $detail['adresse'] }}</span>
                                        @else
                                            <span class="text-gray-400 italic">Non renseignée</span>
                                        @endif
                                    </dd>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Métadonnées et actions -->
                    <div class="space-y-6">
                        <h4 class="text-xl font-bold text-gray-900 dark:text-white uppercase tracking-wide border-b-2 border-green-200 dark:border-green-700 pb-3">
                            <span class="flex items-center">
                                <svg class="w-6 h-6 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Métadonnées
                            </span>
                        </h4>
                        
                        <div class="space-y-5">
                            <div class="flex items-start bg-gray-50 dark:bg-gray-700/50 p-4 rounded-xl">
                                <svg class="w-6 h-6 text-gray-400 mr-4 mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                </svg>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Date de création</dt>
                                    <dd class="text-lg text-gray-900 dark:text-white font-medium mt-1">
                                        @if($detail['created_at'])
                                            {{ $detail['created_at'] }}
                                        @else
                                            <span class="text-gray-400 italic">Non disponible</span>
                                        @endif
                                    </dd>
                                </div>
                            </div>
                            
                            <div class="flex items-start bg-gray-50 dark:bg-gray-700/50 p-4 rounded-xl">
                                <svg class="w-6 h-6 text-gray-400 mr-4 mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Dernière modification</dt>
                                    <dd class="text-lg text-gray-900 dark:text-white font-medium mt-1">
                                        @if($detail['updated_at'])
                                            {{ $detail['updated_at'] }}
                                        @else
                                            <span class="text-gray-400 italic">Non disponible</span>
                                        @endif
                                    </dd>
                                </div>
                            </div>
                        </div>

                        <!-- Section des actions rapides - PLUS GRANDE -->
                        <div class="bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-gray-800 dark:to-gray-700 p-6 rounded-xl border border-blue-200 dark:border-gray-600">
                            <h4 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                                Actions rapides
                            </h4>
                            <div class="grid grid-cols-1 gap-3">
                                <button wire:click="edit({{ $detail['id'] }})" 
                                        class="w-full inline-flex items-center justify-center px-4 py-3 text-base font-medium text-orange-600 dark:text-orange-400 bg-orange-100 dark:bg-orange-900/50 rounded-xl hover:bg-orange-200 dark:hover:bg-orange-800 transition-all duration-200">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                    Modifier les informations
                                </button>
                                
                                <button wire:click="toggle({{ $detail['id'] }})" 
                                        class="w-full inline-flex items-center justify-center px-4 py-3 text-base font-medium text-blue-600 dark:text-blue-400 bg-blue-100 dark:bg-blue-900/50 rounded-xl hover:bg-blue-200 dark:hover:bg-blue-800 transition-all duration-200">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                                    </svg>
                                    {{ $detail['is_active'] ? 'Désactiver le partenaire' : 'Activer le partenaire' }}
                                </button>
                                
                                @if($detail['telephone'])
                                <a href="tel:{{ $detail['telephone'] }}" 
                                   class="w-full inline-flex items-center justify-center px-4 py-3 text-base font-medium text-green-600 dark:text-green-400 bg-green-100 dark:bg-green-900/50 rounded-xl hover:bg-green-200 dark:hover:bg-green-800 transition-all duration-200">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                    </svg>
                                    Appeler maintenant
                                </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Pied de la modal - PLUS SPACIEUX -->
            <div class="flex items-center justify-end px-8 py-6 bg-gray-50 dark:bg-gray-900 border-t border-gray-200 dark:border-gray-700">
                <button wire:click="closeDetail" 
                        class="inline-flex items-center px-6 py-3 bg-gray-500 hover:bg-gray-600 text-white font-semibold rounded-xl transition-all duration-200 shadow-sm hover:shadow-md">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Fermer
                </button>
            </div>
        </div>
    </div>
</div>
@endif
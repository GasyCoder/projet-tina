{{-- resources/views/livewire/categorie/categorieShow.blade.php --}}
<div class="min-h-screen bg-gray-100 dark:bg-gray-900 pt-20 pb-6 md:px-6">
    <!-- Header avec informations catégorie -->
    <div class="px-3 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between space-y-4 sm:space-y-0">
                <!-- Informations catégorie -->
                <div class="flex items-center space-x-4">
                    <a href="{{ route('categories.index') }}" 
                       class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </a>
                    <div class="flex items-center space-x-4">
                        <div class="w-16 h-16 rounded-xl {{ $categorie->type == 'recette' ? 'bg-green-100 dark:bg-green-900' : 'bg-red-100 dark:bg-red-900' }} flex items-center justify-center">
                            <span class="text-xl font-bold {{ $categorie->type == 'recette' ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                {{ $categorie->code_comptable }}
                            </span>
                        </div>
                        <div>
                            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-gray-100">
                                {{ $categorie->nom }}
                            </h1>
                            <div class="flex items-center space-x-3 mt-1">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $categorie->type == 'recette' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' }}">
                                    {{ ucfirst($categorie->type) }}
                                </span>
                                <span class="w-2 h-2 rounded-full {{ $categorie->is_active ? 'bg-green-400' : 'bg-red-400' }}"></span>
                                <span class="text-sm text-gray-600 dark:text-gray-400">{{ $categorie->is_active ? 'Active' : 'Inactive' }}</span>
                                
                                @if($dashboardData['budget']['budget_depasse'] ?? false)
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.5 0L4.346 15.5c-.77.833.192 2.5 1.732 2.5z" />
                                        </svg>
                                        Budget dépassé
                                    </span>
                                @elseif($dashboardData['budget']['seuil_alerte_atteint'] ?? false)
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01" />
                                        </svg>
                                        Seuil d'alerte
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actions rapides -->
                <div class="flex items-center space-x-3">
                    @if($categorie->type === 'recette')
                        <button wire:click="openRecetteModal" 
                                class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Nouvelle Recette
                        </button>
                    @else
                        <button wire:click="openDepenseModal" 
                                class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                            </svg>
                            Nouvelle Dépense
                        </button>
                    @endif
                    
                    <button wire:click="openBudgetModal" 
                            class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                        Gérer Budget
                    </button>
                    
                    <button wire:click="openRapportModal" 
                            class="inline-flex items-center px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Rapport
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="px-0 sm:px-6 lg:px-8 py-6">
        <div class="max-w-7xl mx-auto">
            <!-- Alertes -->
            @if (session()->has('success'))
                <div class="mb-6 mx-3 sm:mx-0 bg-green-50 dark:bg-green-900/20 border-l-4 border-green-400 dark:border-green-600 p-4 rounded-r-lg">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-green-500 dark:text-green-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <p class="text-green-800 dark:text-green-200 font-medium">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            @if (session()->has('error'))
                <div class="mb-6 mx-3 sm:mx-0 bg-red-50 dark:bg-red-900/20 border-l-4 border-red-400 dark:border-red-600 p-4 rounded-r-lg">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-red-500 dark:text-red-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01" />
                        </svg>
                        <p class="text-red-800 dark:text-red-200 font-medium">{{ session('error') }}</p>
                    </div>
                </div>
            @endif

            <!-- Navigation par onglets -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm mb-6">
                <div class="border-b border-gray-200 dark:border-gray-700">
                    <nav class="flex space-x-8 px-6" aria-label="Tabs">
                        <button wire:click="switchTab('dashboard')" 
                                class="py-4 px-1 border-b-2 font-medium text-sm transition-colors {{ $activeTab === 'dashboard' ? 'border-blue-500 text-blue-600 dark:text-blue-400' : 'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300' }}">
                            <span class="flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                </svg>
                                Dashboard
                            </span>
                        </button>

                        <button wire:click="switchTab('transactions')" 
                                class="py-4 px-1 border-b-2 font-medium text-sm transition-colors {{ $activeTab === 'transactions' ? 'border-blue-500 text-blue-600 dark:text-blue-400' : 'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300' }}">
                            <span class="flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                                Transactions
                                <span class="ml-2 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 py-0.5 px-2 rounded-full text-xs">
                                    {{ $dashboardData['temporel']['mois_courant']['nombre'] ?? 0 }}
                                </span>
                            </span>
                        </button>

                        <button wire:click="switchTab('analytics')" 
                                class="py-4 px-1 border-b-2 font-medium text-sm transition-colors {{ $activeTab === 'analytics' ? 'border-blue-500 text-blue-600 dark:text-blue-400' : 'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300' }}">
                            <span class="flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                Analytics
                            </span>
                        </button>

                        <button wire:click="switchTab('budget')" 
                                class="py-4 px-1 border-b-2 font-medium text-sm transition-colors {{ $activeTab === 'budget' ? 'border-blue-500 text-blue-600 dark:text-blue-400' : 'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300' }}">
                            <span class="flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Budget & Objectifs
                                @if($dashboardData['budget']['budget_depasse'] ?? false)
                                    <span class="ml-2 w-2 h-2 bg-red-500 rounded-full"></span>
                                @elseif($dashboardData['budget']['seuil_alerte_atteint'] ?? false)
                                    <span class="ml-2 w-2 h-2 bg-yellow-500 rounded-full"></span>
                                @endif
                            </span>
                        </button>
                    </nav>
                </div>

                <!-- Contenu des onglets -->
                <div class="p-6">
                    @if($activeTab === 'dashboard')
                        @include('livewire.categorie.partials.dashboard-tab')
                    @elseif($activeTab === 'transactions')
                        @include('livewire.categorie.partials.transactions-tab')
                    @elseif($activeTab === 'analytics')
                        @include('livewire.categorie.partials.analytics-tab')
                    @elseif($activeTab === 'budget')
                        @include('livewire.categorie.partials.budget-tab')
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modales -->
    @include('livewire.categorie.modales.categories-transaction-modales')
    @include('livewire.categorie.modales.budget-modal')
    @include('livewire.categorie.modales.rapport-modal')
</div>
<div class="min-h-screen bg-gray-50 pt-20 pb-6 px-4 md:px-6">
    <div class="space-y-6 max-w-7xl mx-auto">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Gestion de Stock</h1>
            </div>
            <div class="flex flex-wrap gap-2">
            </div>
        </div>

        <!-- Navigation par Onglets -->
        <div class="bg-white shadow rounded-lg" x-data="{ activeTab: 'ventes' }">
            <div class="border-b border-gray-200">
                <nav class="-mb-px flex overflow-x-auto">
                    <!-- Onglet Ventes -->
                    <button @click="activeTab = 'ventes'" 
                        :class="{ 'border-blue-500 text-blue-600': activeTab === 'ventes', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'ventes' }"
                        class="whitespace-nowrap py-4 px-4 md:px-6 border-b-2 font-medium text-sm focus:outline-none transition-colors duration-200 flex items-center gap-2">
                        🛒 <span class="truncate">Ventes</span>
                    </button>
                    
                    <!-- Onglet Retours -->
                    <button @click="activeTab = 'retours'" 
                        :class="{ 'border-blue-500 text-blue-600': activeTab === 'retours', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'retours' }"
                        class="whitespace-nowrap py-4 px-4 md:px-6 border-b-2 font-medium text-sm focus:outline-none transition-colors duration-200 flex items-center gap-2">
                        ↩️ <span class="truncate">Retours</span>
                    </button>
                    
                    <!-- Onglet Dépôt -->
                    <button @click="activeTab = 'depot'" 
                        :class="{ 'border-blue-500 text-blue-600': activeTab === 'depot', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'depot' }"
                        class="whitespace-nowrap py-4 px-4 md:px-6 border-b-2 font-medium text-sm focus:outline-none transition-colors duration-200 flex items-center gap-2">
                        🏬 <span class="truncate">Dépôt</span>
                    </button>
                    
                    <!-- Onglet Transferts -->
                    <button @click="activeTab = 'transferts'" 
                        :class="{ 'border-blue-500 text-blue-600': activeTab === 'transferts', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'transferts' }"
                        class="whitespace-nowrap py-4 px-4 md:px-6 border-b-2 font-medium text-sm focus:outline-none transition-colors duration-200 flex items-center gap-2">
                        🔁 <span class="truncate">Transferts</span>
                    </button>
                </nav>
            </div>

            <!-- Contenu des Onglets -->
            <div class="p-4 md:p-6">
                <!-- Onglet Ventes -->
                <div x-show="activeTab === 'ventes'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                    @livewire('stocks.vente')
                </div>

                <!-- Onglet Retours -->
                <div x-show="activeTab === 'retours'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                    @livewire('stocks.retours')
                </div>

                <!-- Onglet Dépôt -->
                <div x-show="activeTab === 'depot'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                    @livewire('stocks.depot')
                </div>

                <!-- Onglet Transferts -->
                <div x-show="activeTab === 'transferts'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                    @livewire('stocks.transfert')
                </div>
            </div>
        </div>
    </div>
</div>
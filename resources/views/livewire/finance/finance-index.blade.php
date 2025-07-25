<div class="min-h-screen bg-gray-50 pt-20 pb-6 px-4 md:px-6">
    <div class="space-y-6 max-w-7xl mx-auto">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <h1 class="text-2xl font-bold text-gray-900">Gestion Financière</h1>
            <div class="flex flex-wrap gap-2">
                @if($activeTab === 'transactions')
                    <button wire:click="createTransaction" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition">
                        ➕ Nouvelle Transaction
                    </button>
                @elseif($activeTab === 'comptes')
                    <button wire:click="createCompte" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition">
                        🏦 Nouveau Compte
                    </button>
                @endif
            </div>
        </div>

        <!-- Alertes -->
        @if (session()->has('success'))
            <div class="bg-green-50 border border-green-200 rounded-md p-4">
                <div class="flex">
                    <svg class="w-5 h-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/>
                    </svg>
                    <p class="ml-3 text-sm text-green-800">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        <!-- Tabs Navigation -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="border-b border-gray-200">
                <nav class="-mb-px flex overflow-x-auto">
                    <button 
                        wire:click="setActiveTab('dashboard')"
                        class="whitespace-nowrap py-4 px-4 border-b-2 font-medium text-sm {{ $activeTab === 'dashboard' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                        📊 Tableau de Bord
                    </button>
                    <button 
                        wire:click="setActiveTab('transactions')"
                        class="whitespace-nowrap py-4 px-4 border-b-2 font-medium text-sm {{ $activeTab === 'transactions' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                        💸 Transactions
                        @if($transactionsEnAttente > 0)
                            <span class="ml-1 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                {{ $transactionsEnAttente }}
                            </span>
                        @endif
                    </button>
                    <button 
                        wire:click="setActiveTab('comptes')"
                        class="whitespace-nowrap py-4 px-4 border-b-2 font-medium text-sm {{ $activeTab === 'comptes' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                        🏦 Comptes ({{ $comptes->count() }})
                    </button>
                    <button 
                        wire:click="setActiveTab('rapports')"
                        class="whitespace-nowrap py-4 px-4 border-b-2 font-medium text-sm {{ $activeTab === 'rapports' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                        📈 Rapports
                    </button>
                </nav>
            </div>

            <!-- Contenu des tabs -->
            <div class="p-4 md:p-6">
                @if($activeTab === 'dashboard')
                    <div wire:key="tab-dashboard">
                        @include('livewire.finance.tabs.dashboard', [
                            'totalEntrees' => $totalEntrees,
                            'totalSorties' => $totalSorties,
                            'beneficeNet' => $beneficeNet,
                            'transactionsEnAttente' => $transactionsEnAttente,
                            'comptes' => $comptes,
                            'dateDebut' => $dateDebut,
                            'dateFin' => $dateFin
                        ])
                    </div>
                @elseif($activeTab === 'transactions')
                    <div wire:key="tab-transactions">
                        @include('livewire.finance.tabs.transactions', [
                            'transactions' => $transactions
                        ])
                    </div>
                @elseif($activeTab === 'comptes')
                    <div wire:key="tab-comptes">
                        @include('livewire.finance.tabs.comptes', [
                            'comptes' => $comptes
                        ])
                    </div>
                @else
                    <div wire:key="tab-rapports">
                        @include('livewire.finance.tabs.rapports', [
                            'totalEntrees' => $totalEntrees,
                            'totalSorties' => $totalSorties,
                            'beneficeNet' => $beneficeNet
                        ])
                    </div>
                @endif
            </div>
        </div>

        <!-- Modals -->
        @include('livewire.finance.modals.transaction-modal', [
            'voyages' => $voyages,
            'comptes' => $comptes
        ])
        
        @include('livewire.finance.modals.compte-modal')
    </div>
</div>
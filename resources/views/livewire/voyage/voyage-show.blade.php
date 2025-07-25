<div class="min-h-screen bg-gray-50 pt-20 pb-6 px-4 md:px-6">
    <div class="max-w-7xl mx-auto space-y-6">
        <!-- Header -->
        @include('livewire.voyage.header', ['voyage' => $voyage])

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

        <!-- Stats synthèse -->
        @include('livewire.voyage.stats', [
            'totalPoidsCharge' => $totalPoidsCharge,
            'totalPoidsDecharge' => $totalPoidsDecharge, 
            'ecartPoids' => $ecartPoids,
            'totalVentes' => $totalVentes
        ])

        <!-- Tabs -->
        @include('livewire.voyage.tabs', [
            'voyage' => $voyage,
            'activeTab' => $activeTab,
            'totalVentes' => $totalVentes,
            'totalPaiements' => $totalPaiements,
            'totalReste' => $totalReste
        ])

        <!-- Modals -->
        @include('livewire.voyage.modals.chargement-modal', [
            'produits' => $produits
        ])
        
        @include('livewire.voyage.modals.dechargement-modal', [
            'produits' => $produits,
            'destinations' => $destinations
        ])
    </div>
</div>
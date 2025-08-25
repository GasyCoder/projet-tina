{{-- resources/views/livewire/partenaire/partenaire-show.blade.php --}}
<div class="min-h-screen bg-gray-100 dark:bg-gray-900 pt-12 pb-4 md:px-6">
    {{-- Header Mobile/Desktop --}}
    @include('livewire.partenaire.partials.header')

    <div class="px-0 sm:px-6 lg:px-8 py-4 sm:py-6">
        <div class="max-w-7xl mx-auto">

            {{-- Container Principal --}}
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-xl overflow-hidden ring-1 ring-gray-200/60 dark:ring-gray-700/50">

                @include('livewire.partenaire.partials.info-partenaire')

                @include('livewire.partenaire.partials.statistique-partenaire')

                {{-- Historique des transactions --}}
                <div class="p-4 sm:p-6">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4">
                        <h2 class="text-base font-semibold text-gray-900 dark:text-white flex items-center">
                            <svg class="w-5 h-5 mr-2 text-gray-600 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 2a8 8 0 100 16 8 8 0 000-16z"/><path d="M11 6v4l3 3"/>
                            </svg>
                            Historique des Transactions
                        </h2>

                        <div class="flex flex-wrap items-center gap-2">
                            <div class="relative">
                                <input type="text" placeholder="Rechercher..."
                                       class="pl-8 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-sm text-gray-900 dark:text-white">
                                <div class="absolute left-2.5 top-2.5 text-gray-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 10-14 0 7 7 0 0014 0z"/>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    @include('livewire.partenaire.partials.listes-partenaires')

                </div>
            </div>

            {{-- Modales --}}
            @include('livewire.partenaire.modales.modal-entrer')
            @include('livewire.partenaire.modales.modal-sortie')
            @include('livewire.partenaire.modales.modal-sorties-entree')
        </div>
    </div>
</div>
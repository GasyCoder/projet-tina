<x-app-layout>

@section('title', 'Accès refusé')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="min-h-[calc(100vh-12rem)] flex items-center justify-center">
            <div class="text-center space-y-8">
                <!-- Icône d'erreur -->
                <div class="flex justify-center">
                    <i class="fas fa-lock text-6xl text-red-500"></i>
                </div>

                <!-- Code et message d'erreur -->
                <div class="space-y-4">
                    <h1 class="text-4xl font-bold text-gray-900">403</h1>
                    <h2 class="text-xl font-semibold text-gray-700">Accès refusé</h2>
                    <p class="text-gray-600 max-w-md mx-auto">
                        Vous n'avez pas l'autorisation d'accéder à cette page. 
                        Seuls les administrateurs peuvent y accéder.
                    </p>
                </div>

                <!-- Boutons d'action -->
                <div class="flex flex-col sm:flex-row justify-center gap-4">
                    @auth
                        @if(!Auth::user()->isAdmin())
                            <a href="{{ route('dashboard') }}" 
                                    class="inline-flex items-center px-6 py-2 border border-gray-300 text-gray-700 bg-white rounded-lg hover:bg-gray-50 transition-colors">
                                <i class="fas fa-arrow-left mr-2"></i> Page précédente
                            </a>
                        @endif
                    @endauth
                </div>

                <!-- Information supplémentaire -->
                @auth
                    @if(!Auth::user()->isAdmin())
                        <div class="mt-6 p-4 bg-gray-50 rounded-lg max-w-md mx-auto">
                            <h3 class="text-lg font-medium text-gray-900 flex items-center">
                                <i class="fas fa-question-circle mr-2"></i> Besoin d'un accès ?
                            </h3>
                            <p class="text-gray-600 text-sm mt-2">
                                Contactez un administrateur si vous pensez avoir besoin d'accès.
                            </p>
                        </div>
                    @endif
                @else
                    <div class="mt-6 p-4 bg-blue-50 rounded-lg max-w-md mx-auto">
                        <h3 class="text-lg font-medium text-blue-900 flex items-center">
                            <i class="fas fa-info-circle mr-2"></i> Non connecté
                        </h3>
                        <p class="text-blue-700 text-sm mt-2">
                            Connectez-vous pour accéder à votre compte.
                        </p>
                        <a href="{{ route('login') }}" 
                           class="mt-2 inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                            <i class="fas fa-sign-in-alt mr-2"></i> Se connecter
                        </a>
                    </div>
                @endauth
            </div>
        </div>
    </div>
</x-app-layout>
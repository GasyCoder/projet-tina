<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    @PwaHead
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- Script pour éviter le flash lors du changement de mode -->
    <script>
        // Applique immédiatement le mode sombre si nécessaire
        (function() {
            const darkMode = localStorage.getItem('darkMode') === 'true' || 
                           (!localStorage.getItem('darkMode') && window.matchMedia('(prefers-color-scheme: dark)').matches);
            if (darkMode) {
                document.documentElement.classList.add('dark');
            }
        })();
    </script>
    @stack('styles')
</head>
<body class="h-full bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 transition-colors duration-300">
    
    <!-- Navigation -->
    @include('layouts.navigation')
    
    <!-- Contenu principal -->
    <main class="pt-16"> <!-- pt-16 pour compenser la navbar fixe -->
        <div class="min-h-screen">
            <!-- Page Header -->
            @if (isset($header))
                <header class="bg-white dark:bg-gray-800 shadow border-b border-gray-200 dark:border-gray-700">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <div class="bg-gray-50 dark:bg-gray-900 min-h-screen">
                {{ $slot }}
            </div>
        </div>
    </main>
    <!-- Scripts Livewire et PHPFlasher -->
    @stack('scripts')
</body>
</html>
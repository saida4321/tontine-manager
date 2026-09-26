<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            🔵 {{ __('Dashboard Administrateur') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-2xl font-bold mb-4">Bienvenue, {{ Auth::user()->nom }} !</h3>
                    <p class="mb-4">Vous êtes connecté en tant qu'<strong>Administrateur</strong>.</p>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6">
                        <!-- Carte 1 -->
                        <div class="bg-blue-100 dark:bg-blue-900 p-4 rounded-lg">
                            <h4 class="font-semibold text-lg">👥 Utilisateurs</h4>
                            <p class="text-3xl font-bold mt-2">{{ \App\Models\User::count() }}</p>
                        </div>
                        
                        <!-- Carte 2 -->
                        <div class="bg-green-100 dark:bg-green-900 p-4 rounded-lg">
                            <h4 class="font-semibold text-lg">🏢 Cabinets</h4>
                            <p class="text-3xl font-bold mt-2">{{ \App\Models\Cabinet::count() }}</p>
                        </div>
                        
                        <!-- Carte 3 -->
                        <div class="bg-purple-100 dark:bg-purple-700 p-4 rounded-lg">
                            <h4 class="font-semibold text-lg">👤 Clients</h4>
                            <p class="text-3xl font-bold mt-2">{{ \App\Models\Client::count() }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
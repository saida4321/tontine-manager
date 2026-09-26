<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            🟡 {{ __('Dashboard Collecteur') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-2xl font-bold mb-4">Bienvenue, {{ Auth::user()->nom }} !</h3>
                    <p class="mb-4">Vous êtes connecté en tant que <strong>Collecteur</strong>.</p>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-6">
                        <!-- Carte 1 : Mes collectes aujourd'hui -->
                        <div class="bg-orange-100 dark:bg-orange-900 p-4 rounded-lg">
                            <h4 class="font-semibold text-lg">📥 Mes collectes aujourd'hui</h4>
                            <p class="text-3xl font-bold mt-2">{{ \App\Models\Cotisation::where('collecteur_id', Auth::id())->whereDate('date_cotisation', today())->count() }}</p>
                            <p class="text-sm mt-1">Cotisations enregistrées</p>
                        </div>
                        
                        <!-- Carte 2 : Montant collecté aujourd'hui -->
                        <div class="bg-green-100 dark:bg-green-900 p-4 rounded-lg">
                            <h4 class="font-semibold text-lg">💰 Montant collecté</h4>
                            <p class="text-3xl font-bold mt-2">{{ number_format(\App\Models\Cotisation::where('collecteur_id', Auth::id())->whereDate('date_cotisation', today())->sum('montant'), 0, ',', ' ') }} FCFA</p>
                            <p class="text-sm mt-1">Aujourd'hui</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
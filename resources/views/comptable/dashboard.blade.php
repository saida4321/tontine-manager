<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            🟠 {{ __('Dashboard Comptable') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-2xl font-bold mb-4">Bienvenue, {{ Auth::user()->nom }} !</h3>
                    <p class="mb-4">Vous êtes connecté en tant que <strong>Comptable</strong>.</p>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6">
                        <!-- Carte 1 : Total des cotisations -->
                        <div class="bg-blue-100 dark:bg-blue-900 p-4 rounded-lg">
                            <h4 class="font-semibold text-lg">💵 Total cotisations</h4>
                            <p class="text-3xl font-bold mt-2">{{ number_format(\App\Models\Cotisation::where('statut', 'validé')->sum('montant'), 0, ',', ' ') }} FCFA</p>
                            <p class="text-sm mt-1">Toutes les cotisations validées</p>
                        </div>
                        
                        <!-- Carte 2 : Total des retraits -->
                        <div class="bg-red-100 dark:bg-red-900 p-4 rounded-lg">
                            <h4 class="font-semibold text-lg">💸 Total retraits</h4>
                            <p class="text-3xl font-bold mt-2">{{ number_format(\App\Models\Retrait::where('statut', 'effectué')->sum('montant'), 0, ',', ' ') }} FCFA</p>
                            <p class="text-sm mt-1">Tous les retraits effectués</p>
                        </div>
                        
                        <!-- Carte 3 : Solde global -->
                        <div class="bg-green-100 dark:bg-green-900 p-4 rounded-lg">
                            <h4 class="font-semibold text-lg">🏦 Solde global</h4>
                            @php
                                $totalCotisations = \App\Models\Cotisation::where('statut', 'validé')->sum('montant');
                                $totalRetraits = \App\Models\Retrait::where('statut', 'effectué')->sum('montant');
                                $soldeGlobal = $totalCotisations - $totalRetraits;
                            @endphp
                            <p class="text-3xl font-bold mt-2">{{ number_format($soldeGlobal, 0, ',', ' ') }} FCFA</p>
                            <p class="text-sm mt-1">Cotisations - Retraits</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
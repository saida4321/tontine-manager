<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            🔴 {{ __('Mon Espace Client') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-2xl font-bold mb-4">Bienvenue, {{ Auth::user()->nom }} !</h3>
                    <p class="mb-4">Vous êtes connecté en tant que <strong>Client</strong>.</p>
                    
                    @php
                        // Récupérer le profil client lié à cet utilisateur
                        $client = \App\Models\Client::where('user_id', Auth::id())->first();
                    @endphp
                    
                    @if($client)
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6">
                            <!-- Carte 1 : Mon solde -->
                            <div class="bg-green-100 dark:bg-green-900 p-4 rounded-lg">
                                <h4 class="font-semibold text-lg">💰 Mon solde</h4>
                                <p class="text-3xl font-bold mt-2">{{ number_format($client->solde, 0, ',', ' ') }} FCFA</p>
                                <p class="text-sm mt-1">Disponible</p>
                            </div>
                            
                            <!-- Carte 2 : Mes cotisations -->
                            <div class="bg-blue-100 dark:bg-blue-900 p-4 rounded-lg">
                                <h4 class="font-semibold text-lg">📥 Mes cotisations</h4>
                                <p class="text-3xl font-bold mt-2">{{ $client->cotisations()->where('statut', 'validé')->count() }}</p>
                                <p class="text-sm mt-1">Cotisations validées</p>
                            </div>
                            
                            <!-- Carte 3 : Mes retraits -->
                            <div class="bg-orange-100 dark:bg-orange-900 p-4 rounded-lg">
                                <h4 class="font-semibold text-lg">💸 Mes retraits</h4>
                                <p class="text-3xl font-bold mt-2">{{ $client->retraits()->where('statut', 'effectué')->count() }}</p>
                                <p class="text-sm mt-1">Retraits effectués</p>
                            </div>
                        </div>
                        
                        <!-- Informations du client -->
                        <div class="mt-8 p-4 bg-gray-100 dark:bg-gray-700 rounded-lg">
                            <h4 class="font-semibold text-lg mb-2">📋 Mes informations</h4>
                            <p><strong>Identifiant :</strong> {{ $client->identifiant_unique }}</p>
                            <p><strong>Cabinet :</strong> {{ $client->cabinet->nom }}</p>
                            <p><strong>Date d'inscription :</strong> {{ $client->date_inscription->format('d/m/Y') }}</p>
                        </div>
                    @else
                        <div class="bg-yellow-100 dark:bg-yellow-900 p-4 rounded-lg">
                            <p>⚠️ Votre profil client n'est pas encore configuré. Contactez un administrateur.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

        {{-- 🎯 EN-TÊTE --}}
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-100">📊 Rapports & Analyses</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Générez des rapports détaillés par période, cabinet et collecteur</p>
        </div>

        {{-- 🔍 FILTRES --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-6 mb-8">
            <h3 class="text-lg font-bold text-gray-800 dark:text-gray-200 mb-4">🔍 Filtres de recherche</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                
                {{-- Période --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">📅 Période</label>
                    <input type="month" wire:model="periode" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-violet-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                </div>

                {{-- Cabinet --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">🏢 Cabinet</label>
                    <select wire:model="cabinet_id" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-violet-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <option value="">Tous les cabinets</option>
                        @foreach($cabinets as $cabinet)
                            <option value="{{ $cabinet->id }}">{{ $cabinet->nom }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Collecteur --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">👤 Collecteur</label>
                    <select wire:model="collecteur_id" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-violet-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <option value="">Tous les collecteurs</option>
                        @foreach($collecteurs as $collecteur)
                            <option value="{{ $collecteur->id }}">{{ $collecteur->nom }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Boutons --}}
                <div class="flex items-end gap-2">
                    <button wire:click="filtrer" 
                            class="flex-1 px-4 py-2 bg-gradient-to-r from-violet-500 to-violet-600 hover:from-violet-600 hover:to-violet-700 text-white font-medium rounded-lg transition shadow-lg">
                        🔄 Filtrer
                    </button>
                    <button wire:click="exporterPDF" 
                            class="flex-1 px-4 py-2 bg-gradient-to-r from-rose-500 to-rose-600 hover:from-rose-600 hover:to-rose-700 text-white font-medium rounded-lg transition shadow-lg">
                        📥 PDF
                    </button>
                </div>

            </div>
        </div>

        {{-- 📊 SYNTHÈSE PÉRIODE --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            
            <div class="bg-gradient-to-br from-emerald-400 to-emerald-600 rounded-2xl shadow-xl p-6 text-white">
                <p class="text-emerald-100 text-xs font-medium uppercase tracking-wide">Cotisations</p>
                <h3 class="text-3xl font-bold mt-2">{{ number_format($stats['total_cotisations'] ?? 0, 0, ',', ' ') }}</h3>
                <p class="text-sm font-light mt-1">FCFA</p>
                <p class="text-xs mt-2 opacity-75">{{ $stats['nb_cotisations'] ?? 0 }} versement(s)</p>
            </div>

            <div class="bg-gradient-to-br from-rose-400 to-rose-600 rounded-2xl shadow-xl p-6 text-white">
                <p class="text-rose-100 text-xs font-medium uppercase tracking-wide">Retraits</p>
                <h3 class="text-3xl font-bold mt-2">{{ number_format($stats['total_retraits'] ?? 0, 0, ',', ' ') }}</h3>
                <p class="text-sm font-light mt-1">FCFA</p>
                <p class="text-xs mt-2 opacity-75">{{ $stats['nb_retraits'] ?? 0 }} retrait(s)</p>
            </div>

            <div class="bg-gradient-to-br from-sky-400 to-sky-600 rounded-2xl shadow-xl p-6 text-white">
                <p class="text-sky-100 text-xs font-medium uppercase tracking-wide">Clients Actifs</p>
                <h3 class="text-3xl font-bold mt-2">{{ number_format($stats['clients_actifs'] ?? 0, 0, ',', ' ') }}</h3>
                <p class="text-sm font-light mt-1">Membres</p>
            </div>

            <div class="bg-gradient-to-br from-violet-400 to-violet-600 rounded-2xl shadow-xl p-6 text-white">
                <p class="text-violet-100 text-xs font-medium uppercase tracking-wide">Solde Global</p>
                <h3 class="text-3xl font-bold mt-2">{{ number_format($stats['solde_global'] ?? 0, 0, ',', ' ') }}</h3>
                <p class="text-sm font-light mt-1">FCFA</p>
            </div>

        </div>

        {{-- 🏢 STATS PAR CABINET --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl overflow-hidden mb-8">
            <div class="px-6 py-4 bg-gradient-to-r from-blue-500 to-blue-600">
                <h3 class="text-lg font-bold text-white">🏢 Statistiques par Cabinet</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Cabinet</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Cotisations</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Retraits</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Solde Net</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($statsCabinets as $cabinet)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-gray-100">{{ $cabinet->nom }}</td>
                                <td class="px-6 py-4 text-sm text-emerald-600 font-semibold">
                                    {{ number_format($cabinet->montant_cotisations ?? 0, 0, ',', ' ') }} FCFA
                                    <span class="text-xs text-gray-500">({{ $cabinet->total_cotisations ?? 0 }})</span>
                                </td>
                                <td class="px-6 py-4 text-sm text-rose-600 font-semibold">
                                    {{ number_format($cabinet->montant_retraits ?? 0, 0, ',', ' ') }} FCFA
                                    <span class="text-xs text-gray-500">({{ $cabinet->total_retraits ?? 0 }})</span>
                                </td>
                                <td class="px-6 py-4 text-sm font-bold text-gray-900 dark:text-gray-100">
                                    {{ number_format(($cabinet->montant_cotisations ?? 0) - ($cabinet->montant_retraits ?? 0), 0, ',', ' ') }} FCFA
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center text-gray-500">Aucune donnée pour cette période</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- 👤 STATS PAR COLLECTEUR --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl overflow-hidden mb-8">
            <div class="px-6 py-4 bg-gradient-to-r from-amber-500 to-amber-600">
                <h3 class="text-lg font-bold text-white">👤 Performance des Collecteurs</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Collecteur</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Nb Cotisations</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Montant Total</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Moyenne</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($statsCollecteurs as $collecteur)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-gray-100">{{ $collecteur->nom }} {{ $collecteur->prenom }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">{{ $collecteur->nb_cotisations ?? 0 }}</td>
                                <td class="px-6 py-4 text-sm text-emerald-600 font-semibold">{{ number_format($collecteur->montant_collecte ?? 0, 0, ',', ' ') }} FCFA</td>
                                <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">
                                    {{ number_format($collecteur->nb_cotisations > 0 ? ($collecteur->montant_collecte / $collecteur->nb_cotisations) : 0, 0, ',', ' ') }} FCFA
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center text-gray-500">Aucune collecte pour cette période</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- 📋 DERNIÈRES TRANSACTIONS --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl overflow-hidden">
            <div class="px-6 py-4 bg-gradient-to-r from-violet-500 to-violet-600">
                <h3 class="text-lg font-bold text-white">📋 Dernières Transactions (20 plus récentes)</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Client</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Montant</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Cabinet</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Agent</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($dernieresTransactions as $transaction)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $transaction['type'] === 'Cotisation' ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                                        {{ $transaction['type'] }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">
                                    {{ \Carbon\Carbon::parse($transaction['date'])->format('d/m/Y') }}
                                </td>
                                <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-gray-100">{{ $transaction['client'] }}</td>
                                <td class="px-6 py-4 text-sm font-semibold {{ $transaction['montant'] > 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                                    {{ number_format(abs($transaction['montant']), 0, ',', ' ') }} FCFA
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">{{ $transaction['cabinet'] }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">{{ $transaction['agent'] }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-gray-500">Aucune transaction pour cette période</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
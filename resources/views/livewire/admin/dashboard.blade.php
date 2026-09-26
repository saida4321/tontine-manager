<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

        {{-- 🎯 TITRE SECTION --}}
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-100">📊 Tableau de Bord - Synthèse Financière</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Vue d'ensemble des performances de votre tontine</p>
        </div>

        {{-- 📊 CARTES KPI AMÉLIORÉES --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            
            {{-- Total Cotisations --}}
            <div class="bg-gradient-to-br from-emerald-400 to-emerald-600 rounded-2xl shadow-xl p-6 text-white relative overflow-hidden">
                <div class="absolute top-0 right-0 opacity-10">
                    <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"/>
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="relative z-10">
                    <p class="text-emerald-100 text-xs font-medium uppercase tracking-wide">Total Cotisations</p>
                    <h3 class="text-4xl font-bold mt-2">{{ number_format($stats['total_cotisations'], 0, ',', ' ') }}</h3>
                    <p class="text-sm font-light mt-1 opacity-90">FCFA</p>
                    @if($stats['evolution_cotisations'] != 0)
                        <div class="mt-3 inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ $stats['evolution_cotisations'] > 0 ? 'bg-emerald-500 bg-opacity-30' : 'bg-red-500 bg-opacity-30' }}">
                            @if($stats['evolution_cotisations'] > 0)
                                <span>↗ +{{ $stats['evolution_cotisations'] }}%</span>
                            @else
                                <span>↘ {{ $stats['evolution_cotisations'] }}%</span>
                            @endif
                            <span class="ml-1.5 opacity-75">vs mois dernier</span>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Total Retraits --}}
            <div class="bg-gradient-to-br from-rose-400 to-rose-600 rounded-2xl shadow-xl p-6 text-white relative overflow-hidden">
                <div class="absolute top-0 right-0 opacity-10">
                    <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="relative z-10">
                    <p class="text-rose-100 text-xs font-medium uppercase tracking-wide">Total Retraits</p>
                    <h3 class="text-4xl font-bold mt-2">{{ number_format($stats['total_retraits'], 0, ',', ' ') }}</h3>
                    <p class="text-sm font-light mt-1 opacity-90">FCFA</p>
                </div>
            </div>

            {{-- Clients Actifs --}}
            <div class="bg-gradient-to-br from-sky-400 to-sky-600 rounded-2xl shadow-xl p-6 text-white relative overflow-hidden">
                <div class="absolute top-0 right-0 opacity-10">
                    <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                    </svg>
                </div>
                <div class="relative z-10">
                    <p class="text-sky-100 text-xs font-medium uppercase tracking-wide">Clients Actifs</p>
                    <h3 class="text-4xl font-bold mt-2">{{ number_format($stats['clients_actifs'], 0, ',', ' ') }}</h3>
                    <p class="text-sm font-light mt-1 opacity-90">Membres</p>
                </div>
            </div>

            {{-- Solde Global --}}
            <div class="bg-gradient-to-br from-violet-400 to-violet-600 rounded-2xl shadow-xl p-6 text-white relative overflow-hidden">
                <div class="absolute top-0 right-0 opacity-10">
                    <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="relative z-10">
                    <p class="text-violet-100 text-xs font-medium uppercase tracking-wide">Solde Global</p>
                    <h3 class="text-4xl font-bold mt-2">{{ number_format($stats['solde_global'], 0, ',', ' ') }}</h3>
                    <p class="text-sm font-light mt-1 opacity-90">FCFA</p>
                </div>
            </div>

        </div>

        {{-- 📈 GRAPHIQUE ÉVOLUTION FULL WIDTH --}}
        <div class="mb-8">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-6">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-xl font-bold text-gray-800 dark:text-gray-200">📈 Évolution des Flux Financiers</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Analyse comparative sur 6 mois</p>
                    </div>
                    <div class="flex items-center space-x-4 text-sm">
                        <div class="flex items-center">
                            <div class="w-4 h-4 rounded-full bg-emerald-500 mr-2"></div>
                            <span class="text-gray-600 dark:text-gray-300">Cotisations</span>
                        </div>
                        <div class="flex items-center">
                            <div class="w-4 h-4 rounded-full bg-rose-500 mr-2"></div>
                            <span class="text-gray-600 dark:text-gray-300">Retraits</span>
                        </div>
                    </div>
                </div>
                <div style="height: 350px; position: relative;">
                    <canvas id="evolutionChart"></canvas>
                </div>
            </div>
        </div>

        {{-- 📋 SECTION 3 COLONNES --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            {{-- Top 5 Clients --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-amber-500 to-amber-600">
                    <h3 class="text-lg font-bold text-white flex items-center">
                        <span class="text-2xl mr-2">🏆</span>
                        Top 5 Épargnants
                    </h3>
                    <p class="text-xs text-amber-100 mt-1">Meilleurs soldes</p>
                </div>
                <div class="p-6">
                    <div class="space-y-3">
                        @forelse($topClients as $index => $client)
                            <div class="flex items-center justify-between p-3 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-600 rounded-xl hover:shadow-md transition">
                                <div class="flex items-center space-x-3">
                                    <div class="flex-shrink-0 w-10 h-10 bg-gradient-to-br from-amber-400 to-amber-600 rounded-full flex items-center justify-center shadow-lg">
                                        <span class="text-white font-bold text-sm">{{ $index + 1 }}</span>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-sm text-gray-800 dark:text-gray-100">{{ $client->prenom }} {{ $client->nom }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $client->identifiant_unique }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="font-bold text-emerald-600 text-sm">{{ number_format($client->solde, 0, ',', ' ') }}</p>
                                    <p class="text-xs text-gray-500">FCFA</p>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-12">
                                <p class="text-gray-400 text-sm">Aucun client avec solde positif</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- Répartition par Cabinet (PIE CHART) --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-blue-500 to-blue-600">
                    <h3 class="text-lg font-bold text-white flex items-center">
                        <span class="text-2xl mr-2">🏢</span>
                        Cotisations par Cabinet
                    </h3>
                    <p class="text-xs text-blue-100 mt-1">Répartition ce mois</p>
                </div>
                <div class="p-6">
                    <div style="height: 280px; position: relative;">
                        <canvas id="cabinetsChart"></canvas>
                    </div>
                    {{-- Légende --}}
                    <div class="mt-4 space-y-2">
                        @foreach($cotisationsParCabinet as $index => $cabinet)
                            <div class="flex items-center justify-between text-xs">
                                <div class="flex items-center">
                                    <div class="w-3 h-3 rounded-full mr-2" style="background-color: {{ ['#3b82f6', '#22c55e', '#a855f7', '#fb923c', '#ec4899'][$index % 5] }}"></div>
                                    <span class="text-gray-600 dark:text-gray-300">{{ $cabinet->nom }}</span>
                                </div>
                                <span class="font-semibold text-gray-800 dark:text-gray-100">{{ number_format($cabinet->total, 0, ',', ' ') }} FCFA</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Retraits en Attente --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-orange-500 to-orange-600">
                    <h3 class="text-lg font-bold text-white flex items-center">
                        <span class="text-2xl mr-2">⚠️</span>
                        Retraits en Attente
                    </h3>
                    <p class="text-xs text-orange-100 mt-1">Nécessitent validation</p>
                </div>
                <div class="p-6">
                    <div class="space-y-3 max-h-96 overflow-y-auto">
                        @forelse($retraitsEnAttente as $retrait)
                            <div class="flex items-center justify-between p-3 bg-gradient-to-r from-orange-50 to-orange-100 dark:from-gray-700 dark:to-gray-600 rounded-xl hover:shadow-md transition border-l-4 border-orange-500">
                                <div class="flex-1">
                                    <p class="font-semibold text-sm text-gray-800 dark:text-gray-100">{{ $retrait->client->prenom ?? '' }} {{ $retrait->client->nom ?? '' }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                        <span class="font-mono">{{ $retrait->reference }}</span> • {{ $retrait->cabinet->nom ?? '' }}
                                    </p>
                                </div>
                                <div class="text-right ml-3">
                                    <p class="font-bold text-rose-600 text-sm">{{ number_format($retrait->montant, 0, ',', ' ') }}</p>
                                    <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($retrait->date_demande)->format('d/m/Y') }}</p>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-12">
                                <div class="text-5xl mb-3">✅</div>
                                <p class="text-gray-400 text-sm">Aucun retrait en attente</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

        </div>

    </div>

    {{-- 📊 SCRIPTS CHART.JS --}}
    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            
            // 📈 Graphique Évolution (LIGNE)
            const ctxEvolution = document.getElementById('evolutionChart');
            if (ctxEvolution) {
                new Chart(ctxEvolution, {
                    type: 'line',
                    data: {
                        labels: {!! json_encode($cotisationsParMois->pluck('label')) !!},
                        datasets: [
                            {
                                label: 'Cotisations',
                                data: {!! json_encode($cotisationsParMois->pluck('value')) !!},
                                borderColor: 'rgb(34, 197, 94)',
                                backgroundColor: 'rgba(34, 197, 94, 0.1)',
                                tension: 0.4,
                                fill: true,
                                borderWidth: 3,
                                pointRadius: 5,
                                pointHoverRadius: 7,
                                pointBackgroundColor: 'rgb(34, 197, 94)',
                                pointBorderColor: '#fff',
                                pointBorderWidth: 2
                            },
                            {
                                label: 'Retraits',
                                data: {!! json_encode($retraitsParMois->pluck('value')) !!},
                                borderColor: 'rgb(244, 63, 94)',
                                backgroundColor: 'rgba(244, 63, 94, 0.1)',
                                tension: 0.4,
                                fill: true,
                                borderWidth: 3,
                                pointRadius: 5,
                                pointHoverRadius: 7,
                                pointBackgroundColor: 'rgb(244, 63, 94)',
                                pointBorderColor: '#fff',
                                pointBorderWidth: 2
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                padding: 12,
                                titleFont: { size: 14, weight: 'bold' },
                                bodyFont: { size: 13 },
                                callbacks: {
                                    label: function(context) {
                                        return context.dataset.label + ': ' + context.parsed.y.toLocaleString() + ' FCFA';
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: {
                                    color: 'rgba(0, 0, 0, 0.05)'
                                },
                                ticks: {
                                    callback: function(value) {
                                        return value.toLocaleString() + ' FCFA';
                                    },
                                    font: { size: 11 }
                                }
                            },
                            x: {
                                grid: {
                                    display: false
                                },
                                ticks: {
                                    font: { size: 11 }
                                }
                            }
                        }
                    }
                });
            }

            // 🥧 Graphique Cabinets (PIE/DOUGHNUT)
            const ctxCabinets = document.getElementById('cabinetsChart');
            if (ctxCabinets) {
                new Chart(ctxCabinets, {
                    type: 'doughnut',
                    data: {
                        labels: {!! json_encode($cotisationsParCabinet->pluck('nom')) !!},
                        datasets: [{
                            data: {!! json_encode($cotisationsParCabinet->pluck('total')) !!},
                            backgroundColor: [
                                'rgba(59, 130, 246, 0.9)',
                                'rgba(34, 197, 94, 0.9)',
                                'rgba(168, 85, 247, 0.9)',
                                'rgba(251, 146, 60, 0.9)',
                                'rgba(236, 72, 153, 0.9)'
                            ],
                            borderColor: '#fff',
                            borderWidth: 3
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                padding: 12,
                                callbacks: {
                                    label: function(context) {
                                        const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                        const percentage = ((context.parsed / total) * 100).toFixed(1);
                                        return context.label + ': ' + context.parsed.toLocaleString() + ' FCFA (' + percentage + '%)';
                                    }
                                }
                            }
                        }
                    }
                });
            }
        });
    </script>
    @endpush
</div>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Rapport {{ $periode }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 11px;
            color: #333;
            line-height: 1.6;
        }

        .header {
            background: linear-gradient(135deg, #7c3aed 0%, #5b21b6 100%);
            color: white;
            padding: 20px;
            text-align: center;
            margin-bottom: 20px;
        }

        .header h1 {
            font-size: 24px;
            margin-bottom: 5px;
        }

        .header p {
            font-size: 14px;
            opacity: 0.9;
        }

        .info-box {
            background: #f3f4f6;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .info-box p {
            margin: 5px 0;
        }

        .info-box strong {
            color: #7c3aed;
        }

        .section-title {
            background: #7c3aed;
            color: white;
            padding: 10px 15px;
            font-size: 14px;
            font-weight: bold;
            margin: 20px 0 10px 0;
            border-radius: 5px;
        }

        .stats-grid {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }

        .stat-card {
            display: table-cell;
            width: 25%;
            padding: 15px;
            text-align: center;
            border: 2px solid #e5e7eb;
        }

        .stat-card.green {
            border-color: #10b981;
        }

        .stat-card.red {
            border-color: #ef4444;
        }

        .stat-card.blue {
            border-color: #3b82f6;
        }

        .stat-card.purple {
            border-color: #8b5cf6;
        }

        .stat-label {
            font-size: 10px;
            color: #6b7280;
            text-transform: uppercase;
            margin-bottom: 5px;
        }

        .stat-value {
            font-size: 20px;
            font-weight: bold;
        }

        .stat-value.green {
            color: #10b981;
        }

        .stat-value.red {
            color: #ef4444;
        }

        .stat-value.blue {
            color: #3b82f6;
        }

        .stat-value.purple {
            color: #8b5cf6;
        }

        .stat-unit {
            font-size: 10px;
            color: #9ca3af;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th {
            background: #f3f4f6;
            padding: 10px;
            text-align: left;
            font-size: 10px;
            text-transform: uppercase;
            color: #6b7280;
            border-bottom: 2px solid #e5e7eb;
        }

        td {
            padding: 10px;
            border-bottom: 1px solid #e5e7eb;
        }

        tr:hover {
            background: #f9fafb;
        }

        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 2px solid #e5e7eb;
            text-align: center;
            color: #6b7280;
            font-size: 9px;
        }

        .page-break {
            page-break-after: always;
        }
    </style>
</head>

<body>

    {{-- EN-TÊTE --}}
    <div class="header">
        <h1>🪙 {{ strtoupper(param_entreprise('nom')) }}</h1>
        <p>Rapport d'activité - {{ $periode }}</p>
    </div>

    {{-- INFORMATIONS DU RAPPORT --}}
    <div class="info-box">
        <p><strong>Cabinet :</strong> {{ $cabinet }}</p>
        <p><strong>Collecteur :</strong> {{ $collecteur }}</p>
        <p><strong>Généré le :</strong> {{ $dateGeneration }}</p>
        <p><strong>Par :</strong> {{ $generePar }}</p>
    </div>

    {{-- SYNTHÈSE GLOBALE --}}
    <div class="section-title">📊 SYNTHÈSE PÉRIODE</div>

    <div class="stats-grid">
        <div class="stat-card green">
            <div class="stat-label">Cotisations</div>
            <div class="stat-value green">{{ number_format($stats['total_cotisations'], 0, ',', ' ') }}</div>
            <div class="stat-unit">FCFA ({{ $stats['nb_cotisations'] }} versements)</div>
        </div>
        <div class="stat-card red">
            <div class="stat-label">Retraits</div>
            <div class="stat-value red">{{ number_format($stats['total_retraits'], 0, ',', ' ') }}</div>
            <div class="stat-unit">FCFA ({{ $stats['nb_retraits'] }} retraits)</div>
        </div>
        <div class="stat-card blue">
            <div class="stat-label">Clients Actifs</div>
            <div class="stat-value blue">{{ $stats['clients_actifs'] }}</div>
            <div class="stat-unit">Membres</div>
        </div>
        <div class="stat-card purple">
            <div class="stat-label">Solde Global</div>
            <div class="stat-value purple">{{ number_format($stats['solde_global'], 0, ',', ' ') }}</div>
            <div class="stat-unit">FCFA</div>
        </div>
    </div>

    {{-- STATISTIQUES PAR CABINET --}}
    @if (count($statsCabinets) > 0)
        <div class="section-title">🏢 RÉPARTITION PAR CABINET</div>
        <table>
            <thead>
                <tr>
                    <th>Cabinet</th>
                    <th style="text-align: right;">Cotisations</th>
                    <th style="text-align: right;">Retraits</th>
                    <th style="text-align: right;">Solde Net</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($statsCabinets as $cabinet)
                    <tr>
                        <td><strong>{{ $cabinet->nom }}</strong></td>
                        <td style="text-align: right; color: #10b981;">
                            {{ number_format($cabinet->montant_cotisations ?? 0, 0, ',', ' ') }} FCFA
                            <span
                                style="color: #9ca3af; font-size: 9px;">({{ $cabinet->total_cotisations ?? 0 }})</span>
                        </td>
                        <td style="text-align: right; color: #ef4444;">
                            {{ number_format($cabinet->montant_retraits ?? 0, 0, ',', ' ') }} FCFA
                            <span style="color: #9ca3af; font-size: 9px;">({{ $cabinet->total_retraits ?? 0 }})</span>
                        </td>
                        <td style="text-align: right; font-weight: bold;">
                            {{ number_format(($cabinet->montant_cotisations ?? 0) - ($cabinet->montant_retraits ?? 0), 0, ',', ' ') }}
                            FCFA
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    {{-- PERFORMANCE PAR COLLECTEUR --}}
    @if (count($statsCollecteurs) > 0)
        <div class="section-title">👤 PERFORMANCE DES COLLECTEURS</div>
        <table>
            <thead>
                <tr>
                    <th>Collecteur</th>
                    <th style="text-align: right;">Nb Cotisations</th>
                    <th style="text-align: right;">Montant Total</th>
                    <th style="text-align: right;">Moyenne</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($statsCollecteurs as $collecteur)
                    <tr>
                        <td><strong>{{ $collecteur->nom }} {{ $collecteur->prenom }}</strong></td>
                        <td style="text-align: right;">{{ $collecteur->nb_cotisations ?? 0 }}</td>
                        <td style="text-align: right; color: #10b981; font-weight: bold;">
                            {{ number_format($collecteur->montant_collecte ?? 0, 0, ',', ' ') }} FCFA
                        </td>
                        <td style="text-align: right;">
                            {{ number_format($collecteur->nb_cotisations > 0 ? $collecteur->montant_collecte / $collecteur->nb_cotisations : 0, 0, ',', ' ') }}
                            FCFA
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    {{-- DÉTAIL DES TRANSACTIONS --}}
    @if (count($transactions) > 0)
        <div class="section-title">📋 DÉTAIL DES TRANSACTIONS (20 plus récentes)</div>
        <table>
            <thead>
                <tr>
                    <th>Type</th>
                    <th>Date</th>
                    <th>Client</th>
                    <th style="text-align: right;">Montant</th>
                    <th>Cabinet</th>
                    <th>Agent</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($transactions as $transaction)
                    <tr>
                        <td>
                            <span
                                style="padding: 3px 8px; border-radius: 4px; font-size: 9px; font-weight: bold; 
                                        {{ $transaction['type'] === 'Cotisation' ? 'background: #d1fae5; color: #065f46;' : 'background: #fee2e2; color: #991b1b;' }}">
                                {{ $transaction['type'] }}
                            </span>
                        </td>
                        <td>{{ \Carbon\Carbon::parse($transaction['date'])->format('d/m/Y') }}</td>
                        <td><strong>{{ $transaction['client'] }}</strong></td>
                        <td
                            style="text-align: right; font-weight: bold; {{ $transaction['montant'] > 0 ? 'color: #10b981;' : 'color: #ef4444;' }}">
                            {{ number_format(abs($transaction['montant']), 0, ',', ' ') }} FCFA
                        </td>
                        <td>{{ $transaction['cabinet'] }}</td>
                        <td>{{ $transaction['agent'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    {{-- PIED DE PAGE --}}
    <div class="footer">
        <p><strong>{{ param_entreprise('nom') }}</strong> - Système de gestion professionnelle</p>
        <p>Document confidentiel - © {{ date('Y') }}</p>
    </div>

</body>

</html>

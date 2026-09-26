<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Client;

class RecalculerSoldesClients extends Command
{
    protected $signature = 'clients:recalculer-soldes';
    protected $description = 'Recalcule les soldes de tous les clients';

    public function handle()
    {
        $clients = Client::all();
        $count = 0;

        foreach ($clients as $client) {
            $totalCotisations = $client->cotisations()
                ->where('statut', 'validé')
                ->sum('montant');

            $totalRetraits = $client->retraits()
                ->where('statut', 'effectué')
                ->sum('montant');

            $nouveauSolde = $totalCotisations - $totalRetraits;

            $client->update(['solde' => $nouveauSolde]);
            $count++;

            $this->info("✅ {$client->prenom} {$client->nom}: {$nouveauSolde} FCFA");
        }

        $this->info("🎉 {$count} soldes recalculés avec succès!");
        return 0;
    }
}
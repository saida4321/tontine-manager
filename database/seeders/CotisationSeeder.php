<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Cotisation;
use App\Models\Client;
use App\Models\User;
use App\Types\Role;
use App\Types\Etat;
use Carbon\Carbon;

class CotisationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Récupérer les clients, collecteur et caissier
        $clients = Client::all();
        $collecteur = User::where('role', Role::Collecteur)->first(); // ✅ CHANGÉ
        $caissier = User::where('role', Role::Caissier)->first();     // ✅ CHANGÉ

        // Créer 30 cotisations
        foreach ($clients->take(15) as $index => $client) {
            // Cotisation en espèces (en_attente)
            Cotisation::create([
                'client_id' => $client->id,
                'cabinet_id' => $client->cabinet_id,
                'montant' => rand(5000, 20000), // Entre 5000 et 20000 FCFA
                'date_cotisation' => Carbon::now()->subDays(rand(1, 30)),
                'type_paiement' => 'especes',
                'collecteur_id' => $collecteur->id,
                'reference_transaction' => null,
                'reference_recu' => null,
                'statut' => 'en_attente',
                'validé_par' => null,
                'date_validation' => null,
                'etat' => Etat::ACTIF, // ✅ AJOUTÉ
            ]);

            // Cotisation en espèces (validée)
            $dateValidation = Carbon::now()->subDays(rand(1, 25));
            Cotisation::create([
                'client_id' => $client->id,
                'cabinet_id' => $client->cabinet_id,
                'montant' => rand(5000, 20000),
                'date_cotisation' => $dateValidation->copy()->subDays(1),
                'type_paiement' => 'especes',
                'collecteur_id' => $collecteur->id,
                'reference_transaction' => null,
                'reference_recu' => 'COT-' . $dateValidation->format('Ymd') . '-' . str_pad($index + 1, 5, '0', STR_PAD_LEFT),
                'statut' => 'validé',
                'validé_par' => $caissier->id,
                'date_validation' => $dateValidation,
                'etat' => Etat::ACTIF, // ✅ AJOUTÉ
            ]);
        }

        // Ajouter quelques cotisations Mobile Money (validées automatiquement)
        foreach ($clients->skip(15)->take(5) as $index => $client) {
            $dateOperation = Carbon::now()->subDays(rand(1, 20));
            Cotisation::create([
                'client_id' => $client->id,
                'cabinet_id' => $client->cabinet_id,
                'montant' => rand(5000, 15000),
                'date_cotisation' => $dateOperation,
                'type_paiement' => 'mobile_money',
                'collecteur_id' => null,
                'reference_transaction' => 'TXN-' . strtoupper(bin2hex(random_bytes(8))),
                'reference_recu' => 'COT-' . $dateOperation->format('Ymd') . '-' . str_pad($index + 100, 5, '0', STR_PAD_LEFT),
                'statut' => 'validé',
                'validé_par' => null, // Validation automatique
                'date_validation' => $dateOperation,
                'etat' => Etat::ACTIF, // ✅ AJOUTÉ
            ]);
        }
    }
}
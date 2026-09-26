<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Cabinet;
use App\Types\Etat;

class CabinetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Cabinet 1 : Lomé Centre
        Cabinet::create([
            'nom' => 'Agence Lomé Centre',
            'adresse' => 'Avenue de la Libération, Lomé',
            'telephone' => '+22822111001',
            'email' => 'lome.centre@tontine.com',
            'etat' => Etat::ACTIF, // ✅ AJOUTÉ
        ]);

        // Cabinet 2 : Kara
        Cabinet::create([
            'nom' => 'Agence Kara',
            'adresse' => 'Quartier Sarakawa, Kara',
            'telephone' => '+22826111001',
            'email' => 'kara@tontine.com',
            'etat' => Etat::ACTIF, // ✅ AJOUTÉ
        ]);

        // Cabinet 3 : Sokodé
        Cabinet::create([
            'nom' => 'Agence Sokodé',
            'adresse' => 'Centre-ville, Sokodé',
            'telephone' => '+22825111001',
            'email' => 'sokode@tontine.com',
            'etat' => Etat::ACTIF, // ✅ AJOUTÉ
        ]);
    }
}
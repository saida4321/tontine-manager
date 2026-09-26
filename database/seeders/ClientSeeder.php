<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Client;
use App\Models\User;
use App\Types\Role;
use App\Types\Etat;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Liste de noms et prénoms togolais
        $clients = [
            ['nom' => 'KOFFI', 'prenom' => 'Amavi', 'telephone' => '+22890111001'],
            ['nom' => 'AGBOKA', 'prenom' => 'Kokou', 'telephone' => '+22890111002'],
            ['nom' => 'ATSOU', 'prenom' => 'Yao', 'telephone' => '+22890111003'],
            ['nom' => 'AFOUDJI', 'prenom' => 'Mensah', 'telephone' => '+22890111004'],
            ['nom' => 'ADAMAH', 'prenom' => 'Kafui', 'telephone' => '+22890111005'],
            ['nom' => 'TCHANILE', 'prenom' => 'Komi', 'telephone' => '+22890111006'],
            ['nom' => 'ADJONOU', 'prenom' => 'Edem', 'telephone' => '+22890111007'],
            ['nom' => 'KOUDADE', 'prenom' => 'Senyo', 'telephone' => '+22890111008'],
            ['nom' => 'AKAKPO', 'prenom' => 'Mawuli', 'telephone' => '+22890111009'],
            ['nom' => 'GNANDI', 'prenom' => 'Komlan', 'telephone' => '+22890111010'],
            ['nom' => 'AYENA', 'prenom' => 'Afi', 'telephone' => '+22890111011'],
            ['nom' => 'HOUNKPE', 'prenom' => 'Akossiwa', 'telephone' => '+22890111012'],
            ['nom' => 'DOSSOU', 'prenom' => 'Ayoko', 'telephone' => '+22890111013'],
            ['nom' => 'KPEGLO', 'prenom' => 'Elom', 'telephone' => '+22890111014'],
            ['nom' => 'SOGLO', 'prenom' => 'Sena', 'telephone' => '+22890111015'],
            ['nom' => 'TCHASSONA', 'prenom' => 'Koffi', 'telephone' => '+22890111016'],
            ['nom' => 'BAMANA', 'prenom' => 'Messan', 'telephone' => '+22890111017'],
            ['nom' => 'DOGBE', 'prenom' => 'Ama', 'telephone' => '+22890111018'],
            ['nom' => 'AMOUSSOU', 'prenom' => 'Ekoue', 'telephone' => '+22890111019'],
            ['nom' => 'KPONTON', 'prenom' => 'Abla', 'telephone' => '+22890111020'],
        ];

        foreach ($clients as $index => $clientData) {
            // 1. Créer un compte user pour chaque client
            $user = User::create([
                'nom' => $clientData['nom'] . ' ' . $clientData['prenom'],
                'email' => strtolower($clientData['prenom'] . '.' . $clientData['nom'] . '@client.com'),
                'telephone' => $clientData['telephone'],
                'password' => Hash::make('password'),
                'role' => Role::Client, // ✅ CHANGÉ : role au lieu de role_id
                'etat' => Etat::ACTIF, // ✅ AJOUTÉ
            ]);

            // 2. Générer l'identifiant unique (CLI-YYYYMMDD-XXXXX)
            $identifiant = 'CLI-' . date('Ymd') . '-' . str_pad($index + 1, 5, '0', STR_PAD_LEFT);
            
            // 3. Alterner les cabinets (1, 2, 3, 1, 2, 3, ...)
            $cabinetId = ($index % 3) + 1;

            // 4. Créer le profil client
            Client::create([
                'user_id' => $user->id,
                'identifiant_unique' => $identifiant,
                'nom' => $clientData['nom'],
                'prenom' => $clientData['prenom'],
                'telephone' => $clientData['telephone'],
                'email' => $user->email,
                'adresse' => 'Lomé, Togo',
                'cabinet_id' => $cabinetId,
                'date_inscription' => Carbon::now()->subDays(rand(1, 365)),
                'solde' => 0.00,
                'etat' => Etat::ACTIF, // ✅ AJOUTÉ
            ]);
        }
    }
}
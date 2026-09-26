<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Types\Role;
use App\Types\Etat;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Administrateur
        User::create([
            'nom' => 'ADMIN Système',
            'email' => 'admin@tontine.com',
            'telephone' => '+22890000001',
            'password' => Hash::make('password'),
            'role' => Role::Admin,
            'etat' => Etat::ACTIF, // ✅ AJOUTÉ
        ]);

        // 2. Caissier
        User::create([
            'nom' => 'KOFFI Amavi',
            'email' => 'caissier@tontine.com',
            'telephone' => '+22890000002',
            'password' => Hash::make('password'),
            'role' => Role::Caissier,
            'etat' => Etat::ACTIF, // ✅ AJOUTÉ
        ]);

        // 3. Collecteur
        User::create([
            'nom' => 'ATSOU Yao',
            'email' => 'collecteur@tontine.com',
            'telephone' => '+22890000003',
            'password' => Hash::make('password'),
            'role' => Role::Collecteur,
            'etat' => Etat::ACTIF, // ✅ AJOUTÉ
        ]);

        // 4. Comptable
        User::create([
            'nom' => 'AGBOKA Kokou',
            'email' => 'comptable@tontine.com',
            'telephone' => '+22890000004',
            'password' => Hash::make('password'),
            'role' => Role::Comptable,
            'etat' => Etat::ACTIF, // ✅ AJOUTÉ
        ]);

        // 5. Client (avec compte de connexion)
        User::create([
            'nom' => 'AFOUDJI Mensah',
            'email' => 'client@tontine.com',
            'telephone' => '+22890000005',
            'password' => Hash::make('password'),
            'role' => Role::Client,
            'etat' => Etat::ACTIF, // ✅ AJOUTÉ
        ]);
    }
}
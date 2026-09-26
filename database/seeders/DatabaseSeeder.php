<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Ordre d'exécution des seeders (important pour les foreign keys)
        $this->call([
            UserSeeder::class,       // 1. Créer les utilisateurs (dépend de roles déjà créés dans la migration)
            CabinetSeeder::class,    // 2. Créer les cabinets
            ClientSeeder::class,     // 3. Créer les clients (dépend de users et cabinets)
            CotisationSeeder::class, // 4. Créer les cotisations (dépend de clients et users)
        ]);

        $this->command->info('✅ Base de données peuplée avec succès !');
    }
}
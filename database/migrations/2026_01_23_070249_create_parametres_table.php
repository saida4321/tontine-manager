<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('parametres', function (Blueprint $table) {
            $table->id();
            
            // 🏢 Informations Entreprise
            $table->string('nom_entreprise')->default('Tontine Manager');
            $table->string('logo')->nullable();
            $table->string('email_entreprise')->nullable();
            $table->string('telephone_entreprise')->nullable();
            $table->text('adresse_entreprise')->nullable();
            
            // 💰 Paramètres Financiers
            $table->decimal('montant_min_cotisation', 10, 2)->default(100);
            $table->decimal('montant_min_retrait', 10, 2)->default(100);
            $table->decimal('taux_commission_collecteur', 5, 2)->default(5);
            
            // 📧 Configuration Email
            $table->string('smtp_host')->nullable();
            $table->integer('smtp_port')->nullable();
            $table->string('smtp_username')->nullable();
            $table->string('smtp_password')->nullable();
            $table->string('smtp_encryption')->default('tls');
            
            // 🔔 Notifications
            $table->boolean('notifications_email')->default(true);
            $table->boolean('notifications_sms')->default(false);
            $table->boolean('alert_retrait_important')->default(true);
            $table->decimal('seuil_retrait_important', 10, 2)->default(50000);
            
            // 🎨 Apparence
            $table->boolean('mode_sombre')->default(false);
            $table->string('langue')->default('fr');
            $table->string('couleur_principale')->default('#8b5cf6');
            
            // 🔒 Sécurité
            $table->boolean('double_auth_active')->default(false);
            $table->integer('session_timeout')->default(120); // minutes
            $table->boolean('force_https')->default(false);
            
            $table->timestamps();
        });
        
        // ✅ Insérer les paramètres par défaut
        DB::table('parametres')->insert([
            'nom_entreprise' => 'Tontine Manager',
            'montant_min_cotisation' => 100,
            'montant_min_retrait' => 100,
            'taux_commission_collecteur' => 5,
            'notifications_email' => true,
            'mode_sombre' => false,
            'langue' => 'fr',
            'couleur_principale' => '#8b5cf6',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('parametres');
    }
};
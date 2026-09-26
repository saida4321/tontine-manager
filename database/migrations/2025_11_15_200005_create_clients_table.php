<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('clients', function (Blueprint $table) {
            // Clé primaire
            $table->id();
            
            // Lien avec users (OBLIGATOIRE - chaque client doit avoir un compte)
            $table->foreignId('user_id')->unique()->constrained('users')->onDelete('restrict');
            
            // Identifiant unique auto-généré (CLI-YYYYMMDD-XXXXX)
            $table->string('identifiant_unique', 50)->unique();
            
            // Informations personnelles
            $table->string('nom', 100);
            $table->string('prenom', 100);
            $table->string('telephone', 20)->unique();
            $table->string('email', 150)->nullable()->unique();
            $table->text('adresse')->nullable();
            
            // Cabinet d'attachement principal
            $table->foreignId('cabinet_id')->constrained('cabinets')->onDelete('restrict');
            
            // Date d'inscription
            $table->date('date_inscription');
            
            // Solde actuel
            $table->decimal('solde', 10, 2)->default(0.00);
            
            // Timestamps
            $table->timestamps();
            
            // ❌ SUPPRIMÉ : $table->softDeletes();
            
            // Index
            $table->index('identifiant_unique');
            $table->index('telephone');
            $table->index('email');
            $table->index('cabinet_id');
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
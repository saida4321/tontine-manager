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
        Schema::create('cotisations', function (Blueprint $table) {
            // Clé primaire
            $table->id();
            
            // Relations
            $table->foreignId('client_id')->constrained('clients')->onDelete('restrict');
            $table->foreignId('cabinet_id')->constrained('cabinets')->onDelete('restrict');
            
            // Informations de la cotisation
            $table->decimal('montant', 10, 2);
            $table->date('date_cotisation');
            
            // Type de paiement
            $table->enum('type_paiement', ['especes', 'mobile_money']);
            
            // Si paiement en espèces : collecteur
            $table->foreignId('collecteur_id')->nullable()->constrained('users')->onDelete('set null');
            
            // Si paiement Mobile Money : référence transaction
            $table->string('reference_transaction', 100)->nullable();
            
            // Référence du reçu généré (COT-YYYYMMDD-XXXXX)
            $table->string('reference_recu', 50)->nullable()->unique();
            
            // Statut de la cotisation
            $table->enum('statut', ['en_attente', 'validé', 'rejeté'])->default('en_attente');
            
            // Validation par un caissier
            $table->foreignId('validé_par')->nullable()->constrained('users')->onDelete('set null');
            $table->dateTime('date_validation')->nullable();
            
            // Timestamps
            $table->timestamps();
            
            // ❌ SUPPRIMÉ : $table->softDeletes();
            
            // Index
            $table->index('client_id');
            $table->index('cabinet_id');
            $table->index('date_cotisation');
            $table->index('statut');
            $table->index('collecteur_id');
            $table->index('validé_par');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cotisations');
    }
};
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
        Schema::create('retraits', function (Blueprint $table) {
            // Clé primaire
            $table->id();
            
            // Relations
            $table->foreignId('client_id')->constrained('clients')->onDelete('restrict');
            $table->foreignId('cabinet_id')->constrained('cabinets')->onDelete('restrict');
            
            // Informations du retrait
            $table->decimal('montant', 10, 2);
            $table->string('motif', 255)->nullable();
            
            // Dates
            $table->date('date_demande');
            $table->date('date_retrait')->nullable();
            
            // Référence du retrait (RET-YYYYMMDD-XXXXX)
            $table->string('reference', 50)->nullable()->unique();
            
            // Statut du retrait
            $table->enum('statut', ['en_attente', 'approuvé', 'rejeté', 'effectué'])->default('en_attente');
            
            // Approbation par un Responsable
            $table->foreignId('approuvé_par')->nullable()->constrained('users')->onDelete('set null');
            $table->dateTime('date_approbation')->nullable();
            
            // Exécution par un Caissier
            $table->foreignId('effectué_par')->nullable()->constrained('users')->onDelete('set null');
            
            // Timestamps
            $table->timestamps();
            
            // ❌ SUPPRIMÉ : $table->softDeletes();
            
            // Index
            $table->index('client_id');
            $table->index('cabinet_id');
            $table->index('statut');
            $table->index('date_demande');
            $table->index('approuvé_par');
            $table->index('effectué_par');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('retraits');
    }
};
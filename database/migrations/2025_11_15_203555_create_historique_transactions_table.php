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
        Schema::create('historique_transactions', function (Blueprint $table) {
            // Clé primaire
            $table->id();
            
            // Type de transaction
            $table->enum('type', ['cotisation', 'retrait']);
            
            // Relations
            $table->foreignId('client_id')->constrained('clients')->onDelete('restrict');
            $table->foreignId('cabinet_id')->constrained('cabinets')->onDelete('restrict');
            
            // Informations de la transaction
            $table->decimal('montant', 10, 2);
            $table->dateTime('date_operation');
            
            // Qui a effectué l'opération
            $table->foreignId('effectué_par')->nullable()->constrained('users')->onDelete('set null');
            
            // Référence (COT-XXX ou RET-XXX)
            $table->string('reference', 50)->nullable();
            
            // Description détaillée
            $table->text('description')->nullable();
            
            // Timestamp de création seulement (pas de updated_at car immuable)
            $table->timestamp('created_at')->useCurrent();
            
            // PAS DE SOFT DELETE - Cette table est IMMUABLE
            
            // Index
            $table->index('client_id');
            $table->index('cabinet_id');
            $table->index('type');
            $table->index('date_operation');
            $table->index('effectué_par');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('historique_transactions');
    }
};
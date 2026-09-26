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
        Schema::create('cabinets', function (Blueprint $table) {
            // Clé primaire
            $table->id();
            
            // Informations du cabinet
            $table->string('nom', 100)->unique();
            $table->text('adresse')->nullable();
            $table->string('telephone', 20)->nullable();
            $table->string('email', 150)->nullable();
            
            // Timestamps
            $table->timestamps();
            
            // ❌ SUPPRIMÉ : $table->softDeletes();
            
            // Index
            $table->index('nom');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cabinets');
    }
};
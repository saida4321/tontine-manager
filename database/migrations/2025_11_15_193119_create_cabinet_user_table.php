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
        Schema::create('cabinet_user', function (Blueprint $table) {
            // Clé primaire
            $table->id();
            
            // Clés étrangères
            $table->foreignId('cabinet_id')->constrained('cabinets')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            
            // Timestamps
            $table->timestamps();
            
            // ❌ SUPPRIMÉ : $table->softDeletes();
            
            // Contrainte : un agent ne peut être associé qu'une fois à un cabinet
            $table->unique(['cabinet_id', 'user_id']);
            
            // Index
            $table->index('cabinet_id');
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cabinet_user');
    }
};
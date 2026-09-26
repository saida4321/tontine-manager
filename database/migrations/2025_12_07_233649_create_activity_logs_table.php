<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration pour créer la table activity_logs
 * 
 * Cette table enregistre toutes les actions importantes dans l'application
 * pour assurer la traçabilité et la sécurité.
 * 
 * @author KOLI Saïdatou-Agbandjala
 * @version 1.0
 */
return new class extends Migration
{
    /**
     * Exécuter la migration (créer la table)
     */
    public function up(): void
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            // Colonne ID (auto-incrémentée)
            $table->id();
            
            // Utilisateur qui a effectué l'action
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('set null'); // Si user supprimé, on garde le log mais user_id = null
            
            // Type d'action (ex: user.created, cabinet.deleted, etc.)
            $table->string('action', 100)->index();
            
            // Type d'entité concernée (ex: User, Cabinet, Client, Cotisation, etc.)
            $table->string('model_type', 100)->nullable()->index();
            
            // ID de l'entité concernée (ex: ID du cabinet supprimé)
            $table->unsignedBigInteger('model_id')->nullable()->index();
            
            // Description détaillée de l'action
            $table->text('description')->nullable();
            
            // Données avant modification (format JSON)
            $table->json('old_values')->nullable();
            
            // Données après modification (format JSON)
            $table->json('new_values')->nullable();
            
            // Adresse IP de l'utilisateur
            $table->string('ip_address', 45)->nullable(); // 45 pour supporter IPv6
            
            // User Agent (navigateur, appareil)
            $table->string('user_agent', 255)->nullable();
            
            // URL de la page où l'action a été effectuée
            $table->string('url', 500)->nullable();
            
            // Méthode HTTP (GET, POST, PUT, DELETE)
            $table->string('http_method', 10)->nullable();
            
            // Date et heure de l'action
            $table->timestamp('created_at')->useCurrent();
            
            // Index composé pour recherches rapides
            $table->index(['user_id', 'created_at']);
            $table->index(['model_type', 'model_id']);
            $table->index(['action', 'created_at']);
        });
    }

    /**
     * Annuler la migration (supprimer la table)
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
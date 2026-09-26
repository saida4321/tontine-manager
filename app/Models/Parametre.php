<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Parametre extends Model
{
    protected $fillable = [
        // Entreprise
        'nom_entreprise',
        'logo',
        'email_entreprise',
        'telephone_entreprise',
        'adresse_entreprise',
        
        // Financier
        'montant_min_cotisation',
        'montant_min_retrait',
        'taux_commission_collecteur',
        
        // Email
        'smtp_host',
        'smtp_port',
        'smtp_username',
        'smtp_password',
        'smtp_encryption',
        
        // Notifications
        'notifications_email',
        'notifications_sms',
        'alert_retrait_important',
        'seuil_retrait_important',
        
        // Apparence
        'mode_sombre',
        'langue',
        'couleur_principale',
        
        // Sécurité
        'double_auth_active',
        'session_timeout',
        'force_https',
    ];

    protected $casts = [
        'montant_min_cotisation' => 'decimal:2',
        'montant_min_retrait' => 'decimal:2',
        'taux_commission_collecteur' => 'decimal:2',
        'seuil_retrait_important' => 'decimal:2',
        'notifications_email' => 'boolean',
        'notifications_sms' => 'boolean',
        'alert_retrait_important' => 'boolean',
        'mode_sombre' => 'boolean',
        'double_auth_active' => 'boolean',
        'force_https' => 'boolean',
        'smtp_port' => 'integer',
        'session_timeout' => 'integer',
    ];
    
    // Méthode helper pour récupérer les paramètres (toujours le 1er enregistrement)
    public static function get()
    {
        return self::first() ?? self::create([]);
    }
}
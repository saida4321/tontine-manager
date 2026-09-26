<?php

use App\Models\Parametre;

if (!function_exists('param_entreprise')) {
    /**
     * Récupère un paramètre de l'entreprise
     * 
     * @param string $key nom|email|telephone|adresse
     * @return string|null
     */
    function param_entreprise($key) {
        static $params = null;
        
        // Cache : charge une seule fois par requête
        if ($params === null) {
            $params = Parametre::first();
        }
        
        return match($key) {
            'nom' => $params->nom_entreprise ?? 'Tontine Manager',
            'email' => $params->email_entreprise ?? '',
            'telephone' => $params->telephone_entreprise ?? '',
            'adresse' => $params->adresse_entreprise ?? '',
            default => null
        };
    }
}

if (!function_exists('montant_min_cotisation')) {
    /**
     * Récupère le montant minimum de cotisation
     * 
     * @return float
     */
    function montant_min_cotisation() {
        static $params = null;
        
        if ($params === null) {
            $params = Parametre::first();
        }
        
        return $params->montant_min_cotisation ?? 100;
    }
}

if (!function_exists('montant_min_retrait')) {
    /**
     * Récupère le montant minimum de retrait
     * 
     * @return float
     */
    function montant_min_retrait() {
        static $params = null;
        
        if ($params === null) {
            $params = Parametre::first();
        }
        
        return $params->montant_min_retrait ?? 100;
    }
}

if (!function_exists('commission_collecteur')) {
    /**
     * Récupère le taux de commission collecteur
     * 
     * @return float
     */
    function commission_collecteur() {
        static $params = null;
        
        if ($params === null) {
            $params = Parametre::first();
        }
        
        return $params->taux_commission_collecteur ?? 5;
    }
}
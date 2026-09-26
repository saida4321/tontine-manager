<?php

namespace App\Constants;

/**
 * Constantes pour les IDs des rôles
 * Ces IDs sont FIXES dans la base de données
 */
class RoleConstants
{
    const ADMINISTRATEUR = 1;
    const CAISSIER = 2;
    const COLLECTEUR = 3;
    const COMPTABLE = 4;
    const CLIENT = 5;

    /**
     * Retourne tous les rôles sous forme de tableau
     */
    public static function all(): array
    {
        return [
            self::ADMINISTRATEUR => 'Administrateur',
            self::CAISSIER => 'Caissier',
            self::COLLECTEUR => 'Collecteur',
            self::COMPTABLE => 'Comptable',
            self::CLIENT => 'Client',
        ];
    }

    /**
     * Vérifie si un ID est valide
     */
    public static function isValid(int $roleId): bool
    {
        return in_array($roleId, [
            self::ADMINISTRATEUR,
            self::CAISSIER,
            self::COLLECTEUR,
            self::COMPTABLE,
            self::CLIENT,
        ]);
    }
}
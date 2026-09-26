<?php

namespace App\Types;

/**
 * Liste des différents états des modèles
 * @author fadhirikah92@gmail.com
 * @version 1
 */
class Etat
{
    const ACTIF = 'actif';
    const INACTIF = 'inactif';
    const SUSPENDU = 'suspendu';
    const SUPPRIME = 'supprime';
    
    /**
     * Retourne tous les états possibles
     */
    public static function all(): array
    {
        return [
            self::ACTIF,
            self::INACTIF,
            self::SUSPENDU,
            self::SUPPRIME,
        ];
    }
    
    /**
     * Vérifie si un état est valide
     */
    public static function isValid(string $etat): bool
    {
        return in_array($etat, self::all());
    }
    
    /**
     * Retourne les états pour ENUM SQL
     */
    public static function toEnum(): array
    {
        return self::all();
    }
}
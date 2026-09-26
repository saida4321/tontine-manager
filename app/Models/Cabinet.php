<?php

namespace App\Models;

use App\Types\Etat;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $nom
 * @property string|null $adresse
 * @property string|null $telephone
 * @property string|null $email
 * @property string $etat
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Client> $clients
 * @property-read int|null $clients_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Cotisation> $cotisations
 * @property-read int|null $cotisations_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Retrait> $retraits
 * @property-read int|null $retraits_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $users
 * @property-read int|null $users_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cabinet actif()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cabinet newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cabinet newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cabinet nonSupprime()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cabinet query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cabinet whereAdresse($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cabinet whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cabinet whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cabinet whereEtat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cabinet whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cabinet whereNom($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cabinet whereTelephone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cabinet whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Cabinet extends Model
{
    // ❌ SUPPRIMÉ : use SoftDeletes

    /**
     * La table associée au modèle
     */
    protected $table = 'cabinets';

    /**
     * Les attributs assignables en masse
     */
    protected $fillable = [
        'nom',
        'adresse',
        'telephone',
        'email',
        'etat', // ✅ AJOUTÉ
    ];

    /**
     * Les attributs à caster en types natifs
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        // ❌ SUPPRIMÉ : 'deleted_at' => 'datetime'
    ];

    /**
     * Relation : Un cabinet a plusieurs agents (users)
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'cabinet_user')
                    ->withTimestamps()
                    ->withPivot('etat'); // ✅ CHANGÉ : deleted_at → etat
    }

    /**
     * Relation : Un cabinet a plusieurs clients
     */
    public function clients()
    {
        return $this->hasMany(Client::class, 'cabinet_id');
    }

    /**
     * Relation : Un cabinet a plusieurs cotisations
     */
    public function cotisations()
    {
        return $this->hasMany(Cotisation::class, 'cabinet_id');
    }

    /**
     * Relation : Un cabinet a plusieurs retraits
     */
    public function retraits()
    {
        return $this->hasMany(Retrait::class, 'cabinet_id');
    }

    /**
     * ✅ NOUVELLES MÉTHODES : Gestion de l'état
     */
    public function isActif(): bool
    {
        return $this->etat === Etat::ACTIF;
    }

    public function isInactif(): bool
    {
        return $this->etat === Etat::INACTIF;
    }

    public function isSupprime(): bool
    {
        return $this->etat === Etat::SUPPRIME;
    }

    /**
     * Scope : Récupérer uniquement les cabinets actifs
     */
    public function scopeActif($query)
    {
        return $query->where('etat', Etat::ACTIF);
    }

    /**
     * Scope : Récupérer les cabinets non supprimés
     */
    public function scopeNonSupprime($query)
    {
        return $query->where('etat', '!=', Etat::SUPPRIME);
    }
}
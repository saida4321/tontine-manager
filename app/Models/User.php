<?php

namespace App\Models;

use App\Types\Role;
use App\Types\Etat;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * @property int $id
 * @property string $nom
 * @property string $email
 * @property string|null $telephone
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property int $role 1=Admin, 2=Caissier, 3=Collecteur, 4=Comptable, 5=Client
 * @property string $etat
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Cabinet> $cabinets
 * @property-read int|null $cabinets_count
 * @property-read \App\Models\Client|null $client
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Cotisation> $cotisationsCollectees
 * @property-read int|null $cotisations_collectees_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Cotisation> $cotisationsValidees
 * @property-read int|null $cotisations_validees_count
 * @property-read string $role_name
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User actif()
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User nonSupprime()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEtat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereNom($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereTelephone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable;
    // ❌ SUPPRIMÉ : SoftDeletes (on utilise maintenant 'etat')

    /**
     * La table associée au modèle
     */
    protected $table = 'users';

    /**
     * Les attributs assignables en masse
     */
    protected $fillable = [
        'nom',
        'email',
        'telephone',
        'password',
        'role',
        'etat', // ✅ AJOUTÉ
    ];

    /**
     * Les attributs cachés (ne pas exposer dans les API/JSON)
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Les attributs à caster en types natifs
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'role' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        // ❌ SUPPRIMÉ : 'deleted_at' => 'datetime'
    ];

    /**
     * Relation : Un utilisateur (agent) peut travailler dans plusieurs cabinets
     */
    public function cabinets()
    {
        return $this->belongsToMany(Cabinet::class, 'cabinet_user')
                    ->withTimestamps()
                    ->withPivot('etat'); // ✅ CHANGÉ : deleted_at → etat
    }

    /**
     * Relation : Un utilisateur (collecteur) peut collecter plusieurs cotisations
     */
    public function cotisationsCollectees()
    {
        return $this->hasMany(Cotisation::class, 'collecteur_id');
    }

    /**
     * Relation : Un utilisateur (caissier) peut valider plusieurs cotisations
     */
    public function cotisationsValidees()
    {
        return $this->hasMany(Cotisation::class, 'validé_par');
    }

    /**
     * Relation : Un utilisateur peut avoir un profil client
     */
    public function client()
    {
        return $this->hasOne(Client::class, 'user_id');
    }

    /**
     * Vérifier si l'utilisateur a un rôle spécifique
     */
    public function hasRole(int $roleId): bool
    {
        return $this->role === $roleId;
    }

    /**
     * Vérifier si l'utilisateur est un administrateur
     */
    public function isAdmin(): bool
    {
        return $this->role === Role::Admin;
    }

    /**
     * Vérifier si l'utilisateur est un caissier
     */
    public function isCaissier(): bool
    {
        return $this->role === Role::Caissier;
    }

    /**
     * Vérifier si l'utilisateur est un collecteur
     */
    public function isCollecteur(): bool
    {
        return $this->role === Role::Collecteur;
    }

    /**
     * Vérifier si l'utilisateur est un comptable
     */
    public function isComptable(): bool
    {
        return $this->role === Role::Comptable;
    }

    /**
     * Vérifier si l'utilisateur est un client
     */
    public function isClient(): bool
    {
        return $this->role === Role::Client;
    }

    /**
     * Obtenir le nom du rôle
     */
    public function getRoleNameAttribute(): string
    {
        return match($this->role) {
            Role::Admin => 'Administrateur',
            Role::Caissier => 'Caissier',
            Role::Collecteur => 'Collecteur',
            Role::Comptable => 'Comptable',
            Role::Client => 'Client',
            default => 'Inconnu',
        };
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

    public function isSuspendu(): bool
    {
        return $this->etat === Etat::SUSPENDU;
    }

    public function isSupprime(): bool
    {
        return $this->etat === Etat::SUPPRIME;
    }

    /**
     * Scope : Récupérer uniquement les utilisateurs actifs
     */
    public function scopeActif($query)
    {
        return $query->where('etat', Etat::ACTIF);
    }

    /**
     * Scope : Récupérer les utilisateurs non supprimés
     */
    public function scopeNonSupprime($query)
    {
        return $query->where('etat', '!=', Etat::SUPPRIME);
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $users
 * @property-read int|null $users_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role withoutTrashed()
 * @mixin \Eloquent
 */
class Role extends Model
{
    use SoftDeletes;

    /**
     * La table associée au modèle
     */
    protected $table = 'roles';

    /**
     * Les attributs assignables en masse
     */
    protected $fillable = [
        'nom',
        'description',
    ];

    /**
     * Les attributs à caster en types natifs
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Relation : Un rôle a plusieurs utilisateurs
     */
    public function users()
    {
        return $this->hasMany(User::class, 'role_id');
    }
}
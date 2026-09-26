<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $type
 * @property int $client_id
 * @property int $cabinet_id
 * @property numeric $montant
 * @property \Illuminate\Support\Carbon $date_operation
 * @property int|null $effectué_par
 * @property string|null $reference
 * @property string|null $description
 * @property \Illuminate\Support\Carbon $created_at
 * @property-read \App\Models\Cabinet $cabinet
 * @property-read \App\Models\Client $client
 * @property-read \App\Models\User|null $executeur
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HistoriqueTransaction newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HistoriqueTransaction newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HistoriqueTransaction query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HistoriqueTransaction whereCabinetId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HistoriqueTransaction whereClientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HistoriqueTransaction whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HistoriqueTransaction whereDateOperation($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HistoriqueTransaction whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HistoriqueTransaction whereEffectuéPar($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HistoriqueTransaction whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HistoriqueTransaction whereMontant($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HistoriqueTransaction whereReference($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HistoriqueTransaction whereType($value)
 * @mixin \Eloquent
 */
class HistoriqueTransaction extends Model
{
    /**
     * La table associée au modèle
     */
    protected $table = 'historique_transactions';

    /**
     * Désactiver updated_at (cette table est en lecture seule)
     */
    const UPDATED_AT = null;

    /**
     * Les attributs assignables en masse
     */
    protected $fillable = [
        'type',
        'client_id',
        'cabinet_id',
        'montant',
        'date_operation',
        'effectué_par',
        'reference',
        'description',
    ];

    /**
     * Les attributs à caster en types natifs
     */
    protected $casts = [
        'montant' => 'decimal:2',
        'date_operation' => 'datetime',
        'created_at' => 'datetime',
    ];

    /**
     * Relation : Une transaction appartient à un client
     */
    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    /**
     * Relation : Une transaction appartient à un cabinet
     */
    public function cabinet()
    {
        return $this->belongsTo(Cabinet::class, 'cabinet_id');
    }

    /**
     * Relation : Une transaction peut être effectuée par un utilisateur
     */
    public function executeur()
    {
        return $this->belongsTo(User::class, 'effectué_par');
    }

    /**
     * Vérifier si c'est une cotisation
     */
    public function estCotisation(): bool
    {
        return $this->type === 'cotisation';
    }

    /**
     * Vérifier si c'est un retrait
     */
    public function estRetrait(): bool
    {
        return $this->type === 'retrait';
    }
}
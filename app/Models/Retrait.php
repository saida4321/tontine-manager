<?php

namespace App\Models;

use App\Types\Etat;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $client_id
 * @property int $cabinet_id
 * @property numeric $montant
 * @property string|null $motif
 * @property string $type_paiement
 * @property string|null $telephone_mobile
 * @property \Illuminate\Support\Carbon $date_demande
 * @property \Illuminate\Support\Carbon|null $date_retrait
 * @property string|null $reference
 * @property string $statut
 * @property int|null $approuvé_par
 * @property \Illuminate\Support\Carbon|null $date_approbation
 * @property string $etat
 * @property int|null $effectué_par
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User|null $approbateur
 * @property-read \App\Models\Cabinet $cabinet
 * @property-read \App\Models\Client $client
 * @property-read \App\Models\User|null $executeur
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Retrait actif()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Retrait newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Retrait newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Retrait nonSupprime()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Retrait query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Retrait whereApprouvéPar($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Retrait whereCabinetId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Retrait whereClientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Retrait whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Retrait whereDateApprobation($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Retrait whereDateDemande($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Retrait whereDateRetrait($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Retrait whereEffectuéPar($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Retrait whereEtat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Retrait whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Retrait whereMontant($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Retrait whereMotif($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Retrait whereReference($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Retrait whereStatut($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Retrait whereTelephoneMobile($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Retrait whereTypePaiement($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Retrait whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Retrait extends Model
{
    // ❌ SUPPRIMÉ : use SoftDeletes

    /**
     * La table associée au modèle
     */
    protected $table = 'retraits';

    /**r
     * Les attributs assignables en masse
     */
    protected $fillable = [
        'client_id',
        'cabinet_id',
        'montant',
        'motif',
        'date_demande',
        'date_retrait',
        'reference',
        'statut',
        'approuvé_par',
        'date_approbation',
        'effectué_par',
        'etat', // ✅ AJOUTÉ
    ];

    /**
     * Les attributs à caster en types natifs
     */
    protected $casts = [
        'montant' => 'decimal:2',
        'date_demande' => 'date',
        'date_retrait' => 'date',
        'date_approbation' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        // ❌ SUPPRIMÉ : 'deleted_at' => 'datetime'
    ];

    /**
     * Relation : Un retrait appartient à un client
     */
    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    /**
     * Relation : Un retrait appartient à un cabinet
     */
    public function cabinet()
    {
        return $this->belongsTo(Cabinet::class, 'cabinet_id');
    }

    /**
     * Relation : Un retrait peut être approuvé par un responsable
     */
    public function approbateur()
    {
        return $this->belongsTo(User::class, 'approuvé_par');
    }

    /**
     * Relation : Un retrait peut être effectué par un caissier
     */
    public function executeur()
    {
        return $this->belongsTo(User::class, 'effectué_par');
    }

    /**
     * Vérifier si le retrait est en attente
     */
    public function estEnAttente(): bool
    {
        return $this->statut === 'en_attente';
    }

    /**
     * Vérifier si le retrait est approuvé
     */
    public function estApprouve(): bool
    {
        return $this->statut === 'approuvé';
    }

    /**
     * Vérifier si le retrait est rejeté
     */
    public function estRejete(): bool
    {
        return $this->statut === 'rejeté';
    }

    /**
     * Vérifier si le retrait est effectué
     */
    public function estEffectue(): bool
    {
        return $this->statut === 'effectué';
    }

    /**
     * ✅ NOUVELLES MÉTHODES : Gestion de l'état
     */
    public function isActif(): bool
    {
        return $this->etat === Etat::ACTIF;
    }

    public function isSupprime(): bool
    {
        return $this->etat === Etat::SUPPRIME;
    }

    /**
     * Scope : Récupérer uniquement les retraits actifs
     */
    public function scopeActif($query)
    {
        return $query->where('etat', Etat::ACTIF);
    }

    /**
     * Scope : Récupérer les retraits non supprimés
     */
    public function scopeNonSupprime($query)
    {
        return $query->where('etat', '!=', Etat::SUPPRIME);
    }
}
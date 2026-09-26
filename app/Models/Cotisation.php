<?php

namespace App\Models;

use App\Types\Etat;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $client_id
 * @property int $cabinet_id
 * @property numeric $montant
 * @property \Illuminate\Support\Carbon $date_cotisation
 * @property string $type_paiement
 * @property string|null $telephone_mobile
 * @property int|null $collecteur_id
 * @property string|null $reference_transaction
 * @property string|null $reference_recu
 * @property string $statut
 * @property int|null $validé_par
 * @property \Illuminate\Support\Carbon|null $date_validation
 * @property string $etat
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Cabinet $cabinet
 * @property-read \App\Models\Client $client
 * @property-read \App\Models\User|null $collecteur
 * @property-read \App\Models\User|null $validateur
 * @property-read \App\Models\User|null $validePar
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cotisation actif()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cotisation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cotisation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cotisation nonSupprime()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cotisation query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cotisation whereCabinetId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cotisation whereClientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cotisation whereCollecteurId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cotisation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cotisation whereDateCotisation($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cotisation whereDateValidation($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cotisation whereEtat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cotisation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cotisation whereMontant($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cotisation whereReferenceRecu($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cotisation whereReferenceTransaction($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cotisation whereStatut($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cotisation whereTelephoneMobile($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cotisation whereTypePaiement($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cotisation whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cotisation whereValidéPar($value)
 * @mixin \Eloquent
 */
class Cotisation extends Model
{
    // ❌ SUPPRIMÉ : use SoftDeletes

    /**
     * La table associée au modèle
     */
    protected $table = 'cotisations';

    /**
     * Les attributs assignables en masse
     */
    protected $fillable = [
        'client_id',
        'cabinet_id',
        'montant',
        'date_cotisation',
        'type_paiement',
        'collecteur_id',
        'reference_transaction',
        'reference_recu',
        'statut',
        'validé_par',
        'date_validation',
        'etat', // ✅ AJOUTÉ
    ];

    /**
     * Les attributs à caster en types natifs
     */
    protected $casts = [
        'montant' => 'decimal:2',
        'date_cotisation' => 'date',
        'date_validation' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        // ❌ SUPPRIMÉ : 'deleted_at' => 'datetime'
    ];

    /**
     * Relation : Une cotisation appartient à un client
     */
    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    /**
     * Relation : Une cotisation appartient à un cabinet
     */
    public function cabinet()
    {
        return $this->belongsTo(Cabinet::class, 'cabinet_id');
    }

    /**
     * Relation : Une cotisation peut être collectée par un collecteur
     */
    public function collecteur()
    {
        return $this->belongsTo(User::class, 'collecteur_id');
    }

    public function validePar()
    {
        return $this->belongsTo(User::class, 'validé_par');
    }

    /**
     * Relation : Une cotisation peut être validée par un caissier
     */
    public function validateur()
    {
        return $this->belongsTo(User::class, 'validé_par');
    }

    /**
     * Vérifier si la cotisation est validée
     */
    public function estValidee(): bool
    {
        return $this->statut === 'validé';
    }

    /**
     * Vérifier si la cotisation est en attente
     */
    public function estEnAttente(): bool
    {
        return $this->statut === 'en_attente';
    }

    /**
     * Vérifier si la cotisation est rejetée
     */
    public function estRejetee(): bool
    {
        return $this->statut === 'rejeté';
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
     * Scope : Récupérer uniquement les cotisations actives
     */
    public function scopeActif($query)
    {
        return $query->where('etat', Etat::ACTIF);
    }

    /**
     * Scope : Récupérer les cotisations non supprimées
     */
    public function scopeNonSupprime($query)
    {
        return $query->where('etat', '!=', Etat::SUPPRIME);
    }
}

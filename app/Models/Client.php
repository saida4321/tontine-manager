<?php

namespace App\Models;

use App\Types\Etat;
use Illuminate\Database\Eloquent\Model;
use App\Models\Cotisation;
use App\Models\Retrait;

/**
 * @property int $id
 * @property int $user_id
 * @property string $identifiant_unique
 * @property string $nom
 * @property string $prenom
 * @property string $telephone
 * @property string|null $email
 * @property string|null $adresse
 * @property int $cabinet_id
 * @property \Illuminate\Support\Carbon $date_inscription
 * @property numeric $solde
 * @property string $etat
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Cabinet $cabinet
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Cotisation> $cotisations
 * @property-read int|null $cotisations_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\HistoriqueTransaction> $historiqueTransactions
 * @property-read int|null $historique_transactions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Retrait> $retraits
 * @property-read int|null $retraits_count
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Client actif()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Client newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Client newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Client nonSupprime()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Client query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Client whereAdresse($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Client whereCabinetId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Client whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Client whereDateInscription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Client whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Client whereEtat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Client whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Client whereIdentifiantUnique($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Client whereNom($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Client wherePrenom($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Client whereSolde($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Client whereTelephone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Client whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Client whereUserId($value)
 * @mixin \Eloquent
 */
class Client extends Model
{
    // ❌ SUPPRIMÉ : use SoftDeletes

    /**
     * La table associée au modèle
     */
    protected $table = 'clients';

    /**
     * Les attributs assignables en masse
     */
    protected $fillable = [
        'user_id',
        'identifiant_unique',
        'nom',
        'prenom',
        'telephone',
        'email',
        'adresse',
        'cabinet_id',
        'date_inscription',
        'etat', // ✅ AJOUTÉ
    ];

    /**
     * Les attributs à caster en types natifs
     */
    protected $casts = [
        'date_inscription' => 'date',
        'solde' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        // ❌ SUPPRIMÉ : 'deleted_at' => 'datetime'
    ];

    /**
     * Relation : Un client appartient à un utilisateur (compte de connexion)
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relation : Un client appartient à un cabinet
     */
    public function cabinet()
    {
        return $this->belongsTo(Cabinet::class, 'cabinet_id');
    }

    /**
     * Relation : Un client a plusieurs cotisations
     */
    public function cotisations()
    {
        return $this->hasMany(Cotisation::class, 'client_id');
    }

    /**
     * Relation : Un client a plusieurs retraits
     */
    public function retraits()
    {
        return $this->hasMany(Retrait::class, 'client_id');
    }

    /**
     * Relation : Un client a plusieurs transactions dans l'historique
     */
    public function historiqueTransactions()
    {
        return $this->hasMany(HistoriqueTransaction::class, 'client_id');
    }

    /**
     * Calculer le solde total du client
     * (Total cotisations validées - Total retraits effectués)
     */
    public function calculerSolde(): float
    {
        $totalCotisations = $this->cotisations()
                                 ->where('statut', 'validé')
                                 ->sum('montant');

        $totalRetraits = $this->retraits()
                              ->where('statut', 'effectué')
                              ->sum('montant');

        return $totalCotisations - $totalRetraits;
    }

    /**
     * Mettre à jour le solde du client
     */
    public function mettreAJourSolde(): void
    {
        $this->solde = $this->calculerSolde();
        $this->save();
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
     * Scope : Récupérer uniquement les clients actifs
     */
    public function scopeActif($query)
    {
        return $query->where('etat', Etat::ACTIF);
    }

    /**
     * Scope : Récupérer les clients non supprimés
     */
    public function scopeNonSupprime($query)
    {
        return $query->where('etat', '!=', Etat::SUPPRIME);
    }
}
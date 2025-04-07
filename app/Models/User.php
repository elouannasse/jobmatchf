<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Cache;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    public const ROLE_ADMINISTRATEUR = 'Administrateur';
    public const ROLE_RECRUTEUR = 'Recruteur';
    public const ROLE_CANDIDAT = 'Candidat';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'prenom',
        'tel',
        'etat',
        'adresse',
        'nom_entreprise',
        'description_entreprise',
        'type_entreprise_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Vérifie si l'utilisateur est administrateur.
     */
    public function isAdmin(): bool
    {
        // Vérifie si l'utilisateur possède un rôle nommé 'Administrateur'
        return Cache::rememberForever("user_{$this->id}_is_administrator", function () {
            return $this->role()->where('name', self::ROLE_ADMINISTRATEUR)->exists();
        });
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Vérifie si l'utilisateur est un recruteur.
     */
    public function isRecruteur(): bool
    {
        // Vérifie si l'utilisateur possède un rôle nommé 'Recruteur'
        return Cache::rememberForever("user_{$this->id}_is_recruteur", function () {
            return $this->role()->where('name', self::ROLE_RECRUTEUR)->exists();
        });
    }

    /**
     * Vérifie si l'utilisateur est un candidat.
     */
    public function isCandidat(): bool
    {
        return Cache::rememberForever("user_{$this->id}_is_candidat", function () {
            return $this->role()->where('name', self::ROLE_CANDIDAT)->exists();
        });
    }

    /**
     * Scope a query to only include active users.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('etat', true);
    }

    public function typeEntreprise(): BelongsTo
    {
        return $this->belongsTo(TypeEntreprise::class);
    }

    /**
     * Les langues parlées par cet utilisateur.
     */
    public function langues(): BelongsToMany
    {
        return $this->belongsToMany(Langue::class, 'candidat_langue')
            ->withPivot('niveau');
    }

    /**
     * Les competences de cet utilisateur.
     */
    public function competences(): BelongsToMany
    {
        return $this->belongsToMany(Competence::class, 'candidat_competence');
    }

    /**
     * Les experiences de cet utilisateur.
     */
    public function experiences(): HasMany
    {
        return $this->hasMany(Experience::class);
    }

    /**
     * Les formations de cet utilisateur.
     */
    public function formations(): HasMany
    {
        return $this->hasMany(Formation::class);
    }

    /**
     * Les offres d'emplois de cet utilisateur.
     */
    public function offres(): HasMany
    {
        return $this->hasMany(Offre::class);
    }

    /**
     * Les candidatures de cet utilisateur.
     */
    public function candidatures(): HasMany
    {
        return $this->hasMany(Candidature::class);
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'etat' => 'boolean',
        ];
    }
}

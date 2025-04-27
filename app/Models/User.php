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
    
    // Role IDs - these should match the IDs in the database
    public const ROLE_ADMINISTRATEUR_ID = 1;
    public const ROLE_RECRUTEUR_ID = 2;
    public const ROLE_CANDIDAT_ID = 3;

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
     * Relation avec les rôles (pour compatibilité avec le code existant)
     * Alias pour la méthode role() pour maintenir la compatibilité
     */
    public function roles()
    {
        return $this->role();
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
     * Commenté car la classe Formation n'existe pas encore
     */
    // public function formations(): HasMany
    // {
    //     return $this->hasMany(Formation::class);
    // }

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
     * Get the candidature details for the user if they are a candidate.
     */
    public function candidatureDetails(): HasMany
    {
        return $this->hasMany(Candidature::class);
    }

    /**
     * Get all messages sent by the user.
     */
    public function sentMessages(): HasMany
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    /**
     * Get all messages received by the user.
     */
    public function receivedMessages(): HasMany
    {
        return $this->hasMany(Message::class, 'recipient_id');
    }

    /**
     * Get unread messages for the user.
     */
    public function unreadMessages()
    {
        return $this->receivedMessages()
            ->whereNull('read_at')
            ->where('deleted_by_recipient', false);
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
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Offre extends Model
{
    use HasFactory;

    /**
     * Les attributs qui sont assignables en masse.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'titre',
        'description',
        'type_contrat',
        'salaire',
        'lieu',
        'date_limite',
        'etat',
        'approved',  // Ajout du champ pour l'approbation
        'title',
        'salaire_proposer',
        'type_offre',
        'date_debut',
        'date_fin',
        // Autres champs existants...
    ];

    /**
     * Les attributs qui doivent être convertis.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date_limite' => 'date',
        'etat' => 'boolean',
        'approved' => 'boolean',  // Cast pour approved
    ];

    /**
     * Obtenir l'utilisateur (recruteur) qui a posté cette offre.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Obtenir les candidatures pour cette offre.
     */
    public function candidatures(): HasMany
    {
        return $this->hasMany(Candidature::class);
    }

    /**
     * Vérifie si l'offre est approuvée.
     */
    public function isApproved(): bool
    {
        return $this->approved === true;
    }

    /**
     * Vérifie si l'offre est rejetée.
     */
    public function isRejected(): bool
    {
        return $this->approved === false;
    }

    /**
     * Vérifie si l'offre est en attente d'approbation.
     */
    public function isPending(): bool
    {
        return $this->approved === null;
    }

    /**
     * Scope pour obtenir uniquement les offres approuvées.
     */
    public function scopeApproved($query)
    {
        return $query->where('approved', true);
    }

    /**
     * Scope pour obtenir uniquement les offres en attente.
     */
    public function scopePending($query)
    {
        return $query->whereNull('approved');
    }

    /**
     * Scope pour obtenir uniquement les offres rejetées.
     */
    public function scopeRejected($query)
    {
        return $query->where('approved', false);
    }

    /**
     * Scope pour obtenir uniquement les offres actives.
     */
    public function scopeActive($query)
    {
        return $query->where('etat', true);
    }

    /**
     * Scope pour obtenir uniquement les offres visibles (actives et approuvées).
     */
    public function scopeVisible($query)
    {
        return $query->where('etat', true)->where('approved', true);
    }

    protected static function booted()
{
    static::saving(function ($offre) {
       
        if ($offre->etat) {
            $offre->approved = true;
        }
    });
}
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Offre extends Model
{
    use HasFactory;

    // Fillable attributes
    protected $fillable = [
        'user_id',
        'titre',
        'description',
        'type_contrat',
        'salaire',
        'lieu',
        'date_limite',
        'etat',
        'approved',  
        'title',
        'salaire_proposer',
        'type_offre',
        'date_debut',
        'date_fin',
        
    ];

    
    protected $casts = [
        'date_limite' => 'date',
        'etat' => 'boolean',
        'approved' => 'boolean',  
    ];

   
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    
    public function candidatures(): HasMany
    {
        return $this->hasMany(Candidature::class);
    }

    
    public function isApproved(): bool
    {
        return $this->approved === true;
    }

    // Check if the offer is rejected
    public function isRejected(): bool
    {
        return $this->approved === false;
    }

    
    public function isPending(): bool
    {
        return $this->approved === null;
    }

    
    public function scopeApproved($query)
    {
        return $query->where('approved', true);
    }

   
    public function scopePending($query)
    {
        return $query->whereNull('approved');
    }

    
    public function scopeRejected($query)
    {
        return $query->where('approved', false);
    }

    
    public function scopeActive($query)
    {
        return $query->where('etat', true);
    }

    
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
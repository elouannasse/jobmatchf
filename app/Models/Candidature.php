<?php

namespace App\Models;

use App\Enums\StatutCandidature;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Candidature extends Model
{
    protected $fillable = [
        'lettre_motivation',
        'cv',
        'statut',
        'offre_id',
        'user_id',
    ];

    protected $casts = [
        'statut' => StatutCandidature::class,
    ];

    public function offre(): BelongsTo
    {
        return $this->belongsTo(Offre::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

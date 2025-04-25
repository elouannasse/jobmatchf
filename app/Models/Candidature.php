<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Candidature extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'offre_id',
        'lettre_motivation',
        'cv', 
        'statut',
        'date_candidature',
    ];

    /**
     * Get the user that owns the candidature.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the offre that the candidature belongs to.
     */
    public function offre()
    {
        return $this->belongsTo(Offre::class);
    }
}
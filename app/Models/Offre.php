<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Offre extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'salaire_proposer',
        'type_offre',
        'date_debut',
        'date_fin',
        'description',
    ];
}

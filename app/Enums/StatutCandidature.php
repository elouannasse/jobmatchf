<?php

namespace App\Enums;

enum StatutCandidature: string
{
    case ACCEPTER = 'accepter';
    case ENTRETIEN_PLANIFIE = 'entretien_planifie';
    case EN_COURS = 'en_cours';
    case REJETER = 'rejeter';

    public static function getValues(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function getLabels(): array
    {
        return [
            self::ACCEPTER->value => 'Acceptée',
            self::ENTRETIEN_PLANIFIE->value => 'Entretien planifié',
            self::EN_COURS->value => 'En cours',
            self::REJETER->value => 'Rejeté',
        ];
    }

    public function badgeClass(): string
    {
        return match ($this) {
            self::ACCEPTER => 'bg-success',
            self::ENTRETIEN_PLANIFIE => 'bg-primary',
            self::EN_COURS => 'bg-warning',
            self::REJETER => 'bg-danger',
        };
    }
}

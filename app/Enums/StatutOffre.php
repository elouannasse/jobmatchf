<?php

namespace App\Enums;

enum StatutOffre: string
{
    case VALIDER = 'validé';
    case EN_ATTENTE = 'en_attente';
    case REJETER = 'rejeté';

    public static function getValues(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function getLabels(): array
    {
        return [
            self::VALIDER->value => 'Validé',
            self::EN_ATTENTE->value => 'En attente',
            self::REJETER->value => 'Rejeté',
        ];
    }

    public function badgeClass(): string
    {
        return match ($this) {
            self::VALIDER => 'bg-success',
            self::EN_ATTENTE => 'bg-warning', 
            self::REJETER => 'bg-danger',
        };
    }
}

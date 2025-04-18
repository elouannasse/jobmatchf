<?php

namespace App\Enums;

enum Niveau: string
{
    case DEBUTANT = 'débutant';
    case INTERMEDIAIRE = 'intermédiaire';
    case AVANCE = 'avancé';
    case NATIF = 'natif';

    public static function getValues(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function getLabels(): array
    {
        return [
            self::DEBUTANT->value => 'Débutant',
            self::INTERMEDIAIRE->value => 'Intermédiaire',
            self::AVANCE->value => 'Avancé',
            self::NATIF->value => 'Natif',
        ];
    }
}

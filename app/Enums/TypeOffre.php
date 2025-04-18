<?php

namespace App\Enums;

enum TypeOffre: string
{
    case TEMPS_PLEIN = 'temps plein';
    case FREELANCE = 'freelance';
    case TEMPS_PARTIEL = 'temps partiel';
    case STAGE = 'stage';
    case TEMPORAIRE = 'temporaire';

    public static function getValues(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function getLabels(): array
    {
        return [
            self::TEMPS_PLEIN->value => 'Temps plein',
            self::FREELANCE->value => 'Freelance',
            self::TEMPS_PARTIEL->value => 'Temps partiel',
            self::STAGE->value => 'Stage',
            self::TEMPORAIRE->value => 'Temporaire',
        ];
    }
}

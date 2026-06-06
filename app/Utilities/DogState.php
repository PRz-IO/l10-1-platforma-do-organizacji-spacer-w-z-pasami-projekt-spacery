<?php

namespace App\Utilities;

class DogState
{
    public static function label(string $state): string
    {
        return match ($state) {
            'Ready' => 'Gotowy na spacer',
            'Sick' => 'Pod opieką weterynarza',
            'Difficult' => 'Wymaga doświadczonej opieki',
            'Dead' => 'Odszedł',
            default => $state,
        };
    }
}
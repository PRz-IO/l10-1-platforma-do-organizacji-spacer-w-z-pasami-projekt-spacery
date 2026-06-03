<?php

namespace App\Utilities;

class DogState
{
    public static function label(string $state): string
    {
        return match ($state) {
            'Ready' => 'Gotowy na spacer',
            'Sick' => 'Chory',
            'Difficult' => 'Trudny w obsłudze',
            'Dead' => 'Nieaktywny',
            default => $state,
        };
    }
}
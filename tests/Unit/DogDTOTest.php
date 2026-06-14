<?php

use App\DTOs\DogDTO;
use Illuminate\Http\Request;

test('dog dto stores constructor values', function () {
    $dto = new DogDTO(
        name: 'Burek',
        age: 5,
        behaviour: 'Friendly',
        state: 'Ready',
        photo: 'burek.jpg'
    );

    expect($dto->name)->toBe('Burek');
    expect($dto->age)->toBe(5);
    expect($dto->behaviour)->toBe('Friendly');
    expect($dto->state)->toBe('Ready');
    expect($dto->photo)->toBe('burek.jpg');
});

test('dog dto can be created from request', function () {
    $request = new Request([
        'Name' => 'Azor',
        'Age' => '3',
        'Behaviour' => 'Calm',
        'State' => 'Walking',
        'Photo' => 'azor.jpg',
    ]);

    $dto = DogDTO::fromRequest($request);

    expect($dto->name)->toBe('Azor');
    expect($dto->age)->toBe(3);
    expect($dto->behaviour)->toBe('Calm');
    expect($dto->state)->toBe('Walking');
    expect($dto->photo)->toBe('azor.jpg');
});

test('dog dto converts to array', function () {
    $dto = new DogDTO(
        name: 'Burek',
        age: 5,
        behaviour: 'Friendly',
        state: 'Ready',
        photo: 'burek.jpg'
    );

    expect($dto->toArray())->toBe([
        'Name' => 'Burek',
        'Age' => 5,
        'Behaviour' => 'Friendly',
        'State' => 'Ready',
        'Photo' => 'burek.jpg',
    ]);
});
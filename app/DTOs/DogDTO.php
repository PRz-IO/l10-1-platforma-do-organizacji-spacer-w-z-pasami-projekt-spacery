<?php

namespace App\DTOs;

use Illuminate\Http\Request;

class DogDTO
{
    public function __construct(
        public readonly string $name,
        public readonly int $age,
        public readonly string $behaviour,
        public readonly string $photo,
        public readonly string $state = 'Ready'
        
    ) {}

    
    public static function fromRequest(Request $request): self
    {
        return new self(
            name: $request->input('Name'),
            age: (int) $request->input('Age'),
            behaviour: $request->input('Behaviour'),
            photo: $request->input('Photo'),
            state: $request->input('State', 'Ready')
            
        );
    }

    
    public function toArray(): array
    {
        return [
            'Name' => $this->name,
            'Age' => $this->age,
            'Behaviour' => $this->behaviour,
            'State' => $this->state,
            'Photo' => $this->photo,
        ];
    }
}
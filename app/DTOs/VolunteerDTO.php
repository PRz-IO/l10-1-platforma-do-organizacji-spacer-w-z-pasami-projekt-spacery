<?php

namespace App\DTOs;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class VolunteerDTO
{
    public function __construct(
        public readonly string $name,
        public readonly string $lastName,
        public readonly string $login,
        public readonly string $password,
        public readonly bool $isExperienced,
        public readonly string $accState = 'Active'
    ) {}

    /**
     * Mapuje dane przychodzące z formularza (Request) na obiekt DTO
     */
    public static function fromRequest(Request $request): self
    {
        return new self(
            name: $request->input('Name'),
            lastName: $request->input('Last_Name'),
            login: $request->input('Login'),
            password: $request->input('Password'),
            isExperienced: $request->boolean('Is_Experienced', false),
            accState: $request->input('Acc_State', 'Active')
        );
    }

    /**
     * Przygotowuje dane w postaci bezpiecznej tablicy do zapisu w modelu Account
     */
    public function toArray(): array
    {
        return [
            'Name' => $this->name,
            'Last_Name' => $this->lastName,
            'Login' => $this->login,
            'Password' => Hash::make($this->password), 
            'Acc_State' => $this->accState,
        ];
    }
}
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
        public readonly string $email,
        public readonly string $phoneNum,
        public readonly string $password,
        public readonly bool $isExperienced,
        public readonly string $accState = 'Pending'
    ) {}

    /**
     * Mapuje dane przychodzące z formularza (Request) oraz wygenerowane hasło na obiekt DTO.
     */
    public static function fromRequest(Request $request, string $generatedPassword): self
    {
        return new self(
            name: $request->input('Name'),
            lastName: $request->input('Last_Name'),
            login: $request->input('Login'),
            email: $request->input('Email'),
            phoneNum: $request->input('Phone_Num'),
            password: $generatedPassword,
            isExperienced: $request->boolean('Is_Experienced', false),
            accState: 'Pending' // Nowy wolontariusz domyślnie oczekuje na zatwierdzenie
        );
    }

    /**
     * Przygotowuje dane w postaci kompletnej i bezpiecznej tablicy do zapisu w modelu Account.
     */
    public function toAccountArray(): array
    {
        return [
            'Name' => $this->name,
            'Last_Name' => $this->lastName,
            'Login' => $this->login,
            'Password' => Hash::make($this->password), // Bezpieczne hashowanie za pomocą Laravel Hash facade
            'Email' => $this->email,
            'Phone_Num' => $this->phoneNum,
            'Acc_State' => $this->accState,
            'Creation_Date' => now()->format('Y-m-d'),
        ];
    }
}
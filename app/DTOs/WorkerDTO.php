<?php

namespace App\DTOs;

use Illuminate\Http\Request;

final readonly class WorkerDTO
{
    public function __construct(
        public string $name,
        public string $lastName,
        public string $login,
        public string $password,
        public string $accState = 'Active',
        public bool $isAdmin = false
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['Name'],
            lastName: $data['Last_Name'],
            login: $data['Login'],
            password: $data['Password'],
            accState: $data['Acc_State'] ?? 'Active',
            isAdmin: (bool) ($data['Is_Admin'] ?? false)
        );
    }

    public static function fromRequest(Request $request): self
    {
        return self::fromArray($request->validated());
    }

    public function toAccountArray(): array
    {
        return [
            'Name' => $this->name,
            'Last_Name' => $this->lastName,
            'Login' => $this->login,
            'Password' => brcypt($this->password),
            'Acc_State' => $this->accState,
        ];
    }

    public function toWorkerArray(int $accountId): array
    {
        return [
            'Account_Id' => $accountId,
            'Is_Admin' => $this->isAdmin,
        ];
    }
}

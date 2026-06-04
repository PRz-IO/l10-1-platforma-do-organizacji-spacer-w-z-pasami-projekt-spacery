<?php

namespace App\DTOs;

use Illuminate\Http\Request;

// TODO:
// Split WorkerDTO into Create and Update Worker DTO

final readonly class WorkerDTO
{
    public function __construct
    (
        public string $name,
        public string $lastName,
        public string $login,
        public string $password,
        public string $email,
        public string $phone_num,
        public string $accState = 'Active',
        public bool $isAdmin = false
    ) {}

    public static function fromArray (array $data) : self
    {
        return new self
        (
            name: $data['Name'],
            lastName: $data['Last_Name'],
            login: $data['Login'],
            password: $data['Password'],
            accState: $data['Acc_State'] ?? 'Active',
            email: $data['Email'],
            phone_num: $data['Phone_Num'],
            isAdmin: (bool) ($data['Is_Admin'] ?? false)
        );
    }

    public static function fromRequest(Request $request) : self
    {
        return self::fromArray($request->validated());
    }

    public function toAccountArray(): array
    {
        return [
            'Name' => $this->name,
            'Last_Name' => $this->lastName,
            'Login' => $this->login,
            'Password' => bcrypt($this->password),
            'Acc_State' => $this->accState,
            'Email' => $this->email,
            'Phone_Num' => $this->phone_num
        ];
    }

    public function toWorkerArray(int $accountId): array
    {
        return [
            'account_id' => $accountId,
            'Is_Admin' => $this->isAdmin
        ];
    }
}

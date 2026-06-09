<?php

namespace App\DTOs;

use Illuminate\Http\Request;

final readonly class UpdateWorkerDTO
{
    public function __construct(
        public string $name,
        public string $lastName,
        public string $email,
        public string $phone_num,
        public bool $isAdmin = false
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            $data['Name'],
            $data['Last_Name'],
            $data['Email'],
            $data['Phone_Num'],
            (bool) ($data['Is_Admin'] ?? false),
        );
    }

    public function toAccountArray(): array
    {
        return [
            'Name' => $this->name,
            'Last_Name' => $this->lastName,
            'Email' => $this->email,
            'Phone_Num' => $this->phone_num,
        ];
    }

    public function toWorkerArray(): array
    {
        return [
            'Is_Admin' => $this->isAdmin,
        ];
    }
}
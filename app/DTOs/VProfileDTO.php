<?php

namespace App\DTOs;

class VProfileDTO extends ProfileDTO
{
    protected bool $Is_Experienced;

    public function __construct(
        string $Name, string $Last_Name,
        string $Login, string $Creation_Date,
        array $History, bool $Is_Experienced
    ) {
        $this->Name = $Name;
        $this->Last_Name = $Last_Name;
        $this->Login = $Login;
        $this->Creation_Date = $Creation_Date;
        $this->History = $History;
        $this->Is_Experienced = $Is_Experienced;
    }

    public function getIs_Experienced(): bool
    {
        return $this->Is_Experienced;
    }

    public function setIs_Experienced(bool $Is_Experienced)
    {
        $this->Is_Experienced = $Is_Experienced;
    }
}

<?php

namespace App\DTOs;

class CurrentUserDTO
{
    protected int $Id;

    protected string $Role;

    protected bool $Param;

    protected string $Acc_State;

    public function __construct(int $Id, string $Role, bool $Param, string $Acc_State)
    {
        $this->Id = $Id;
        $this->Role = $Role;
        $this->Param = $Param;
        $this->Acc_State = $Acc_State;
    }

    public function getId(): int
    {
        return $this->Id;
    }

    public function setId(int $Id)
    {
        $this->Id = $Id;
    }

    public function getRole(): string
    {
        return $this->Role;
    }

    public function setRole(string $Role)
    {
        $this->Role = $Role;
    }

    public function getParam(): bool
    {
        return $this->Param;
    }

    public function setParam(bool $Param)
    {
        $this->Param = $Param;
    }

    public function getAcc_State(): string
    {
        return $this->Acc_State;
    }

    public function setAcc_State(string $Acc_State)
    {
        $this->Acc_State = $Acc_State;
    }
}

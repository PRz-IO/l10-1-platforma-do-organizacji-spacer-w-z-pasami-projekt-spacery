<?php

namespace App\DTOs;

class ProfileWalkDTO
{
    protected int $Id;

    protected string $DName;

    protected string $Date;

    protected string $Time;

    protected int $Grade;

    protected string $Name;

    protected string $Last_Name;

    public function __construct(
        int $Id, string $DName,
        string $Date, string $Time,
        int $Grade, string $Name,
        string $Last_Name
    ) {
        $this->Id = $Id;
        $this->DName = $DName;
        $this->Date = $Date;
        $this->Time = $Time;
        $this->Grade = $Grade;
        $this->Name = $Name;
        $this->Last_Name = $Last_Name;
    }

    public function getId(): int
    {
        return $this->Id;
    }

    public function setId(int $Id)
    {
        $this->Id = $Id;
    }

    public function getDName(): string
    {
        return $this->DName;
    }

    public function setDName(string $DName)
    {
        $this->DName = $DName;
    }

    public function getDate(): string
    {
        return $this->Date;
    }

    public function setDate(string $Date)
    {
        $this->Date = $Date;
    }

    public function getTime(): string
    {
        return $this->Time;
    }

    public function setTime(string $Time)
    {
        $this->Time = $Time;
    }

    public function getGrade(): int
    {
        return $this->Grade;
    }

    public function setGrade(int $Grade)
    {
        $this->Grade = $Grade;
    }

    public function getName(): string
    {
        return $this->Name;
    }

    public function setName(string $Name)
    {
        $this->Name = $Name;
    }

    public function getLast_Name(): string
    {
        return $this->Last_Name;
    }

    public function setLast_Name(string $Last_Name)
    {
        $this->Last_Name = $Last_Name;
    }
}

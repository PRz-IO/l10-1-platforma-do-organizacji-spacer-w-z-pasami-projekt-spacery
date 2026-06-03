<?php

namespace App\DTOs;

require_once 'ScheduleBaseDTO.php';

class WalkListDTO extends ScheduleBaseDTO
{
    protected string $Dog_Name;

    protected string $Volunteer_Name;

    public function getDog_Name(): string
    {
        return $this->Dog_Name;
    }

    public function setDog_Name(string $Dog_Name): void
    {
        $this->Dog_Name = $Dog_Name;
    }

    public function getVolunteer_Name(): string
    {
        return $this->Volunteer_Name;
    }

    public function setVolunteer_Name(string $Volunteer_Name): void
    {
        $this->Volunteer_Name = $Volunteer_Name;
    }
}

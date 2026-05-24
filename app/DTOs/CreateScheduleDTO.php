<?php
namespace App\DTOs;
require_once 'ScheduleBaseDTO.php';

class CreateScheduleDTO extends ScheduleBaseDTO {
    protected int $Volunteer_Id;
    protected int $Dog_Id;
    protected int $Supervisor_Id;

    public function getVolunteer_Id(): int {
        return $this->Volunteer_Id;
    }

    public function setVolunteer_Id(int $Volunteer_Id): void {
        $this->Volunteer_Id = $Volunteer_Id;
    }

    public function getDog_Id(): int {
        return $this->Dog_Id;
    }

    public function setDog_Id(int $Dog_Id): void {
        $this->Dog_Id = $Dog_Id;
    }

    public function getSupervisor_Id(): int {
        return $this->Supervisor_Id;
    }

    public function setSupervisor_Id(int $Supervisor_Id): void {
        $this->Supervisor_Id = $Supervisor_Id;
    }
}
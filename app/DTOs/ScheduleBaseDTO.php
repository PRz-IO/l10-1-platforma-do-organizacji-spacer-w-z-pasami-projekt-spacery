<?php
namespace App\DTOs;

class ScheduleBaseDTO {
    protected int $Walk_Id;
    protected string $Date;
    protected string $Time;

    public function getWalk_Id(): int {
        return $this->Walk_Id;
    }

    public function setWalk_Id(int $Walk_Id): void {
        $this->Walk_Id = $Walk_Id;
    }

    public function getDate(): string {
        return $this->Date;
    }

    public function setDate(string $Date): void {
        $this->Date = $Date;
    }

    public function getTime(): string {
        return $this->Time;
    }

    public function setTime(string $Time): void {
        $this->Time = $Time;
    }
}
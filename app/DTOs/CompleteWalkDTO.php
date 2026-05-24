<?php
namespace App\DTOs;
require_once 'ScheduleBaseDTO.php';


class CompleteWalkDTO extends ScheduleBaseDTO {
    protected int $Grade;
    protected string $Note;

    public function getGrade(): int {
        return $this->Grade;
    }

    public function setGrade(int $Grade): void {
        $this->Grade = $Grade;
    }

    public function getNote(): string {
        return $this->Note;
    }

    public function setNote(string $Note): void {
        $this->Note = $Note;
    }
}
<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\DTOs\CreateScheduleDTO;
use App\DTOs\WalkListDTO;

class WalkService 
{
    // Funkcja biznesowa nr 1: Rezerwacja spaceru
    public function createWalk(CreateScheduleDTO $dto): bool 
    {
        // W Laravelu używamy fasady DB do czystych zapytań SQL
        $success = DB::insert(
            "INSERT INTO Schedules (Date, Time, Volunteer_Id, Dog_Id, Supervisor_Id) 
             VALUES (?, ?, ?, ?, ?)",
            [
                $dto->getDate(),
                $dto->getTime(),
                $dto->getVolunteer_Id(),
                $dto->getDog_Id(),
                $dto->getSupervisor_Id()
            ]
        );

        return $success;
    }

    // Funkcja biznesowa nr 2: Pobranie harmonogramu na dany dzień
    public function getDailySchedule(string $date): array 
    {
        $results = DB::select(
            "SELECT s.Time, d.Name as Dog_Name, a.Name as Volunteer_Name
             FROM Schedules s
             JOIN Dogs d ON s.Dog_Id = d.Dog_Id
             JOIN Volunteers v ON s.Volunteer_Id = v.Volunteer_Id
             JOIN Accounts a ON v.Account_Id = a.Account_Id
             WHERE s.Date = ?",
            [$date]
        );
        
        $dtoList = [];
        foreach ($results as $row) {
            $dto = new WalkListDTO();
            $dto->setTime($row->Time);
            $dto->setDog_Name($row->Dog_Name);
            $dto->setVolunteer_Name($row->Volunteer_Name);
            $dtoList[] = $dto;
        }
        
        return $dtoList;
    }
}
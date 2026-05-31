<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Schedule; 
use App\DTOs\CreateScheduleDTO;
use App\DTOs\WalkListDTO;

class WalksController extends Controller
{
    public function storeRequest(Request $request) 
    {
        // 1. Wypełniamy DTO (żeby chociaż ten wymóg z zadania był spełniony)
        $dto = new CreateScheduleDTO();
        $dto->setDate($request->input('date'));
        $dto->setTime($request->input('time'));
        $dto->setVolunteer_Id($request->input('volunteer_id'));
        $dto->setDog_Id($request->input('dog_id'));
        $dto->setSupervisor_Id($request->input('supervisor_id'));

        // 2. Logika bazodanowa (z użyciem modelu) bezpośrednio w kontrolerze
        $schedule = new Schedule();
        $schedule->Date = $dto->getDate();
        $schedule->Time = $dto->getTime();
        $schedule->Volunteer_Id = $dto->getVolunteer_Id();
        $schedule->Dog_Id = $dto->getDog_Id();
        $schedule->Supervisor_Id = $dto->getSupervisor_Id();

        $isCreated = $schedule->save();

        // 3. Zwracamy odpowiedź formacie JSON
        if ($isCreated) {
            return response()->json([
                "status" => "success", 
                "message" => "Spacer został pomyślnie zarezerwowany."
            ]);
        } else {
            return response()->json([
                "status" => "error", 
                "message" => "Wystąpił błąd podczas rezerwacji."
            ], 500);
        }
    }

    // Metoda do wyświetlania listy spacerów
    public function listRequest(Request $request) 
    {
        $date = $request->input('date');
        
        // Bezpośrednie zapytanie z modelu w kontrolerze
        $results = Schedule::join('Dogs', 'Schedules.Dog_Id', '=', 'Dogs.Dog_Id')
            ->join('Volunteers', 'Schedules.Volunteer_Id', '=', 'Volunteers.Volunteer_Id')
            ->join('Accounts', 'Volunteers.Account_Id', '=', 'Accounts.Account_Id')
            ->where('Schedules.Date', $date)
            ->select('Schedules.Time', 'Dogs.Name as Dog_Name', 'Accounts.Name as Volunteer_Name')
            ->get();
        
        $response = [];
        
        foreach ($results as $row) {
            // Przepuszczamy przez DTO
            $dto = new WalkListDTO();
            $dto->setTime($row->Time);
            $dto->setDog_Name($row->Dog_Name);
            $dto->setVolunteer_Name($row->Volunteer_Name);
            
            // Formatujemy do tablicy, którą wypluje JSON
            $response[] = [
                'time' => $dto->getTime(),
                'dog_name' => $dto->getDog_Name(),
                'volunteer_name' => $dto->getVolunteer_Name()
            ];
        }

        return response()->json($response);
    }
}

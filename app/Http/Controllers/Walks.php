<?php

namespace App\Http\Controllers;

use App\DTOs\CreateScheduleDTO;
// Musisz zaimportować swój serwis oraz DTO (załóżmy, że DTO masz w folderze app/DTOs a serwis w app/Services)
use App\Services\WalkService;
use Illuminate\Http\Request;

class Walks extends Controller
{
    private $walkService;

    // Laravel sam "wstrzyknie" ten serwis dzięki Dependency Injection
    public function __construct(WalkService $walkService)
    {
        $this->walkService = $walkService;
    }

    // Metoda przyjmująca dane z frontu (z obiektu Request Laravela)
    public function storeRequest(Request $request)
    {
        // 1. Tworzymy DTO z danych z Requestu
        $dto = new CreateScheduleDTO;
        $dto->setDate($request->input('date'));
        $dto->setTime($request->input('time'));
        $dto->setVolunteer_Id($request->input('volunteer_id'));
        $dto->setDog_Id($request->input('dog_id'));
        $dto->setSupervisor_Id($request->input('supervisor_id'));

        // 2. Przekazujemy DTO do serwisu
        $isCreated = $this->walkService->createWalk($dto);

        // 3. Zwracamy odpowiedź formacie JSON używając helperów Laravela
        if ($isCreated) {
            return response()->json([
                'status' => 'success',
                'message' => 'Spacer został pomyślnie zarezerwowany.',
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Wystąpił błąd podczas rezerwacji.',
            ], 500);
        }
    }

    // Metoda do wyświetlania listy spacerów
    public function listRequest(Request $request)
    {
        $date = $request->input('date');

        $walksDTOArray = $this->walkService->getDailySchedule($date);

        $response = [];
        foreach ($walksDTOArray as $walkDTO) {
            $response[] = [
                'time' => $walkDTO->getTime(),
                'dog_name' => $walkDTO->getDog_Name(),
                'volunteer_name' => $walkDTO->getVolunteer_Name(),
            ];
        }

        return response()->json($response);
    }
}

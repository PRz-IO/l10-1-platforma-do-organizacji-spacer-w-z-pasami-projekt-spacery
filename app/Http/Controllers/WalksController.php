<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dog;
use Illuminate\Support\Facades\DB;
use App\Utilities\CurrUser;
    
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
    public function index()
{
    // Scenariusz A: Jesteś Pracownikiem
    if (CurrUser::isLogged() && CurrUser::getRole() == "Worker") {
        $today = date('Y-m-d');
        
        // Tłumaczymy account_id na id z tabeli workers
        $realWorkerId = DB::table('workers')->where('account_id', CurrUser::getId())->value('id');

        $supervisorWalks = DB::table('schedules')
            ->join('dogs', 'schedules.dog_id', '=', 'dogs.id')
            ->select('schedules.*', 'dogs.Name as dog_name')
            ->where('schedules.supervisor_id', $realWorkerId) // Używamy prawdziwego ID pracownika
            ->where('schedules.Date', '<', $today)
            ->whereNotNull('schedules.Note')
            ->get();
            
        return view('walks.dashboard', compact('supervisorWalks'));
    }

    // Scenariusz B: Jesteś Wolontariuszem (lub gościem)
    // 👇 TUTAJ: Zamiast Dog::all() pobieramy tylko psy, które nie są chore ani martwe
    // (Upewnij się, czy w bazie kolumna nazywa się 'status', 'state' czy np. 'stan')
    $dogs = Dog::whereNotIn('State', ['sick', 'dead'])->get();
    
    $myWalks = [];
    if (CurrUser::isLogged() && CurrUser::getRole() == "Volunteer") {
        // Tłumaczymy account_id na volunteer_id
        $realVolunteerId = DB::table('volunteers')->where('account_id', CurrUser::getId())->value('id');
        
        $myWalks = DB::table('schedules')
            ->join('dogs', 'schedules.dog_id', '=', 'dogs.id')
            ->select('schedules.*', 'dogs.Name as dog_name')
            ->where('schedules.volunteer_id', $realVolunteerId)
            ->orderBy('schedules.Date', 'desc')
            ->orderBy('schedules.Time', 'desc')
            ->get();
    }

    return view('walks.index', compact('dogs', 'myWalks'));
}

    public function addNote(Request $request, $id)
    {
        // Ochrona
        if (!CurrUser::isLogged() || CurrUser::getRole() != "Volunteer") {
            return redirect()->back()->with('error', 'Brak uprawnień.');
        }

        // Tłumaczymy account_id na volunteer_id
        $realVolunteerId = DB::table('volunteers')->where('account_id', CurrUser::getId())->value('id');

        // Aktualizujemy rekord w tabeli schedules
        DB::table('schedules')
            ->where('id', $id)
            ->where('volunteer_id', $realVolunteerId) // Używamy poprawnego ID
            ->update([
                'Note' => $request->input('note')
            ]);

        return redirect()->back()->with('success', 'Dodano notatkę do spaceru!');
    }

    public function addGrade(Request $request, $schedule_id)
    {
        // Sprawdzenie uprawnień
        if (!CurrUser::isLogged() || CurrUser::getRole() != "Worker") {
            return redirect()->back()->with('error', 'Tylko pracownik może oceniać spacery.');
        }

        // Tłumaczymy account_id na id z tabeli workers
        $realWorkerId = DB::table('workers')->where('account_id', CurrUser::getId())->value('id');

        if (!$realWorkerId) {
            return redirect()->back()->with('error', 'Błąd: nie znaleziono profilu pracownika przypisanego do tego konta.');
        }

        // Aktualizacja oceny w bazie
        $updated = DB::table('schedules')
            ->where('id', $schedule_id)
            ->where('supervisor_id', $realWorkerId) // Pracownik ocenia tylko "swoje"
            ->update([
                'Grade' => $request->input('grade')
            ]);

        if ($updated) {
            return redirect()->back()->with('success', 'Ocena została zapisana!');
        } else {
            return redirect()->back()->with('error', 'Nie można dodać oceny do tego spaceru.');
        }
    }

    public function cancelWalk($id)
    {
        if (!CurrUser::isLogged() || CurrUser::getRole() != "Volunteer") {
            return redirect()->back()->with('error', 'Brak uprawnień.');
        }

        $realVolunteerId = DB::table('volunteers')->where('account_id', CurrUser::getId())->value('id');

        // Pobieramy spacer, żeby sprawdzić jego dokładny czas
        $walk = DB::table('schedules')
            ->where('id', $id)
            ->where('volunteer_id', $realVolunteerId)
            ->first();

        if (!$walk) {
            return redirect()->back()->with('error', 'Nie znaleziono spaceru.');
        }

        // Sprawdzamy czy dokładny czas spaceru minął
        $walkDateTime = \Carbon\Carbon::parse($walk->Date . ' ' . $walk->Time, 'Europe/Warsaw');

        if ($walkDateTime->isPast()) {
            return redirect()->back()->with('error', 'Nie można anulować spaceru, który już się rozpoczął lub odbył.');
        }

        // Jeśli czas jest w przyszłości - usuwamy
        DB::table('schedules')->where('id', $id)->delete();

        return redirect()->back()->with('success', 'Spacer został anulowany.');
    }

    public function show($id)
    {
        $dog = Dog::findOrFail($id);
        $isFavorite = false;

        if (CurrUser::isLogged() && CurrUser::getRole() == "Volunteer") {
            // Tłumaczymy account_id na volunteer_id
            $realVolunteerId = DB::table('volunteers')->where('account_id', CurrUser::getId())->value('id');
            
            if ($realVolunteerId) {
                $isFavorite = DB::table('fav_dogs')
                    ->where('volunteer_id', $realVolunteerId)
                    ->where('dog_id', $dog->id)
                    ->exists();
            }
        }

        return view('walks.show', compact('dog', 'isFavorite'));
    }

    public function reserve(Request $request, $id)
    {
        // Blokada: tylko wolontariusz może rezerwować
        if (!CurrUser::isLogged() || CurrUser::getRole() != "Volunteer") {
            return redirect()->back()->with('error', 'Tylko zalogowani wolontariusze mogą rezerwować spacery.');
        }

        $dog = Dog::findOrFail($id);
        $date = $request->input('walk_date');
        $time = $request->input('walk_time');
        
        // ZABEZPIECZENIE: Blokada rezerwacji godzin, które już minęły w danym dniu
        if ($date == date('Y-m-d')) {
            $currentHour = (int) date('H');
            $selectedHour = (int) substr($time, 0, 2);
            
            if ($selectedHour <= $currentHour) {
                return redirect()->back()->with('error', 'Nie możesz zarezerwować spaceru w godzinie, która już minęła.');
            }
        }

        $volunteerId = DB::table('volunteers')->where('account_id', CurrUser::getId())->value('id');
        
        if (!$volunteerId) {
            return redirect()->back()->with('error', 'Błąd: nie znaleziono profilu wolontariusza przypisanego do konta.');
        }

        $existingWalk = DB::table('schedules')
            ->where('dog_id', $dog->id)
            ->where('Date', $date)
            ->where('Time', $time)
            ->first();

        if ($existingWalk) {
            return redirect()->back()->with('error', 'Niestety, ' . $dog->Name . ' ma już zaplanowany spacer w tym terminie.');
        }

        $randomWorkerId = DB::table('workers')->inRandomOrder()->value('id');

        // 2. Awaryjne zabezpieczenie (gdyby tabela workers była pusta)
        if (!$randomWorkerId) {
            $randomWorkerId = 1;
        }

        // 3. Zapis do bazy z nowym, wylosowanym nadzorcą
        DB::table('schedules')->insert([
            'Date' => $date,
            'Time' => $time,
            'volunteer_id' => $volunteerId, 
            'dog_id' => $dog->id,
            'supervisor_id' => $randomWorkerId, // Zamiast sztywnego 1, wstawiamy wylosowane ID
        ]);

        return redirect()->back()->with('success', 'Udało się! Spacer zarezerwowany.');
    }

    public function toggleFavorite(Request $request, $id)
    {
        if (!CurrUser::isLogged() || CurrUser::getRole() != "Volunteer") {
            return redirect()->back()->with('error', 'Brak uprawnień.');
        }

        // Tłumaczymy account_id na volunteer_id
        $realVolunteerId = DB::table('volunteers')->where('account_id', CurrUser::getId())->value('id');

        if (!$realVolunteerId) {
            return redirect()->back()->with('error', 'Błąd: nie znaleziono profilu wolontariusza.');
        }

        $existing = DB::table('fav_dogs')
            ->where('volunteer_id', $realVolunteerId)
            ->where('dog_id', $id)
            ->first();

        if ($existing) {
            DB::table('fav_dogs')
                ->where('volunteer_id', $realVolunteerId)
                ->where('dog_id', $id)
                ->delete();
            return redirect()->back()->with('success', 'Usunięto psa z ulubionych.');
        } else {
            DB::table('fav_dogs')->insert([
                'volunteer_id' => $realVolunteerId,
                'dog_id' => $id
            ]);
            return redirect()->back()->with('success', 'Dodano psa do ulubionych!');
        }
    }

    public function getBookedTimes(Request $request, $id)
    {
        $date = $request->query('date');
        $bookedTimes = DB::table('schedules')
            ->where('dog_id', $id)
            ->where('Date', $date)
            ->pluck('Time');
            
        return response()->json($bookedTimes);
    }

    public function showDetails($id)
{
    // 1. Pobieramy główny rekord spaceru
    $walk = DB::table('schedules')->where('id', $id)->first();
    
    if (!$walk) {
        abort(404, 'Nie znaleziono takiego spaceru.');
    }

    // 2. BEZPIECZEŃSTWO: Wolontariusz może zobaczyć TYLKO swój spacer
    if (CurrUser::isLogged() && CurrUser::getRole() == "Volunteer") {
        $realVolunteerId = DB::table('volunteers')->where('account_id', CurrUser::getId())->value('id');
        
        if ($walk->volunteer_id != $realVolunteerId) {
            abort(403, 'Nie masz uprawnień do podglądania szczegółów tego spaceru.');
        }
    }
    // Pracownik (Worker) i Admin przechodzą bez powyższej blokady automatycznie

    // 3. Pobieramy szczegółowe dane psa
    $dog = DB::table('dogs')->where('id', $walk->dog_id)->first();

    // 4. Pobieramy dane zalogowanych osób do wyświetlenia (opcjonalnie)
    $volunteerName = DB::table('volunteers')
        ->where('id', $walk->volunteer_id)
        ->select('id') // Tutaj możesz dociągnąć imię/nazwisko jeśli macie je w tabeli volunteers
        ->first();

    return view('walks.details', compact('walk', 'dog'));
}

}
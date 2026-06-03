<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dog;
use Illuminate\Support\Facades\DB;
use App\Utilities\CurrUser;

class WalksController extends Controller
{
    public function index()
{
    // Scenariusz A: Jesteś Pracownikiem
    if (CurrUser::isLogged() && CurrUser::getRole() == "Worker") {
    $today = date('Y-m-d'); // Pobieramy dzisiejszą datę

    $supervisorWalks = DB::table('schedules')
        ->join('dogs', 'schedules.dog_id', '=', 'dogs.id')
        ->select('schedules.*', 'dogs.Name as dog_name')
        ->where('schedules.supervisor_id', CurrUser::getId())
        ->where('schedules.Date', '<', $today) // TYLKO spacery z datą mniejszą niż dzisiaj
        ->whereNotNull('schedules.Note')       // Opcjonalnie: tylko jeśli wolontariusz już dodał notatkę
        ->get();
            
    return view('walks.dashboard', compact('supervisorWalks'));
}

    // Scenariusz B: Jesteś Wolontariuszem (lub gościem)
    $dogs = Dog::all();
    $myWalks = [];
    if (CurrUser::isLogged() && CurrUser::getRole() == "Volunteer") {
        $myWalks = DB::table('schedules')
            ->join('dogs', 'schedules.dog_id', '=', 'dogs.id')
            ->select('schedules.*', 'dogs.Name as dog_name')
            ->where('schedules.volunteer_id', CurrUser::getId())
            ->orderBy('schedules.Date', 'desc')
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

        // Aktualizujemy rekord w tabeli schedules
        DB::table('schedules')
            ->where('id', $id)
            ->where('volunteer_id', CurrUser::getId()) // Upewniamy się, że edytuje swój spacer
            ->update([
                'Note' => $request->input('note')
            ]);

        return redirect()->back()->with('success', 'Dodano notatkę do spaceru!');
    }

    public function addGrade(Request $request, $schedule_id)
    {
        // 1. Sprawdzenie uprawnień: tylko pracownik (Supervisor)
        if (!CurrUser::isLogged() || CurrUser::getRole() != "Worker") {
            return redirect()->back()->with('error', 'Tylko pracownik może oceniać spacery.');
        }

        // 2. Aktualizacja oceny w bazie
        DB::table('schedules')
            ->where('id', $schedule_id)
            ->where('supervisor_id', CurrUser::getId()) // Pracownik ocenia tylko "swoje"
            ->update([
                'Grade' => $request->input('grade')
            ]);

        return redirect()->back()->with('success', 'Ocena została zapisana!');
    }

    public function cancelWalk($id)
{
    // Sprawdzamy czy to wolontariusz i czy spacer należy do niego
    if (!CurrUser::isLogged() || CurrUser::getRole() != "Volunteer") {
        return redirect()->back()->with('error', 'Brak uprawnień.');
    }

    // Usuwamy tylko jeśli spacer jest w przyszłości (dla bezpieczeństwa)
    $walk = DB::table('schedules')
        ->where('id', $id)
        ->where('volunteer_id', CurrUser::getId())
        ->where('Date', '>=', date('Y-m-d'))
        ->delete();

    if ($walk) {
        return redirect()->back()->with('success', 'Spacer został anulowany.');
    } else {
        return redirect()->back()->with('error', 'Nie można anulować tego spaceru.');
    }
}

    public function show($id)
    {
        $dog = Dog::findOrFail($id);
        $isFavorite = false;

        // Sprawdzamy ulubione TYLKO jeśli użytkownik to zalogowany wolontariusz
        if (CurrUser::isLogged() && CurrUser::getRole() == "Volunteer") {
            $isFavorite = DB::table('fav_dogs')
                ->where('volunteer_id', CurrUser::getId())
                ->where('dog_id', $dog->id)
                ->exists();
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
        $volunteerId = CurrUser::getId(); // Pobieramy poprawne ID z Waszej sesji

        $existingWalk = DB::table('schedules')
            ->where('dog_id', $dog->id)
            ->where('Date', $date)
            ->where('Time', $time)
            ->first();

        if ($existingWalk) {
            return redirect()->back()->with('error', 'Niestety, ' . $dog->Name . ' ma już zaplanowany spacer w tym terminie.');
        }

        DB::table('schedules')->insert([
            'Date' => $date,
            'Time' => $time,
            'volunteer_id' => $volunteerId, 
            'dog_id' => $dog->id,
            'supervisor_id' => 1, // Pozostawiam 1 awaryjnie dla klucza obcego Pawła
        ]);

        return redirect()->back()->with('success', 'Udało się! Spacer zarezerwowany.');
    }

    public function toggleFavorite(Request $request, $id)
    {
        // Blokada: tylko wolontariusz może polubić psa
        if (!CurrUser::isLogged() || CurrUser::getRole() != "Volunteer") {
            return redirect()->back()->with('error', 'Brak uprawnień.');
        }

        $volunteerId = CurrUser::getId();

        $existing = DB::table('fav_dogs')
            ->where('volunteer_id', $volunteerId)
            ->where('dog_id', $id)
            ->first();

        if ($existing) {
            DB::table('fav_dogs')
                ->where('volunteer_id', $volunteerId)
                ->where('dog_id', $id)
                ->delete();
            return redirect()->back()->with('success', 'Usunięto psa z ulubionych.');
        } else {
            DB::table('fav_dogs')->insert([
                'volunteer_id' => $volunteerId,
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

}
<?php

namespace App\Http\Controllers;

use App\Models\Dog;
use App\Models\Schedule;
use App\DTOs\DogDTO;
use Illuminate\Http\Request;
use App\Models\Fav_Dog;
use App\Utilities\CurrUser;
use App\Models\Volunteer;
use Illuminate\Support\Facades\DB;

class DogsController extends Controller
{

private function getVolunteerId(): ?int
{
    if (!CurrUser::IsLogged() || CurrUser::getRole() !== 'Volunteer') {
        return null;
    }

    $volunteer = Volunteer::where('account_id', CurrUser::getId())->first();

    return $volunteer?->id;
}


private function uploadPhoto($request, $existingPhoto = null): string
{
    if ($request->hasFile('Photo')) {
        $file = $request->file('Photo');
        $path = $file->store('dogs', 'public');
        return '/storage/' . $path;
    }

    if ($existingPhoto) {
        return $existingPhoto;
    }

    return asset('images/default_dog.png');
}


    public function index()
{
    $dogs = Dog::all();

    $favDogIds = [];

    if (CurrUser::IsLogged() && CurrUser::getRole() === 'Volunteer') {

        $volunteer = Volunteer::where(
            'account_id',
            CurrUser::getId()
        )->first();

        if ($volunteer) {
            $favDogIds = Fav_Dog::where('volunteer_id', $volunteer->id)
                ->pluck('dog_id')
                ->toArray();
        }
    }

    $favoriteDogs = $dogs->whereIn('id', $favDogIds);
    $otherDogs = $dogs->whereNotIn('id', $favDogIds);

    return view('dogs.index', compact(
        'favoriteDogs',
        'otherDogs',
        'favDogIds'
    ));
}

    public function show($id)
    {
        $dog = Dog::findOrFail($id);

        return view('dogs.show', compact('dog'));
    }

    public function create()
    {
        return view('dogs.create');
    }

    public function store(Request $request)
    {
         $data = $request->only([
        'Name',
        'Age',
        'Behaviour',
        'State'
    ]);

    $data['Photo'] = $this->uploadPhoto($request);


        Dog::create($data);
        return redirect('/dogs');
    }

    public function edit($id)
    {
        $dog = Dog::findOrFail($id);

        return view('dogs.edit', compact('dog'));
    }

    public function update(Request $request, $id)
    {
        $dog = Dog::findOrFail($id);

         $data = $request->only([
        'Name',
        'Age',
        'Behaviour',
        'State'
    ]);

        $data['Photo'] = $this->uploadPhoto($request, $dog->Photo);
        $dog->update($data);

        return redirect('/dogs/' . $id);
    }



    // spacery
    public function walks($id)
{
    if (!\App\Utilities\CurrUser::IsLogged()) {
        return redirect('/login');
    }

    $dog = \App\Models\Dog::findOrFail($id);

    $role = \App\Utilities\CurrUser::getRole();
    

    if ($role === 'Worker') {
        $walks = \App\Models\Schedule::where('dog_id', $id)
            ->orderBy('Date', 'desc')
            ->orderBy('Time', 'desc')
            ->get();
    }

    elseif ($role === 'Volunteer') {
        $volunteerId = $this->getVolunteerId();

        if (!$volunteerId) {
            return redirect('/dogs');
        }

        $walks = \App\Models\Schedule::where('dog_id', $id)
            ->where('volunteer_id', $volunteerId)
            ->orderBy('Date', 'desc')
            ->orderBy('Time', 'desc')
            ->get()
            ->map(function ($walk) {
                $walk->datetime = \Carbon\Carbon::parse($walk->Date . ' ' . $walk->Time);
                 $walk->can_note = $walk->datetime->greaterThan(now()->subHours(72)) &&
                          $walk->datetime->lessThanOrEqualTo(now());
                return $walk;});
    }

    else {
        return redirect('/dogs');
    }

    return view('dogs.walks', compact('walks', 'dog'));
}



public function reserveWalk(Request $request, $id)
{
    $volunteerId = $this->getVolunteerId();

    if (!$volunteerId) {
        return redirect('/login');
    }

    $dog = Dog::findOrFail($id);

    $exists =DB::table('schedules')
        ->where('dog_id', $dog->id)
        ->where('Date', $request->walk_date)
        ->where('Time', $request->walk_time)
        ->exists();

    if ($exists) {
        return redirect()->back()
            ->with('error', 'Ten termin jest już zajęty.');
    }

    $walkDateTime = \Carbon\Carbon::parse($request->walk_date . ' ' . $request->walk_time);

    if ($walkDateTime->lessThanOrEqualTo(now())) {
    return redirect()->back()
        ->with('error', 'Termin niedostępny');
}

    DB::table('schedules')->insert([
        'Date' => $request->walk_date,
        'Time' => $request->walk_time,
        'dog_id' => $dog->id,
        'volunteer_id' => $volunteerId,
        'supervisor_id' => \App\Models\Worker::first()->id,
        'Note' => null,
        'Grade' => null,
    ]);

    return redirect()->back()
        ->with('success', 'Zarezerwowano spacer');
}


public function cancelWalk($scheduleId)
{
    $volunteerId = $this->getVolunteerId();

    if (!$volunteerId) {
        return redirect('/login');
    }

    $walk = DB::table('schedules')
        ->where('id', $scheduleId)
        ->where('volunteer_id', $volunteerId)
        ->first();

    if (!$walk) {
        return redirect()->back();
    }

    $walkDateTime = \Carbon\Carbon::parse(
        $walk->Date . ' ' . $walk->Time
    );

    if ($walkDateTime->lessThanOrEqualTo(now())) {
        return redirect()->back()
            ->with('error', 'Nie można anulować zakończonego spaceru.');
    }

    DB::table('schedules')
        ->where('id', $scheduleId)
        ->delete();

    return redirect()->back()
        ->with('success', 'Anulowano spacer');
}





//ulubione 

    public function toggleFavorite($id)
    {
        if (!CurrUser::IsLogged()) {
            return redirect('/login');
        }

        if (CurrUser::getRole() !== 'Volunteer') {
            return redirect('/dogs');
        }

        $volunteerId = $this->getVolunteerId();

        if (!$volunteerId) {
            return redirect('/dogs');
        }

        $existing = Fav_Dog::where('dog_id', $id)
            ->where('volunteer_id', $volunteerId)
            ->first();

        if ($existing) {
            $existing->delete();

            return redirect()->back()->with('favorite_removed', true);
        }

        Fav_Dog::create([
            'dog_id' => $id,
            'volunteer_id' => $volunteerId
        ]);

        return redirect()->back()->with('favorite_added', true);
    }
}
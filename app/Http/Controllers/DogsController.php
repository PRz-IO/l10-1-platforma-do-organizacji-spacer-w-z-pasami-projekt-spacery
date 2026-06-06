<?php

namespace App\Http\Controllers;

use App\Models\Dog;
use App\Models\Schedule;
use App\DTOs\DogDTO;
use Illuminate\Http\Request;
use App\Models\Fav_Dog;
use App\Utilities\CurrUser;
use App\Models\Volunteer;

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

    public function walks($id)
{
    if (!\App\Utilities\CurrUser::IsLogged()) {
        return redirect('/login');
    }

    $dog = \App\Models\Dog::findOrFail($id);

    $role = \App\Utilities\CurrUser::getRole();
    $userId = \App\Utilities\CurrUser::getId();

    if ($role === 'Worker') {
        $walks = \App\Models\Schedule::where('dog_id', $id)
            ->orderBy('Date', 'desc')
            ->orderBy('Time', 'desc')
            ->get();
    }

    elseif ($role === 'Volunteer') {
        $walks = \App\Models\Schedule::where('dog_id', $id)
            ->where('volunteer_id', $userId)
            ->orderBy('Date', 'desc')
            ->orderBy('Time', 'desc')
            ->get();
    }

    else {
        return redirect('/dogs');
    }

    return view('dogs.walks', compact('walks', 'dog'));
}

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
<?php

namespace App\Http\Controllers;

use App\Models\Dog;
use App\Models\Schedule;
use App\DTOs\DogDTO;
use Illuminate\Http\Request;
use App\Models\Fav_Dog;
use App\Utilities\CurrUser;

class DogsController extends Controller
{
    public function index()
    {
        $dogs = Dog::all();

        $favDogIds = [];

        if (CurrUser::IsLogged() && CurrUser::getRole() === 'Volunteer') {
            $favDogIds = Fav_Dog::where('volunteer_id', CurrUser::getId())
                ->pluck('dog_id')
                ->toArray();
        }

        return view('dogs.index', compact('dogs', 'favDogIds'));
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
        $dto = DogDTO::fromRequest($request);

        Dog::create($dto->toArray());

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

        $dog->update([
            'Name' => $request->input('Name'),
            'Age' => $request->input('Age'),
            'Behaviour' => $request->input('Behaviour'),
            'State' => $request->input('State'),
            'Photo' => $request->input('Photo'),
        ]);

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

        $volunteerId = CurrUser::getId();

        $existing = Fav_Dog::where('dog_id', $id)
            ->where('volunteer_id', $volunteerId)
            ->first();

        if ($existing) {
            $existing->delete();

            return redirect('/dogs')
                ->with('favorite_removed', true);
        }

        Fav_Dog::create([
            'dog_id' => $id,
            'volunteer_id' => $volunteerId
        ]);

        return redirect('/dogs')
            ->with('favorite_added', true);
    }
}
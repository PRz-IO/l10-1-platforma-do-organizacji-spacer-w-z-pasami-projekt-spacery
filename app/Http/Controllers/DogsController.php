<?php

namespace App\Http\Controllers;

use App\Models\Dog;
use App\Models\Schedule;
use App\DTOs\DogDTO;
use Illuminate\Http\Request;

class DogsController extends Controller
{
    public function index()
    {
        $dogs = Dog::all();

        return view('dogs.index', compact('dogs'));
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
        $walks = Schedule::where('dog_id', $id)
            ->orderBy('Date', 'desc')
            ->get();

        return view('dogs.walks', compact('walks'));
    }
}
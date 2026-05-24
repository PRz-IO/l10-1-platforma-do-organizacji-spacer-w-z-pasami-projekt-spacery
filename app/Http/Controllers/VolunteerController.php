<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Volunteer;
use App\DTOs\VolunteerDTO;
use Illuminate\Http\Request;

class VolunteerController extends Controller
{
    public function index()
    {
        $volunteers = Volunteer::with('account')->get();

        return view('volunteers.index', compact('volunteers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'Name' => 'required|string|max:50',
            'Last_Name' => 'required|string|max:50',
            'Login' => 'required|string|unique:accounts,Login|max:50',
            'Password' => 'required|string|min:8',
            'Is_Experienced' => 'boolean'
        ]);

        $dto = VolunteerDTO::fromRequest($request);

        $account = Account::create($dto->toArray());
        
        $account->volunteer()->create([
            'Is_Experienced' => $dto->isExperienced
        ]);

        return redirect()->route('volunteers.index')->with('success', 'Wolontariusz zarejestrowany!');
    }
}
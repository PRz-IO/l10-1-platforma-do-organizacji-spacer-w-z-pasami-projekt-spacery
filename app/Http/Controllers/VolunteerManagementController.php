<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\Volunteer;
use App\Models\Account;
use App\Models\Schedule;

class VolunteerManagementController extends Controller
{
    public function index()
    {
        $volunteers = Volunteer::with('account')->get();
        return view('volunteers.index', compact('volunteers'));
    }

    public function create()
    {
        return view('volunteers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'Name' => 'required|string|max:255',
            'Last_Name' => 'required|string|max:255',
            'Login' => 'required|string|max:255|unique:accounts,Login',
            'Password' => 'required|string|min:6|confirmed',
            'Email' => 'required|email|unique:accounts,Email',
            'Phone_Num' => 'required|string|max:15',
        ]);

        DB::transaction(function () use ($validated, $request) {
            $account = Account::create([
                'Name' => $validated['Name'],
                'Last_Name' => $validated['Last_Name'],
                'Login' => $validated['Login'],
                'Password' => Hash::make($validated['Password']), 
                'Email' => $validated['Email'],
                'Phone_Num' => $validated['Phone_Num'],
                'Acc_State' => 'Active', 
            ]);

            Volunteer::create([
                'Account_Id' => $account->id,
                'Is_Experienced' => $request->has('Is_Experienced'),
            ]);
        });

        return redirect()->route('worker.volunteers.index')->with('success', 'Wolontariusz został dodany do systemu.');
    }

    public function show($id)
    {
        $volunteer = Volunteer::with('account')->findOrFail($id);
        
        $schedules = Schedule::where('Volunteer_Id', $volunteer->Id)
                             ->with(['dog', 'worker.account'])
                             ->orderBy('Date', 'desc')
                             ->get();

        return view('volunteers.show', compact('volunteer', 'schedules'));
    }

    public function edit($id)
    {
        $volunteer = Volunteer::with('account')->findOrFail($id);
        return view('volunteers.edit', compact('volunteer'));
    }

    public function update(Request $request, $id)
    {
        $volunteer = Volunteer::with('account')->findOrFail($id);
        
        $validated = $request->validate([
            'Name' => 'required|string|max:255',
            'Last_Name' => 'required|string|max:255',
            'Email' => 'required|email',
            'Phone_Num' => 'required|string|max:15',
        ]);

        $volunteer->account->update([
            'Name' => $validated['Name'],
            'Last_Name' => $validated['Last_Name'],
            'Email' => $validated['Email'],
            'Phone_Num' => $validated['Phone_Num'],
        ]);

        $volunteer->update([
            'Is_Experienced' => $request->has('Is_Experienced')
        ]);

        return redirect()->route('worker.volunteers.index')->with('success', 'Dane wolontariusza zostały zaktualizowane.');
    }

    public function destroy($id)
    {
        $volunteer = Volunteer::findOrFail($id);
        $accountId = $volunteer->Account_Id;

        DB::transaction(function () use ($volunteer, $accountId) {
            $volunteer->delete();
            Account::findOrFail($accountId)->delete();
        });

        return redirect()->route('worker.volunteers.index')->with('success', 'Konto wolontariusza zostało trwale usunięte.');
    }

    public function approve($id)
    {
        $volunteer = Volunteer::findOrFail($id);
        $volunteer->account->update(['Acc_State' => 'Active']);
        return redirect()->back()->with('success', 'Konto wolontariusza zostało zatwierdzone.');
    }

    public function block($id)
    {
        $volunteer = Volunteer::findOrFail($id);
        $volunteer->account->update(['Acc_State' => 'Blocked']);
        return redirect()->back()->with('success', 'Konto wolontariusza zostało zablokowane.');
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Volunteer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

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
            'Name' => 'required',
            'Last_Name' => 'required',
            'Login' => 'required|unique:accounts,Login',
            'Password' => 'required|min:6',
            'Email' => 'required|email|unique:accounts,Email',
            'Phone_Num' => 'required',
        ]);

        DB::transaction(function () use ($validated, $request) {
            $account = Account::create([
                'Name' => $validated['Name'],
                'Last_Name' => $validated['Last_Name'],
                'Login' => $validated['Login'],
                'Password' => Hash::make($validated['Password']),
                'Email' => $validated['Email'],
                'Phone_Num' => $validated['Phone_Num'],
                'Acc_State' => 'Pending',
            ]);

            Volunteer::create([
                'account_id' => $account->id, 
                'Is_Experienced' => $request->has('Is_Experienced'),
            ]);
        });

        return redirect()->route('worker.volunteers.index')->with('success', 'Wolontariusz został dodany do systemu.');
    }

    public function show($id)
    {
        $volunteer = Volunteer::with(['account', 'schedules.dog'])->findOrFail($id);
        return view('volunteers.show', compact('volunteer'));
    }

    public function edit($id)
    {
        $volunteer = Volunteer::with('account')->findOrFail($id);
        return view('volunteers.edit', compact('volunteer'));
    }

    public function update(Request $request, $id)
    {
        $volunteer = Volunteer::findOrFail($id);
        
        $validated = $request->validate([
            'Name' => 'required',
            'Last_Name' => 'required',
            'Email' => 'required|email|unique:accounts,Email,' . $volunteer->account_id . ',id',
            'Phone_Num' => 'required',
        ]);

        if ($volunteer->account) {
            $volunteer->account->update([
                'Name' => $validated['Name'],
                'Last_Name' => $validated['Last_Name'],
                'Email' => $validated['Email'],
                'Phone_Num' => $validated['Phone_Num'],
            ]);
        }

        $volunteer->update([
            'Is_Experienced' => $request->has('Is_Experienced'),
        ]);

        return redirect()->route('worker.volunteers.index')->with('success', 'Dane wolontariusza zostały zaktualizowane.');
    }

    public function approve($id)
    {
        $volunteer = Volunteer::findOrFail($id);
        if ($volunteer->account) {
            $volunteer->account->update(['Acc_State' => 'Active']);
        }
        return redirect()->route('worker.volunteers.index')->with('success', 'Konto wolontariusza zostało aktywowane.');
    }

    public function block($id)
    {
        $volunteer = Volunteer::findOrFail($id);
        if ($volunteer->account) {
            $volunteer->account->update(['Acc_State' => 'Blocked']);
        }
        return redirect()->route('worker.volunteers.index')->with('success', 'Konto wolontariusza zostało zablokowane.');
    }

    public function destroy($id)
    {
        $volunteer = Volunteer::findOrFail($id);
        
        if ($volunteer->account) {
            $volunteer->account->update(['Acc_State' => 'Deleted']);
        }

        return redirect()->route('worker.volunteers.index')->with('success', 'Status wolontariusza został zmieniony na usunięty.');
    }
}
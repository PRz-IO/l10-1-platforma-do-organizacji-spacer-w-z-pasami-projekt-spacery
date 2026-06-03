<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Volunteer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

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
            'Email' => 'required|email|unique:accounts,Email',
            'Phone_Num' => 'required',
        ]);

        $randomPassword = Str::random(8);

        DB::transaction(function () use ($validated, $request, $randomPassword) {
            $account = Account::create([
                'Name' => $validated['Name'],
                'Last_Name' => $validated['Last_Name'],
                'Login' => $validated['Login'],
                'Password' => Hash::make($randomPassword),
                'Email' => $validated['Email'],
                'Phone_Num' => $validated['Phone_Num'],
                'Acc_State' => 'Pending',
                'Creation_Date' => now()->format('Y-m-d'),
            ]);

            Volunteer::create([
                'account_id' => $account->id, 
                'Is_Experienced' => $request->has('Is_Experienced'),
            ]);
        });

        return redirect()->route('worker.volunteers.index')
            ->with('success', "Wolontariusz został dodany! Hasło startowe: {$randomPassword}");
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
        
        DB::transaction(function () use ($volunteer) {
            if ($volunteer->account) {
                $volunteer->account->update(['Acc_State' => 'Blocked']);
            }
            
            $volunteer->schedules()->where('date', '>=', now()->format('Y-m-d'))->delete();
        });

        return redirect()->route('worker.volunteers.index')->with('success', 'Konto zostało zablokowane, a przyszłe spacery anulowane.');
    }

    public function destroy($id)
    {
        $volunteer = Volunteer::findOrFail($id);
        
        DB::transaction(function () use ($volunteer) {
            if ($volunteer->account) {
                $volunteer->account->update(['Acc_State' => 'Deleted']);
            }
            
            $volunteer->schedules()->where('date', '>=', now()->format('Y-m-d'))->delete();
        });

        return redirect()->route('worker.volunteers.index')->with('success', 'Status wolontariusza został zmieniony na usunięty, a przyszłe rezerwacje zwolnione.');
    }

    public function storeRating(Request $request, $id)
    {
        $volunteer = Volunteer::findOrFail($id);

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:500'
        ]);

        $volunteer->update([
            'last_rating' => $validated['rating'],
            'rating_comment' => $validated['comment']
        ]);

        return redirect()->route('worker.volunteers.show', $id)->with('success', 'Ocena wolontariusza została pomyślnie dodana.');
    }

    public function resetPassword($id)
    {
        $volunteer = Volunteer::findOrFail($id);
        if (!$volunteer->account) {
            return redirect()->route('worker.volunteers.index')->with('error', 'Nie znaleziono powiązanego konta.');
        }

        $newPassword = Str::random(8);
        $volunteer->account->update(['Password' => Hash::make($newPassword)]);

        return redirect()->route('worker.volunteers.index')
            ->with('success', "Hasło dla wolontariusza zostało zresetowane na: <strong>{$newPassword}</strong>");
    }
}
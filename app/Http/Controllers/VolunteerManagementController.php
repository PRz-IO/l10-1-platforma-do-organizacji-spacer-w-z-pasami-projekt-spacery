<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Volunteer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Schedule;
use App\DTOs\VolunteerDTO; // <-- Import klasy DTO

class VolunteerManagementController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $query = Volunteer::query()
            ->join('accounts', 'volunteers.account_id', '=', 'accounts.id')
            ->select('volunteers.*')
            ->with('account')
            ->withAvg('schedules as average_rating', 'Grade'); 

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('accounts.Last_Name', 'like', "%{$search}%")
                  ->orWhere('accounts.Name', 'like', "%{$search}%")
                  ->orWhere('accounts.Login', 'like', "%{$search}%");
            });
        }
        $volunteers = $query->orderBy('accounts.Name', 'asc')
                            ->orderBy('accounts.Last_Name', 'asc')
                            ->get();

        return view('volunteers.index', compact('volunteers', 'search'));
    }

    public function create()
    {
        return view('volunteers.create');
    }

    public function store(Request $request)
    {
        // 1. Walidacja unikalności danych wejściowych
        $validated = $request->validate([
            'Name' => 'required',
            'Last_Name' => 'required',
            'Login' => 'required|unique:accounts,Login',
            'Email' => 'required|email|unique:accounts,Email',
            'Phone_Num' => 'required|unique:accounts,Phone_Num',
        ], [
            'Phone_Num.unique' => 'Ten numer telefonu jest już przypisany do innego konta w systemie.',
            'Email.unique' => 'Ten email jest już zajęty.',
            'Login.unique' => 'Ten login jest już zajęty.',
        ]);

        // 2. Generowanie losowego hasła startowego
        $randomPassword = substr(str_shuffle('abcdefghjkmnpqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ123456789'), 0, 8);

        // 3. Inicjalizacja obiektu DTO z przekazaniem wygenerowanego hasła
        $dto = VolunteerDTO::fromRequest($request, $randomPassword);

        // 4. Bezpieczny zapis transakcyjny w bazie danych przy użyciu struktury z DTO
        DB::transaction(function () use ($dto) {
            // toAccountArray() dostarcza kompletną tablicę z już zahashowanym hasłem
            $account = Account::create($dto->toAccountArray());

            Volunteer::create([
                'account_id' => $account->id, 
                'Is_Experienced' => $dto->isExperienced,
            ]);
        });

        return redirect()->route('worker.volunteers.index')
            ->with('success', "Wolontariusz został dodany! Hasło startowe: <strong style='font-family: Consolas, Courier New, monospace; font-size: 16px; background: #fff; padding: 4px 8px; border: 1px solid #ced4da; border-radius: 4px; letter-spacing: 2px; color: #dc3545;'>{$randomPassword}</strong>");
    }

    public function show($id)
    {
        $volunteer = Volunteer::with(['account', 'schedules.dog'])
            ->withAvg('schedules as average_rating', 'Grade')
            ->findOrFail($id);
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

        return redirect()->route('worker.volunteers.show', $id)
                     ->with('success', 'Dane wolontariusza zostały pomyślnie zaktualizowane.');
    }

    public function approve($id)
    {
        $volunteer = Volunteer::findOrFail($id);
        if ($volunteer->account) {
            $volunteer->account->update(['Acc_State' => 'Active']);
        }
        return redirect()->back()->with('success', 'Konto wolontariusza zostało aktywowane.');
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

        return redirect()->back()->with('success', 'Konto wolontariusza zostało zablokowane.');
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

        $newPassword = substr(str_shuffle('abcdefghjkmnpqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ23456789'), 0, 8);
        $volunteer->account->update(['Password' => Hash::make($newPassword)]);

        return redirect()->back()
        ->with('success', "Hasło dla wolontariusza zostało zresetowane na: <strong style='font-family: Consolas, Courier New, monospace; font-size: 16px; background: #fff; padding: 4px 8px; border: 1px solid #ced4da; border-radius: 4px; letter-spacing: 2px; color: #dc3545;'>{$newPassword}</strong>");
    }

    public function rateSchedule(Request $request, $id)
    {
        $validated = $request->validate([
            'Grade' => 'required|integer|between:1,5',
            'Note' => 'nullable|string|max:1000',
        ]);

        $schedule = Schedule::findOrFail($id);
        $schedule->update([
            'Grade' => $validated['Grade'],
            'Note' => $validated['Note'],
        ]);

        return back()->with('success', 'Ocena oraz uwagi ze spaceru zostały pomyślnie zapisane!');
    }
}
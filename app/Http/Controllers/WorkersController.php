<?php

namespace App\Http\Controllers;

use App\DTOs\CreateWorkerDTO;
use App\DTOs\UpdateWorkerDTO;
use App\Models\Account;
use App\Models\Worker;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;




class WorkersController extends Controller
{
    public function index()
    {
        $query = Worker::with('account');
        if (!auth()->user()?->Is_Admin) {
            $query->whereHas('account', function ($q) {
                $q->where('Acc_State', '!=', 'Deleted');
            });
        }

        $workers = $query->get();

        return view('workers.index', compact('workers'));
    }



    public function show($id)
    {
        $worker = Worker::with([
            'account',
            'schedules.volunteer',
            'schedules.dog'
        ])->findOrFail($id);

        $schedules = $worker->schedules()->orderByDesc('Date')->orderByDesc('Time')->get();
        return view('workers.show', compact('worker', 'schedules'));
    }

    public function create()
    {
        return view('workers.create');
    }

    public function store(Request $request)
    {
        $password = Str::random(10);
        $validated = $request->validate([
            'Name' => 'required|string',
            'Last_Name' => 'required|string',
            'Login' => 'required|string',
            'Email' => 'required|email',
            'Phone_Num' => 'required|string',
            'Is_Admin' => 'boolean',
        ]);

        $validated['Password'] = $password;

        $dto = CreateWorkerDTO::fromArray($validated);

        DB::transaction(function () use ($dto, $request) 
        {
            $account = Account::create($dto->toAccountArray());
            $worker = Worker::create($dto->toWorkerArray($account->getKey()));
        });

        return redirect()->route('workers.index')->with('success', 'Pracownik utworzony')->with('generated_password', $password);
    }


    public function update(Request $request, $id)
    {
        $worker = Worker::findOrFail($id);
        $account = Account::findOrFail($worker->account_id);
        $validated = $request->validate([
            'Name' => 'required|string|max:40',
            'Last_Name' => 'required|string|max:40',
            'Email' => 'required|email|max:60',
            'Phone_Num' => 'required|string|min:9|max:9',
            'Is_Admin' => 'nullable|boolean',
        ]);

        $dto = UpdateWorkerDTO::fromArray($validated);

        DB::transaction(function () use ($dto, $request, $account, $worker) 
        {

            $account->update($dto->toAccountArray());
            $worker->update($dto->toWorkerArray());
        });

        return redirect()->route('workers.index')->with('success', 'Dane pracownika zostały zaktualizowane.');
    }



    public function destroy($id)
    {
        $worker = Worker::find($id);

        if (!$worker) {
            return response()->json([
                'message' => 'Worker not found'
            ], 404);
        }

        $account = Account::find($worker->account_id);

        if (!$account) {
            return response()->json([
                'message' => 'Account not found'
            ], 404);
        }

        $account->update([
            'Acc_State' => 'Deleted'
        ]);

        $worker->update(['Is_Admin' => false]);

        return redirect()->route('workers.index')->with('success', 'Pracownik został usunięty.');
    }



    public function edit($id)
    {
        $worker = Worker::with('account')->find($id);

        if (!$worker) {
            return response()->json([
                'message' => 'Worker not found'
            ], 404);
        }

        return view('workers.edit', compact('worker'));
    }

    public function block($id)
    {
        $worker = Worker::findOrFail($id);
        $account = Account::find($worker->account_id);

        $account->update([
            'Acc_State' => 'Blocked'
        ]);

        return redirect()->back()->with('success', 'Pracownik został zablokowany.');
    }


    public function unblock($id)
    {
        $worker = Worker::findOrFail($id);
        $account = Account::find($worker->account_id);

        $account->update([
            'Acc_State' => 'Active'
        ]);

        return redirect()->back()->with('success', 'Pracownik został odblokowany.');
    }

    public function approve($id)
    {
        $worker = Worker::findOrFail($id);
        $account = Account::find($worker->account_id);

        $account->update([
            'Acc_State' => 'Active'
        ]);

        return redirect()->back()->with('success', 'Pracownik został zatwierdzony.');
    }

    public function resetPassword($id)
    {
        $worker = Worker::with('account')->findOrFail($id);

        $password = Str::random(10);

        $worker->account->update([
            'Password' => bcrypt($password),
        ]);

        return redirect()->back()->with('success', 'Hasło zostało zresetowane.')->with('generated_password', $password);
    }
}

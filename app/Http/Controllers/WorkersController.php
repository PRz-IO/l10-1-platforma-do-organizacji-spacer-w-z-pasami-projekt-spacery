<?php

namespace App\Http\Controllers;

use App\DTOs\WorkerDTO;
use App\Models\Account;
use App\Models\Worker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class WorkersController extends Controller
{
    public function index()
    {
        $workers = Worker::with('account')->get();
        return view('workers.index', compact('workers'));
    }



    public function show($id)
    {
        $worker = Worker::with('account')->find($id);

        if (!$worker) {
            return response()->json([
                'message' => 'Worker not found'
            ], 404);
        }

        return view('workers.show', compact('worker'));
    }

    public function create()
    {
        return view('workers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'Name' => 'required|string|max:40',
            'Last_Name' => 'required|string|max:40',
            'Login' => 'required|string|max:40|unique:Accounts,Login',
            'Password' => 'required|string|min:6',
            'Email' => 'required|string|max:60',
            'Phone_Num' => 'required|string|max:40',
            'Is_Admin' => 'nullable|boolean',
        ]);

        $dto = WorkerDTO::fromArray($validated);

        DB::transaction(function () use ($dto, $request) 
        {
            $account = Account::create($dto->toAccountArray());
            $worker = Worker::create($dto->toWorkerArray($account->getKey()));
        });

        return redirect()->route('workers.index')->with('success', 'Pracownik utworzony');
    }


    // TO DO:
    // Needs fixing
    // Update somewhere god knows where loses connection or something
    // Tests return error 404
    public function update(Request $request, $id)
    {
        $worker = Worker::find($id);

        if (!$worker) {
            return response()->json([
                'message' => 'Worker not found'
            ], 404);
        }

        $account = Account::find($worker->Account_Id);

        if (!$account) {
            return response()->json([
                'message' => 'Account not found'
            ], 404);
        }


        $validated = $request->validate([
            'Name' => 'required|string|max:40',
            'Last_Name' => 'required|string|max:40',
            'Login' => 'required|string|max:40',
            'Password' => 'required|string|min:6',
            'Email' => 'required|string|max:60',
            'Phone_Num' => 'required|string|min:9|max:9',
            'Acc_State' => 'nullable|string|max:40',            
            'Is_Admin' => 'boolean'
        ]);

        $dto = WorkerDTO::fromArray($validated);

        $account->update(
            $dto->toAccountArray()
        );
    

        $worker->update(
            $dto->toWorkerArray($account->Id)
        );

        return response()->json([
            'account' => $account,
            'worker' => $worker
        ]);
    }



    public function destroy($id)
    {
        $worker = Worker::find($id);

        if (!$worker) {
            return response()->json([
                'message' => 'Worker not found'
            ], 404);
        }

        $account = Account::find($worker->Account_Id);

        $worker->delete();

        if ($account) {
            $account->delete();
        }

        return response()->json([
            'message' => 'Worker deleted'
        ]);
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
}

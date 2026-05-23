<?php

namespace App\Http\Controllers;

use App\DTOs\VolunteerDTO;
use App\Models\Account;
use App\Models\Volunteer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Volunteers extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'Name' => 'required|string|max:255',
            'Last_Name' => 'required|string|max:255',
            'Login' => 'required|string|unique:accounts,Login',
            'Password' => 'required|string|min:8',
        ]);
        $dto = VolunteerDTO::fromRequest($request);
        try {
            DB::beginTransaction();
            $account = Account::create($dto->toArray());
            $account->volunteer()->create([
                'Is_Experienced' => $dto->isExperienced,
            ]);
            DB::commit();
            return response()->json(['message' => 'Volunteer registered successfully'], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Registration failed', 'error' => $e->getMessage()], 500);
        }
    }
}
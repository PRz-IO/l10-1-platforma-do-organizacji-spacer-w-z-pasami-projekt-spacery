<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Volunteer;
use App\Models\Worker;
use App\Utilities\CurrUser;

class ProfilesController extends Controller
{
    public function profile_type()
    {
        $role = CurrUser::getRole();
        $accountId = CurrUser::getId();

        if ($role === 'Volunteer') {
            $profile = Volunteer::where('account_id', $accountId)->first();

            return view('profiles.volunteer', compact('profile'));
        }

        if ($role === 'Worker') {
            $profile = Worker::where('account_id', $accountId)->first();

            return view('profiles.worker', compact('profile'));
        }

        return redirect('/login');
    }
}

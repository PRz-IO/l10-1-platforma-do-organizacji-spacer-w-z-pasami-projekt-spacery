<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Volunteer;
use App\Models\Worker;
use App\Utilities\CurrUser;
use App\Models\Dog;
use App\Models\Fav_Dog;


class ProfilesController extends Controller
{
    public function show()
    {
        $role = CurrUser::getRole();
        $accountId = CurrUser::getId();

        if ($role === 'Volunteer') {
            $profile = Volunteer::where('account_id', $accountId)->first();

            $favDogIds = Fav_Dog::where('volunteer_id', $profile->id)
                ->pluck('dog_id');

            $favoriteDogs = Dog::whereIn('id', $favDogIds)->get();

            return view('profiles.volunteer', compact('profile', 'favoriteDogs'));
        }

        if ($role === 'Worker') {
            $profile = Worker::where('account_id', $accountId)->first();

            return view('profiles.worker', compact('profile'));
        }

        return redirect('/login');
    }
}

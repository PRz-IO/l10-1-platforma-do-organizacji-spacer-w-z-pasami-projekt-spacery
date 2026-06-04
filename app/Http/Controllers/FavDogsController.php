<?php

namespace App\Http\Controllers;

use App\Models\Fav_Dog;
use App\Utilities\CurrUser;
use Illuminate\Http\Request;

class FavoriteDogsController extends Controller
{
    public function toggle($dogId)
    {
        if (!CurrUser::IsLogged() || CurrUser::getRole() !== 'Volunteer') {
            return redirect('/dogs');
        }

        $volunteerId = CurrUser::getId();

        $fav = Fav_Dog::where('dog_id', $dogId)
            ->where('volunteer_id', $volunteerId)
            ->first();

        if ($fav) {
            $fav->delete();

            return redirect('/dogs')
                ->with('favorite_removed', true);
        }

        Fav_Dog::create([
            'dog_id' => $dogId,
            'volunteer_id' => $volunteerId,
        ]);

        return redirect('/dogs')
            ->with('favorite_added', true);
    }
}
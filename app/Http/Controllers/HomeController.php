<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dog;
use App\Models\Volunteer;
use App\Models\Schedule;
use App\Utilities\CurrUser; // Importujemy klasę pomocniczą

class HomeController extends Controller
{
    public function index()
    {
        // Ogólne statystyki przydatne dla obu widoków
        $stats = [
            'dogsCount' => Dog::count(),
            'walksCount' => Schedule::count(),
            'volunteersCount' => Volunteer::count()
        ];

        // Jeśli użytkownik jest zalogowany, dajemy mu dedykowany panel
        if (CurrUser::IsLogged()) {
            return view('dashboard', $stats);
        }

        // Jeśli to gość, widzi standardową stronę powitalną
        return view('welcome', $stats);
    }
}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dog;
use App\Models\Walk;
use App\Models\User;

class HomeController extends Controller
{
    public function index()
    {
        return view('welcome', [
            'dogsCount' => Dog::count(),
            // Zlicza wszystkie spacery z tabeli
            'walksCount' => Walk::count(), 
            // Zlicza tylko aktywnych wolontariuszy
            'volunteersCount' => User::where('role', 'volunteer')->where('status', 'active')->count()
        ]);
    }
}
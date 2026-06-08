<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dog;
use App\Models\Volunteer;
use App\Models\Schedule; // Zakładam, że tu trzymacie zaplanowane/odbyte spacery

class HomeController extends Controller
{
    public function index()
    {
        return view('welcome', [
            // Korzystamy dokładnie z tych modeli, które masz w projekcie
            'dogsCount' => Dog::count(),
            'walksCount' => Schedule::count(), 
            'volunteersCount' => Volunteer::count()
        ]);
    }
}
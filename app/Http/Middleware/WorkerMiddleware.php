<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Utilities\CurrUser;

class WorkerMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!CurrUser::IsLogged()) {
            return redirect()->route('login.index');
        }

        if (CurrUser::getRole() !== 'Worker') {
            abort(403);
        }

        return $next($request);
    }
}
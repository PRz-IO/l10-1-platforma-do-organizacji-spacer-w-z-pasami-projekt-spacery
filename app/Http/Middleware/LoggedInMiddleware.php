<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Utilities\CurrUser;

class LoggedInMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!CurrUser::IsLogged()) {
            return redirect()->route('login.index');
        }

        return $next($request);
    }
}

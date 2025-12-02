<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Spatie\Activitylog\Facades\LogBatch;
use Spatie\Activitylog\Models\Activity;
use Illuminate\Support\Facades\Auth;

class LogRouteAccess
{
    public function handle(Request $request, Closure $next)
    {
//        if (Auth::check()) {
            $response=activity()
                ->causedBy(Auth::user())
                ->withProperties([
                    'ip' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'method' => $request->method(),
                    'route' => $request->path(),
                ])
                ->log('User accessed ' . $request->path());
//        }

        return $next($request);
    }
}


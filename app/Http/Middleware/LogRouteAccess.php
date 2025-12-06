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
        // Skip logging for specific routes
        $excludedRoutes = [
            'notifications/unread-count',
        ];
        
        $path = $request->path();
        $shouldLog = true;
        
        foreach ($excludedRoutes as $excludedRoute) {
            if (str_contains($path, $excludedRoute)) {
                $shouldLog = false;
                break;
            }
        }
        
        if ($shouldLog) {
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
        }

        return $next($request);
    }
}


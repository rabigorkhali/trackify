<?php

namespace App\Http\Middleware;

use App\Models\Redirection;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $currentHost = $request->fullUrl();
        $redirectData = Redirection::where('source_link', $currentHost)->where('status',1)->first();
        if ($redirectData) {
            if ($currentHost ===$redirectData->source_link) {
                return redirect()->away($redirectData->destination_link, $redirectData->redirection_type);
            }
        }
        return $next($request);
    }
}

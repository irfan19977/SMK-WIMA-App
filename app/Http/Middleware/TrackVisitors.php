<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;
use App\Models\Visitor;

class TrackVisitors
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Only track GET requests to avoid counting form submissions, API calls, etc.
        if ($request->method() === 'GET') {
            try {
                Visitor::track();
            } catch (\Exception $e) {
                // Silently fail if visitor tracking fails - don't break the site
                Log::error('Visitor tracking failed: ' . $e->getMessage());
            }
        }

        return $next($request);
    }
}

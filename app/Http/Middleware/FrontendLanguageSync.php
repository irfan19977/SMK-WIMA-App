<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class FrontendLanguageSync
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Force sync language for frontend routes if user is authenticated
        if (Auth::check()) {
            $user = Auth::user();
            $language = $user->language ?? 'id';
            
            // Always sync session with user language for frontend
            Session::put('locale', $language);
            App::setLocale($language);
        } else {
            // Use session locale for guest users
            $language = session('locale', 'id');
            App::setLocale($language);
        }
        
        return $next($request);
    }
}

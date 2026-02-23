<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class SetLanguage
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Cek user login
        if (Auth::check()) {
            $user = Auth::user();
            if ($user && $user->language) {
                $language = $user->language;
            } else {
                $language = 'id';
            }
        } else {
            // Untuk guest, gunakan session
            $language = session('locale', 'id');
        }
        
        // Set locale untuk aplikasi
        App::setLocale($language);
        Session::put('locale', $language);
        
        // Debug: Tambahkan header untuk debugging
        $response = $next($request);
        $response->headers->set('X-App-Locale', $language);
        $response->headers->set('X-User-Language', $language);
        
        return $response;
    }
}

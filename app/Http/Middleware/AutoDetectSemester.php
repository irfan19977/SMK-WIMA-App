<?php

namespace App\Http\Middleware;

use App\Models\Semester;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class AutoDetectSemester
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // Auto-detect dan set active semester berdasarkan tanggal sekarang
        $currentSemester = Semester::autoSetActiveSemester();
        
        // Share ke semua views
        View::share('currentSemester', $currentSemester ?: Semester::getCurrentActiveSemester());
        
        return $next($request);
    }
}

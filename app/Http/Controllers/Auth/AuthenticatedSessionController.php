<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $loginField = $request->input('email');
        $password = $request->input('password');
        
        // Cek apakah user login dengan email
        $user = \App\Models\User::where('email', $loginField)->first();
        
        // Jika tidak ditemukan email, coba cari berdasarkan nomor telepon di tabel role
        if (!$user) {
            $roleTables = ['administrator', 'teacher', 'student', 'parent'];
            foreach ($roleTables as $table) {
                $record = DB::table($table)->where('phone', $loginField)->first();
                if ($record) {
                    $user = \App\Models\User::find($record->user_id);
                    break;
                }
            }
        }
        
        if (!$user) {
            return back()->withInput($request->only('email', 'remember'))
                ->withErrors(['email' => 'Email atau nomor telepon tidak ditemukan.']);
        }
        
        // Cek apakah akun aktif
        if (!$user->status) {
            return back()->withInput($request->only('email', 'remember'))
                ->withErrors(['email' => 'Akun Anda telah diblokir. Silahkan hubungi administrator.']);
        }
        
        // Cek password
        if (!\Illuminate\Support\Facades\Hash::check($password, $user->password)) {
            return back()->withInput($request->only('email', 'remember'))
                ->withErrors(['email' => 'Password salah.']);
        }
        
        // Login user
        \Illuminate\Support\Facades\Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}

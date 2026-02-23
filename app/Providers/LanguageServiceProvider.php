<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class LanguageServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Set language based on user preference or session
        $this->setLanguage();
    }

    private function setLanguage(): void
    {
        if (Auth::check()) {
            $user = Auth::user();
            $language = $user && $user->language ? $user->language : 'id';
            \Log::info('LanguageServiceProvider: User logged in, setting language to: ' . $language);
        } else {
            $language = session('locale', 'id');
            \Log::info('LanguageServiceProvider: Guest user, using session language: ' . $language);
        }
        
        App::setLocale($language);
        Session::put('locale', $language);
        \Log::info('LanguageServiceProvider: Final app locale: ' . App::getLocale());
    }
}

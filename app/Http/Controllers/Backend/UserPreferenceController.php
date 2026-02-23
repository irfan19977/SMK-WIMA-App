<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserPreferenceController extends Controller
{
    public function update(Request $request)
    {
        $request->validate([
            'theme_mode' => 'sometimes|required|in:light,dark',
            'language' => 'sometimes|required|in:id,en',
        ]);

        $user = Auth::user();
        
        $updateData = [];
        if ($request->has('theme_mode')) {
            $updateData['theme_mode'] = $request->theme_mode;
        }
        if ($request->has('language')) {
            $updateData['language'] = $request->language;
            // Also set session locale
            session(['locale' => $request->language]);
        }
        
        $user->update($updateData);

        return response()->json([
            'success' => true,
            'message' => 'Preferences updated successfully',
            'preferences' => [
                'theme_mode' => $user->theme_mode ?? 'light',
                'language' => $user->language ?? 'id',
            ]
        ]);
    }

    public function get()
    {
        $user = Auth::user();
        
        return response()->json([
            'theme_mode' => $user->theme_mode ?? 'light',
            'language' => $user->language ?? 'id',
        ]);
    }
}

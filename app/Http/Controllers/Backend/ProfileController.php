<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Display the user's profile view.
     */
    public function show(Request $request): View
    {
        $user = $request->user();
        
        // Check if viewing another user's profile (for admin/teacher)
        if ($request->has('user_id') && auth()->user()->hasRole(['admin', 'Super Admin', 'teacher'])) {
            $targetUser = \App\Models\User::find($request->input('user_id'));
            if ($targetUser) {
                $user = $targetUser;
            }
        }
        
        return view('profile.show', [
            'user' => $user,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $originalData = $user->toArray();
        
        // Handle password change
        if ($request->filled('current_password') && $request->filled('password')) {
            $request->validate([
                'current_password' => ['required', 'current_password'],
                'password' => ['required', 'confirmed', 'min:8'],
            ]);
            
            $user->password = bcrypt($request->password);
            $message = 'Password berhasil diubah!';
        } else {
            // Update profile fields
            $validated = $request->validated();
            $user->fill($validated);

            if ($user->isDirty('email')) {
                $user->email_verified_at = null;
            }

            // Handle address based on role
            if (isset($validated['address'])) {
                $profileData = $user->getProfileData();
                if ($profileData) {
                    $profileData->address = $validated['address'];
                    $profileData->save();
                }
            }

            // Handle student-specific fields
            if ($user->hasRole('student') || $user->hasRole('Student')) {
                $profileData = $user->getProfileData();
                if ($profileData) {
                    $studentFields = [
                        'no_absen', 'no_card', 'nisn', 'nik', 'gender', 
                        'birth_place', 'birth_date', 'religion',
                        'jurusan_utama', 'jurusan_cadangan', 'academic_year'
                    ];
                    
                    foreach ($studentFields as $field) {
                        if (isset($validated[$field])) {
                            $profileData->$field = $validated[$field];
                        }
                    }
                    $profileData->save();
                }
            }

            // Handle avatar upload
            if ($request->hasFile('avatar')) {
                $avatar = $request->file('avatar');
                $path = $avatar->store('avatars', 'public');
                $user->photo_path = $path;
            }

            // Determine what was changed
            $changes = [];
            if ($user->isDirty('name')) $changes[] = 'nama';
            if ($user->isDirty('email')) $changes[] = 'email';
            if ($user->isDirty('phone')) $changes[] = 'nomor HP';
            if ($user->isDirty('photo_path')) $changes[] = 'foto';
            
            // Check if address was changed in profile data
            if (isset($validated['address'])) {
                $profileData = $user->getProfileData();
                if ($profileData && $profileData->isDirty('address')) {
                    $changes[] = 'alamat';
                }
            }

            if (empty($changes)) {
                $message = 'Tidak ada perubahan data.';
            } elseif (count($changes) === 1) {
                $message = ucfirst($changes[0]) . ' berhasil diperbarui!';
            } else {
                $message = 'Profile berhasil diperbarui!';
            }
        }

        $user->save();

        return Redirect::route('profile.show')->with('success', $message);
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}

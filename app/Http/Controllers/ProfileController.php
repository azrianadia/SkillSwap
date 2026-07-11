<?php

namespace App\Http\Controllers;

use App\Models\Skill;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function show(Request $request): View
    {
        $user = $request->user()->load(['offeredSkills', 'soughtSkills', 'reviewsReceived']);
        
        return view('profile.show', [
            'user' => $user,
        ]);
    }

    public function edit(Request $request): View
    {
        $user = $request->user()->load(['offeredSkills', 'soughtSkills']);
        $skills = Skill::orderBy('category')->orderBy('skill_name')->get();
        
        return view('profile.edit', [
            'user' => $user,
            'skills' => $skills,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        
        \Log::info('Profile Update Request:', [
            'has_file' => $request->hasFile('avatar'),
            'delete_avatar' => $request->boolean('delete_avatar'),
            'all_data' => $request->all(),
        ]);

        // Fill validated data except avatar and delete_avatar (handled separately)
        $user->fill($request->safe()->except(['avatar', 'delete_avatar']));

        // Handle avatar deletion (before upload check so new upload takes precedence)
        if ($request->boolean('delete_avatar')) {
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }
            $user->avatar = null;
            \Log::info('Avatar deleted for user: ' . $user->id);
        }

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            // Delete old avatar if exists
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }
            
            // Store new avatar
            $path = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $path;
            \Log::info('New avatar stored at: ' . $path);
        }

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
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

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ProfileController extends Controller
{
    public function edit()
    {
        try {
            return view('profile.edit', ['user' => Auth::user()]);
        } catch (\Exception $e) {
            Log::error('Profile Edit Page Error: ' . $e->getMessage());
            return back()->with('error', 'Unable to load profile page.');
        }
    }

    public function update(Request $request)
    {
        try {
            $user = Auth::user();

            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email,' . $user->id,
                'phone' => 'nullable|string|max:20',
                'avatar' => 'nullable|image|max:2048',
            ]);

            $user->name = $validated['name'];
            $user->email = $validated['email'];
            $user->phone = $validated['phone'] ?? null;

            // Handle avatar upload
            if ($request->hasFile('avatar')) {
                // Delete old avatar if exists
                if ($user->avatar) {
                    Storage::disk('public')->delete($user->avatar);
                }
                
                $avatarPath = $request->file('avatar')->store('avatars', 'public');
                $user->avatar = $avatarPath;
            }

            $user->save();

            return redirect()->route('profile.edit')->with('success', 'Profile updated successfully!');
        } catch (\Exception $e) {
            Log::error('Profile Update Error: ' . $e->getMessage());
            return back()->with('error', 'Unable to update profile.');
        }
    }
}

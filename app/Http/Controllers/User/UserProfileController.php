<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserProfileController extends Controller
{
     public function edit()
    {
        $user = Auth::user();
        return view('users.profile.edit', compact('user'));
    }

    // Memproses Update Profile
    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone_number' => 'nullable|string|max:15',
            'address' => 'nullable|string',
            'photo' => 'nullable|image|max:2048', // Max 2MB
            'password' => 'nullable|min:8|confirmed', // Konfirmasi password
        ]);

        $data = $request->only(['name', 'username', 'email', 'phone_number', 'address']);

        // Handle Photo Upload
        if ($request->hasFile('photo')) {
            // Hapus foto lama jika ada
            if ($user->photo_path && Storage::disk('public')->exists($user->photo_path)) {
                Storage::disk('public')->delete($user->photo_path);
            }
            $data['photo_path'] = $request->file('photo')->store('users/photos', 'public');
        }

        // Handle Password Change
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        // Update User (harus pakai instance model user yang sedang login)
        /** @var \App\Models\User $user */
        $user->update($data);

        return redirect()->route('user.profile.edit')->with('success', 'Profil berhasil diperbarui!');
    }
}

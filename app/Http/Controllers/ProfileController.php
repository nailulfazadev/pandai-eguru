<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    /**
     * Show the profile edit form.
     */
    public function edit()
    {
        $user = Auth::user();
        return view('pengaturan', compact('user'));
    }

    /**
     * Update the user's profile.
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20|unique:users,phone,' . $user->id,
            'nip' => 'nullable|string|max:50',
            'school_name' => 'nullable|string|max:255',
            'principal_name' => 'nullable|string|max:255',
            'principal_nip' => 'nullable|string|max:50',
            'current_password' => 'nullable|string',
            'new_password' => 'nullable|string|min:6|confirmed',
        ]);

        // Handle password reset
        if (!empty($data['new_password'])) {
            if (empty($data['current_password'])) {
                return back()->withErrors(['current_password' => 'Password saat ini harus diisi jika ingin mengubah password.']);
            }
            if (!\Illuminate\Support\Facades\Hash::check($data['current_password'], $user->password)) {
                return back()->withErrors(['current_password' => 'Password saat ini tidak sesuai.']);
            }
            $user->password = \Illuminate\Support\Facades\Hash::make($data['new_password']);
        }

        // Update other fields
        $user->name = $data['name'];
        $user->email = $data['email'];
        if (isset($data['phone'])) {
            $user->phone = $data['phone'];
        }
        $user->nip = $data['nip'];
        $user->school_name = $data['school_name'];
        $user->principal_name = $data['principal_name'];
        $user->principal_nip = $data['principal_nip'];
        
        $user->save();

        return redirect()->route('profile.edit')->with('success', 'Profil berhasil diperbarui!');
    }
}

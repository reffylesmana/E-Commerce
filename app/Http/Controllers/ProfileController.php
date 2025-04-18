<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
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
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();

        // Update data profil
        $user->fill($request->validated());

        // Jika email diubah, set ulang verifikasi email
        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        // Handle upload gambar
        if ($request->hasFile('image')) {
            // Hapus gambar lama
            if ($user->image) {
                Storage::disk('public')->delete('images/' . $user->image);
            }
            
            // Upload gambar baru
            $imageName = time() . '.' . $request->image->extension();
            $request->image->storeAs('images', $imageName, 'public');
            $user->image = $imageName;
        }

        // Simpan perubahan
        $user->save();

        // Redirect berbeda berdasarkan role
        return $user->role === 'seller' ?
             Redirect::route('profile.edit')->with('status', 'profile-updated')
            : Redirect::route('account.index')->with('success', 'Profil berhasil diperbarui!');
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

        // Hapus gambar profil jika ada
        if ($user->image && Storage::exists('public/images/' . $user->image)) {
            Storage::delete('public/images/' . $user->image);
        }

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
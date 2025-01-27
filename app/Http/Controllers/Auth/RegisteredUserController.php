<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Menampilkan halaman pendaftaran.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Menangani permintaan pendaftaran yang masuk.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:users,name'],
            'username' => ['required', 'string', 'max:255', 'unique:users,username'],  // Validasi untuk username
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', 'min:8', 'max:20'],
            'role' => ['required', 'in:seller,buyer'],
        ]);

        // Membuat pengguna baru
        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,  // Menambahkan username
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        // Redirect dengan pesan sukses dari SweetAlert
        return redirect()->route('register')->with('success', 'Pendaftaran Berhasil! Silakan login.');
    }
}

<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Tampilkan tampilan login.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Tangani permintaan autentikasi.
     */
    public function store(Request $request)
    {
        // Validasi untuk memastikan username dan password diisi
        $request->validate([
            'username' => 'required|string', // Validasi untuk username
            'password' => 'required|string', // Validasi untuk password
        ]);
    
        if (Auth::attempt(['username' => $request->username, 'password' => $request->password], $request->remember)) {
            $request->session()->regenerate();
    
            $role = Auth::user()->role; 
    
            // Redirect berdasarkan peran pengguna
            if ($role === 'seller') {
                return redirect()->route('seller.dashboard'); // Mengarahkan ke halaman seller dashboard
            } elseif ($role === 'buyer') {
                return redirect('/'); // Arahkan ke halaman utama (buyer)
            }
    
            return redirect()->route('home'); // Jika tidak ada role yang ditemukan
        }
    
        return back()->withErrors([
            'username' => 'Username tidak ditemukan.',
            'password' => 'Password salah.',
        ]);
    }
    

    /**
     * Menghancurkan sesi autentikasi.
     */
    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}

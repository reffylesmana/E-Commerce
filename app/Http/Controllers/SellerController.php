<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class SellerController extends Controller
{
    /**
     * Tampilkan halaman dashboard untuk seller.
     */
    public function index()
    {
        // Cek apakah pengguna terautentikasi dan memiliki peran 'seller'
        if (!Auth::check() || Auth::user()->role !== 'seller') {
            abort(403, 'Unauthorized action.');
        }

        // Ambil store pengguna
        $store = Auth::user()->store;

        // Pastikan store ada, jika tidak, set $store ke null
        if (!$store) {
            $store = null; // Atau Anda bisa mengatur nilai default lainnya
        }

        // Tampilkan view dengan data store
        return view('seller.dashboard', compact('store'));
    }

    /**
     * Tampilkan halaman store untuk seller.
     */
    public function store()
    {
        // Cek apakah pengguna terautentikasi dan memiliki peran 'seller'
        if (!Auth::check() || Auth::user()->role !== 'seller') {
            abort(403, 'Unauthorized action.');
        }

        // Ambil store pengguna
        $store = Auth::user()->store;

        // Pastikan store ada, jika tidak, set $store ke null
        if (!$store) {
            $store = null; // Atau Anda bisa mengatur nilai default lainnya
        }

        // Tampilkan view dengan data store
        return view('seller.store', compact('store'));
    }
}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BuyerController extends Controller
{
    /**
     * Tampilkan halaman dashboard untuk buyer.
     */
    public function dashboard()
    {
        return view('buyer.dashboard');
    }
}

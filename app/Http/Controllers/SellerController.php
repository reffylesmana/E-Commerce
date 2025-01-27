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
        if (!view()->exists('seller.dashboard')) {
            return response()->json(['error' => 'View not found!'], 404);
        }
    
        if (Auth::user()->role !== 'seller') {
            abort(403, 'Unauthorized action.');
        }

        return view('seller.dashboard');
    }
    
}

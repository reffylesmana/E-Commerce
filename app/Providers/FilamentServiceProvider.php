<?php

namespace App\Providers;

use Filament\Facades\Filament;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class FilamentServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Batasi akses ke Filament hanya untuk pengguna dengan role 'admin'
        Filament::serving(function () {
            if (Auth::check() && Auth::user()->role !== 'admin') {
                abort(403, 'Unauthorized action.');
            }
        });
    }
}

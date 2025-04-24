<?php

// app/Http/Middleware/SetLocale.php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SetLocale
{
    public function handle(Request $request, Closure $next)
    {
        $locale = session('applocale', config('app.fallback_locale'));
        app()->setLocale($locale);
        return $next($request);
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticatedWithRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $user = Auth::user();

            // Redirect based on user role
            if ($user->role === 'seller') {
                return redirect()->route('seller.dashboard');
            } elseif ($user->role === 'buyer') {
                return redirect()->route('/');
            }
        }

        return $next($request);
    }
}

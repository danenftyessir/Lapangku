<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckDevAuth
{
    /**
     * handle incoming request untuk cek dev authentication
     *
     * middleware ini cek apakah user sudah bypass login via dev-login
     */
    public function handle(Request $request, Closure $next): Response
    {
        // cek apakah user sudah login via dev mode
        if (!session('authenticated')) {
            return redirect('/dev-login')->with('error', 'silakan login terlebih dahulu');
        }

        // set user data ke request agar bisa diakses di controller
        $request->merge(['current_user' => session('user')]);

        return $next($request);
    }
}
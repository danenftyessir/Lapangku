<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckUserType
{
    /**
     * handle incoming request
     */
    public function handle(Request $request, Closure $next, string $userType): Response
    {
        // cek apakah user sudah login
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'silakan login terlebih dahulu');
        }

        // cek tipe user
        if (auth()->user()->user_type !== $userType) {
            // redirect ke dashboard sesuai role mereka
            $redirectRoute = match(auth()->user()->user_type) {
                'student' => 'student.dashboard',
                'institution' => 'institution.dashboard',
                default => 'home',
            };

            return redirect()->route($redirectRoute)
                ->with('error', 'anda tidak memiliki akses ke halaman ini');
        }

        return $next($request);
    }
}
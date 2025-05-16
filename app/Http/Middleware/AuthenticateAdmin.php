<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::guard('admin')->check()) {
            // Jika menggunakan API, kembalikan response JSON
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthenticated.'], 401);
            }
            // Jika web, redirect ke halaman login admin
            return redirect()->route('admin.login.form'); // Anda perlu membuat rute ini
        }
        return $next($request);
    }
}
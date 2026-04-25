<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     * Cek apakah user yang login memiliki app_role yang sesuai.
     *
     * @param  string  $role  Role yang dibutuhkan (e.g. 'admin')
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // Cek guard admin
        if (! Auth::guard('admin')->check()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthenticated.'], 401);
            }

            return redirect()->route('admin.login.form');
        }

        // Cek app_role
        if (Auth::guard('admin')->user()->app_role !== $role) {
            Auth::guard('admin')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            if ($request->expectsJson()) {
                return response()->json(['message' => 'Anda tidak memiliki akses ke halaman ini.'], 403);
            }

            return redirect()->route('admin.login.form')
                ->withErrors(['role' => 'Anda tidak memiliki akses ke halaman ini.']);
        }

        return $next($request);
    }
}

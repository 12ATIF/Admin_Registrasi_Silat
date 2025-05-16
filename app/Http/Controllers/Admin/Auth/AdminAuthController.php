<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AdminLoginRequest;
use App\Traits\LogsActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AdminAuthController extends Controller
{
    use LogsActivity;
    
    // Menampilkan form login
    public function showLoginForm(): View
    {
        return view('admin.auth.login');
    }

    public function login(AdminLoginRequest $request)
    {
        $credentials = $request->validated();

        if (Auth::guard('admin')->attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            
            // Catat log login
            $this->logActivity('login', Auth::guard('admin')->user());
            
            // Jika API
            if ($request->expectsJson()) {
                $admin = Auth::guard('admin')->user();
                $token = $admin->createToken('admin-api-token')->plainTextToken;
                return response()->json(['token' => $token, 'admin' => $admin]);
            }

            // Jika Web
            return redirect()->intended(route('admin.dashboard'));
        }

        // Jika API
        if ($request->expectsJson()) {
            return response()->json(['message' => 'Email atau password salah.'], 401);
        }

        // Jika Web
        return back()->withErrors([
            'email' => 'Email atau password yang diberikan tidak cocok dengan catatan kami.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        // Catat log logout
        $this->logActivity('logout', Auth::guard('admin')->user());
        
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Jika API
        if ($request->expectsJson()) {
            return response()->json(['message' => 'Successfully logged out']);
        }

        // Jika Web
        return redirect()->route('admin.login.form');
    }
}
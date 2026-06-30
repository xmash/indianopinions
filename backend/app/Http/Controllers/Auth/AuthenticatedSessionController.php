<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Concerns\ResolvesStaffLogin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthenticatedSessionController extends Controller
{
    use ResolvesStaffLogin;
    public function create()
    {
        return view('auth.login');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'login' => 'required|string|max:255',
            'password' => 'required',
        ]);

        $user = $this->resolveStaffUser($validated['login']);

        if (! $user || ! Hash::check($validated['password'], $user->password)) {
            return back()
                ->withErrors(['login' => 'The provided credentials do not match our records.'])
                ->onlyInput('login');
        }

        if (! $user->is_active) {
            return back()
                ->withErrors(['login' => 'This account has been deactivated.'])
                ->onlyInput('login');
        }

        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        return redirect(admin_home());
    }

    public function destroy(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect(config('app.frontend_url', '/'));
    }
}

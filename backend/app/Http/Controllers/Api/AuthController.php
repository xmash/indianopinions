<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Concerns\ResolvesStaffLogin;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    use ResolvesStaffLogin;

    public function store(Request $request): JsonResponse
    {
        if (Auth::check()) {
            return response()->json([
                'redirect' => admin_url(),
            ]);
        }

        $validated = $request->validate([
            'login' => 'required|string|max:255',
            'password' => 'required|string',
        ]);

        $user = $this->resolveStaffUser($validated['login']);

        if (! $user || ! Hash::check($validated['password'], $user->password)) {
            throw ValidationException::withMessages([
                'login' => ['The provided credentials do not match our records.'],
            ]);
        }

        if (! $user->is_active) {
            throw ValidationException::withMessages([
                'login' => ['This account has been deactivated.'],
            ]);
        }

        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        return response()->json([
            'redirect' => admin_url(),
        ]);
    }
}

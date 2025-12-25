<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Services\ActivityLogService;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()->symbols()],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        return response()->json(['message' => 'Register berhasil', 'user' => $user], 201);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        try {
            if (! $token = auth('api')->attempt($credentials)) {
                return response()->json(['message' => 'Kredensial salah'], 401);
            }
        } catch (JWTException $e) {
            return response()->json(['message' => 'Tidak dapat membuat token'], 500);
        }

        $user = auth('api')->user();
        if (method_exists($user, 'isSuperAdmin') && $user->isSuperAdmin()) {
            ActivityLogService::logAdminLogin($user, 'Super admin login');
        }

        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60,
            'user' => $user,
        ]);
    }

    public function logout()
    {
        auth('api')->logout();

        return response()->json(['message' => 'Logout berhasil']);
    }
}

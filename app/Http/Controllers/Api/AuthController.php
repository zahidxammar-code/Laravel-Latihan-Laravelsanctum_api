<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
        ]);

        // cast 'hashed' di model User otomatis meng-hash password
        $user = User::create($data);

        return response()->json([
            'message' => 'Registrasi berhasil, silakan login.',
            'data' => $user->only('id', 'name', 'email'),
        ], 201);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (!Auth::attempt($credentials)) {
            return response()->json(['message' => 'Email atau password salah.'], 401);
        }

        $user = User::where('email', $credentials['email'])->first();
        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'message' => 'Login berhasil.',
            'token' => $token,
        ]);
    }

    public function profil(Request $request)
    {
        return response()->json($request->user()->only('id', 'name', 'email'));
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logout berhasil.']);
    }
}
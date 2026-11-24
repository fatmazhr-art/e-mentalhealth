<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Traits\ApiResponse;
use App\Models\Pengguna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    use ApiResponse;

    public function login(Request $request)
    {
        $request->validate([
            'email'      => 'required|email',
            'kata_sandi' => 'required|string',
        ]);

        $pengguna = Pengguna::where('email', $request->email)->first();

        if (! $pengguna || ! Hash::check($request->kata_sandi, $pengguna->kata_sandi)) {
            return $this->error('Email atau kata sandi salah', null, 401);
        }

        $token = $pengguna->createToken('api-token')->plainTextToken;

        return $this->success([
            'token'      => $token,
            'token_type' => 'Bearer',
            'pengguna'   => $pengguna,
        ], 'Login berhasil');
    }

    public function me(Request $request)
    {
        return $this->success($request->user());
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return $this->success(null, 'Logout berhasil');
    }
}

<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Interfaces\AuthRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    private AuthRepositoryInterface $authRepository;

    public function __construct(AuthRepositoryInterface $authRepository)
    {
        $this->authRepository = $authRepository;
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $token = $this->authRepository->login($request->only('email', 'password'));

        if (!$token) {
            return response()->json([
                'message' => 'Email atau password salah',
                'data' => null
            ], 401);
        }

        return response()->json([
            'message' => 'Login berhasil',
            'data' => [
                'access_token' => $token,
            ]
        ]);
    }
}
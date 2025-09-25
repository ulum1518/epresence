<?php

namespace App\Repositories;

use App\Interfaces\AuthRepositoryInterface;
use Illuminate\Support\Facades\Auth;

class AuthRepository implements AuthRepositoryInterface
{
    public function login(array $credentials): ?string
    {
        $token = Auth::guard('api')->attempt($credentials);

        if (!$token) {
            return null;
        }

        return $token;
    }
}
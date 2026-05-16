<?php

namespace App\Http\Responses;

use App\Http\Resources\Auth\AuthenticatedUserResource;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

class LoginResponse implements LoginResponseContract
{
    public function toResponse($request)
    {
        $user = $request->user();

        $user->tokens()->delete();

        return response()->json([
            'access_token' => $user->createToken('auth_token')->plainTextToken,
            'token_type' => 'bearer',
            'user' => (new AuthenticatedUserResource($user))->resolve(),
        ]);
    }
}

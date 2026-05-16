<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\Auth\AuthenticatedUserResource;
use App\Services\Auth\AuthService;
use App\Models\Guru;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(
        private readonly AuthService $authService
    ) {}

    public function login(LoginRequest $request)
    {
        $auth = $this->authService->login($request->validated());

        return response()->json($this->authResponse($auth));
    }

    public function register(RegisterRequest $request)
    {
        $auth = $this->authService->registerAdmin($request->validated());

        return response()->json($this->authResponse($auth), 201);
    }

    public function me(Request $request)
    {
        $user = $request->user();

        if (!$user) {

            return response()->json([
                'detail' => 'Unauthorized'
            ], 401);
        }

        if ($user instanceof Guru) {
            $user->loadMissing([
                'kelasYangDiwalikan',
                'mapelYangDiajar',
            ]);
        }

        return response()->json(
            (new AuthenticatedUserResource($user))->resolve()
        );
    }

    public function logout(Request $request)
    {
        $user = $request->user();

        if ($user) {
            $this->authService->logout($user);
        }

        return response()->json([
            'message' => 'Logout success'
        ]);
    }

    /**
     * @param  array{access_token: string, token_type: string, user: mixed}  $auth
     * @return array{access_token: string, token_type: string, user: array<string, mixed>}
     */
    private function authResponse(array $auth): array
    {
        return [
            'access_token' => $auth['access_token'],
            'token_type' => $auth['token_type'],
            'user' => (new AuthenticatedUserResource($auth['user']))->resolve(),
        ];
    }
}

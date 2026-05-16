<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\IndexUserRequest;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Resources\User\UserResource;
use App\Models\User;
use App\Services\User\UserService;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    public function __construct(private readonly UserService $service) {}

    public function index(IndexUserRequest $request): JsonResponse
    {
        return response()->json(UserResource::collection($this->service->getAll($request->validated()))->resolve());
    }

    public function store(StoreUserRequest $request): JsonResponse
    {
        return response()->json((new UserResource($this->service->create($request->validated())))->resolve(), 201);
    }

    public function show(User $user): JsonResponse
    {
        return response()->json((new UserResource($user))->resolve());
    }

    public function update(UpdateUserRequest $request, User $user): JsonResponse
    {
        return response()->json((new UserResource($this->service->update($user, $request->validated())))->resolve());
    }

    public function destroy(User $user): JsonResponse
    {
        $this->service->delete($user);

        return response()->json(['message' => 'User deleted successfully']);
    }
}

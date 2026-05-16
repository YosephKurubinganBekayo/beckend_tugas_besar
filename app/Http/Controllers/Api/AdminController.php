<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\IndexAdminRequest;
use App\Http\Requests\Admin\StoreAdminRequest;
use App\Http\Requests\Admin\UpdateAdminRequest;
use App\Http\Resources\Admin\AdminResource;
use App\Models\Admin;
use App\Services\Admin\AdminService;
use Illuminate\Http\JsonResponse;

class AdminController extends Controller
{
    public function __construct(private readonly AdminService $service) {}

    public function index(IndexAdminRequest $request): JsonResponse
    {
        return response()->json(AdminResource::collection($this->service->getAll($request->validated()))->resolve());
    }

    public function store(StoreAdminRequest $request): JsonResponse
    {
        return response()->json((new AdminResource($this->service->create($request->validated())))->resolve(), 201);
    }

    public function show(Admin $admin): JsonResponse
    {
        return response()->json((new AdminResource($admin))->resolve());
    }

    public function update(UpdateAdminRequest $request, Admin $admin): JsonResponse
    {
        return response()->json((new AdminResource($this->service->update($admin, $request->validated())))->resolve());
    }

    public function destroy(Admin $admin): JsonResponse
    {
        $this->service->delete($admin);

        return response()->json(['message' => 'Admin deleted successfully']);
    }
}

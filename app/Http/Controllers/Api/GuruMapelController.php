<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\GuruMapel\IndexGuruMapelRequest;
use App\Http\Requests\GuruMapel\StoreGuruMapelRequest;
use App\Http\Requests\GuruMapel\UpdateGuruMapelRequest;
use App\Http\Resources\GuruMapel\GuruMapelResource;
use App\Models\GuruMapel;
use App\Services\GuruMapel\GuruMapelService;
use Illuminate\Http\JsonResponse;

class GuruMapelController extends Controller
{
    public function __construct(private readonly GuruMapelService $service) {}

    public function index(IndexGuruMapelRequest $request): JsonResponse
    {
        return response()->json(GuruMapelResource::collection($this->service->getAll($request->validated()))->resolve());
    }

    public function store(StoreGuruMapelRequest $request): JsonResponse
    {
        return response()->json((new GuruMapelResource($this->service->create($request->validated())))->resolve(), 201);
    }

    public function show(GuruMapel $guruMapel): JsonResponse
    {
        $guruMapel->load(['guru', 'mapel']);

        return response()->json((new GuruMapelResource($guruMapel))->resolve());
    }

    public function update(UpdateGuruMapelRequest $request, GuruMapel $guruMapel): JsonResponse
    {
        return response()->json((new GuruMapelResource($this->service->update($guruMapel, $request->validated())))->resolve());
    }

    public function destroy(GuruMapel $guruMapel): JsonResponse
    {
        $this->service->delete($guruMapel);

        return response()->json(['message' => 'Guru mapel deleted successfully']);
    }
}

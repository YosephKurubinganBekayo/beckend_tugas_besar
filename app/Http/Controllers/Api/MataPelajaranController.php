<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\MataPelajaran\IndexMataPelajaranRequest;
use App\Http\Requests\MataPelajaran\StoreMataPelajaranRequest;
use App\Http\Requests\MataPelajaran\UpdateMataPelajaranRequest;
use App\Http\Resources\MataPelajaran\MataPelajaranResource;
use App\Models\MataPelajaran;
use App\Services\MataPelajaran\MataPelajaranService;
use Illuminate\Http\JsonResponse;

class MataPelajaranController extends Controller
{
    public function __construct(private readonly MataPelajaranService $service) {}

    public function index(IndexMataPelajaranRequest $request): JsonResponse
    {
        return response()->json(MataPelajaranResource::collection($this->service->getAll($request->validated()))->resolve());
    }

    public function store(StoreMataPelajaranRequest $request): JsonResponse
    {
        return response()->json((new MataPelajaranResource($this->service->create($request->validated())))->resolve(), 201);
    }

    public function show(MataPelajaran $mataPelajaran): JsonResponse
    {
        $mataPelajaran->load('guru')->loadCount(['guru', 'jadwal']);

        return response()->json((new MataPelajaranResource($mataPelajaran))->resolve());
    }

    public function update(UpdateMataPelajaranRequest $request, MataPelajaran $mataPelajaran): JsonResponse
    {
        return response()->json((new MataPelajaranResource($this->service->update($mataPelajaran, $request->validated())))->resolve());
    }

    public function destroy(MataPelajaran $mataPelajaran): JsonResponse
    {
        $this->service->delete($mataPelajaran);

        return response()->json(['message' => 'Mata pelajaran deleted successfully']);
    }
}

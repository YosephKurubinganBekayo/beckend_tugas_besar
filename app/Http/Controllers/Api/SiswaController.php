<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Siswa\IndexSiswaRequest;
use App\Http\Requests\Siswa\StoreSiswaRequest;
use App\Http\Requests\Siswa\UpdateSiswaRequest;
use App\Http\Resources\Siswa\SiswaResource;
use App\Models\Siswa;
use App\Services\Siswa\SiswaService;
use Illuminate\Http\JsonResponse;

class SiswaController extends Controller
{
    public function __construct(private readonly SiswaService $service) {}

    public function index(IndexSiswaRequest $request): JsonResponse
    {
        return response()->json(SiswaResource::collection($this->service->getAll($request->validated()))->resolve());
    }

    public function store(StoreSiswaRequest $request): JsonResponse
    {
        return response()->json((new SiswaResource($this->service->create($request->validated())))->resolve(), 201);
    }

    public function show(Siswa $siswa): JsonResponse
    {
        $siswa->load('kelas')->loadCount('embeddings');

        return response()->json((new SiswaResource($siswa))->resolve());
    }

    public function update(UpdateSiswaRequest $request, Siswa $siswa): JsonResponse
    {
        return response()->json((new SiswaResource($this->service->update($siswa, $request->validated())))->resolve());
    }

    public function byKelas(int $kelasId): JsonResponse
    {
        return response()->json(SiswaResource::collection($this->service->getAll(['kelas_id' => $kelasId, 'limit' => 100]))->resolve());
    }

    public function destroy(Siswa $siswa): JsonResponse
    {
        $this->service->delete($siswa);

        return response()->json(['message' => 'Siswa deleted successfully']);
    }
}

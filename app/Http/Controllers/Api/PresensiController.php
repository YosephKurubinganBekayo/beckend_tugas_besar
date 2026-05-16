<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Presensi\IndexPresensiRequest;
use App\Http\Requests\Presensi\StorePresensiRequest;
use App\Http\Requests\Presensi\UpdatePresensiRequest;
use App\Http\Resources\Presensi\PresensiResource;
use App\Models\Presensi;
use App\Services\Presensi\PresensiService;
use Illuminate\Http\JsonResponse;

class PresensiController extends Controller
{
    public function __construct(
        private readonly PresensiService $presensiService
    ) {}

    public function index(IndexPresensiRequest $request): JsonResponse
    {
        $presensi = $this->presensiService->getAll($request->validated());

        return response()->json(
            PresensiResource::collection($presensi)->resolve()
        );
    }

    public function store(StorePresensiRequest $request): JsonResponse
    {
        $presensi = $this->presensiService->create($request->validated());

        return response()->json(
            (new PresensiResource($presensi))->resolve(),
            201
        );
    }

    public function show(Presensi $presensi): JsonResponse
    {
        $presensi->load([
            'siswa',
            'guru',
            'jadwal.kelas',
            'jadwal.mapel',
        ]);

        return response()->json(
            (new PresensiResource($presensi))->resolve()
        );
    }

    public function update(UpdatePresensiRequest $request, Presensi $presensi): JsonResponse
    {
        $presensi = $this->presensiService->update($presensi, $request->validated());

        return response()->json(
            (new PresensiResource($presensi))->resolve()
        );
    }

    public function byTanggal(string $tanggal): JsonResponse
    {
        $presensi = $this->presensiService->getAll(['tanggal' => $tanggal, 'limit' => 500]);

        return response()->json(
            PresensiResource::collection($presensi)->resolve()
        );
    }

    public function destroy(Presensi $presensi): JsonResponse
    {
        $this->presensiService->delete($presensi);

        return response()->json([
            'message' => 'Presensi deleted successfully',
        ]);
    }
}

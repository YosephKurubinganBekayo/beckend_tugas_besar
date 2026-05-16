<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Kelas\IndexKelasRequest;
use App\Http\Requests\Kelas\StoreKelasRequest;
use App\Http\Requests\Kelas\UpdateKelasRequest;
use App\Http\Resources\Kelas\KelasResource;
use App\Models\Kelas;
use App\Services\Kelas\KelasService;
use Illuminate\Http\JsonResponse;

class KelasController extends Controller
{
    public function __construct(
        private readonly KelasService $kelasService
    ) {}

    public function index(IndexKelasRequest $request): JsonResponse
    {
        $kelas = $this->kelasService->getAll($request->validated());

        return response()->json(
            KelasResource::collection($kelas)->resolve()
        );
    }

    public function store(StoreKelasRequest $request): JsonResponse
    {
        $kelas = $this->kelasService->create($request->validated());

        return response()->json(
            (new KelasResource($kelas))->resolve(),
            201
        );
    }

    public function show(Kelas $kelas): JsonResponse
    {
        $kelas->load('waliKelas')
            ->loadCount([
                'siswa',
                'jadwal',
            ]);

        return response()->json(
            (new KelasResource($kelas))->resolve()
        );
    }

    public function update(UpdateKelasRequest $request, Kelas $kelas): JsonResponse
    {
        $kelas = $this->kelasService->update($kelas, $request->validated());

        return response()->json(
            (new KelasResource($kelas))->resolve()
        );
    }

    public function destroy(Kelas $kelas): JsonResponse
    {
        $this->kelasService->delete($kelas);

        return response()->json([
            'message' => 'Kelas deleted successfully',
        ]);
    }
}

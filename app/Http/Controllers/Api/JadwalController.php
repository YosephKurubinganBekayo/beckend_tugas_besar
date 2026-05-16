<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Jadwal\BatchStoreJadwalRequest;
use App\Http\Requests\Jadwal\IndexJadwalRequest;
use App\Http\Requests\Jadwal\StoreJadwalRequest;
use App\Http\Requests\Jadwal\UpdateJadwalRequest;
use App\Http\Resources\Jadwal\JadwalResource;
use App\Models\Jadwal;
use App\Services\Jadwal\JadwalService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class JadwalController extends Controller
{
    public function __construct(private readonly JadwalService $service) {}

    public function index(IndexJadwalRequest $request): JsonResponse
    {
        return response()->json(JadwalResource::collection($this->service->getAll($request->validated()))->resolve());
    }

    public function store(StoreJadwalRequest $request): JsonResponse
    {
        return response()->json((new JadwalResource($this->service->create($request->validated())))->resolve(), 201);
    }

    public function batchStore(BatchStoreJadwalRequest $request): JsonResponse
    {
        return response()->json(JadwalResource::collection($this->service->batchCreate($request->validated()['items']))->resolve(), 201);
    }

    public function show(Jadwal $jadwal): JsonResponse
    {
        $jadwal->load(['kelas', 'mapel', 'guru']);

        return response()->json((new JadwalResource($jadwal))->resolve());
    }

    public function update(UpdateJadwalRequest $request, Jadwal $jadwal): JsonResponse
    {
        return response()->json((new JadwalResource($this->service->update($jadwal, $request->validated())))->resolve());
    }

    public function byGuru(int $guruId): JsonResponse
    {
        return response()->json(JadwalResource::collection($this->service->getAll(['guru_id' => $guruId, 'limit' => 100]))->resolve());
    }

    public function byKelas(int $kelasId): JsonResponse
    {
        return response()->json(JadwalResource::collection($this->service->getAll(['kelas_id' => $kelasId, 'limit' => 100]))->resolve());
    }

    public function destroy(Jadwal $jadwal): JsonResponse
    {
        $this->service->delete($jadwal);

        return response()->json(['message' => 'Jadwal deleted successfully']);
    }
}

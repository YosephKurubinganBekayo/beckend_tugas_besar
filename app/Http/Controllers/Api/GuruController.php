<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Guru\IndexGuruRequest;
use App\Http\Requests\Guru\StoreGuruRequest;
use App\Http\Requests\Guru\UpdateGuruRequest;
use App\Http\Resources\Guru\GuruResource;
use App\Models\Guru;
use App\Services\Guru\GuruService;
use Illuminate\Http\JsonResponse;

class GuruController extends Controller
{
    public function __construct(private readonly GuruService $service) {}

    public function index(IndexGuruRequest $request): JsonResponse
    {
        return response()->json(GuruResource::collection($this->service->getAll($request->validated()))->resolve());
    }

    public function store(StoreGuruRequest $request): JsonResponse
    {
        return response()->json((new GuruResource($this->service->create($request->validated())))->resolve(), 201);
    }

    public function show(Guru $guru): JsonResponse
    {
        $guru->load(['kelasYangDiwalikan', 'mataPelajaran'])->loadCount(['kelasYangDiwalikan', 'mapelYangDiajar']);

        return response()->json((new GuruResource($guru))->resolve());
    }

    public function update(UpdateGuruRequest $request, Guru $guru): JsonResponse
    {
        return response()->json((new GuruResource($this->service->update($guru, $request->validated())))->resolve());
    }

    public function byMapel($mapelId): JsonResponse
    {
        $gurus = Guru::whereHas('mataPelajaran', function ($query) use ($mapelId) {
            $query->where('mata_pelajaran.id', $mapelId);
        })->get();

        return response()->json(GuruResource::collection($gurus)->resolve());
    }

    public function destroy(Guru $guru): JsonResponse
    {
        $this->service->delete($guru);

        return response()->json(['message' => 'Guru deleted successfully']);
    }
}

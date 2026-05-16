<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Embedding\IndexEmbeddingRequest;
use App\Http\Requests\Embedding\StoreEmbeddingRequest;
use App\Http\Requests\Embedding\UpdateEmbeddingRequest;
use App\Http\Resources\Embedding\EmbeddingResource;
use App\Models\Embedding;
use App\Services\Embedding\EmbeddingService;
use Illuminate\Http\JsonResponse;

class EmbeddingController extends Controller
{
    public function __construct(private readonly EmbeddingService $service) {}

    public function index(IndexEmbeddingRequest $request): JsonResponse
    {
        return response()->json(EmbeddingResource::collection($this->service->getAll($request->validated()))->resolve());
    }

    public function store(StoreEmbeddingRequest $request): JsonResponse
    {
        return response()->json((new EmbeddingResource($this->service->create($request->validated())))->resolve(), 201);
    }

    public function show(Embedding $embedding): JsonResponse
    {
        $embedding->load('siswa');

        return response()->json((new EmbeddingResource($embedding))->resolve());
    }

    public function update(UpdateEmbeddingRequest $request, Embedding $embedding): JsonResponse
    {
        return response()->json((new EmbeddingResource($this->service->update($embedding, $request->validated())))->resolve());
    }

    public function destroy(Embedding $embedding): JsonResponse
    {
        $this->service->delete($embedding);

        return response()->json(['message' => 'Embedding deleted successfully']);
    }
}

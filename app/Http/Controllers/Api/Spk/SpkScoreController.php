<?php

namespace App\Http\Controllers\Api\Spk;

use App\Http\Controllers\Controller;
use App\Http\Requests\Spk\IndexSpkScoreRequest;
use App\Http\Requests\Spk\StoreSpkScoreRequest;
use App\Http\Requests\Spk\UpdateSpkScoreRequest;
use App\Http\Resources\Spk\SpkScoreResource;
use App\Models\SpkScore;
use Illuminate\Http\JsonResponse;

class SpkScoreController extends Controller
{
    public function index(IndexSpkScoreRequest $request): JsonResponse
    {
        $scores = SpkScore::query()
            ->with('criterion')
            ->when($request->validated('target_type'), fn ($query, $type) => $query->where('target_type', $type))
            ->when($request->validated('target_id'), fn ($query, $id) => $query->where('target_id', $id))
            ->when($request->has('periode'), fn ($query) => $query->where('periode', $request->input('periode')))
            ->latest('id')
            ->limit(100)
            ->get();

        return response()->json(SpkScoreResource::collection($scores)->resolve());
    }

    public function store(StoreSpkScoreRequest $request): JsonResponse
    {
        $score = SpkScore::query()->create($request->validated())->load('criterion');

        return response()->json((new SpkScoreResource($score))->resolve(), 201);
    }

    public function show(SpkScore $score): JsonResponse
    {
        return response()->json((new SpkScoreResource($score->load('criterion')))->resolve());
    }

    public function update(UpdateSpkScoreRequest $request, SpkScore $score): JsonResponse
    {
        $score->update($request->validated());

        return response()->json((new SpkScoreResource($score->refresh()->load('criterion')))->resolve());
    }

    public function destroy(SpkScore $score): JsonResponse
    {
        $score->delete();

        return response()->json(['message' => 'Nilai SPK deleted successfully']);
    }
}

<?php

namespace App\Http\Controllers\Api\Spk;

use App\Http\Controllers\Controller;
use App\Http\Requests\Spk\IndexSpkCriterionRequest;
use App\Http\Requests\Spk\StoreSpkCriterionRequest;
use App\Http\Requests\Spk\UpdateSpkCriterionRequest;
use App\Http\Resources\Spk\SpkCriterionResource;
use App\Models\SpkCriterion;
use Illuminate\Http\JsonResponse;

class SpkCriterionController extends Controller
{
    public function index(IndexSpkCriterionRequest $request): JsonResponse
    {
        $criteria = SpkCriterion::query()
            ->when($request->validated('target_type'), fn ($query, $type) => $query->where('target_type', $type))
            ->when($request->has('is_active'), fn ($query) => $query->where('is_active', $request->boolean('is_active')))
            ->orderBy('target_type')
            ->orderBy('id')
            ->get();

        return response()->json(SpkCriterionResource::collection($criteria)->resolve());
    }

    public function store(StoreSpkCriterionRequest $request): JsonResponse
    {
        $criterion = SpkCriterion::query()->create($request->validated());

        return response()->json((new SpkCriterionResource($criterion))->resolve(), 201);
    }

    public function show(SpkCriterion $criterion): JsonResponse
    {
        return response()->json((new SpkCriterionResource($criterion))->resolve());
    }

    public function update(UpdateSpkCriterionRequest $request, SpkCriterion $criterion): JsonResponse
    {
        $criterion->update($request->validated());

        return response()->json((new SpkCriterionResource($criterion->refresh()))->resolve());
    }

    public function destroy(SpkCriterion $criterion): JsonResponse
    {
        $criterion->delete();

        return response()->json(['message' => 'Kriteria SPK deleted successfully']);
    }
}

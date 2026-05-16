<?php

namespace App\Http\Controllers\Api\Spk;

use App\Http\Controllers\Controller;
use App\Http\Requests\Spk\RankingRequest;
use App\Services\Spk\SpkRankingService;
use Illuminate\Http\JsonResponse;

class SpkRankingController extends Controller
{
    public function __construct(
        private readonly SpkRankingService $service
    ) {}

    public function guru(RankingRequest $request): JsonResponse
    {
        return response()->json($this->service->rank('guru', $request->validated('periode'))->all());
    }

    public function siswa(RankingRequest $request): JsonResponse
    {
        return response()->json($this->service->rank('siswa', $request->validated('periode'))->all());
    }
}

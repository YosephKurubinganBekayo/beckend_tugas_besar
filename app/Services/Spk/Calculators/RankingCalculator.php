<?php

namespace App\Services\Spk\Calculators;

use Illuminate\Support\Collection;

interface RankingCalculator
{
    /**
     * @param  Collection<int, array{id: int, nama: string, scores: array<int, float>}>  $alternatives
     * @param  Collection<int, array{id: int, bobot: float, tipe: string}>  $criteria
     * @return Collection<int, array<string, mixed>>
     */
    public function calculate(Collection $alternatives, Collection $criteria): Collection;
}

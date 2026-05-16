<?php

namespace App\Providers;

use App\Services\Spk\Calculators\RankingCalculator;
use App\Services\Spk\Calculators\SawCalculator;
use Illuminate\Support\ServiceProvider;

class SpkServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(RankingCalculator::class, SawCalculator::class);
    }
}

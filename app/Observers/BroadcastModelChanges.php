<?php

namespace App\Observers;

use App\Events\ModelChanged;
use Illuminate\Database\Eloquent\Model;

class BroadcastModelChanges
{
    public bool $afterCommit = true;

    public function created(Model $model): void
    {
        ModelChanged::dispatch('created', $model);
    }

    public function updated(Model $model): void
    {
        ModelChanged::dispatch('updated', $model);
    }

    public function deleted(Model $model): void
    {
        ModelChanged::dispatch('deleted', $model);
    }
}

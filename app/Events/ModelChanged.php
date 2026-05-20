<?php

namespace App\Events;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Queue\Attributes\Connection;
use Illuminate\Queue\Attributes\Queue;
use Illuminate\Queue\SerializesModels;

#[Connection('redis')]
#[Queue('default')]
class ModelChanged implements ShouldBroadcast
{
    use SerializesModels;

    public function __construct(
        public readonly string $action,
        public readonly string $model,
        public readonly string $table,
        public readonly int|string|null $id,
        public readonly array $data,
    ) {}

    public static function fromModel(string $action, Model $model): self
    {
        return new self(
            action: $action,
            model: class_basename($model),
            table: $model->getTable(),
            id: $model->getKey(),
            data: $model->withoutRelations()->toArray(),
        );
    }

    public function broadcastAs(): string
    {
        return 'model.changed';
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('app-updates'),
        ];
    }

    public function broadcastWith(): array
    {
        return [
            'action' => $this->action,
            'model' => $this->model,
            'table' => $this->table,
            'id' => $this->id,
            'data' => $this->data,
        ];
    }
}

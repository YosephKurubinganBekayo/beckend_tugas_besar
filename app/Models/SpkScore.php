<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpkScore extends Model
{
    use HasFactory;

    protected $table = 'spk_scores';

    protected $fillable = [
        'spk_criterion_id',
        'target_type',
        'target_id',
        'nilai',
        'periode',
    ];

    protected function casts(): array
    {
        return [
            'nilai' => 'decimal:4',
        ];
    }

    public function criterion()
    {
        return $this->belongsTo(SpkCriterion::class, 'spk_criterion_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpkCriterion extends Model
{
    use HasFactory;

    protected $table = 'spk_criteria';

    protected $fillable = [
        'target_type',
        'kode',
        'nama',
        'bobot',
        'tipe',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'bobot' => 'decimal:4',
            'is_active' => 'boolean',
        ];
    }

    public function scores()
    {
        return $this->hasMany(SpkScore::class, 'spk_criterion_id');
    }
}

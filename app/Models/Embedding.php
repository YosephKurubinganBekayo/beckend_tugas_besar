<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Embedding extends Model
{
    use HasFactory;

    protected $table = 'embeddings';

    protected $fillable = [
        'siswa_id',
        'embedding',
    ];

    public function siswa()
    {
        return $this->belongsTo(
            Siswa::class,
            'siswa_id'
        );
    }
    protected $casts = [
        'embedding' => 'array',
    ];
}

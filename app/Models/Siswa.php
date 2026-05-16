<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Siswa extends Model
{
    use HasFactory;

    protected $table = 'siswa';

    protected $fillable = [
        'nama',
        'nis',
        'jenis_kelamin',
        'kelas_id',
        'alamat',
        'foto_url',
        'embedding_status',
    ];

    protected $attributes = [
        'embedding_status' => 'belum_diproses',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS
    |--------------------------------------------------------------------------
    */

    // siswa belongs to kelas
    public function kelas()
    {
        return $this->belongsTo(
            Kelas::class,
            'kelas_id'
        );
    }

    // siswa has many embeddings
    public function embeddings()
    {
        return $this->hasMany(
            Embedding::class,
            'siswa_id'
        );
    }

    // siswa has many presensi
    public function presensi()
    {
        return $this->hasMany(
            Presensi::class,
            'siswa_id'
        );
    }
}
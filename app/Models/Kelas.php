<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Kelas extends Model
{
    use HasFactory;

    protected $table = 'kelas';

    protected $fillable = [
        'nama_kelas',
        'wali_kelas_id',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS
    |--------------------------------------------------------------------------
    */

    // kelas memiliki 1 wali kelas
    public function waliKelas()
    {
        return $this->belongsTo(
            Guru::class,
            'wali_kelas_id'
        );
    }

    // kelas memiliki banyak siswa
    public function siswa()
    {
        return $this->hasMany(
            Siswa::class,
            'kelas_id'
        );
    }

    // kelas memiliki banyak jadwal
    public function jadwal()
    {
        return $this->hasMany(
            Jadwal::class,
            'kelas_id'
        );
    }
}
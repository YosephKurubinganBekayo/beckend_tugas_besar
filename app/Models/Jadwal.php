<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Jadwal extends Model
{
    use HasFactory;

    protected $table = 'jadwal';

    protected $fillable = [
        'kelas_id',
        'mapel_id',
        'guru_id',
        'hari',
        'jam_mulai',
        'jam_selesai',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS
    |--------------------------------------------------------------------------
    */

    // jadwal milik kelas
    public function kelas()
    {
        return $this->belongsTo(
            Kelas::class,
            'kelas_id'
        );
    }

    // jadwal milik mapel
    public function mapel()
    {
        return $this->belongsTo(
            MataPelajaran::class,
            'mapel_id'
        );
    }

    // jadwal milik guru
    public function guru()
    {
        return $this->belongsTo(
            Guru::class,
            'guru_id'
        );
    }

    // jadwal memiliki banyak presensi
    public function presensi()
    {
        return $this->hasMany(
            Presensi::class,
            'jadwal_id'
        );
    }
}
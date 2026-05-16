<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Presensi extends Model
{
    use HasFactory;

    protected $table = 'presensi';

    protected $fillable = [
        'siswa_id',
        'jadwal_id',
        'guru_id',
        'status',
        'tanggal',
        'jam_presensi',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS
    |--------------------------------------------------------------------------
    */

    // presensi milik siswa
    public function siswa()
    {
        return $this->belongsTo(
            Siswa::class,
            'siswa_id'
        );
    }

    // presensi milik jadwal
    public function jadwal()
    {
        return $this->belongsTo(
            Jadwal::class,
            'jadwal_id'
        );
    }

    // presensi dilakukan guru
    public function guru()
    {
        return $this->belongsTo(
            Guru::class,
            'guru_id'
        );
    }
}
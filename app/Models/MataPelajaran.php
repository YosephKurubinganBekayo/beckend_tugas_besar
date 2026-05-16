<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MataPelajaran extends Model
{
    use HasFactory;

    protected $table = 'mata_pelajaran';

    protected $fillable = [
        'nama_mapel',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS
    |--------------------------------------------------------------------------
    */

    // relasi ke pivot guru_mapel
    public function guruMapel()
    {
        return $this->hasMany(
            GuruMapel::class,
            'mapel_id'
        );
    }

    // relasi jadwal
    public function jadwal()
    {
        return $this->hasMany(
            Jadwal::class,
            'mapel_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | MANY TO MANY
    |--------------------------------------------------------------------------
    */

    // banyak guru mengajar mapel ini
    public function guru()
    {
        return $this->belongsToMany(
            Guru::class,
            'guru_mapel',
            'mapel_id',
            'guru_id'
        );
    }
}
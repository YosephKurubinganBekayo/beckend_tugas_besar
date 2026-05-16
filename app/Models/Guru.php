<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Guru extends Authenticatable
{
    use HasFactory, HasApiTokens, Notifiable;

    protected $table = 'guru';

    /*
    |--------------------------------------------------------------------------
    | FILLABLE
    |--------------------------------------------------------------------------
    */

    protected $fillable = [
        'nama',
        'jenis_kelamin',
        'email',
        'password',
        'nip',
        'foto_url',
    ];

    /*
    |--------------------------------------------------------------------------
    | HIDDEN
    |--------------------------------------------------------------------------
    */

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /*
    |--------------------------------------------------------------------------
    | CASTS
    |--------------------------------------------------------------------------
    */

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | APPENDS
    |--------------------------------------------------------------------------
    */

    protected $appends = [
        'mapel_id',
        'mapel_ids',
        'kelas_asuh_id',
        'kelas_asuh_ids',
    ];

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | MAPEL PERTAMA
    |--------------------------------------------------------------------------
    */

    protected function mapelId(): Attribute
    {
        return Attribute::make(
            get: fn () =>
                $this->mataPelajaran
                    ->first()?->id
        );
    }

    /*
    |--------------------------------------------------------------------------
    | SEMUA MAPEL
    |--------------------------------------------------------------------------
    */

    protected function mapelIds(): Attribute
    {
        return Attribute::make(
            get: fn () =>
                $this->mataPelajaran
                    ->pluck('id')
                    ->toArray()
        );
    }

    /*
    |--------------------------------------------------------------------------
    | KELAS ASUH PERTAMA
    |--------------------------------------------------------------------------
    */

    protected function kelasAsuhId(): Attribute
    {
        return Attribute::make(
            get: fn () =>
                $this->kelasYangDiwalikan
                    ->first()?->id
        );
    }

    /*
    |--------------------------------------------------------------------------
    | SEMUA KELAS ASUH
    |--------------------------------------------------------------------------
    */

    protected function kelasAsuhIds(): Attribute
    {
        return Attribute::make(
            get: fn () =>
                $this->kelasYangDiwalikan
                    ->pluck('id')
                    ->toArray()
        );
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | GURU MENJADI WALI KELAS
    |--------------------------------------------------------------------------
    */

    public function kelasYangDiwalikan()
    {
        return $this->hasMany(
            Kelas::class,
            'wali_kelas_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | PIVOT GURU MAPEL
    |--------------------------------------------------------------------------
    */

    public function mapelYangDiajar()
    {
        return $this->hasMany(
            GuruMapel::class,
            'guru_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | MANY TO MANY MATA PELAJARAN
    |--------------------------------------------------------------------------
    */

    public function mataPelajaran()
    {
        return $this->belongsToMany(
            MataPelajaran::class,
            'guru_mapel',
            'guru_id',
            'mapel_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | RELASI JADWAL
    |--------------------------------------------------------------------------
    */

    public function jadwal()
    {
        return $this->hasMany(
            Jadwal::class,
            'guru_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | RELASI PRESENSI
    |--------------------------------------------------------------------------
    */

    public function presensi()
    {
        return $this->hasMany(
            Presensi::class,
            'guru_id'
        );
    }
}
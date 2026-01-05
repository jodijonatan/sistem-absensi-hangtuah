<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Siswa extends Authenticatable
{
    protected $table = 'siswa';

    protected $fillable = [
        'nis',
        'nama_lengkap',
        'kelas_id',
        'jenis_kelamin',
        'password',
        'foto'
    ];

    protected $casts = [
        'jenis_kelamin' => 'string'
    ];

    /**
     * Relationship: Siswa belongs to Kelas
     */
    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class);
    }

    /**
     * Relationship: Siswa has many Absensi
     */
    public function absensi(): HasMany
    {
        return $this->hasMany(Absensi::class)
            // Secara default, urutkan berdasarkan waktu_tap terbaru
            ->orderBy('waktu_tap', 'desc');
    }

    /**
     * Get the student's full name with NIS
     */
    public function getFullIdentityAttribute(): string
    {
        return "{$this->nis} - {$this->nama_lengkap}";
    }
}

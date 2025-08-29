<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Siswa extends Model
{
    protected $table = 'siswa';
    
    protected $fillable = [
        'nis',
        'nama_lengkap',
        'kelas_id',
        'uid_rfid',
        'jenis_kelamin',
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
        return $this->hasMany(Absensi::class);
    }

    /**
     * Get the student's full name with NIS
     */
    public function getFullIdentityAttribute(): string
    {
        return "{$this->nis} - {$this->nama_lengkap}";
    }

    /**
     * Scope to find student by RFID UID
     */
    public function scopeByRfidUid($query, $uid)
    {
        return $query->where('uid_rfid', $uid);
    }
}

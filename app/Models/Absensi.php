<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Absensi extends Model
{
    protected $table = 'absensi';

    protected $fillable = [
        'siswa_id',
        'waktu_tap',
        'kode_barcode',
        'latitude',
        'longitude',
        'type',
        'status'
    ];

    protected $casts = [
        'waktu_tap' => 'datetime',
        'latitude' => 'float',
        'longitude' => 'float',
    ];

    /**
     * Relationship: Absensi belongs to Siswa
     */
    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class);
    }

    /**
     * Scope to get attendance for specific date
     */
    public function scopeForDate($query, $date)
    {
        return $query->whereDate('waktu_tap', $date);
    }

    /**
     * Scope to get attendance for specific student
     */
    public function scopeForSiswa($query, $siswaId)
    {
        return $query->where('siswa_id', $siswaId);
    }

    /**
     * Scope to get attendance by type (masuk/pulang)
     */
    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope to get attendance by status
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Get the last attendance for a specific student on a specific date
     */
    public static function getLastAttendanceForStudentToday($siswaId, $date = null)
    {
        $date = $date ?? Carbon::today();

        return self::forSiswa($siswaId)
            ->forDate($date)
            ->orderBy('waktu_tap', 'desc')
            ->first();
    }

    /**
     * Check if student has checked in today
     */
    public static function hasCheckedInToday($siswaId, $date = null)
    {
        $date = $date ?? Carbon::today();

        return self::forSiswa($siswaId)
            ->forDate($date)
            ->byType('masuk')
            ->exists();
    }

    /**
     * Check if student has checked out today
     */
    public static function hasCheckedOutToday($siswaId, $date = null)
    {
        $date = $date ?? Carbon::today();

        return self::forSiswa($siswaId)
            ->forDate($date)
            ->byType('pulang')
            ->exists();
    }

    /**
     * Get formatted time for display
     */
    public function getFormattedTimeAttribute()
    {
        return $this->waktu_tap->format('H:i:s');
    }

    /**
     * Get formatted date for display
     */
    public function getFormattedDateAttribute()
    {
        return $this->waktu_tap->format('d/m/Y');
    }

    /**
     * Get status badge color for UI
     */
    public function getStatusBadgeColorAttribute()
    {
        return match ($this->status) {
            'hadir' => 'green',
            'terlambat' => 'yellow',
            'pulang_awal' => 'red',
            default => 'gray'
        };
    }
}

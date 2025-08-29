<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class JadwalPelajaran extends Model
{
    protected $table = 'jadwal_pelajaran';
    
    protected $fillable = [
        'kelas_id',
        'hari',
        'jam_masuk',
        'jam_pulang',
        'keterangan'
    ];

    protected $casts = [
        'jam_masuk' => 'datetime:H:i:s',
        'jam_pulang' => 'datetime:H:i:s'
    ];

    /**
     * Relationship: JadwalPelajaran belongs to Kelas
     */
    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class);
    }

    /**
     * Scope to get schedule for specific day
     */
    public function scopeForDay($query, $day)
    {
        return $query->where('hari', $day);
    }

    /**
     * Scope to get schedule for specific class
     */
    public function scopeForKelas($query, $kelasId)
    {
        return $query->where('kelas_id', $kelasId);
    }

    /**
     * Get current day schedule for a specific class
     */
    public static function getCurrentDaySchedule($kelasId)
    {
        $currentDay = Carbon::now()->locale('id')->dayName;
        $dayMap = [
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa', 
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu',
            'Sunday' => 'Minggu'
        ];
        
        $indonesianDay = $dayMap[$currentDay] ?? 'Senin';
        
        return self::forKelas($kelasId)->forDay($indonesianDay)->first();
    }

    /**
     * Check if current time is late for entry
     */
    public function isLateEntry($currentTime = null)
    {
        $currentTime = $currentTime ?? Carbon::now();
        $jamMasuk = Carbon::createFromFormat('H:i:s', $this->jam_masuk);
        
        return $currentTime->format('H:i:s') > $jamMasuk->format('H:i:s');
    }

    /**
     * Check if current time is early departure
     */
    public function isEarlyDeparture($currentTime = null)
    {
        $currentTime = $currentTime ?? Carbon::now();
        $jamPulang = Carbon::createFromFormat('H:i:s', $this->jam_pulang);
        
        return $currentTime->format('H:i:s') < $jamPulang->format('H:i:s');
    }
}

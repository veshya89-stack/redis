<?php

namespace App\Models;

use App\Services\PenugasanStatusCalculator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Penugasan extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'kode',
        'isu_strategis',
        'pic',
        'tanggal_mulai',
        'target_selesai',
        'status',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'target_selesai' => 'date',
    ];

    public function tindakLanjut()
    {
        return $this->hasMany(TindakLanjutPenugasan::class)->orderByDesc('tanggal');
    }

    /**
     * Progress keseluruhan diambil dari tindak lanjut TERBARU.
     * (Konsisten dengan pola "progress terkini" ala Strategic Initiative.)
     */
    public function getProgressAttribute(): int
    {
        $latest = $this->tindakLanjut()->first();
        return $latest ? $latest->progress : 0;
    }

    /**
     * Hitung ulang status otomatis berdasarkan progress & deadline,
     * lalu simpan ke kolom status. Panggil ini tiap kali tindak lanjut
     * baru ditambahkan.
     */
    public function refreshStatus(): void
    {
        $this->status = PenugasanStatusCalculator::calculate($this);
        $this->save();
    }

    /**
     * Scope: penugasan yang butuh notifikasi H-3 menjelang deadline.
     */
    public function scopeNeedsDeadlineReminder($query)
    {
        return $query->where('status', '!=', 'selesai')
            ->whereNotNull('target_selesai')
            ->whereDate('target_selesai', '=', now()->addDays(3)->toDateString());
    }
}

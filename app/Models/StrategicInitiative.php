<?php

namespace App\Models;

use App\Support\StatusCalculator;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StrategicInitiative extends Model
{
    use SoftDeletes;

    protected $fillable = ['kode', 'judul', 'deskripsi', 'pic_evp_id'];

    protected static function booted(): void
    {
        // Kalau Strategic Initiative dihapus, ikutkan semua Action Plan-nya
        // (soft delete juga, bukan hilang permanen)
        static::deleting(function (StrategicInitiative $initiative) {
            if (! $initiative->isForceDeleting()) {
                $initiative->actionPlans()->get()->each->delete();
            }
        });
    }

    public function picEvp()
    {
        return $this->belongsTo(Evp::class, 'pic_evp_id');
    }

    public function evpTerkait()
    {
        return $this->belongsToMany(Evp::class, 'initiative_evp');
    }

    public function meetings()
    {
        return $this->belongsToMany(Meeting::class, 'initiative_meeting')
            ->withPivot('catatan_pembahasan')
            ->withTimestamps();
    }

    public function actionPlans()
    {
        return $this->hasMany(ActionPlan::class)->orderBy('urutan');
    }

    // Total bobot seluruh action plan (idealnya = 100, sama seperti aturan di Excel)
    protected function bobotTotal(): Attribute
    {
        return Attribute::get(fn () => $this->actionPlans->sum('bobot'));
    }

    // Rata-rata progres tertimbang bobot, replikasi formula SUMPRODUCT di Excel
    protected function progressPercent(): Attribute
    {
        return Attribute::get(function () {
            $plans = $this->actionPlans;
            $totalBobot = $plans->sum('bobot');

            if ($totalBobot > 0) {
                return round($plans->sum(fn ($p) => $p->progress_percent * $p->bobot) / $totalBobot, 1);
            }

            return $plans->count() > 0 ? round($plans->avg('progress_percent'), 1) : 0;
        });
    }

    // Deadline terdekat dari action plan yang belum selesai, dipakai untuk status level inisiatif
    protected function nearestDeadline(): Attribute
    {
        return Attribute::get(fn () => $this->actionPlans
            ->where('progress_percent', '<', 100)
            ->pluck('deadline')
            ->filter()
            ->min());
    }

    protected function status(): Attribute
    {
        return Attribute::get(fn () => StatusCalculator::compute($this->nearest_deadline, (float) $this->progress_percent));
    }

    // Jumlah action plan berstatus Terlambat/Perlu Perhatian -> badge "Perlu atensi Direktur"
    protected function perluAtensi(): Attribute
    {
        return Attribute::get(fn () => $this->actionPlans
            ->filter(fn ($p) => in_array($p->status, ['Terlambat', 'Perlu Perhatian']))
            ->count());
    }

    // Label & warna badge Bootstrap untuk ditampilkan di card/list, sesuai draft UI REDIS
    public const STATUS_BADGES = [
        'Selesai' => ['label' => 'Selesai', 'class' => 'bg-success'],
        'On Track' => ['label' => 'On Progress', 'class' => 'bg-primary'],
        'Perlu Perhatian' => ['label' => 'Need Attention', 'class' => 'bg-warning text-dark'],
        'Terlambat' => ['label' => 'Critical', 'class' => 'bg-danger'],
        'Belum Mulai' => ['label' => 'Belum Mulai', 'class' => 'bg-secondary'],
    ];

    protected function statusLabel(): Attribute
    {
        return Attribute::get(fn () => self::STATUS_BADGES[$this->status]['label'] ?? $this->status);
    }

    protected function statusBadgeClass(): Attribute
    {
        return Attribute::get(fn () => self::STATUS_BADGES[$this->status]['class'] ?? 'bg-secondary');
    }
}

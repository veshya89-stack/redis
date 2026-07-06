<?php

namespace App\Models;

use App\Support\StatusCalculator;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ActionPlan extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'strategic_initiative_id', 'urutan', 'nama_action_plan', 'output',
        'pic_user_id', 'pic_pendukung', 'stakeholder_eksternal', 'deadline',
        'bobot', 'progress_percent', 'kendala', 'dukungan_direktur',
        'update_terakhir', 'tgl_update',
    ];

    protected $casts = [
        'deadline' => 'date',
        'tgl_update' => 'date',
        'bobot' => 'decimal:2',
        'progress_percent' => 'decimal:2',
    ];

    protected static function booted(): void
    {
        // Replikasi formula Q di Excel: Tgl Update otomatis terisi TODAY() saat Update Terakhir diisi
        static::saving(function (ActionPlan $plan) {
            if ($plan->isDirty('update_terakhir') && filled($plan->update_terakhir)) {
                $plan->tgl_update = now();
            }
        });
    }

    public function strategicInitiative()
    {
        return $this->belongsTo(StrategicInitiative::class);
    }

    public function pic()
    {
        return $this->belongsTo(User::class, 'pic_user_id');
    }

    public function divisiTerkait()
    {
        return $this->belongsToMany(Division::class, 'action_plan_division');
    }

    public function attachments()
    {
        return $this->hasMany(Attachment::class);
    }

    protected function status(): Attribute
    {
        return Attribute::get(fn () => StatusCalculator::compute($this->deadline, (float) $this->progress_percent));
    }

    protected function sisaHari(): Attribute
    {
        return Attribute::get(fn () => $this->deadline ? now()->diffInDays($this->deadline, false) : null);
    }
}

<?php

namespace App\Services;

use App\Models\Penugasan;
use Carbon\Carbon;

class PenugasanStatusCalculator
{
    /**
     * Hitung status 5-tier untuk sebuah Penugasan.
     *
     * Logic (sama pola dengan StatusCalculator Strategic Initiative):
     * - belum_mulai      : belum ada tindak lanjut sama sekali
     * - selesai          : progress terbaru = 100
     * - critical         : sudah lewat target_selesai, progress belum 100
     * - need_attention   : H-3 atau kurang menuju target_selesai, progress belum 100
     * - on_progress      : sudah ada tindak lanjut, progress < 100, masih jauh dari deadline
     */
    public static function calculate(Penugasan $penugasan): string
    {
        $latestProgress = $penugasan->tindakLanjut()->first()?->progress ?? 0;

        if ($latestProgress >= 100) {
            return 'selesai';
        }

        $hasTindakLanjut = $penugasan->tindakLanjut()->exists();

        if (! $hasTindakLanjut) {
            return 'belum_mulai';
        }

        if ($penugasan->target_selesai) {
            $today = Carbon::today();
            $deadline = Carbon::parse($penugasan->target_selesai);

            if ($today->greaterThan($deadline)) {
                return 'critical';
            }

            if ($today->diffInDays($deadline, false) <= 3) {
                return 'need_attention';
            }
        }

        return 'on_progress';
    }

    /**
     * Label & warna badge untuk ditampilkan di view.
     */
    public static function badge(string $status): array
    {
        return match ($status) {
            'belum_mulai'      => ['label' => 'Belum Mulai', 'class' => 'bg-secondary'],
            'on_progress'      => ['label' => 'On Progress', 'class' => 'bg-primary'],
            'need_attention'   => ['label' => 'Need Attention', 'class' => 'bg-warning text-dark'],
            'critical'         => ['label' => 'Critical', 'class' => 'bg-danger'],
            'selesai'          => ['label' => 'Selesai', 'class' => 'bg-success'],
            default            => ['label' => ucfirst($status), 'class' => 'bg-secondary'],
        };
    }
}

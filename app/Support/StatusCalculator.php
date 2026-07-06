<?php

namespace App\Support;

use Carbon\Carbon;

class StatusCalculator
{
    /**
     * Replikasi persis logika formula kolom "Status" di Excel Action Tracker:
     * 1. Progres >= 100%          -> Selesai
     * 2. Deadline sudah lewat     -> Terlambat
     * 3. Progres masih 0%         -> Belum Mulai
     * 4. Sisa hari <= 30 & progres < 80% -> Perlu Perhatian
     * 5. Selain itu               -> On Track
     */
    public static function compute(?Carbon $deadline, float $progressPercent): string
    {
        if ($progressPercent >= 100) {
            return 'Selesai';
        }

        if ($deadline && $deadline->isPast()) {
            return 'Terlambat';
        }

        if ($progressPercent <= 0) {
            return 'Belum Mulai';
        }

        if ($deadline && now()->diffInDays($deadline, false) <= 30 && $progressPercent < 80) {
            return 'Perlu Perhatian';
        }

        return 'On Track';
    }
}

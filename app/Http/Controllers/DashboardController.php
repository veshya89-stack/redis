<?php

namespace App\Http\Controllers;

use App\Models\StrategicInitiative;

class DashboardController extends Controller
{
    public function index()
    {
        $initiatives = StrategicInitiative::with('actionPlans')->get();

        $totalInitiative = $initiatives->count();
        $avgProgress = $totalInitiative > 0 ? round($initiatives->avg('progress_percent'), 1) : 0;

        $countByStatus = $initiatives->groupBy('status')->map->count();

        $needAttention = $initiatives
            ->filter(fn ($i) => in_array($i->status, ['Terlambat', 'Perlu Perhatian']))
            ->sortByDesc('perlu_atensi')
            ->values();

        $totalActionPlan = $initiatives->sum(fn ($i) => $i->actionPlans->count());
        $actionPlanSelesai = $initiatives->sum(fn ($i) => $i->actionPlans->where('status', 'Selesai')->count());

        return view('dashboard', [
            'totalInitiative' => $totalInitiative,
            'avgProgress' => $avgProgress,
            'countByStatus' => $countByStatus,
            'needAttention' => $needAttention,
            'totalActionPlan' => $totalActionPlan,
            'actionPlanSelesai' => $actionPlanSelesai,
        ]);
    }
}

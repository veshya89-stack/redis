@extends('layouts.app')

@section('title','Dashboard')

@section('content')

<div class="row g-3 mb-4">

    <div class="col-md-3">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-body">
                <div class="text-muted small mb-1">Total Strategic Initiative</div>
                <div class="fs-2 fw-bold">{{ $totalInitiative }}</div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-body">
                <div class="text-muted small mb-1">Rata-rata Progress</div>
                <div class="fs-2 fw-bold">{{ $avgProgress }}%</div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-body">
                <div class="text-muted small mb-1">Perlu Atensi Direktur</div>
                <div class="fs-2 fw-bold text-danger">{{ $needAttention->count() }}</div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-body">
                <div class="text-muted small mb-1">Action Plan Selesai</div>
                <div class="fs-2 fw-bold">{{ $actionPlanSelesai }} <span class="fs-6 text-muted fw-normal">/ {{ $totalActionPlan }}</span></div>
            </div>
        </div>
    </div>

</div>

<div class="card border-0 shadow-sm rounded-4 mb-4">

    <div class="card-body">

        <h6 class="fw-bold mb-3">Sebaran Status Strategic Initiative</h6>

        <div class="d-flex flex-wrap gap-2">
            @forelse (\App\Models\StrategicInitiative::STATUS_BADGES as $key => $badge)
                <span class="badge {{ $badge['class'] }} fs-6">
                    {{ $badge['label'] }}: {{ $countByStatus[$key] ?? 0 }}
                </span>
            @empty
            @endforelse
        </div>

    </div>

</div>

<div class="card border-0 shadow-sm rounded-4">

    <div class="card-body">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="fw-bold mb-0">Perlu Atensi Direktur</h5>
            <a href="{{ route('strategic-initiative.index') }}" class="btn btn-outline-primary btn-sm">
                Lihat Semua Strategic Initiative
            </a>
        </div>

        <div class="table-responsive">

            <table class="table align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Kode</th>
                        <th>Strategic Initiative</th>
                        <th>Progress</th>
                        <th>Status</th>
                        <th>Action Plan Bermasalah</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($needAttention as $initiative)
                        <tr>
                            <td class="text-primary fw-semibold">{{ $initiative->kode }}</td>
                            <td>{{ $initiative->judul }}</td>
                            <td style="min-width:120px">
                                <div class="progress" style="height:8px">
                                    <div class="progress-bar {{ $initiative->status_badge_class }}"
                                         style="width: {{ $initiative->progress_percent }}%"></div>
                                </div>
                                <small>{{ $initiative->progress_percent }}%</small>
                            </td>
                            <td>
                                <span class="badge {{ $initiative->status_badge_class }}">{{ $initiative->status_label }}</span>
                            </td>
                            <td class="text-danger fw-semibold">{{ $initiative->perlu_atensi }}</td>
                            <td class="text-end">
                                <a href="{{ route('strategic-initiative.show', $initiative) }}" class="btn btn-sm btn-outline-primary">Open</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                Tidak ada Strategic Initiative yang perlu atensi saat ini. 🎉
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

        </div>

    </div>

</div>

@endsection

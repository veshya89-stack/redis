@extends('layouts.app')

@section('title','Strategic Initiative')

@section('content')

@if (session('success'))
    <div class="alert alert-success d-flex align-items-center gap-2">
        <i class="bi bi-check-circle-fill"></i>
        {{ session('success') }}
    </div>
@endif

<nav aria-label="breadcrumb" class="mb-3 d-flex justify-content-between align-items-center">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item">
            <a href="{{ route('strategic-initiative.index') }}" class="text-decoration-none">Strategic Initiative</a>
        </li>
        <li class="breadcrumb-item active">Detail</li>
    </ol>

    <div class="d-flex gap-2">
        <a href="{{ route('strategic-initiative.edit', $initiative) }}" class="btn btn-outline-primary btn-sm">
            <i class="bi bi-pencil"></i>
            Edit Strategic Initiative
        </a>

        <form method="POST" action="{{ route('strategic-initiative.destroy', $initiative) }}"
              onsubmit="return confirm('Hapus Strategic Initiative {{ $initiative->kode }} beserta seluruh Action Plan-nya? Data masih bisa dipulihkan lewat menu Administration > Trash.')">
            @csrf @method('DELETE')
            <button type="submit" class="btn btn-outline-danger btn-sm">
                <i class="bi bi-trash"></i>
                Delete
            </button>
        </form>
    </div>
</nav>

<div class="card border-0 shadow-sm rounded-4 mb-4">

    <div class="card-body">

        <div class="d-flex justify-content-between align-items-start">

            <div class="d-flex">

                <span class="badge bg-primary-subtle text-primary fs-6 align-self-start me-3 px-3 py-2">
                    {{ $initiative->kode }}
                </span>

                <div>

                    <h3 class="fw-bold mb-1">
                        {{ $initiative->judul }}
                    </h3>

                    <p class="text-muted mb-0">
                        Meeting: {{ $initiative->meetings->pluck('judul')->implode(', ') ?: '-' }}
                    </p>

                </div>

            </div>

            @if ($initiative->perlu_atensi > 0)
                <span class="badge bg-danger-subtle text-danger border border-danger-subtle">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    Perlu Atensi Direktur
                </span>
            @endif

        </div>

        <div class="row mt-4">

            <div class="col-md-3">
                <strong>PIC</strong>
                <br>
                {{ $initiative->picEvp->nama }}
            </div>

            <div class="col-md-3">
                <strong>EVP Terkait</strong>
                <br>
                {{ $initiative->evpTerkait->pluck('nama')->implode(', ') ?: '-' }}
            </div>

            <div class="col-md-3">
                <strong>Status</strong>
                <br>
                <span class="badge {{ $initiative->status_badge_class }}">
                    {{ $initiative->status_label }}
                </span>
            </div>

            <div class="col-md-3">
                <strong>Progress</strong>
                <div class="progress mt-1" style="height:10px">
                    <div class="progress-bar {{ $initiative->status_badge_class }}"
                         style="width: {{ $initiative->progress_percent }}%">
                    </div>
                </div>
                <small>{{ $initiative->progress_percent }}%</small>
            </div>

        </div>

        <hr class="my-4">

        <h6 class="fw-bold">Description</h6>
        <p class="text-muted mb-0">
            {{ $initiative->deskripsi ?: '-' }}
        </p>

    </div>

</div>

<div class="card border-0 shadow-sm rounded-4">

    <div class="card-body">

        <div class="d-flex justify-content-between align-items-center mb-3">

            <h5 class="fw-bold mb-0">Action Plan</h5>

            <a href="{{ route('action-plan.create', $initiative) }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-lg"></i>
                Add Action Plan
            </a>

        </div>

        <div class="table-responsive">

            <table class="table align-middle">

                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Action Plan</th>
                        <th>PIC</th>
                        <th>Divisi Terkait</th>
                        <th>Deadline</th>
                        <th>Bobot</th>
                        <th>Progress</th>
                        <th>Status</th>
                        <th>Perlu Atensi</th>
                        <th>Last Update</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($initiative->actionPlans as $plan)
                        <tr>
                            <td>{{ $plan->urutan }}</td>
                            <td style="min-width:220px">{{ $plan->nama_action_plan }}</td>
                            <td>{{ $plan->pic->name ?? '-' }}</td>
                            <td>{{ $plan->divisiTerkait->pluck('kode')->implode(', ') ?: '-' }}</td>
                            <td>{{ $plan->deadline?->format('d M Y') ?? '-' }}</td>
                            <td>{{ $plan->bobot }}%</td>
                            <td style="min-width:120px">
                                <div class="progress" style="height:8px">
                                    <div class="progress-bar {{ \App\Models\StrategicInitiative::STATUS_BADGES[$plan->status]['class'] ?? 'bg-secondary' }}"
                                         style="width: {{ $plan->progress_percent }}%">
                                    </div>
                                </div>
                                <small>{{ $plan->progress_percent }}%</small>
                            </td>
                            <td>
                                <span class="badge {{ \App\Models\StrategicInitiative::STATUS_BADGES[$plan->status]['class'] ?? 'bg-secondary' }}">
                                    {{ \App\Models\StrategicInitiative::STATUS_BADGES[$plan->status]['label'] ?? $plan->status }}
                                </span>
                            </td>
                            <td>
                                @if (in_array($plan->status, ['Terlambat', 'Perlu Perhatian']))
                                    <span class="text-danger fw-bold">Yes</span>
                                @else
                                    -
                                @endif
                            </td>
                            <td>{{ $plan->tgl_update?->format('d M Y') ?? '-' }}</td>
                            <td class="text-end">
                                <div class="d-flex gap-1 justify-content-end">
                                    <a href="#" class="btn btn-sm btn-light" title="Lihat"><i class="bi bi-eye"></i></a>
                                    <a href="{{ route('action-plan.edit', [$initiative, $plan]) }}" class="btn btn-sm btn-light" title="Update Progress"><i class="bi bi-pencil"></i></a>
                                    <form method="POST" action="{{ route('action-plan.destroy', [$initiative, $plan]) }}"
                                          onsubmit="return confirm('Hapus Action Plan ini? Masih bisa dipulihkan lewat menu Administration > Trash.')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-light text-danger" title="Hapus"><i class="bi bi-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11" class="text-center text-muted py-4">
                                Belum ada Action Plan untuk Strategic Initiative ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>

                @if ($initiative->actionPlans->isNotEmpty())
                    <tfoot>
                        <tr class="fw-bold">
                            <td colspan="5" class="text-end">Total Bobot</td>
                            <td>{{ $initiative->bobot_total }}%</td>
                            <td colspan="2">Progress Overall</td>
                            <td colspan="3">{{ $initiative->progress_percent }}%</td>
                        </tr>
                    </tfoot>
                @endif

            </table>

        </div>

    </div>

</div>

@endsection

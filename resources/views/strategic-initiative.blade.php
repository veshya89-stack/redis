@extends('layouts.app')

@section('title','Strategic Initiative')

@section('content')

<form method="GET" action="{{ route('strategic-initiative.index') }}">

    <div class="row mb-4 g-2">

        <div class="col-md-4">
            <input type="text" name="search" value="{{ request('search') }}"
                   class="form-control form-control-lg" placeholder="🔍 Search Strategic Initiative">
        </div>

        <div class="col-md-2">
            <select name="status" class="form-select form-select-lg">
                <option value="">Status</option>
                @foreach (\App\Models\StrategicInitiative::STATUS_BADGES as $key => $badge)
                    <option value="{{ $key }}" @selected(request('status') === $key)>
                        {{ $badge['label'] }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-2">
            <select name="pic_evp_id" class="form-select form-select-lg">
                <option value="">PIC</option>
                @foreach ($evps as $evp)
                    <option value="{{ $evp->id }}" @selected((string) request('pic_evp_id') === (string) $evp->id)>
                        {{ $evp->nama }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-2">
            <select name="meeting_id" class="form-select form-select-lg">
                <option value="">Meeting</option>
                @foreach ($meetings as $meeting)
                    <option value="{{ $meeting->id }}" @selected((string) request('meeting_id') === (string) $meeting->id)>
                        {{ $meeting->judul }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-2 text-end">
            <button type="submit" class="btn btn-primary btn-lg w-100">
                Cari
            </button>
        </div>

    </div>

</form>

<div class="text-end mb-3">
    <a href="{{ route('strategic-initiative.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i>
        New Strategic Initiative
    </a>
</div>

@forelse ($initiatives as $initiative)

    <div class="card border-0 shadow-sm rounded-4 mb-4">

        <div class="card-body">

            <div class="d-flex justify-content-between">

                <div>

                    <h6 class="text-primary fw-bold">
                        {{ $initiative->kode }}
                    </h6>

                    <h3 class="fw-bold">
                        {{ $initiative->judul }}
                    </h3>

                    <p class="text-muted">
                        {{ $initiative->meetings->pluck('judul')->implode(', ') ?: '-' }}
                    </p>

                </div>

                <div class="text-end d-flex flex-column align-items-end gap-2">

                    <span class="badge {{ $initiative->status_badge_class }}">
                        {{ $initiative->status_label }}
                    </span>

                    @if ($initiative->perlu_atensi > 0)
                        <div class="text-danger small">
                            <i class="bi bi-exclamation-triangle-fill"></i>
                            Perlu atensi Direktur
                        </div>
                    @endif

                </div>

            </div>

            <div class="mt-4">

                <div class="progress" style="height:10px">

                    <div class="progress-bar {{ $initiative->status_badge_class }}"
                         style="width: {{ $initiative->progress_percent }}%">
                    </div>

                </div>

                <small class="text-muted d-inline-block mt-2">Progress {{ $initiative->progress_percent }}%</small>

            </div>

            <div class="row mt-4">

                <div class="col-md-4">

                    <div class="text-muted small mb-1">PIC</div>

                    <div class="fw-semibold">{{ $initiative->picEvp->nama }}</div>

                </div>

                <div class="col-md-4">

                    <div class="text-muted small mb-1">Deadline</div>

                    <div class="fw-semibold">{{ $initiative->nearest_deadline?->locale('id')->isoFormat('D MMMM YYYY') ?? '-' }}</div>

                </div>

                <div class="col-md-4 text-end">

                    <a href="{{ route('strategic-initiative.show', $initiative) }}" class="btn btn-outline-primary">

                        Open

                    </a>

                </div>

            </div>

        </div>

    </div>

@empty

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body text-center text-muted py-5">
            Tidak ada Strategic Initiative yang cocok dengan pencarian/filter kamu.
        </div>
    </div>

@endforelse

@endsection

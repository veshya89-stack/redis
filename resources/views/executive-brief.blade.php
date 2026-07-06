@extends('layouts.app')

@section('title','Executive Brief')

@section('content')

<div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-body">

        <form method="GET" action="{{ route('executive-brief.index') }}" class="row g-2 align-items-end">

            <div class="col-md-6">
                <label class="form-label fw-semibold">Pilih Meeting</label>
                <select name="meeting_id" class="form-select" onchange="this.form.submit()">
                    @forelse ($meetings as $meeting)
                        <option value="{{ $meeting->id }}" @selected($selectedMeeting?->id === $meeting->id)>
                            {{ $meeting->judul }} @if($meeting->tanggal) &mdash; {{ $meeting->tanggal->format('d M Y') }} @endif
                        </option>
                    @empty
                        <option value="">Belum ada meeting</option>
                    @endforelse
                </select>
            </div>

            @if ($selectedMeeting)
                <div class="col-md-6 text-end">
                    <a href="{{ route('executive-brief.pdf', $selectedMeeting) }}" class="btn btn-primary">
                        <i class="bi bi-file-earmark-pdf"></i>
                        Export PDF
                    </a>
                </div>
            @endif

        </form>

    </div>
</div>

@if ($selectedMeeting)

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body">

            <h3 class="fw-bold mb-1">Executive Brief</h3>
            <p class="text-muted mb-4">
                {{ $selectedMeeting->judul }}
                @if ($selectedMeeting->tanggal) &mdash; {{ $selectedMeeting->tanggal->format('d F Y') }} @endif
            </p>

            @forelse ($initiatives as $initiative)

                <div class="border-bottom pb-3 mb-3">

                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <span class="badge bg-primary-subtle text-primary me-2">{{ $initiative->kode }}</span>
                            <strong>{{ $initiative->judul }}</strong>
                        </div>
                        <div class="text-end">
                            <span class="badge {{ $initiative->status_badge_class }}">{{ $initiative->status_label }}</span>
                            <span class="fw-semibold ms-2">{{ $initiative->progress_percent }}%</span>
                        </div>
                    </div>

                    @php
                        $dukungan = $initiative->actionPlans->pluck('dukungan_direktur')->filter()->unique();
                    @endphp

                    @if ($dukungan->isNotEmpty())
                        <div class="mt-2">
                            <div class="text-muted small fw-semibold mb-1">Dukungan Direktur yang Diperlukan:</div>
                            <ul class="mb-0 ps-3">
                                @foreach ($dukungan as $item)
                                    <li>{{ $item }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                </div>

            @empty
                <p class="text-muted text-center py-4 mb-0">
                    Belum ada Strategic Initiative yang dibahas di meeting ini.
                </p>
            @endforelse

        </div>
    </div>

@else

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body text-center text-muted py-5">
            Belum ada meeting. Tambahkan dulu lewat menu Administration.
        </div>
    </div>

@endif

@endsection

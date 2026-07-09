@extends('layouts.app')

@section('title', 'Detail Penugasan')

@section('content')

    @php $badge = \App\Services\PenugasanStatusCalculator::badge($penugasan->status); @endphp

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                <div>
                    <div class="text-muted small mb-1">{{ $penugasan->kode }}</div>
                    <h5 class="fw-bold mb-2">{{ $penugasan->isu_strategis }}</h5>
                    <span class="badge {{ $badge['class'] }}">{{ $badge['label'] }}</span>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('penugasan.edit', $penugasan) }}" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-pencil"></i> Edit
                    </a>
                    <form method="POST" action="{{ route('penugasan.destroy', $penugasan) }}" onsubmit="return confirm('Pindahkan penugasan ini ke Trash?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger btn-sm">
                            <i class="bi bi-trash"></i> Hapus
                        </button>
                    </form>
                </div>
            </div>

            <hr>

            <div class="row">
                <div class="col-md-3 mb-2">
                    <div class="text-muted small">PIC ED Retail</div>
                    <div class="fw-semibold">{{ $penugasan->pic }}</div>
                </div>
                <div class="col-md-3 mb-2">
                    <div class="text-muted small">Tanggal Mulai</div>
                    <div class="fw-semibold">{{ $penugasan->tanggal_mulai->format('d M Y') }}</div>
                </div>
                <div class="col-md-3 mb-2">
                    <div class="text-muted small">Target Selesai</div>
                    <div class="fw-semibold">{{ $penugasan->target_selesai?->format('d M Y') ?? '-' }}</div>
                </div>
                <div class="col-md-3 mb-2">
                    <div class="text-muted small">Progress Terkini</div>
                    <div class="fw-semibold">{{ $penugasan->progress }}%</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-5 mb-4">
            <div class="card">
                <div class="card-body">
                    <h6 class="fw-bold mb-3">Tambah Tindak Lanjut</h6>

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('penugasan.tindak-lanjut.store', $penugasan) }}">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Tanggal</label>
                            <input type="date" name="tanggal" class="form-control" value="{{ old('tanggal', now()->toDateString()) }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Divisi Terkait</label>
                            <select name="division_id" class="form-select" required>
                                <option value="">-- Pilih Divisi --</option>
                                @foreach($divisions as $division)
                                    <option value="{{ $division->id }}" {{ old('division_id') == $division->id ? 'selected' : '' }}>
                                        {{ $division->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Deskripsi Tindak Lanjut</label>
                            <textarea name="deskripsi" class="form-control" rows="3" required>{{ old('deskripsi') }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Progress (%)</label>
                            <input type="number" name="progress" class="form-control" min="0" max="100" value="{{ old('progress', 0) }}" required>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Simpan Tindak Lanjut</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-7 mb-4">
            <div class="card">
                <div class="card-body">
                    <h6 class="fw-bold mb-3">Riwayat Tindak Lanjut</h6>

                    @forelse($penugasan->tindakLanjut as $tl)
                        <div class="border-bottom pb-3 mb-3">
                            <div class="d-flex justify-content-between">
                                <span class="fw-semibold">{{ $tl->division->nama }}</span>
                                <span class="text-muted small">{{ $tl->tanggal->format('d M Y') }}</span>
                            </div>
                            <p class="mb-1">{{ $tl->deskripsi }}</p>
                            <div class="progress" style="height:8px;">
                                <div class="progress-bar" style="width:{{ $tl->progress }}%"></div>
                            </div>
                            <div class="text-end small text-muted mt-1">{{ $tl->progress }}%</div>
                        </div>
                    @empty
                        <p class="text-muted mb-0">Belum ada tindak lanjut yang dicatat.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

@endsection

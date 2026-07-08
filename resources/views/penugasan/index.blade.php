@extends('layouts.app')

@section('title', 'Penugasan ED Retail')

@section('content')

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <form method="GET" class="d-flex gap-2">
            <select name="status" class="form-select" onchange="this.form.submit()">
                <option value="">Semua Status</option>
                @foreach(['belum_mulai' => 'Belum Mulai', 'on_progress' => 'On Progress', 'need_attention' => 'Need Attention', 'critical' => 'Critical', 'selesai' => 'Selesai'] as $value => $label)
                    <option value="{{ $value }}" {{ request('status') === $value ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
        </form>

        <a href="{{ route('penugasan.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Tambah Penugasan
        </a>
    </div>

    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Isu Strategis</th>
                            <th>PIC</th>
                            <th>Target Selesai</th>
                            <th>Progress</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($penugasans as $item)
                            @php $badge = \App\Services\PenugasanStatusCalculator::badge($item->status); @endphp
                            <tr>
                                <td>{{ $item->kode }}</td>
                                <td>{{ \Illuminate\Support\Str::limit($item->isu_strategis, 60) }}</td>
                                <td>{{ $item->pic }}</td>
                                <td>{{ $item->target_selesai?->format('d M Y') ?? '-' }}</td>
                                <td>{{ $item->progress }}%</td>
                                <td><span class="badge {{ $badge['class'] }}">{{ $badge['label'] }}</span></td>
                                <td>
                                    <a href="{{ route('penugasan.show', $item) }}" class="btn btn-sm btn-outline-primary">
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
                                    Belum ada penugasan. Klik "Tambah Penugasan" untuk mulai.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-3">
        {{ $penugasans->links() }}
    </div>

@endsection

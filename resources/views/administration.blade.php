@extends('layouts.app')

@section('title','Administration')

@section('content')

@if (session('success'))
    <div class="alert alert-success d-flex align-items-center gap-2">
        <i class="bi bi-check-circle-fill"></i>
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger d-flex align-items-center gap-2">
        <i class="bi bi-exclamation-triangle-fill"></i>
        {{ session('error') }}
    </div>
@endif

@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0 ps-3">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<ul class="nav nav-tabs mb-4" id="adminTabs" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-evp" type="button">EVP</button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-divisi" type="button">Divisi</button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-meeting" type="button">Meeting</button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-user" type="button">User / PIC</button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-trash" type="button">
            Trash
            @if ($trashedInitiatives->count() + $trashedActionPlans->count() > 0)
                <span class="badge bg-danger rounded-pill ms-1">{{ $trashedInitiatives->count() + $trashedActionPlans->count() }}</span>
            @endif
        </button>
    </li>
</ul>

<div class="tab-content">

    {{-- EVP --}}
    <div class="tab-pane fade show active" id="tab-evp">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body">

                <h5 class="fw-bold mb-3">Tambah EVP</h5>

                <form method="POST" action="{{ route('administration.evp.store') }}" class="row g-2 mb-4">
                    @csrf
                    <div class="col-md-4">
                        <input type="text" name="kode" class="form-control" placeholder="Kode, contoh: EVP PPR" required>
                    </div>
                    <div class="col-md-6">
                        <input type="text" name="nama" class="form-control" placeholder="Nama lengkap (opsional)">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">Tambah</button>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Kode</th>
                                <th>Nama</th>
                                <th>Dipakai di</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($evps as $evp)
                                <tr>
                                    <td class="fw-semibold">{{ $evp->kode }}</td>
                                    <td>{{ $evp->nama }}</td>
                                    <td>{{ $evp->strategic_initiatives_count }} Strategic Initiative</td>
                                    <td class="text-end">
                                        <form method="POST" action="{{ route('administration.evp.destroy', $evp) }}"
                                              onsubmit="return confirm('Hapus EVP {{ $evp->kode }}?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-center text-muted py-4">Belum ada EVP.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>

    {{-- Divisi --}}
    <div class="tab-pane fade" id="tab-divisi">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body">

                <h5 class="fw-bold mb-3">Tambah Divisi</h5>

                <form method="POST" action="{{ route('administration.division.store') }}" class="row g-2 mb-4">
                    @csrf
                    <div class="col-md-4">
                        <input type="text" name="kode" class="form-control" placeholder="Kode, contoh: DIV RKJ" required>
                    </div>
                    <div class="col-md-6">
                        <input type="text" name="nama" class="form-control" placeholder="Nama lengkap (opsional)">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">Tambah</button>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Kode</th>
                                <th>Nama</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($divisions as $division)
                                <tr>
                                    <td class="fw-semibold">{{ $division->kode }}</td>
                                    <td>{{ $division->nama }}</td>
                                    <td class="text-end">
                                        <form method="POST" action="{{ route('administration.division.destroy', $division) }}"
                                              onsubmit="return confirm('Hapus Divisi {{ $division->kode }}?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="text-center text-muted py-4">Belum ada Divisi.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>

    {{-- Meeting --}}
    <div class="tab-pane fade" id="tab-meeting">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body">

                <h5 class="fw-bold mb-3">Tambah Meeting</h5>

                <form method="POST" action="{{ route('administration.meeting.store') }}" class="row g-2 mb-4">
                    @csrf
                    <div class="col-md-6">
                        <input type="text" name="judul" class="form-control" placeholder="Judul, contoh: Rapim Retail 2 Juli 2026" required>
                    </div>
                    <div class="col-md-4">
                        <input type="date" name="tanggal" class="form-control">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">Tambah</button>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Judul</th>
                                <th>Tanggal</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($meetings as $meeting)
                                <tr>
                                    <td class="fw-semibold">{{ $meeting->judul }}</td>
                                    <td>{{ $meeting->tanggal?->format('d M Y') ?? '-' }}</td>
                                    <td class="text-end">
                                        <form method="POST" action="{{ route('administration.meeting.destroy', $meeting) }}"
                                              onsubmit="return confirm('Hapus meeting {{ $meeting->judul }}?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="text-center text-muted py-4">Belum ada Meeting.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>

    {{-- User / PIC --}}
    <div class="tab-pane fade" id="tab-user">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body">

                <h5 class="fw-bold mb-3">Tambah User</h5>

                <form method="POST" action="{{ route('administration.user.store') }}" class="row g-2 mb-4">
                    @csrf
                    <div class="col-md-3">
                        <input type="text" name="name" class="form-control" placeholder="Nama / Jabatan (VP ...)" required>
                    </div>
                    <div class="col-md-3">
                        <input type="email" name="email" class="form-control" placeholder="Email" required>
                    </div>
                    <div class="col-md-2">
                        <select name="role" class="form-select" required>
                            <option value="pic">PIC</option>
                            <option value="admin">Admin</option>
                            <option value="viewer">Viewer</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="division_id" class="form-select">
                            <option value="">Divisi</option>
                            @foreach ($divisions as $division)
                                <option value="{{ $division->id }}">{{ $division->kode }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">Tambah</button>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Divisi</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($users as $user)
                                <tr>
                                    <td class="fw-semibold">{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td><span class="badge bg-secondary">{{ strtoupper($user->role) }}</span></td>
                                    <td>{{ $user->division?->kode ?? '-' }}</td>
                                    <td class="text-end">
                                        <form method="POST" action="{{ route('administration.user.destroy', $user) }}"
                                              onsubmit="return confirm('Hapus user {{ $user->name }}?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="text-center text-muted py-4">Belum ada User.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>

    {{-- Trash --}}
    <div class="tab-pane fade" id="tab-trash">

        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body">

                <h5 class="fw-bold mb-3">Strategic Initiative Terhapus</h5>

                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Kode</th>
                                <th>Judul</th>
                                <th>Dihapus Pada</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($trashedInitiatives as $initiative)
                                <tr>
                                    <td class="fw-semibold">{{ $initiative->kode }}</td>
                                    <td>{{ $initiative->judul }}</td>
                                    <td>{{ $initiative->deleted_at?->format('d M Y H:i') }}</td>
                                    <td class="text-end">
                                        <div class="d-flex gap-1 justify-content-end">
                                            <form method="POST" action="{{ route('administration.trash.initiative.restore', $initiative->id) }}">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-success">
                                                    <i class="bi bi-arrow-counterclockwise"></i> Restore
                                                </button>
                                            </form>
                                            <form method="POST" action="{{ route('administration.trash.initiative.force', $initiative->id) }}"
                                                  onsubmit="return confirm('Hapus PERMANEN {{ $initiative->kode }}? Tidak bisa dibatalkan lagi.')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    <i class="bi bi-trash3"></i> Hapus Permanen
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-center text-muted py-4">Tidak ada Strategic Initiative di Trash.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body">

                <h5 class="fw-bold mb-3">Action Plan Terhapus</h5>

                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Strategic Initiative</th>
                                <th>Action Plan</th>
                                <th>Dihapus Pada</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($trashedActionPlans as $plan)
                                <tr>
                                    <td>{{ $plan->strategicInitiative?->kode ?? '-' }}</td>
                                    <td>{{ $plan->nama_action_plan }}</td>
                                    <td>{{ $plan->deleted_at?->format('d M Y H:i') }}</td>
                                    <td class="text-end">
                                        <div class="d-flex gap-1 justify-content-end">
                                            <form method="POST" action="{{ route('administration.trash.action-plan.restore', $plan->id) }}">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-success">
                                                    <i class="bi bi-arrow-counterclockwise"></i> Restore
                                                </button>
                                            </form>
                                            <form method="POST" action="{{ route('administration.trash.action-plan.force', $plan->id) }}"
                                                  onsubmit="return confirm('Hapus PERMANEN Action Plan ini? Tidak bisa dibatalkan lagi.')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    <i class="bi bi-trash3"></i> Hapus Permanen
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-center text-muted py-4">Tidak ada Action Plan di Trash.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>

    </div>

</div>

@endsection

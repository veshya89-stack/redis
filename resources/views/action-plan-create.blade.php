@extends('layouts.app')

@section('title','Strategic Initiative')

@section('content')

<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="{{ route('strategic-initiative.index') }}" class="text-decoration-none">Strategic Initiative</a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('strategic-initiative.show', $initiative) }}" class="text-decoration-none">{{ $initiative->kode }}</a>
        </li>
        <li class="breadcrumb-item active">Add Action Plan</li>
    </ol>
</nav>

<div class="card border-0 shadow-sm rounded-4">

    <div class="card-body">

        <h3 class="fw-bold mb-1">Tambah Action Plan</h3>
        <p class="text-muted mb-4">
            {{ $initiative->kode }} &middot; {{ $initiative->judul }}
        </p>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0 ps-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('action-plan.store', $initiative) }}">
            @csrf

            <div class="mb-3">
                <label class="form-label fw-semibold">Action Plan <span class="text-danger">*</span></label>
                <input type="text" name="nama_action_plan" value="{{ old('nama_action_plan') }}"
                       class="form-control" placeholder="Nama action plan" required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Output</label>
                <input type="text" name="output" value="{{ old('output') }}"
                       class="form-control" placeholder="Output yang dihasilkan">
            </div>

            <div class="row">

                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">PIC</label>
                    <select name="pic_user_id" class="form-select">
                        <option value="">- Pilih PIC -</option>
                        @foreach ($picOptions as $user)
                            <option value="{{ $user->id }}" @selected(old('pic_user_id') == $user->id)>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                    <div class="form-text">Kalau PIC belum ada di daftar, tambahkan dulu lewat menu Administration.</div>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">PIC Pendukung</label>
                    <input type="text" name="pic_pendukung" value="{{ old('pic_pendukung') }}"
                           class="form-control" placeholder="Contoh: VP A, VP B">
                </div>

            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Divisi Terkait (Lintas Direktorat)</label>
                <div class="row row-cols-2 row-cols-md-4 g-2">
                    @foreach ($divisions as $division)
                        <div class="col">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="divisi[]"
                                       value="{{ $division->id }}" id="divisi{{ $division->id }}"
                                       @checked(collect(old('divisi'))->contains($division->id))>
                                <label class="form-check-label" for="divisi{{ $division->id }}">
                                    {{ $division->kode }}
                                </label>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Stakeholder Eksternal</label>
                <input type="text" name="stakeholder_eksternal" value="{{ old('stakeholder_eksternal') }}"
                       class="form-control" placeholder="Contoh: Kementerian ESDM">
            </div>

            <div class="row">

                <div class="col-md-4 mb-3">
                    <label class="form-label fw-semibold">Deadline</label>
                    <input type="date" name="deadline" value="{{ old('deadline') }}" class="form-control">
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label fw-semibold">Bobot (%) <span class="text-danger">*</span></label>
                    <input type="number" name="bobot" value="{{ old('bobot', 0) }}" min="0" max="100" step="0.01"
                           class="form-control" required>
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label fw-semibold">Progress (%) <span class="text-danger">*</span></label>
                    <input type="number" name="progress_percent" value="{{ old('progress_percent', 0) }}" min="0" max="100" step="0.01"
                           class="form-control" required>
                </div>

            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Kendala / Issues</label>
                <textarea name="kendala" class="form-control" rows="2">{{ old('kendala') }}</textarea>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Dukungan Direktur yang Diperlukan</label>
                <textarea name="dukungan_direktur" class="form-control" rows="2">{{ old('dukungan_direktur') }}</textarea>
            </div>

            <div class="mb-4">
                <label class="form-label fw-semibold">Update / Komentar</label>
                <textarea name="update_terakhir" class="form-control" rows="2">{{ old('update_terakhir') }}</textarea>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('strategic-initiative.show', $initiative) }}" class="btn btn-light">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan Action Plan</button>
            </div>

        </form>

    </div>

</div>

@endsection

@extends('layouts.app')

@section('title','Strategic Initiative')

@section('content')

@php $isEdit = $initiative->exists; @endphp

<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="{{ route('strategic-initiative.index') }}" class="text-decoration-none">Strategic Initiative</a>
        </li>
        <li class="breadcrumb-item active">{{ $isEdit ? 'Edit' : 'New' }}</li>
    </ol>
</nav>

<div class="card border-0 shadow-sm rounded-4">

    <div class="card-body">

        <h3 class="fw-bold mb-4">
            {{ $isEdit ? 'Edit Strategic Initiative' : 'Strategic Initiative Baru' }}
        </h3>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0 ps-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ $isEdit ? route('strategic-initiative.update', $initiative) : route('strategic-initiative.store') }}">
            @csrf
            @if ($isEdit) @method('PUT') @endif

            <div class="row">

                <div class="col-md-3 mb-3">
                    <label class="form-label fw-semibold">Kode <span class="text-danger">*</span></label>
                    <input type="text" name="kode" value="{{ old('kode', $initiative->kode) }}"
                           class="form-control" placeholder="INS-06" required>
                </div>

                <div class="col-md-9 mb-3">
                    <label class="form-label fw-semibold">Judul Strategic Initiative <span class="text-danger">*</span></label>
                    <input type="text" name="judul" value="{{ old('judul', $initiative->judul) }}"
                           class="form-control" required>
                </div>

            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Deskripsi</label>
                <textarea name="deskripsi" class="form-control" rows="3">{{ old('deskripsi', $initiative->deskripsi) }}</textarea>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">PIC (EVP Penanggung Jawab) <span class="text-danger">*</span></label>
                <select name="pic_evp_id" class="form-select" required>
                    <option value="">- Pilih EVP -</option>
                    @foreach ($evps as $evp)
                        <option value="{{ $evp->id }}" @selected(old('pic_evp_id', $initiative->pic_evp_id) == $evp->id)>
                            {{ $evp->nama }}
                        </option>
                    @endforeach
                </select>
                <div class="form-text">EVP baru bisa ditambahkan lewat menu Administration.</div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">EVP Terkait</label>
                <div class="row row-cols-2 row-cols-md-3 g-2">
                    @php $selectedEvp = old('evp_terkait', $initiative->evpTerkait->pluck('id')->all()); @endphp
                    @foreach ($evps as $evp)
                        <div class="col">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="evp_terkait[]"
                                       value="{{ $evp->id }}" id="evp{{ $evp->id }}"
                                       @checked(collect($selectedEvp)->contains($evp->id))>
                                <label class="form-check-label" for="evp{{ $evp->id }}">
                                    {{ $evp->nama }}
                                </label>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label fw-semibold">Dibahas di Meeting</label>
                <div class="row row-cols-1 row-cols-md-2 g-2">
                    @php $selectedMeeting = old('meetings', $initiative->meetings->pluck('id')->all()); @endphp
                    @forelse ($meetings as $meeting)
                        <div class="col">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="meetings[]"
                                       value="{{ $meeting->id }}" id="meeting{{ $meeting->id }}"
                                       @checked(collect($selectedMeeting)->contains($meeting->id))>
                                <label class="form-check-label" for="meeting{{ $meeting->id }}">
                                    {{ $meeting->judul }}
                                </label>
                            </div>
                        </div>
                    @empty
                        <div class="text-muted small">Belum ada meeting. Tambahkan lewat menu Administration.</div>
                    @endforelse
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ $isEdit ? route('strategic-initiative.show', $initiative) : route('strategic-initiative.index') }}" class="btn btn-light">Batal</a>
                <button type="submit" class="btn btn-primary">
                    {{ $isEdit ? 'Simpan Perubahan' : 'Buat Strategic Initiative' }}
                </button>
            </div>

        </form>

    </div>

</div>

@endsection

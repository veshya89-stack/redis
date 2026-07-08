@extends('layouts.app')

@section('title', 'Edit Penugasan')

@section('content')

    <div class="card">
        <div class="card-body">

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('penugasan.update', $penugasan) }}">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">Isu Strategis</label>
                    <textarea name="isu_strategis" class="form-control" rows="3" required>{{ old('isu_strategis', $penugasan->isu_strategis) }}</textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">PIC ED Retail</label>
                    <input type="text" name="pic" class="form-control" value="{{ old('pic', $penugasan->pic) }}" required>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Tanggal Mulai</label>
                        <input type="date" name="tanggal_mulai" class="form-control" value="{{ old('tanggal_mulai', $penugasan->tanggal_mulai->toDateString()) }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Target Selesai</label>
                        <input type="date" name="target_selesai" class="form-control" value="{{ old('target_selesai', $penugasan->target_selesai?->toDateString()) }}">
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    <a href="{{ route('penugasan.show', $penugasan) }}" class="btn btn-outline-secondary">Batal</a>
                </div>

            </form>

        </div>
    </div>

@endsection

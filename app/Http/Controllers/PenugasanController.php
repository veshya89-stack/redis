<?php

namespace App\Http\Controllers;

use App\Models\Division;
use App\Models\Penugasan;
use App\Models\TindakLanjutPenugasan;
use Illuminate\Http\Request;

class PenugasanController extends Controller
{
    public function index(Request $request)
    {
        $query = Penugasan::query()->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $penugasans = $query->paginate(15);

        return view('penugasan.index', compact('penugasans'));
    }

    public function create()
    {
        return view('penugasan.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'isu_strategis'   => ['required', 'string'],
            'pic'             => ['required', 'string', 'max:255'],
            'tanggal_mulai'   => ['required', 'date'],
            'target_selesai'  => ['nullable', 'date', 'after_or_equal:tanggal_mulai'],
        ]);

        $validated['kode'] = $this->generateKode();
        $validated['status'] = 'belum_mulai';

        $penugasan = Penugasan::create($validated);

        return redirect()
            ->route('penugasan.show', $penugasan)
            ->with('success', 'Penugasan "' . $penugasan->isu_strategis . '" berhasil ditambahkan.');
    }

    public function show(Penugasan $penugasan)
    {
        $divisions = Division::orderBy('kode')->get();
        $penugasan->load('tindakLanjut.division');

        return view('penugasan.show', compact('penugasan', 'divisions'));
    }

    public function edit(Penugasan $penugasan)
    {
        return view('penugasan.edit', compact('penugasan'));
    }

    public function update(Request $request, Penugasan $penugasan)
    {
        $validated = $request->validate([
            'isu_strategis'   => ['required', 'string'],
            'pic'             => ['required', 'string', 'max:255'],
            'tanggal_mulai'   => ['required', 'date'],
            'target_selesai'  => ['nullable', 'date', 'after_or_equal:tanggal_mulai'],
        ]);

        $penugasan->update($validated);
        $penugasan->refreshStatus();

        return redirect()
            ->route('penugasan.show', $penugasan)
            ->with('success', 'Penugasan berhasil diperbarui.');
    }

    public function destroy(Penugasan $penugasan)
    {
        $penugasan->delete();

        return redirect()
            ->route('penugasan.index')
            ->with('success', 'Penugasan "' . $penugasan->isu_strategis . '" dipindahkan ke Trash.');
    }

    /**
     * Tambah catatan tindak lanjut baru untuk sebuah penugasan.
     */
    public function storeTindakLanjut(Request $request, Penugasan $penugasan)
    {
        $validated = $request->validate([
            'tanggal'     => ['required', 'date'],
            'division_id' => ['required', 'exists:divisions,id'],
            'deskripsi'   => ['required', 'string'],
            'progress'    => ['required', 'integer', 'min:0', 'max:100'],
        ]);

        $validated['penugasan_id'] = $penugasan->id;

        TindakLanjutPenugasan::create($validated);

        $penugasan->refreshStatus();

        return redirect()
            ->route('penugasan.show', $penugasan)
            ->with('success', 'Tindak lanjut berhasil dicatat.');
    }

    private function generateKode(): string
    {
        $year = now()->year;
        $count = Penugasan::withTrashed()->whereYear('created_at', $year)->count() + 1;

        return sprintf('PGS-%d-%03d', $year, $count);
    }
}

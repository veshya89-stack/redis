<?php

namespace App\Http\Controllers;

use App\Models\Evp;
use App\Models\Meeting;
use App\Models\StrategicInitiative;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class StrategicInitiativeController extends Controller
{
    public function index(Request $request)
    {
        $query = StrategicInitiative::with(['actionPlans', 'picEvp', 'meetings']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                    ->orWhere('kode', 'like', "%{$search}%");
            });
        }

        if ($request->filled('pic_evp_id')) {
            $query->where('pic_evp_id', $request->pic_evp_id);
        }

        if ($request->filled('meeting_id')) {
            $meetingId = $request->meeting_id;
            $query->whereHas('meetings', fn ($q) => $q->where('meetings.id', $meetingId));
        }

        $initiatives = $query->get();

        // Status (Selesai/On Track/Perlu Perhatian/Terlambat/Belum Mulai) dihitung
        // dinamis di accessor model, bukan kolom DB, jadi filter-nya dilakukan
        // setelah data diambil. Aman untuk skala data prototype ini (belasan inisiatif).
        if ($request->filled('status')) {
            $initiatives = $initiatives->filter(fn ($i) => $i->status === $request->status);
        }

        $evps = Evp::orderBy('kode')->get();
        $meetings = Meeting::orderByDesc('tanggal')->get();

        return view('strategic-initiative', compact('initiatives', 'evps', 'meetings'));
    }

    public function show(StrategicInitiative $strategicInitiative)
    {
        $strategicInitiative->load([
            'picEvp',
            'evpTerkait',
            'meetings',
            'actionPlans.pic',
            'actionPlans.divisiTerkait',
        ]);

        return view('strategic-initiative-detail', [
            'initiative' => $strategicInitiative,
        ]);
    }

    public function create()
    {
        return view('strategic-initiative-form', [
            'initiative' => new StrategicInitiative(),
            'evps' => Evp::orderBy('kode')->get(),
            'meetings' => Meeting::orderByDesc('tanggal')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $this->validated($request);

        $initiative = StrategicInitiative::create([
            'kode' => $validated['kode'],
            'judul' => $validated['judul'],
            'deskripsi' => $validated['deskripsi'] ?? null,
            'pic_evp_id' => $validated['pic_evp_id'],
        ]);

        $initiative->evpTerkait()->sync($validated['evp_terkait'] ?? []);
        $initiative->meetings()->sync($validated['meetings'] ?? []);

        return redirect()
            ->route('strategic-initiative.show', $initiative)
            ->with('success', 'Strategic Initiative "'.$initiative->judul.'" berhasil dibuat.');
    }

    public function edit(StrategicInitiative $strategicInitiative)
    {
        $strategicInitiative->load('evpTerkait', 'meetings');

        return view('strategic-initiative-form', [
            'initiative' => $strategicInitiative,
            'evps' => Evp::orderBy('kode')->get(),
            'meetings' => Meeting::orderByDesc('tanggal')->get(),
        ]);
    }

    public function update(Request $request, StrategicInitiative $strategicInitiative)
    {
        $validated = $this->validated($request, $strategicInitiative->id);

        $strategicInitiative->update([
            'kode' => $validated['kode'],
            'judul' => $validated['judul'],
            'deskripsi' => $validated['deskripsi'] ?? null,
            'pic_evp_id' => $validated['pic_evp_id'],
        ]);

        $strategicInitiative->evpTerkait()->sync($validated['evp_terkait'] ?? []);
        $strategicInitiative->meetings()->sync($validated['meetings'] ?? []);

        return redirect()
            ->route('strategic-initiative.show', $strategicInitiative)
            ->with('success', 'Strategic Initiative "'.$strategicInitiative->judul.'" berhasil diperbarui.');
    }

    private function validated(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'kode' => ['required', 'string', 'max:20', Rule::unique('strategic_initiatives', 'kode')->ignore($ignoreId)],
            'judul' => ['required', 'string', 'max:255'],
            'deskripsi' => ['nullable', 'string'],
            'pic_evp_id' => ['required', 'exists:evps,id'],
            'evp_terkait' => ['nullable', 'array'],
            'evp_terkait.*' => ['exists:evps,id'],
            'meetings' => ['nullable', 'array'],
            'meetings.*' => ['exists:meetings,id'],
        ]);
    }

    public function destroy(StrategicInitiative $strategicInitiative)
    {
        // Soft delete: IS ditandai terhapus, action plan-nya ikut ditandai terhapus
        // juga (bukan cascade DB, karena soft delete bukan DELETE beneran).
        // Semuanya masih bisa dipulihkan lewat menu Administration > Trash.
        $strategicInitiative->actionPlans()->get()->each->delete();
        $strategicInitiative->delete();

        return redirect()
            ->route('strategic-initiative.index')
            ->with('success', 'Strategic Initiative "'.$strategicInitiative->judul.'" dipindahkan ke Trash.');
    }
}

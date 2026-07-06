<?php

namespace App\Http\Controllers;

use App\Models\Division;
use App\Models\StrategicInitiative;
use App\Models\User;
use App\Models\ActionPlan;
use Illuminate\Http\Request;

class ActionPlanController extends Controller
{
    public function create(StrategicInitiative $strategicInitiative)
    {
        $picOptions = User::where('role', 'pic')->orderBy('name')->get();
        $divisions = Division::orderBy('kode')->get();

        return view('action-plan-create', [
            'initiative' => $strategicInitiative,
            'picOptions' => $picOptions,
            'divisions' => $divisions,
        ]);
    }

    public function store(Request $request, StrategicInitiative $strategicInitiative)
    {
        $validated = $request->validate([
            'nama_action_plan' => ['required', 'string', 'max:255'],
            'output' => ['nullable', 'string', 'max:255'],
            'pic_user_id' => ['nullable', 'exists:users,id'],
            'pic_pendukung' => ['nullable', 'string', 'max:255'],
            'stakeholder_eksternal' => ['nullable', 'string', 'max:255'],
            'deadline' => ['nullable', 'date'],
            'bobot' => ['required', 'numeric', 'min:0', 'max:100'],
            'progress_percent' => ['required', 'numeric', 'min:0', 'max:100'],
            'kendala' => ['nullable', 'string'],
            'dukungan_direktur' => ['nullable', 'string'],
            'update_terakhir' => ['nullable', 'string'],
            'divisi' => ['nullable', 'array'],
            'divisi.*' => ['exists:divisions,id'],
        ]);

        $nextUrutan = (int) $strategicInitiative->actionPlans()->max('urutan') + 1;

        $plan = $strategicInitiative->actionPlans()->create([
            'urutan' => $nextUrutan,
            'nama_action_plan' => $validated['nama_action_plan'],
            'output' => $validated['output'] ?? null,
            'pic_user_id' => $validated['pic_user_id'] ?? null,
            'pic_pendukung' => $validated['pic_pendukung'] ?? null,
            'stakeholder_eksternal' => $validated['stakeholder_eksternal'] ?? null,
            'deadline' => $validated['deadline'] ?? null,
            'bobot' => $validated['bobot'],
            'progress_percent' => $validated['progress_percent'],
            'kendala' => $validated['kendala'] ?? null,
            'dukungan_direktur' => $validated['dukungan_direktur'] ?? null,
            'update_terakhir' => $validated['update_terakhir'] ?? null,
        ]);

        if (! empty($validated['divisi'])) {
            $plan->divisiTerkait()->sync($validated['divisi']);
        }

        return redirect()
            ->route('strategic-initiative.show', $strategicInitiative)
            ->with('success', 'Action Plan "'.$plan->nama_action_plan.'" berhasil ditambahkan.');
    }

    public function edit(StrategicInitiative $strategicInitiative, ActionPlan $actionPlan)
    {
        $actionPlan->load('divisiTerkait', 'attachments');

        $picOptions = User::where('role', 'pic')->orderBy('name')->get();
        $divisions = Division::orderBy('kode')->get();

        return view('action-plan-edit', [
            'initiative' => $strategicInitiative,
            'actionPlan' => $actionPlan,
            'picOptions' => $picOptions,
            'divisions' => $divisions,
        ]);
    }

    public function update(Request $request, StrategicInitiative $strategicInitiative, ActionPlan $actionPlan)
    {
        $validated = $request->validate([
            'nama_action_plan' => ['required', 'string', 'max:255'],
            'output' => ['nullable', 'string', 'max:255'],
            'pic_user_id' => ['nullable', 'exists:users,id'],
            'pic_pendukung' => ['nullable', 'string', 'max:255'],
            'stakeholder_eksternal' => ['nullable', 'string', 'max:255'],
            'deadline' => ['nullable', 'date'],
            'bobot' => ['required', 'numeric', 'min:0', 'max:100'],
            'progress_percent' => ['required', 'numeric', 'min:0', 'max:100'],
            'kendala' => ['nullable', 'string'],
            'dukungan_direktur' => ['nullable', 'string'],
            'update_terakhir' => ['nullable', 'string'],
            'divisi' => ['nullable', 'array'],
            'divisi.*' => ['exists:divisions,id'],
            'lampiran' => ['nullable', 'file', 'max:10240'], // maks 10MB
        ]);

        $actionPlan->update([
            'nama_action_plan' => $validated['nama_action_plan'],
            'output' => $validated['output'] ?? null,
            'pic_user_id' => $validated['pic_user_id'] ?? null,
            'pic_pendukung' => $validated['pic_pendukung'] ?? null,
            'stakeholder_eksternal' => $validated['stakeholder_eksternal'] ?? null,
            'deadline' => $validated['deadline'] ?? null,
            'bobot' => $validated['bobot'],
            'progress_percent' => $validated['progress_percent'],
            'kendala' => $validated['kendala'] ?? null,
            'dukungan_direktur' => $validated['dukungan_direktur'] ?? null,
            'update_terakhir' => $validated['update_terakhir'] ?? null,
        ]);

        $actionPlan->divisiTerkait()->sync($validated['divisi'] ?? []);

        if ($request->hasFile('lampiran')) {
            $file = $request->file('lampiran');
            $path = $file->store('attachments', 'public');

            $actionPlan->attachments()->create([
                'file_name' => $file->getClientOriginalName(),
                'file_path' => $path,
                'uploaded_by' => auth()->id(),
                'uploaded_at' => now(),
            ]);
        }

        return redirect()
            ->route('strategic-initiative.show', $strategicInitiative)
            ->with('success', 'Progress "'.$actionPlan->nama_action_plan.'" berhasil diperbarui.');
    }

    public function destroy(StrategicInitiative $strategicInitiative, ActionPlan $actionPlan)
    {
        $actionPlan->delete();

        return redirect()
            ->route('strategic-initiative.show', $strategicInitiative)
            ->with('success', 'Action Plan "'.$actionPlan->nama_action_plan.'" dipindahkan ke Trash.');
    }
}

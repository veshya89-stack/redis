<?php

namespace App\Http\Controllers;

use App\Models\ActionPlan;
use App\Models\Division;
use App\Models\Evp;
use App\Models\Meeting;
use App\Models\StrategicInitiative;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class AdministrationController extends Controller
{
    public function index()
    {
        return view('administration', [
            'evps' => Evp::withCount('strategicInitiatives')->orderBy('kode')->get(),
            'divisions' => Division::orderBy('kode')->get(),
            'meetings' => Meeting::orderByDesc('tanggal')->get(),
            'users' => User::orderBy('name')->get(),
            'trashedInitiatives' => StrategicInitiative::onlyTrashed()->orderByDesc('deleted_at')->get(),
            'trashedActionPlans' => ActionPlan::onlyTrashed()->with('strategicInitiative')->orderByDesc('deleted_at')->get(),
        ]);
    }

    public function restoreInitiative(int $id)
    {
        $initiative = StrategicInitiative::onlyTrashed()->findOrFail($id);
        $initiative->restore();
        $initiative->actionPlans()->onlyTrashed()->get()->each->restore();

        return back()->with('success', 'Strategic Initiative "'.$initiative->judul.'" berhasil dipulihkan.');
    }

    public function restoreActionPlan(int $id)
    {
        $actionPlan = ActionPlan::onlyTrashed()->findOrFail($id);
        $actionPlan->restore();

        return back()->with('success', 'Action Plan "'.$actionPlan->nama_action_plan.'" berhasil dipulihkan.');
    }

    public function forceDeleteInitiative(int $id)
    {
        $initiative = StrategicInitiative::onlyTrashed()->findOrFail($id);
        $judul = $initiative->judul;
        $initiative->actionPlans()->onlyTrashed()->get()->each->forceDelete();
        $initiative->forceDelete();

        return back()->with('success', 'Strategic Initiative "'.$judul.'" dihapus permanen.');
    }

    public function forceDeleteActionPlan(int $id)
    {
        $actionPlan = ActionPlan::onlyTrashed()->findOrFail($id);
        $nama = $actionPlan->nama_action_plan;
        $actionPlan->forceDelete();

        return back()->with('success', 'Action Plan "'.$nama.'" dihapus permanen.');
    }

    // --- EVP ---

    public function storeEvp(Request $request)
    {
        $validated = $request->validate([
            'kode' => ['required', 'string', 'max:30', 'unique:evps,kode'],
            'nama' => ['nullable', 'string', 'max:255'],
        ]);

        Evp::create($validated);

        return back()->with('success', 'EVP berhasil ditambahkan.');
    }

    public function destroyEvp(Evp $evp)
    {
        return $this->safeDelete($evp, 'EVP');
    }

    // --- Divisi ---

    public function storeDivision(Request $request)
    {
        $validated = $request->validate([
            'kode' => ['required', 'string', 'max:30', 'unique:divisions,kode'],
            'nama' => ['nullable', 'string', 'max:255'],
        ]);

        Division::create($validated);

        return back()->with('success', 'Divisi berhasil ditambahkan.');
    }

    public function destroyDivision(Division $division)
    {
        return $this->safeDelete($division, 'Divisi');
    }

    // --- Meeting ---

    public function storeMeeting(Request $request)
    {
        $validated = $request->validate([
            'judul' => ['required', 'string', 'max:255'],
            'tanggal' => ['nullable', 'date'],
        ]);

        Meeting::create($validated);

        return back()->with('success', 'Meeting berhasil ditambahkan.');
    }

    public function destroyMeeting(Meeting $meeting)
    {
        return $this->safeDelete($meeting, 'Meeting');
    }

    // --- User / PIC ---

    public function storeUser(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'role' => ['required', 'in:admin,pic,viewer'],
            'jabatan' => ['nullable', 'string', 'max:255'],
            'division_id' => ['nullable', 'exists:divisions,id'],
        ]);

        $validated['password'] = bcrypt('password');

        User::create($validated);

        return back()->with('success', 'User berhasil ditambahkan (password default: "password").');
    }

    public function destroyUser(User $user)
    {
        return $this->safeDelete($user, 'User');
    }

    /**
     * Hapus record master data dengan aman. Kalau masih dipakai di tabel lain
     * yang FK-nya restrictOnDelete (misalnya EVP masih jadi PIC sebuah
     * Strategic Initiative), tampilkan pesan yang jelas alih-alih error 500.
     */
    private function safeDelete($model, string $label)
    {
        try {
            $model->delete();

            return back()->with('success', "$label berhasil dihapus.");
        } catch (QueryException $e) {
            return back()->with('error', "$label tidak bisa dihapus karena masih dipakai di data lain.");
        }
    }
}

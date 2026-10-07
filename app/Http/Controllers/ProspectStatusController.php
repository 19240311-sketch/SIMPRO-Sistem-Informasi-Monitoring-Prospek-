<?php

namespace App\Http\Controllers;

use App\Models\ProspectStatus;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProspectStatusController extends Controller
{
    public function index(): View
    {
        $statuses = ProspectStatus::orderBy('urutan')->get();
        return view('master.statuses.index', compact('statuses'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'max:30', 'unique:status_prospek,kode'],
            'name' => ['required', 'string', 'max:50'],
            'sort_order' => ['required', 'integer', 'min:0'],
            'is_final' => ['boolean'],
        ]);

        ProspectStatus::create([
            'kode' => $validated['code'],
            'nama_status' => $validated['name'],
            'urutan' => $validated['sort_order'],
            'status_akhir' => $request->boolean('is_final'),
        ]);

        return redirect()->route('master.statuses.index')
            ->with('success', 'Status prospek baru berhasil ditambahkan!');
    }

    public function update(Request $request, ProspectStatus $status): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:50'],
            'sort_order' => ['required', 'integer', 'min:0'],
            'is_final' => ['boolean'],
        ]);

        $status->update([
            'nama_status' => $validated['name'],
            'urutan' => $validated['sort_order'],
            'status_akhir' => $request->boolean('is_final'),
        ]);

        return redirect()->route('master.statuses.index')
            ->with('success', 'Data status prospek berhasil diperbarui!');
    }
    public function destroy(ProspectStatus $status): RedirectResponse
    {
        try {
            $status->delete();
            return redirect()->route('master.statuses.index')
                ->with('success', 'Status prospek berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->route('master.statuses.index')
                ->with('error', 'Status tidak dapat dihapus karena masih terhubung dengan data prospek.');
        }
    }
}

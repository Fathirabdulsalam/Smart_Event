<?php

namespace App\Http\Controllers\Admin;

use App\Models\Event;
use Illuminate\Http\Request;
use App\Models\Advertisement;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class AdvertisementsController extends Controller
{
    public function index(Request $request)
    {
        // Eager load relasi event agar query lebih efisien (N+1 problem)
        $query = Advertisement::with('event');

        // Logic Pencarian (Berdasarkan Nama Event yang diiklankan)
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->whereHas('event', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        // Urutkan terbaru dan paginasi
        $advertisements = $query->latest()->paginate(10);

        // Ambil data events untuk dropdown di Modal Create/Edit
        // Hanya event aktif yang bisa diiklankan (opsional)
        $events = Event::where('status', 'active')->get();

        return view('admin.advertisements.index', compact('advertisements', 'events'));
    }

    /**
     * Menyimpan advertisement baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'event_id' => 'required|exists:events,id',
            // Banner wajib diisi saat create
            'banner' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048', 
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|in:active,expired,pending',
        ]);

        $data = $request->except('banner');

        // Proses Upload Gambar Banner
        if ($request->hasFile('banner')) {
            $data['banner_path'] = $request->file('banner')->store('advertisements', 'public');
        }

        Advertisement::create($data);

        return redirect()->route('advertisements.index')
            ->with('success', 'Iklan berhasil ditambahkan!');
    }

    /**
     * Mengupdate advertisement.
     */
    public function update(Request $request, $id)
    {
        $advertisement = Advertisement::findOrFail($id);

        $request->validate([
            'event_id' => 'required|exists:events,id',
            // Banner boleh kosong saat update (artinya tidak ganti gambar)
            'banner' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048', 
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|in:active,expired,pending',
        ]);

        $data = $request->except('banner');

        // Proses Ganti Gambar Banner
        if ($request->hasFile('banner')) {
            // 1. Hapus gambar lama jika ada
            if ($advertisement->banner_path && Storage::disk('public')->exists($advertisement->banner_path)) {
                Storage::disk('public')->delete($advertisement->banner_path);
            }

            // 2. Simpan gambar baru
            $data['banner_path'] = $request->file('banner')->store('advertisements', 'public');
        }

        $advertisement->update($data);

        return redirect()->route('advertisements.index')
            ->with('success', 'Iklan berhasil diperbarui!');
    }

    /**
     * Menghapus advertisement.
     */
    public function destroy($id)
    {
        $advertisement = Advertisement::findOrFail($id);

        // Hapus file fisik banner dari storage
        if ($advertisement->banner_path && Storage::disk('public')->exists($advertisement->banner_path)) {
            Storage::disk('public')->delete($advertisement->banner_path);
        }

        $advertisement->delete();

        // Menggunakan kata 'dihapus' agar memicu Modal Success di view
        return redirect()->route('advertisements.index')
            ->with('success', 'Iklan berhasil dihapus!');
    }
}

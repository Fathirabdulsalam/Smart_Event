<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\MasterTicketCategory;

class MasterTicketCategoryController extends Controller
{
    public function index()
    {
        // Mengambil data terbaru dengan pagination
        $ticketCategories = MasterTicketCategory::latest()->paginate(10);
        
        return view('admin.master.ticket_categories.index', compact('ticketCategories'));
    }

    /**
     * Menyimpan kategori tiket baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'is_paid' => 'required|boolean', // 1 (True) atau 0 (False)
        ]);

        MasterTicketCategory::create([
            'name' => $request->name,
            'is_paid' => $request->is_paid
        ]);

        return redirect()->route('master.ticket-categories.index')
            ->with('success', 'Kategori tiket berhasil ditambahkan!');
    }

    /**
     * Mengupdate data kategori tiket.
     */
    public function update(Request $request, $id)
    {
        $category = MasterTicketCategory::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'is_paid' => 'required|boolean',
        ]);

        $category->update([
            'name' => $request->name,
            'is_paid' => $request->is_paid
        ]);

        return redirect()->route('master.ticket-categories.index')
            ->with('success', 'Kategori tiket berhasil diperbarui!');
    }

    /**
     * Menghapus kategori tiket.
     */
    public function destroy($id)
    {
        $category = MasterTicketCategory::findOrFail($id);
        
        // Opsional: Cek apakah kategori sedang digunakan di event
        if ($category->events()->exists()) {
             return redirect()->route('master.ticket-categories.index')
                ->with('error', 'Kategori tidak bisa dihapus karena sedang digunakan oleh Event!');
        }

        $category->delete();

        // Kata 'dihapus' penting untuk memicu Modal Success di view
        return redirect()->route('master.ticket-categories.index')
            ->with('success', 'Kategori tiket berhasil dihapus!');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Models\MasterPage;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MasterPageController extends Controller
{
    public function index()
    {
        $pages = MasterPage::latest()->paginate(10);
        return view('admin.terms.index', compact('pages'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required',
        ]);

        MasterPage::create([
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'content' => $request->content,
            'is_active' => true
        ]);

        return back()->with('success', 'Halaman berhasil dibuat!');
    }

    public function update(Request $request, $id)
    {
        $page = MasterPage::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required',
            'is_active' => 'required|boolean'
        ]);

        $page->update([
            'title' => $request->title,
            'slug' => Str::slug($request->title), // Update slug jika judul berubah
            'content' => $request->content,
            'is_active' => $request->is_active
        ]);

        return back()->with('success', 'Halaman berhasil diperbarui!');
    }

    public function destroy($id)
    {
        MasterPage::findOrFail($id)->delete();
        return back()->with('success', 'Halaman berhasil dihapus!');
    }
}

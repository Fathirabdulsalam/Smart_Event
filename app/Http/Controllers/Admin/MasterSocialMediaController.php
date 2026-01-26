<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\MasterSocialMedia;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class MasterSocialMediaController extends Controller
{
    public function index()
    {
        $sosmeds = MasterSocialMedia::latest()->paginate(10);
        return view('admin.master.social_medias.index', compact('sosmeds'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'platform' => 'required|string',
            'link_url' => 'required|url',
        ]);

        MasterSocialMedia::create($request->all());

        return back()->with('success', 'Link Social Media berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $sosmed = MasterSocialMedia::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string',
            'platform' => 'required|string',
            'link_url' => 'required|url',
        ]);

        $sosmed->update($request->all());

        return back()->with('success', 'Link Social Media berhasil diperbarui!');
    }

    public function destroy($id)
    {
        MasterSocialMedia::findOrFail($id)->delete();
        return back()->with('success', 'Link Social Media dihapus!');
    }
}

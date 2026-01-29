<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\MasterLocation;
use App\Http\Controllers\Controller;

class MasterLocationController extends Controller
{
    public function index()
    {
        $locations = MasterLocation::latest()->paginate(10);
        return view('admin.master.locations.index', compact('locations'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'type' => 'required|in:Fisik,Virtual'
        ]);
        MasterLocation::create($request->all());
        return back()->with('success', 'Lokasi Berhasil Dibuat!');
    }

    public function update(Request $request, $id)
    {
        $loc = MasterLocation::findOrFail($id);
        $request->validate([
            'name' => 'required|string',
            'type' => 'required|in:Fisik,Virtual'
        ]);
        $loc->update($request->all());
        return back()->with('success', 'Lokasi Berhasil Di Perbarui!');
    }

    public function destroy($id)
    {
        MasterLocation::findOrFail($id)->delete();
        return back()->with('success', 'Lokasi Berhasil Dihapus!');
    }
}

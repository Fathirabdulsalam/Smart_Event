<?php

namespace App\Http\Controllers\Admin;

use App\Models\MasterZone;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MasterZoneController extends Controller
{
    public function index()
    {
        $zones = MasterZone::latest()->paginate(10);
        return view('admin.master.zones.index', compact('zones'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'gmt_offset' => 'required|string' // e.g., +07:00
        ]);
        MasterZone::create($request->all());
        return back()->with('success', 'Zone created successfully!');
    }

    public function update(Request $request, $id)
    {
        $zone = MasterZone::findOrFail($id);
        $request->validate([
            'name' => 'required|string',
            'gmt_offset' => 'required|string'
        ]);
        $zone->update($request->all());
        return back()->with('success', 'Zone updated successfully!');
    }

    public function destroy($id)
    {
        MasterZone::findOrFail($id)->delete();
        return back()->with('success', 'Zone deleted successfully!');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MasterEventKind;
use Illuminate\Http\Request;

class MasterEventKindController extends Controller
{
    public function index()
    {
        $eventKinds = MasterEventKind::latest()->paginate(10);
        return view('admin.master.event_kinds.index', compact('eventKinds'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            ]);
        MasterEventKind::create($request->all());
        return back()->with('success', 'Data saved successfully!');
    }

    public function update(Request $request, $id)
    {
        $type = MasterEventKind::findOrFail($id);
        $request->validate([
            'name' => 'required|string|max:255',
            ]);
        $type->update($request->all());
        return back()->with('success', 'Data updated successfully!');
    }

    public function destroy($id)
    {
        MasterEventKind::findOrFail($id)->delete();
        return back()->with('success', 'Data deleted successfully!');
    }
}

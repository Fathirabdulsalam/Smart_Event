<?php

namespace App\Http\Controllers\Admin;

use App\Models\MasterType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MasterTypeController extends Controller
{
     public function index()
    {
        $types = MasterType::latest()->paginate(10);
        // Pastikan Anda sudah membuat view di resources/views/admin/master/types/index.blade.php
        return view('admin.master.types.index', compact('types'));
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);
        MasterType::create($request->all());
        return back()->with('success', 'Data saved successfully!');
    }

    public function update(Request $request, $id)
    {
        $type = MasterType::findOrFail($id);
        $request->validate(['name' => 'required|string|max:255']);
        $type->update($request->all());
        return back()->with('success', 'Data updated successfully!');
    }

    public function destroy($id)
    {
        MasterType::findOrFail($id)->delete();
        return back()->with('success', 'Data deleted successfully!');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Models\MasterSlide;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class MasterSlideController extends Controller
{
    public function index()
    {
        $slides = MasterSlide::orderBy('order', 'asc')->paginate(10);
        return view('admin.master.slides.index', compact('slides'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'description' => 'nullable|string',
            'image' => 'required|image|max:2048',
            'order' => 'integer',
            'is_active' => 'boolean'
        ]);

        $path = $request->file('image')->store('slides', 'public');

        MasterSlide::create([
            'title' => $request->title,
            'description' => $request->description,
            'link_url' => $request->link_url,
            'image_path' => $path,
            'order' => $request->order ?? 0,
            'is_active' => $request->is_active
        ]);

        return back()->with('success', 'Slide added successfully!');
    }

    public function update(Request $request, $id)
    {
        $slide = MasterSlide::findOrFail($id);
        
        $request->validate([
            'title' => 'required|string',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048', // Nullable on update
        ]);

        $data = $request->except('image');

        if ($request->hasFile('image')) {
            if ($slide->image_path) Storage::disk('public')->delete($slide->image_path);
            $data['image_path'] = $request->file('image')->store('slides', 'public');
        }

        $slide->update($data);

        return back()->with('success', 'Slide updated successfully!');
    }

    public function destroy($id)
    {
        $slide = MasterSlide::findOrFail($id);
        if ($slide->image_path) Storage::disk('public')->delete($slide->image_path);
        $slide->delete();
        return back()->with('success', 'Slide deleted successfully!');
    }
}

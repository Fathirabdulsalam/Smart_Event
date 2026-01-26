<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Categories;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AuthorsController extends Controller
{
    public function index(Request $request)
    {
        // Ambil User yang role-nya 'author'
        $query = User::where('role', 'author')->with('category');

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $authors = $query->latest()->paginate(10);
        $categories = Categories::all();

        return view('admin.authors.index', compact('authors', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|string|unique:users,username',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'category_id' => 'required|exists:categories,id',
            'address' => 'required|string',
            'photo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'ktp' => 'required|file|mimes:jpeg,png,pdf|max:2048',
            'npwp' => 'required|file|mimes:jpeg,png,pdf|max:2048',
        ]);

        $data = $request->except(['photo', 'ktp', 'npwp', 'password']);
        $data['password'] = Hash::make($request->password);
        $data['role'] = 'author';

        // Handle File Uploads
        if ($request->hasFile('photo')) {
            $data['photo_path'] = $request->file('photo')->store('authors/photos', 'public');
        }
        if ($request->hasFile('ktp')) {
            $data['ktp_path'] = $request->file('ktp')->store('authors/ktp', 'public');
        }
        if ($request->hasFile('npwp')) {
            $data['npwp_path'] = $request->file('npwp')->store('authors/npwp', 'public');
        }

        User::create($data);

        return redirect()->route('authors.index')->with('success', 'Author berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $author = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'address' => 'required|string',
            'photo' => 'nullable|image|max:2048',
            'ktp' => 'nullable|file|max:2048',
            'npwp' => 'nullable|file|max:2048',
        ]);

        $data = $request->only(['name', 'category_id', 'address']);

        // Handle Files Update
        if ($request->hasFile('photo')) {
            if ($author->photo_path) Storage::disk('public')->delete($author->photo_path);
            $data['photo_path'] = $request->file('photo')->store('authors/photos', 'public');
        }
        if ($request->hasFile('ktp')) {
            if ($author->ktp_path) Storage::disk('public')->delete($author->ktp_path);
            $data['ktp_path'] = $request->file('ktp')->store('authors/ktp', 'public');
        }
        if ($request->hasFile('npwp')) {
            if ($author->npwp_path) Storage::disk('public')->delete($author->npwp_path);
            $data['npwp_path'] = $request->file('npwp')->store('authors/npwp', 'public');
        }

        $author->update($data);

        return redirect()->route('authors.index')->with('success', 'Data Author berhasil diupdate!');
    }

    public function destroy($id)
    {
        $author = User::findOrFail($id);

        // Delete Files
        if ($author->photo_path) Storage::disk('public')->delete($author->photo_path);
        if ($author->ktp_path) Storage::disk('public')->delete($author->ktp_path);
        if ($author->npwp_path) Storage::disk('public')->delete($author->npwp_path);

        $author->delete();

        return redirect()->route('authors.index')->with('success', 'Data Author berhasil dihapus!');
    }
}

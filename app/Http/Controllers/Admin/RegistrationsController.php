<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Categories;
use App\Models\Registration;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RegistrationsController extends Controller
{
   public function index(Request $request)
    {
        $query = Registration::query();
        $query->with(['user', 'category']);

        // Search Logic
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $query->latest();
        $registrations = $query->paginate(10);

        $users = User::where('role', 'author')->get(); 
        $categories = Categories::all();

        return view('admin.registrations.index', compact('registrations', 'users', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id|unique:registrations,user_id', 
            'category_id' => 'required|exists:categories,id',
            'status' => 'required|in:active,pending,rejected'
        ]);

        Registration::create($request->all());

        return redirect()->route('registrations.index')
            ->with('success', 'Author berhasil ditambahkan!');
    }

    public function update(Request $request, Registration $registration)
    {
        $request->validate([
            // User ID boleh sama kalau itu punya dia sendiri (ignore id saat ini)
            'user_id' => 'required|exists:users,id|unique:registrations,user_id,' . $registration->id,
            'category_id' => 'required|exists:categories,id',
            'status' => 'required|in:active,pending,rejected'
        ]);

        $registration->update($request->all());

        return redirect()->route('registrations.index')
            ->with('success', 'Data Author berhasil diupdate!');
    }

    public function destroy(Registration $registration)
    {
        $registration->delete();

        // Kata 'dihapus' penting untuk memicu Modal Success Delete di JS
        return redirect()->route('registrations.index')
            ->with('success', 'Data Author berhasil dihapus!'); 
    }
}

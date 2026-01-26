<?php

namespace App\Http\Controllers\User;

use App\Models\Article;
use App\Models\Categories;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ArticleController extends Controller
{
    public function index()
    {
        $articles = Article::with('category') // Eager load category
            ->where('author_id', Auth::id())
            ->latest()
            ->paginate(10);

        // Ambil semua kategori untuk Dropdown di Modal
        $categories = Categories::all(); 
        
        return view('users.articles.index', compact('articles', 'categories'));
    }

    public function create()
    {
        return view('users.dashboard.create_article');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id', // Validasi Kategori
            'content' => 'required',
            'thumbnail' => 'required|image|max:2048',
            'status' => 'required|in:draft,published'
        ]);

        $path = $request->file('thumbnail')->store('articles', 'public');

        Article::create([
            'title' => $request->title,
            'slug' => Str::slug($request->title) . '-' . time(),
            'category_id' => $request->category_id, // Simpan ID
            'content' => $request->content,
            'thumbnail_path' => $path,
            'author_id' => Auth::id(),
            'status' => $request->status
        ]);

        return redirect()->route('user.articles.index')->with('success', 'Article created!');
    }

    public function update(Request $request, $id)
    {
        $article = Article::where('id', $id)->where('author_id', Auth::id())->firstOrFail();
        
        $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id', // Validasi Kategori
            'content' => 'required',
            'thumbnail' => 'nullable|image|max:2048',
            'status' => 'required|in:draft,published'
        ]);

        $data = [
            'title' => $request->title,
            'slug' => Str::slug($request->title) . '-' . time(),
            'category_id' => $request->category_id, // Update ID
            'content' => $request->content,
            'status' => $request->status
        ];

        if ($request->hasFile('thumbnail')) {
            if ($article->thumbnail_path) Storage::disk('public')->delete($article->thumbnail_path);
            $data['thumbnail_path'] = $request->file('thumbnail')->store('articles', 'public');
        }

        $article->update($data);

        return redirect()->route('user.articles.index')->with('success', 'Article updated!');
    }

    public function destroy($id)
    {
        $article = Article::where('id', $id)->where('author_id', Auth::id())->firstOrFail();
        if ($article->thumbnail_path) Storage::disk('public')->delete($article->thumbnail_path);
        $article->delete();

        return redirect()->route('user.articles.index')->with('success', 'Article deleted!');
    }
}

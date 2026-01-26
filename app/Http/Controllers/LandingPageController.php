<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use App\Models\User;
use App\Models\Event;
use App\Models\Article;
use App\Models\Categories;
use App\Models\MasterPage;
use Illuminate\Http\Request;
use App\Models\Advertisement;
use App\Models\MasterLocation;
use App\Http\Controllers\Controller;
use App\Models\MasterSlide; // Import Model Slide

class LandingPageController extends Controller
{
    public function index(Request $request)
    {
        // 1. Banner Slider
        $slides = MasterSlide::where('is_active', true)
            ->orderBy('order', 'asc')
            ->get();

        // 2. Kategori
        $categories = Categories::withCount([
            'events' => function ($q) {
                $q->where('status', 'active');
            }
        ])->orderBy('events_count', 'desc')->take(10)->get();

        // 3. Iklan / Advertisement (INI YANG KEMARIN HILANG)
        $activeAd = Advertisement::where('status', 'active')
            ->whereDate('start_date', '<=', now())
            ->whereDate('end_date', '>=', now())
            ->with('event')
            ->latest()
            ->first();

        // Query Dasar Event Aktif
        $query = Event::with(['category', 'author', 'eventKind', 'type', 'location'])
            ->where('status', 'active');

        // Logic Search (Jika ada input search)
        if ($request->has('search') && $request->search != '') {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // 4. Featured Events (Acak)
        $featuredEvents = (clone $query)->inRandomOrder()->take(6)->get();

        // 5. Latest Events (Terbaru)
        $latestEvents = (clone $query)->latest()->take(5)->get();

        // 6. Kreator Favorit
        $favoriteCreators = User::withCount([
            'events' => function ($q) {
                // Opsional: Hanya hitung event yang statusnya 'active' agar lebih relevan
                $q->where('status', 'active');
            }
        ])
            ->having('events_count', '>', 0) 
            ->orderBy('events_count', 'desc') 
            ->take(10) // Tampilkan 10 teratas
            ->get();

        // 7. Workshop Events
        $workshopEvents = (clone $query)->where(function ($q) {
            $q->whereHas('category', function ($c) {
                $c->where('name', 'like', '%Workshop%');
            })->orWhereHas('eventKind', function ($k) {
                $k->where('name', 'like', '%Workshop%');
            });
        })->latest()->take(4)->get();

        $allLocations = MasterLocation::orderBy('name', 'asc')->get();

        $locationName = $request->input('location', 'Jakarta'); // Bisa diganti dinamis nanti

        $popularLocationEvents = Event::with(['category', 'author', 'location'])
            ->where('status', 'active')
            ->whereHas('location', function ($q) use ($locationName) {
                $q->where('name', 'like', '%' . $locationName . '%');
            })
            ->latest()
            ->take(8)
            ->get();

        return view('landing-page', compact(
            'slides',
            'categories',
            'activeAd',
            'featuredEvents',
            'latestEvents',
            'favoriteCreators',
            'workshopEvents',
            'popularLocationEvents', // <--- Tambahkan variable ini
            'locationName',
            'allLocations'
        ));
    }

    public function showCreator($id)
    {
        // 1. Ambil Data Creator (User dengan role author)
        $creator = User::where('id', $id)
            ->whereIn('role', ['author', 'admin', 'creator', 'user']) // Antisipasi berbagai role
            ->withCount('events') // Hitung total event
            ->firstOrFail();

        // 2. Event Akan Datang (Active & Tanggal >= Hari ini)
        $upcomingEvents = Event::where('author_id', $id)
            ->where('status', 'active')
            ->whereDate('date', '>=', now())
            ->with('category')
            ->orderBy('date', 'asc') // Urutkan dari yang terdekat
            ->get();

        // 3. Event Lewat (Past Events)
        $pastEvents = Event::where('author_id', $id)
            ->where(function ($q) {
                $q->where('status', '!=', 'active')
                    ->orWhereDate('date', '<', now());
            })
            ->with('category')
            ->latest() // Urutkan dari yang baru selesai
            ->get();

        return view('users.creator.show', compact('creator', 'upcomingEvents', 'pastEvents'));
    }

    public function allEvents(Request $request)
    {
        // Query Dasar (Hanya event active)
        $query = Event::with(['category', 'author'])->where('status', 'active');

        // Filter Search
        if ($request->has('search') && $request->search != '') {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Filter Kategori
        if ($request->has('category') && $request->category != '') {
            $query->where('category_id', $request->category);
        }

        // Sorting (Urutan)
        if ($request->has('sort')) {
            switch ($request->sort) {
                case 'oldest':
                    $query->oldest();
                    break;
                case 'price_low':
                    $query->orderBy('price', 'asc');
                    break;
                case 'price_high':
                    $query->orderBy('price', 'desc');
                    break;
                default:
                    $query->latest();
                    break;
            }
        } else {
            $query->latest();
        }

        // Pagination
        $events = $query->paginate(12)->withQueryString();

        // Ambil data Kategori untuk Sidebar Filter
        $categories = Categories::withCount([
            'events' => function ($q) {
                $q->where('status', 'active');
            }
        ])->get();

        return view('users.event.events', compact('events', 'categories'));
    }

    public function show($id)
    {
        // Ambil data event beserta relasinya
        // Relasi master data lain (lokasi, tiket, dll) bisa ditambahkan di with([]) jika diperlukan di detail
        $event = Event::with(['author', 'category', 'location'])->findOrFail($id);

        // Ambil event terkait (kategori sama, kecuali event ini sendiri)
        $relatedEvents = Event::where('category_id', $event->category_id)
            ->where('id', '!=', $id)
            ->where('status', 'active')
            ->with(['author', 'category']) // Load relasi juga untuk card event terkait
            ->take(4)
            ->get();

        return view('users.event.event-detail', compact('event', 'relatedEvents'));
    }

    public function allArticles(Request $request)
    {
        $query = Article::where('status', 'published')->with(['author', 'category']);

        // Filter Search
        if ($request->has('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        // Filter Kategori (NEW)
        if ($request->has('category') && $request->category != '') {
            $query->where('category_id', $request->category);
        }

        $articles = $query->latest()->paginate(9)->withQueryString();

        // Ambil kategori yang memiliki artikel (untuk dropdown/filter)
        $categories = Categories::whereHas('articles')->get();

        // Ambil Recent untuk sidebar
        $recentArticles = Article::where('status', 'published')->latest()->take(5)->get();

        return view('users.articles.all-article', compact('articles', 'categories', 'recentArticles'));
    }

    public function showArticle($slug)
    {
        // 1. Cari artikel berdasarkan Slug dan status Published
        $article = Article::where('slug', $slug)
            ->where('status', 'published')
            ->with('author') // Eager load author
            ->firstOrFail(); // 404 jika tidak ketemu

        // 2. Ambil artikel terkait (Related Articles) untuk Sidebar
        $relatedArticles = Article::where('status', 'published')
            ->where('id', '!=', $article->id) // Jangan tampilkan artikel yang sedang dibaca
            ->latest()
            ->take(4)
            ->get();

        // 3. Return view detail (pastikan path view sesuai)
        // Kita gunakan view yang sama dengan detail user karena layoutnya sama (layouts.user)
        return view('users.articles.show', compact('article', 'relatedArticles'));
    }

    public function faq()
    {
        // Ambil FAQ aktif dan kelompokkan berdasarkan kategori
        $faqs = Faq::where('is_active', true)->get()->groupBy('category');

        return view('users.faq.index', compact('faqs'));
    }

    public function showPage($slug)
    {
        $page = MasterPage::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        return view('user.page_detail', compact('page'));
    }

    public function terms()
    {
        $buyerTerms   = MasterPage::where('slug', 'terms-buyer')->where('is_active', true)->first();
        $creatorTerms = MasterPage::where('slug', 'terms-creator')->where('is_active', true)->first();

        return view('users.terms.index', compact('buyerTerms', 'creatorTerms'));
    }
}
@extends('layouts.user')

@section('content')
    <!-- Header -->
    <div class="bg-dark-navy py-12">
        <div class="container mx-auto px-4 text-center">
            <h1 class="text-3xl md:text-4xl font-bold text-white mb-2">Artikel & Berita</h1>
            <p class="text-blue-200">Tips, wawasan, dan berita terbaru seputar dunia event.</p>
        </div>
    </div>

    <!-- Content -->
    <div class="container mx-auto px-4 py-12">
        
        <!-- Search -->
        <div class="max-w-xl mx-auto mb-10">
            <form action="{{ route('user.articles.index') }}" method="GET" class="relative">
                <input type="text" name="search" value="{{ request('search') }}" 
                    class="block w-full pl-5 pr-12 py-3 rounded-full border border-gray-300 focus:ring-[#4838CC] focus:border-[#4838CC] outline-none shadow-sm"
                    placeholder="Cari artikel...">
                <button type="submit" class="absolute right-2 top-1/2 -translate-y-1/2 bg-[#4838CC] text-white p-2 rounded-full hover:bg-[#3b2db0] transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </button>
            </form>
        </div>

        <!-- Grid Articles -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($articles as $article)
                <a href="{{ route('articles.user.show', $article->slug) }}" class="group flex flex-col bg-white rounded-xl shadow-sm hover:shadow-lg border border-gray-100 overflow-hidden transition-all duration-300">
                    <div class="h-48 overflow-hidden bg-gray-200">
                        @if($article->thumbnail_path)
                            <img src="{{ Storage::url($article->thumbnail_path) }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-gray-400">No Image</div>
                        @endif
                    </div>
                    <div class="p-6 flex flex-col flex-grow">
                        <div class="flex items-center gap-2 text-xs text-gray-500 mb-3">
                            <span>{{ $article->created_at->format('d M Y') }}</span>
                            <span>•</span>
                            <span>{{ $article->author->name ?? 'Admin' }}</span>
                        </div>
                        <h2 class="text-xl font-bold text-gray-800 mb-3 line-clamp-2 group-hover:text-[#4838CC] transition">
                            {{ $article->title }}
                        </h2>
                        <p class="text-gray-600 text-sm line-clamp-3 mb-4">
                            {{ Str::limit(strip_tags($article->content), 100) }}
                        </p>
                        <div class="mt-auto text-[#4838CC] font-semibold text-sm flex items-center gap-1 group-hover:gap-2 transition-all">
                            Baca Selengkapnya 
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                        </div>
                    </div>
                </a>
            @empty
                <div class="col-span-full text-center py-12">
                    <div class="text-gray-300 mb-2">
                        <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
                    </div>
                    <p class="text-gray-500 font-medium">Belum ada artikel yang tersedia.</p>
                </div>
            @endforelse
        </div>

        <div class="mt-10">
            {{ $articles->links() }}
        </div>
    </div>
@endsection
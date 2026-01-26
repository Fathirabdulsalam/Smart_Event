@extends('layouts.user')

@section('content')

    <!-- HEADER BACKGROUND -->
    <div class="bg-[#4838CC] py-16 relative overflow-hidden">
        <!-- Dekorasi Background -->
        <div class="absolute top-0 left-0 w-64 h-64 bg-white opacity-5 rounded-full -translate-x-1/2 -translate-y-1/2 blur-3xl"></div>
        <div class="absolute bottom-0 right-0 w-96 h-96 bg-indigo-500 opacity-20 rounded-full translate-x-1/2 translate-y-1/2 blur-3xl"></div>

        <div class="container mx-auto px-4 text-center relative z-10">
            <h1 class="text-3xl md:text-5xl font-bold text-white mb-4">Artikel & Wawasan</h1>
            <p class="text-indigo-200 text-lg max-w-2xl mx-auto">Temukan berita terbaru, tips event, dan cerita inspiratif dari komunitas kami.</p>
            
            <!-- Search Bar & Filter -->
            <div class="max-w-2xl mx-auto mt-8 bg-white p-2 rounded-full shadow-xl flex flex-col md:flex-row gap-2">
                
                <!-- Category Filter -->
                <div class="relative min-w-[150px]">
                    <select onchange="location = this.value;" class="w-full h-full bg-gray-50 border-none text-gray-700 text-sm font-semibold rounded-full px-4 py-3 focus:ring-0 cursor-pointer hover:bg-gray-100 transition outline-none appearance-none">
                        <option value="{{ route('articles.all') }}">Semua Kategori</option>
                        @foreach($categories as $cat)
                            <option value="{{ route('articles.all', ['category' => $cat->id]) }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                    <!-- Custom Arrow Icon -->
                    <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none text-gray-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </div>
                </div>

                <!-- Search Input -->
                <form action="{{ route('articles.all') }}" method="GET" class="relative flex-grow flex items-center">
                    @if(request('category')) <input type="hidden" name="category" value="{{ request('category') }}"> @endif
                    <input type="text" name="search" value="{{ request('search') }}" 
                        class="w-full pl-5 pr-12 py-3 text-sm text-gray-900 border-none focus:ring-0 focus:outline-none placeholder-gray-400 bg-transparent" 
                        placeholder="Cari artikel menarik...">
                    <button type="submit" class="bg-[#4838CC] text-white p-2.5 rounded-full hover:bg-[#3b2db0] transition mr-1 shadow-md">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </button>
                </form>

            </div>
        </div>
    </div>

    <!-- MAIN CONTENT -->
    <div class="container mx-auto px-4 py-12">
        <div class="flex flex-col lg:flex-row gap-10">
            
            <!-- LEFT: ARTICLE GRID -->
            <div class="w-full lg:w-2/3">
                
                @if(request('search'))
                    <p class="text-gray-600 mb-6">Menampilkan hasil pencarian untuk: <span class="font-bold text-gray-900">"{{ request('search') }}"</span></p>
                @endif
                @if(request('category'))
                    @php $catName = $categories->where('id', request('category'))->first()->name ?? 'Unknown'; @endphp
                    <div class="flex items-center gap-2 mb-6">
                        <span class="text-gray-600">Kategori:</span>
                        <span class="bg-indigo-100 text-[#4838CC] px-3 py-1 rounded-full text-sm font-bold flex items-center gap-1">
                            {{ $catName }}
                            <a href="{{ route('articles.all') }}" class="hover:text-red-500 ml-1"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></a>
                        </span>
                    </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    @forelse($articles as $article)
                        <a href="{{ route('articles.show', $article->slug) }}" class="group flex flex-col bg-white rounded-2xl shadow-sm hover:shadow-xl border border-gray-100 overflow-hidden transition-all duration-300 transform hover:-translate-y-1">
                            <!-- Image -->
                            <div class="h-56 overflow-hidden bg-gray-200 relative">
                                @if($article->thumbnail_path)
                                    <img src="{{ Storage::url($article->thumbnail_path) }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-700 ease-in-out">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-400 bg-gray-100">
                                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    </div>
                                @endif
                                <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent opacity-60"></div>
                                
                                <!-- KATEGORI BADGE (NEW) -->
                                <div class="absolute bottom-4 left-4 flex gap-2">
                                    <span class="text-white text-[10px] font-bold bg-[#4838CC] px-2.5 py-1 rounded shadow-sm uppercase tracking-wide">
                                        ARTICLE
                                    </span>
                                    @if($article->category)
                                        <span class="text-[#4838CC] text-[10px] font-bold bg-white px-2.5 py-1 rounded shadow-sm uppercase tracking-wide">
                                            {{ $article->category->name }}
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <!-- Content -->
                            <div class="p-6 flex flex-col flex-grow">
                                <div class="flex items-center gap-2 text-xs text-gray-500 mb-3">
                                    <span class="flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        {{ $article->created_at->format('d M Y') }}
                                    </span>
                                    <span>•</span>
                                    <span class="text-[#4838CC] font-semibold">{{ $article->author->name ?? 'Admin' }}</span>
                                </div>
                                
                                <h2 class="text-xl font-bold text-gray-900 mb-3 line-clamp-2 group-hover:text-[#4838CC] transition">
                                    {{ $article->title }}
                                </h2>
                                
                                <p class="text-gray-600 text-sm line-clamp-3 mb-4 leading-relaxed">
                                    {{ Str::limit(strip_tags($article->content), 120) }}
                                </p>
                                
                                <div class="mt-auto pt-4 border-t border-gray-50 flex justify-between items-center">
                                    <span class="text-sm font-semibold text-[#4838CC] group-hover:underline">Baca Selengkapnya</span>
                                    <svg class="w-5 h-5 text-[#4838CC] transform group-hover:translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4 4H3"></path></svg>
                                </div>
                            </div>
                        </a>
                    @empty
                        <div class="col-span-full py-16 text-center bg-gray-50 rounded-2xl border border-dashed border-gray-300">
                            <div class="inline-block p-4 rounded-full bg-indigo-50 text-[#4838CC] mb-4">
                                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
                            </div>
                            <h3 class="text-lg font-bold text-gray-800">Artikel tidak ditemukan</h3>
                            <p class="text-gray-500 text-sm mt-1">Coba kata kunci lain atau pilih kategori berbeda.</p>
                            <a href="{{ route('articles.all') }}" class="inline-block mt-4 text-[#4838CC] font-semibold hover:underline">Reset Pencarian</a>
                        </div>
                    @endforelse
                </div>

                <div class="mt-12">
                    {{ $articles->links() }}
                </div>
            </div>

            <!-- RIGHT: SIDEBAR (RECENT POSTS & CTA) -->
            <!-- RIGHT: SIDEBAR (RECENT POSTS & CTA) -->
            <aside class="w-full lg:w-1/3">
                
                <!-- 
                    PERBAIKAN: 
                    Tambahkan Wrapper "sticky" di sini agar semua widget di sidebar 
                    ikut menempel bersamaan dan tidak saling menabrak.
                -->
                <div class="sticky top-28 space-y-8">
                    
                    <!-- Widget 1: Recent Articles -->
                    <!-- Hapus class 'sticky top-24' dari div ini karena sudah dipindah ke wrapper induk -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <h3 class="text-lg font-bold text-gray-800 mb-6 pb-2 border-b border-gray-100">Artikel Terbaru</h3>
                        <div class="space-y-6">
                            @foreach($recentArticles as $recent)
                                <a href="{{ route('articles.show', $recent->slug) }}" class="flex gap-4 group">
                                    <div class="w-20 h-20 bg-gray-200 rounded-lg overflow-hidden flex-shrink-0 relative">
                                        @if($recent->thumbnail_path)
                                            <img src="{{ Storage::url($recent->thumbnail_path) }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-300">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-xs text-gray-400">IMG</div>
                                        @endif
                                        
                                        <!-- Small Category Badge -->
                                        <div class="absolute bottom-0 left-0 right-0 bg-black/60 text-white text-[8px] text-center py-0.5">
                                            {{ $recent->category->name ?? 'Article' }}
                                        </div>
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-bold text-gray-900 line-clamp-2 group-hover:text-[#4838CC] transition mb-1">{{ $recent->title }}</h4>
                                        <span class="text-xs text-gray-500 flex items-center gap-1">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            {{ $recent->created_at->diffForHumans() }}
                                        </span>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>

                    <!-- Widget 2: CTA -->
                    <div class="bg-gradient-to-br from-[#4838CC] to-[#6C5DD3] rounded-2xl p-8 text-center text-white shadow-lg relative overflow-hidden">
                        <div class="relative z-10">
                            <h3 class="text-xl font-bold mb-2">Punya Cerita Menarik?</h3>
                            <p class="text-indigo-100 text-sm mb-6">Bagikan pengalaman atau tips eventmu kepada ribuan pembaca.</p>
                            
                            <!-- Cek Login untuk tombol -->
                            <a href="{{ Auth::check() ? route('user.article.create') : route('login') }}" class="inline-block bg-white text-[#4838CC] px-6 py-2.5 rounded-full text-sm font-bold shadow-md hover:bg-gray-50 transition transform hover:-translate-y-0.5">
                                Tulis Artikel
                            </a>
                        </div>
                        <!-- Decor -->
                        <div class="absolute top-0 right-0 w-32 h-32 bg-white opacity-10 rounded-full translate-x-1/2 -translate-y-1/2"></div>
                    </div>

                </div> <!-- End Sticky Wrapper -->

            </aside>

        </div>
    </div>
@endsection
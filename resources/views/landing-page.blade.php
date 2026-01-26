@extends('layouts.user')

@section('content')

    <style>
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .no-scrollbar {
            -ms-overflow-style: none;
            /* IE and Edge */
            scrollbar-width: none;
            /* Firefox */
        }
    </style>

    <!-- HERO SECTION (Carousel) -->
    <!-- HERO SECTION (Carousel) -->
    <section class="bg-white py-8 relative">
        <div class="container mx-auto px-4 relative">
            <!-- Banner Container -->
            <!-- Note: Tinggi saya atur agak besar sedikit agar teks deskripsi muat -->
            <div class="bg-gray-200 rounded-2xl h-[250px] md:h-[400px] w-full relative overflow-hidden shadow-lg group">

                @if ($slides->count() > 0)
                    <!-- Slides Container -->
                    <div id="slider-container" class="w-full h-full relative">
                        @foreach ($slides as $index => $slide)
                            <div class="slide absolute inset-0 w-full h-full transition-opacity duration-700 ease-in-out {{ $index === 0 ? 'opacity-100 z-10' : 'opacity-0 z-0' }}"
                                data-index="{{ $index }}">

                                <!-- Link Wrapper -->
                                @if ($slide->link_url)
                                    <a href="{{ $slide->link_url }}"
                                        class="block w-full h-full relative cursor-pointer group">
                                    @else
                                        <div class="block w-full h-full relative group">
                                @endif

                                <!-- Image -->
                                @if ($slide->image_path)
                                    <img src="{{ Storage::url($slide->image_path) }}"
                                        class="w-full h-full object-cover object-center transform group-hover:scale-105 transition duration-700"
                                        alt="{{ $slide->title }}">
                                @else
                                    <div class="w-full h-full flex items-center justify-center bg-gray-300">
                                        <span class="text-gray-500 font-bold text-xl">{{ $slide->title }}</span>
                                    </div>
                                @endif

                                <!-- OVERLAY TEXT (Gradient Background agar teks terbaca) -->
                                <div
                                    class="absolute bottom-0 left-0 w-full bg-gradient-to-t from-black via-black/60 to-transparent p-6 md:p-12 pt-20 flex flex-col justify-end">
                                    <div class="max-w-3xl">
                                        <!-- Judul -->
                                        <h2
                                            class="text-2xl md:text-4xl font-bold text-white mb-2 drop-shadow-md leading-tight">
                                            {{ $slide->title }}
                                        </h2>

                                        @if ($slide->description)
                                            <p class="text-white/90 text-sm md:text-lg font-medium drop-shadow-sm">
                                                {{ \Illuminate\Support\Str::limit($slide->description, 80, '...') }}
                                            </p>
                                        @endif
                                    </div>
                                </div>

                                @if ($slide->link_url)
                                    </a>
                                @else
                            </div>
                        @endif

                    </div>
                @endforeach
            </div>

            <!-- Navigation Arrows -->
            <button onclick="moveSlide(-1)"
                class="absolute left-4 top-1/2 -translate-y-1/2 bg-white/20 hover:bg-white/90 backdrop-blur-md w-10 h-10 md:w-12 md:h-12 rounded-full flex items-center justify-center text-white hover:text-[#4838CC] z-20 transition shadow-lg border border-white/30">
                <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </button>
            <button onclick="moveSlide(1)"
                class="absolute right-4 top-1/2 -translate-y-1/2 bg-white/20 hover:bg-white/90 backdrop-blur-md w-10 h-10 md:w-12 md:h-12 rounded-full flex items-center justify-center text-white hover:text-[#4838CC] z-20 transition shadow-lg border border-white/30">
                <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>

            <!-- Dots Indicators -->
            <div class="absolute bottom-4 right-6 flex justify-center gap-2 z-20">
                @foreach ($slides as $index => $slide)
                    <button onclick="goToSlide({{ $index }})"
                        class="dot h-1.5 md:h-2 rounded-full transition-all duration-300 {{ $index === 0 ? 'bg-[#4838CC] w-6 md:w-8' : 'bg-white/60 w-1.5 md:w-2 hover:bg-white' }}"
                        data-index="{{ $index }}"></button>
                @endforeach
            </div>
        @else
            <div class="w-full h-full flex items-center justify-center bg-gray-200">
                <div class="text-center">
                    <h2 class="text-xl md:text-2xl font-bold text-gray-400">No Banner Available</h2>
                </div>
            </div>
            @endif
        </div>
        </div>
    </section>

    <!-- SECTION: ACARA UNGGULAN (Slider Mode) -->
    <section class="py-8 bg-gray-50 relative group">
        <div class="container mx-auto px-4">

            <!-- Header -->
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Acara Unggulan</h2>
                    <p class="text-gray-500 text-sm mt-1">Acara-acara pilihan khusus untuk Anda.</p>
                </div>
                <a href="{{ route('allEvents') }}" class="text-[#4838CC] font-semibold text-sm hover:underline">Lihat
                    Semua</a>
            </div>

            <!-- Slider Wrapper -->
            <div class="relative">

                <!-- Tombol Left -->
                <button id="btnLeftFeatured" onclick="scrollFeatured(-1)"
                    class="hidden md:flex absolute -left-4 top-1/2 -translate-y-1/2 z-10 w-12 h-12 bg-white rounded-full shadow-lg items-center justify-center text-gray-700 hover:text-[#4838CC] hover:scale-110 transition duration-300 opacity-0 group-hover:opacity-100 focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </button>

                <!-- Container Card -->
                <div id="scrollContainerFeatured" class="flex gap-6 overflow-x-auto scroll-smooth no-scrollbar py-4 px-1">

                    @foreach ($featuredEvents as $event)
                        <div class="flex-shrink-0 w-72 md:w-80 snap-start">
                            <a href="{{ route('event.detail', $event->id) }}"
                                class="block group/card bg-white rounded-xl shadow-sm hover:shadow-xl border border-gray-100 overflow-hidden transition-all duration-300 transform hover:-translate-y-1 h-full flex flex-col">

                                <!-- Image -->
                                <div class="relative h-48 bg-gray-200 overflow-hidden">
                                    @if ($event->poster_path)
                                        <img src="{{ Storage::url($event->poster_path) }}"
                                            class="w-full h-full object-cover group-hover/card:scale-110 transition duration-500">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-gray-400">No Image
                                        </div>
                                    @endif
                                    <div
                                        class="absolute top-3 left-3 bg-white/90 backdrop-blur px-2 py-1 rounded text-[10px] font-bold uppercase text-[#4838CC] shadow-sm">
                                        {{ $event->category->name ?? 'Event' }}
                                    </div>
                                </div>

                                <!-- Content -->
                                <div class="p-4 flex flex-col flex-grow">

                                    <!-- Tanggal -->
                                    <div class="flex items-center gap-2 text-xs text-gray-500 mb-1">
                                        <svg class="w-3 h-3 text-[#4838CC]" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                            </path>
                                        </svg>
                                        {{ $event->date_label }}
                                    </div>

                                    <!-- JUDUL EVENT (DITAMBAHKAN KEMBALI) -->
                                    <h3
                                        class="text-base font-bold text-gray-900 mb-3 line-clamp-2 group-hover/card:text-[#4838CC] transition min-h-[3rem] pt-1 ">
                                        {{ \Illuminate\Support\Str::limit($event->name, 30, '...') }}
                                    </h3>

                                    <!-- Harga -->
                                    <div class="mt-auto flex items-end justify-between border-t border-gray-50 pt-3">
                                        <div>
                                            <div class="text-xs text-gray-500">Mulai dari</div>
                                            <div class="text-[#4838CC] font-bold text-lg">
                                                {{ $event->price == 0 ? 'Gratis' : 'Rp ' . number_format($event->price, 0, ',', '.') }}
                                            </div>
                                        </div>
                                        @if ($event->discount_percentage > 0)
                                            <span
                                                class="bg-red-100 text-red-600 text-[10px] font-bold px-2 py-1 rounded-full">
                                                {{ $event->discount_percentage }}% OFF
                                            </span>
                                        @endif
                                    </div>

                                    <!-- Author / Penyelenggara -->
                                    <div class="pt-3 mt-2 border-t border-gray-100 flex items-center gap-2 bg-white">
                                        <div class="w-6 h-6 rounded-full bg-indigo-100 overflow-hidden flex-shrink-0">
                                            @if ($event->author->photo_path)
                                                <img src="{{ Storage::url($event->author->photo_path) }}"
                                                    class="w-full h-full object-cover">
                                            @else
                                                <img src="https://ui-avatars.com/api/?name={{ urlencode($event->author->name) }}&background=random&size=64"
                                                    class="w-full h-full object-cover">
                                            @endif
                                        </div>
                                        <span class="text-xs text-gray-500 truncate font-medium max-w-[180px] uppercase">
                                            {{ $event->author->name }}
                                        </span>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach

                </div>

                <!-- Tombol Right -->
                <button id="btnRightFeatured" onclick="scrollFeatured(1)"
                    class="hidden md:flex absolute -right-4 top-1/2 -translate-y-1/2 z-10 w-12 h-12 bg-white rounded-full shadow-lg items-center justify-center text-gray-700 hover:text-[#4838CC] hover:scale-110 transition duration-300 opacity-0 group-hover:opacity-100 focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </button>

            </div>
        </div>
    </section>

    <!-- SECTION ADS (Banner Iklan Panjang) -->
    @if ($activeAd && $activeAd->banner_path)
        <section class="py-8">
            <div class="container mx-auto px-4">
                <!-- Link ke Detail Event yang diiklankan -->
                <a href="{{ route('event.detail', $activeAd->event_id) }}"
                    class="block w-full group relative overflow-hidden rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300">

                    <!-- Gambar Banner -->
                    <img src="{{ Storage::url($activeAd->banner_path) }}" alt="Advertisement"
                        class="w-full h-[100px] object-cover min-h-[100px] md:min-h-[150px]">

                </a>

                <!-- Label Kecil "Ad" (Opsional) -->
                <div class="flex justify-end mt-1">
                    <span class="text-[10px] text-gray-400 bg-gray-100 px-2 py-0.5 rounded">Sponsored</span>
                </div>
            </div>
        </section>
    @endif

    <!-- SECTION 3: LATEST EVENTS (Style: Vertical Poster) -->
    <section class="py-8">
        <div class="container mx-auto px-4">

            <!-- Header dengan Badge BARU -->
            <div class="flex items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-900">Acara Terbaru</h2>
                <span
                    class="ml-3 bg-[#FF6B00] text-white text-[10px] tracking-wide font-bold px-2.5 py-1 rounded-md uppercase">Baru</span>
            </div>

            <!-- Grid Vertical Poster -->
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-6">
                @foreach ($latestEvents as $event)
                    <a href="{{ route('event.detail', $event->id) }}" class="group block">
                        <!-- Poster Image Container (Rasio 2:3) -->
                        <div
                            class="relative w-full aspect-[2/3] rounded-2xl overflow-hidden shadow-sm group-hover:shadow-xl transition-all duration-300 transform group-hover:-translate-y-1">
                            @if ($event->poster_path)
                                <img src="{{ Storage::url($event->poster_path) }}"
                                    class="w-full h-full object-cover transition duration-500 group-hover:scale-105"
                                    alt="{{ $event->name }}">
                            @else
                                <div
                                    class="w-full h-full flex items-center justify-center bg-gray-200 text-gray-400 text-sm font-medium">
                                    No Poster</div>
                            @endif

                            <!-- Gradient Overlay (Optional: Agar teks putih terbaca jika ditaruh di atas gambar) -->
                            <div
                                class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            </div>
                        </div>

                        <!-- Detail Minimalis di Bawah -->
                        <div class="mt-3">
                            <h3
                                class="text-base font-bold text-gray-900 line-clamp-1 group-hover:text-[#4838CC] transition">
                                {{ $event->name }}
                            </h3>
                            <div class="flex justify-between items-center mt-1">
                                <p class="text-xs text-gray-500">{{ $event->date->format('d M Y') }}</p>
                                <p class="text-sm font-bold text-[#4838CC]">
                                    {{ $event->price == 0 ? 'Gratis' : 'Rp ' . number_format($event->price, 0) }}
                                </p>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>

            <!-- View All Button (Centered below) -->
            <div class="mt-8 text-center">
                <a href="{{ route('allEvents') }}"
                    class="inline-block border border-gray-300 text-white px-6 py-2 rounded-full text-sm font-medium hover:border-white hover:bg-white hover:text-blue-950 hover:border-blue-950  transition bg-blue-950">
                    Lihat Semua Event Terbaru
                </a>
            </div>
        </div>
    </section>

    <!-- SECTION 1: KATEGORI (Slider Mode) -->
    <section class="py-10 bg-white group/category relative">
        <div class="container mx-auto px-4">
            
            <!-- Header -->
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Cari Berdasarkan Kategori</h2>
                    <p class="text-gray-500 text-sm mt-1">Temukan event sesuai minat Anda.</p>
                </div>
                <!-- Link ke semua event -->
                <a href="{{ route('allEvents') }}" class="text-[#4838CC] font-semibold text-sm hover:underline">Lihat Semua</a>
            </div>

            <!-- Slider Wrapper -->
            <div class="relative">

                <!-- Tombol Left -->
                <button onclick="document.getElementById('scrollContainerCategory').scrollLeft -= 320" class="hidden md:flex absolute -left-4 top-1/2 -translate-y-1/2 z-10 w-12 h-12 bg-white rounded-full shadow-lg items-center justify-center text-gray-700 hover:text-[#4838CC] hover:scale-110 transition duration-300 opacity-0 group-hover/category:opacity-100 focus:outline-none border border-gray-100">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                </button>

                <!-- Scroll Container -->
                <div id="scrollContainerCategory" class="flex gap-6 overflow-x-auto scroll-smooth no-scrollbar py-4 px-1">

                    <!-- Kartu Statis "Semua Acara" -->
                    <div class="flex-shrink-0 w-40 md:w-48 snap-start">
                        <a href="{{ route('allEvents') }}" class="block relative w-full h-64 rounded-2xl overflow-hidden group shadow-md hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                            <div class="absolute inset-0 bg-gradient-to-t from-[#4838CC] to-[#6C5DD3] opacity-90 group-hover:opacity-100 transition duration-500"></div>
                            
                            <div class="absolute inset-0 flex flex-col items-center justify-center text-white z-10 p-4 text-center">
                                <div class="bg-white/20 p-4 rounded-full mb-3 backdrop-blur-sm shadow-inner">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                                </div>
                                <span class="font-bold text-lg leading-tight">Semua Acara</span>
                                <span class="text-xs opacity-80 mt-1">Jelajahi Semua</span>
                            </div>
                        </a>
                    </div>

                    <!-- Kartu Dinamis dari Database -->
                    @foreach ($categories as $cat)
                        <div class="flex-shrink-0 w-40 md:w-48 snap-start">
                            <a href="{{ route('allEvents', ['category' => $cat->id]) }}" class="block relative w-full h-64 rounded-2xl overflow-hidden group shadow-sm hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                                
                                <!-- Background Image -->
                                @if ($cat->thumbnail)
                                    <img src="{{ Storage::url($cat->thumbnail) }}" class="absolute inset-0 w-full h-full object-cover transition duration-700 group-hover:scale-110" alt="{{ $cat->name }}">
                                @else
                                    <div class="absolute inset-0 bg-gray-300 flex items-center justify-center text-gray-400">
                                        <svg class="w-10 h-10 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    </div>
                                @endif

                                <!-- Gradient Overlay (Ungu Transparan saat Hover) -->
                                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent group-hover:from-[#4838CC]/90 group-hover:via-[#4838CC]/60 transition duration-500"></div>

                                <!-- Content -->
                                <div class="absolute bottom-0 left-0 w-full p-5 text-white z-10">
                                    <h4 class="font-bold text-lg leading-tight mb-1 group-hover:translate-y-[-2px] transition duration-300">{{ $cat->name }}</h4>
                                    <p class="text-xs opacity-80 font-medium group-hover:opacity-100 transition">{{ $cat->events_count }} Acara</p>
                                </div>
                            </a>
                        </div>
                    @endforeach

                </div>

                <!-- Tombol Right -->
                <button onclick="document.getElementById('scrollContainerCategory').scrollLeft += 320" class="hidden md:flex absolute -right-4 top-1/2 -translate-y-1/2 z-10 w-12 h-12 bg-white rounded-full shadow-lg items-center justify-center text-gray-700 hover:text-[#4838CC] hover:scale-110 transition duration-300 opacity-0 group-hover/category:opacity-100 focus:outline-none border border-gray-100">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </button>

            </div>
        </div>
    </section>

    <!-- SECTION: KREATOR FAVORIT -->
    <section class="py-10 bg-white">
        <div class="container mx-auto px-4">
            <h2 class="text-2xl font-bold text-[#152955] mb-6">Kreator Favorit</h2>

            <div class="flex gap-6 overflow-x-auto pb-4 scrollbar-hide">
                @foreach ($favoriteCreators as $creator)
                    <!-- MODIFIKASI: Bungkus dengan Tag A -->
                    <a href="{{ route('creator.show', $creator->id) }}"
                        class="flex flex-col items-center flex-shrink-0 w-24 group cursor-pointer">

                        <!-- Creator Image -->
                        <div
                            class="w-20 h-20 rounded-full border border-gray-200 overflow-hidden shadow-sm group-hover:ring-4 ring-[#4838CC]/30 transition p-0.5 bg-white">
                            @if ($creator->photo_path)
                                <img src="{{ Storage::url($creator->photo_path) }}"
                                    class="w-full h-full object-cover rounded-full" alt="{{ $creator->name }}">
                            @else
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($creator->name) }}&background=random&color=fff"
                                    class="w-full h-full object-cover rounded-full">
                            @endif
                        </div>

                        <!-- Creator Name -->
                        <h4
                            class="mt-3 text-sm font-semibold text-gray-800 text-center leading-tight line-clamp-2 group-hover:text-[#4838CC] transition">
                            {{ $creator->name }}
                        </h4>
                    </a>
                @endforeach

                <!-- ... -->
            </div>
        </div>
    </section>

    <!-- SECTION: WORKSHOP -->
    @if ($workshopEvents->count() > 0)
        <section class="py-8 bg-white">
            <div class="container mx-auto px-4">
                <!-- Header -->
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-[#152955]">Workshop</h2>
                    <a href="{{ route('allEvents') }}" class="text-[#4838CC] font-semibold text-sm hover:underline">Lihat
                        Semua</a>
                </div>

                <!-- Grid Card Workshop -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach ($workshopEvents as $event)
                        <a href="{{ route('event.detail', $event->id) }}"
                            class="group block bg-white border border-gray-200 rounded-xl overflow-hidden hover:shadow-lg transition-all duration-300">

                            <!-- Image -->
                            <div class="relative h-40 bg-gray-100 overflow-hidden">
                                @if ($event->poster_path)
                                    <img src="{{ Storage::url($event->poster_path) }}"
                                        class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-400 text-xs">No
                                        Image</div>
                                @endif

                                <!-- Badge Online/Offline (Optional) -->
                                <div
                                    class="absolute top-2 right-2 bg-black/60 text-white text-[10px] font-bold px-2 py-1 rounded">
                                    {{ $event->type->name ?? 'Event' }}
                                </div>
                            </div>

                            <!-- Content -->
                            <div class="p-4">
                                <!-- Title -->
                                <h3
                                    class="font-bold text-gray-900 text-base line-clamp-2 mb-2 h-11 group-hover:text-[#4838CC] transition">
                                    {{ \Illuminate\Support\Str::limit($event->name, 30, '...') }}
                                </h3>

                                <!-- Date -->
                                <div class="text-gray-500 text-xs mb-3">
                                    {{ $event->date_label }}
                                </div>

                                <!-- Price -->
                                <div class="font-bold text-[#4838CC] text-base mb-4">
                                    {{ $event->price == 0 ? 'Gratis' : 'Rp ' . number_format($event->price, 0, ',', '.') }}
                                </div>

                                <!-- Footer: Author Info -->
                                <div class="flex items-center gap-2 pt-3 border-t border-gray-100">
                                    <div class="w-6 h-6 rounded-full bg-gray-200 overflow-hidden flex-shrink-0">
                                        @if ($event->author->photo_path)
                                            <img src="{{ Storage::url($event->author->photo_path) }}"
                                                class="w-full h-full object-cover">
                                        @else
                                            <img src="https://ui-avatars.com/api/?name={{ urlencode($event->author->name) }}&background=random"
                                                class="w-full h-full object-cover">
                                        @endif
                                    </div>
                                    <span class="text-xs text-gray-600 truncate font-medium max-w-[150px]">
                                        {{ $event->author->name }}
                                    </span>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <!-- SECTION: POPULER BERDASARKAN LOKASI -->
    <section class="py-10 bg-white" id="popularLocationSection">
        <div class="container mx-auto px-4">

            <!-- HEADER DENGAN DROPDOWN -->
            <div class="flex items-center gap-2 mb-6 w-fit relative" x-data="{ openLocation: false }">
                <h2 class="text-2xl font-bold text-[#152955] flex items-center gap-2">
                    Populer di 
                    <!-- Tombol Trigger -->
                    <button @click="openLocation = !openLocation" @click.away="openLocation = false" class="text-[#4838CC] hover:underline decoration-dashed decoration-2 underline-offset-4 flex items-center gap-1 focus:outline-none">
                        {{ $locationName }}
                        <!-- Icon Chevron -->
                        <svg class="w-6 h-6 transition-transform duration-200" :class="{'rotate-180': openLocation}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                </h2>

                <!-- ISI DROPDOWN (Daftar Lokasi) -->
                <div x-show="openLocation" 
                     x-transition:enter="transition ease-out duration-100"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     class="absolute top-10 left-32 z-50 mt-2 w-56 bg-white rounded-xl shadow-xl border border-gray-100 overflow-hidden" 
                     style="display: none;">
                    
                    <div class="py-1 max-h-60 overflow-y-auto custom-scrollbar">
                        @foreach($allLocations as $loc)
                            <!-- LINK FILTER LOKASI -->
                            <a href="{{ route('landingPage', ['location' => $loc->name]) }}#popularLocationSection" 
                               class="block px-4 py-2.5 text-sm hover:bg-indigo-50 transition {{ $locationName == $loc->name ? 'text-[#4838CC] font-bold bg-indigo-50' : 'text-gray-700' }}">
                                {{ $loc->name }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- CONTENT SLIDER -->
            @if ($popularLocationEvents->count() > 0)
                <div class="relative group/slider">

                    <!-- Tombol Left -->
                    <button onclick="document.getElementById('scrollContainerLoc').scrollLeft -= 320"
                        class="hidden md:flex absolute -left-4 top-1/2 -translate-y-1/2 z-10 w-10 h-10 bg-white rounded-full shadow-lg items-center justify-center text-gray-700 hover:text-[#4838CC] border border-gray-100 transition opacity-0 group-hover/slider:opacity-100">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7">
                            </path>
                        </svg>
                    </button>

                    <!-- Scroll Container -->
                    <div id="scrollContainerLoc" class="flex gap-6 overflow-x-auto scroll-smooth no-scrollbar py-2 px-1">

                        @foreach ($popularLocationEvents as $event)
                            <!-- Card Item -->
                            <div class="flex-shrink-0 w-72 md:w-[18rem]">
                                <a href="{{ route('event.detail', $event->id) }}"
                                    class="block bg-white border border-gray-200 rounded-xl overflow-hidden hover:shadow-lg transition-all duration-300 h-full flex flex-col group">

                                    <!-- Image -->
                                    <div class="relative h-44 bg-gray-100 overflow-hidden">
                                        @if ($event->poster_path)
                                            <img src="{{ Storage::url($event->poster_path) }}"
                                                class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                                        @else
                                            <div
                                                class="w-full h-full flex items-center justify-center text-gray-400 text-xs">
                                                No Image</div>
                                        @endif
                                    </div>

                                    <!-- Body -->
                                    <div class="p-4 flex-grow flex flex-col">
                                        <h3
                                            class="text-base font-bold text-gray-900 line-clamp-2 mb-2 group-hover:text-[#4838CC] transition min-h-[3rem]">
                                            {{ \Illuminate\Support\Str::limit($event->name, 40, '...') }}
                                        </h3>
                                        <p class="text-xs text-gray-500 mb-3">
                                            {{ $event->date_label }}
                                        </p>
                                        <div class="mt-auto font-bold text-gray-800">
                                            {{ $event->price == 0 ? 'Gratis' : 'Rp ' . number_format($event->price, 0, ',', '.') }}
                                        </div>
                                    </div>

                                    <!-- Footer: Author -->
                                    <div class="px-4 py-3 border-t border-gray-100 flex items-center gap-2 bg-gray-50/50">
                                        <div class="w-6 h-6 rounded-full bg-indigo-100 overflow-hidden flex-shrink-0">
                                            @if ($event->author->photo_path)
                                                <img src="{{ Storage::url($event->author->photo_path) }}"
                                                    class="w-full h-full object-cover">
                                            @else
                                                <img src="https://ui-avatars.com/api/?name={{ urlencode($event->author->name) }}&background=random&size=64"
                                                    class="w-full h-full object-cover">
                                            @endif
                                        </div>
                                        <span class="text-xs text-gray-500 truncate font-medium max-w-[180px] uppercase">
                                            {{ $event->author->name }}
                                        </span>
                                    </div>

                                </a>
                            </div>
                        @endforeach
                    </div>

                    <!-- Tombol Right -->
                    <button onclick="document.getElementById('scrollContainerLoc').scrollLeft += 320"
                        class="hidden md:flex absolute -right-4 top-1/2 -translate-y-1/2 z-10 w-10 h-10 bg-white rounded-full shadow-lg items-center justify-center text-gray-700 hover:text-[#4838CC] border border-gray-100 transition opacity-0 group-hover/slider:opacity-100">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                            </path>
                        </svg>
                    </button>
                </div>
            @else
                <!-- Empty State jika lokasi dipilih tapi tidak ada event -->
                <div class="bg-gray-50 border border-dashed border-gray-300 rounded-xl p-8 text-center">
                    <p class="text-gray-500">Belum ada event populer di <strong>{{ $locationName }}</strong> saat ini.</p>
                </div>
            @endif

        </div>
    </section>

    <!-- SECTION 3: CTA BANNER -->
    <section class="py-12">
        <div class="container mx-auto px-4">
            <div
                class="bg-gradient-to-r from-[#4838CC] to-[#6C5DD3] rounded-2xl p-10 text-center text-white relative overflow-hidden shadow-lg">
                <div class="relative z-10">
                    <h2 class="text-3xl font-bold mb-3">Mau Bikin Event Sendiri?</h2>
                    <p class="text-indigo-100 mb-8 max-w-xl mx-auto text-lg">Buat eventmu sekarang dan jangkau ribuan
                        audiens di Smart Event ID. Gratis dan mudah!</p>

                    <!-- MODIFIKASI: Logika Create Event -->
                    <a href="{{ !Auth::check()
                        ? route('login')
                        : (in_array(auth()->user()->role, ['admin', 'super_admin'])
                            ? route('events.index')
                            : route('user.events.index')) }}"
                        class="bg-white text-[#4838CC] px-8 py-3.5 rounded-full font-bold hover:bg-gray-50 transition shadow-md transform hover:scale-105 inline-block">
                        Buat Event Sekarang
                    </a>

                </div>
                <div
                    class="absolute top-0 left-0 w-64 h-64 bg-white opacity-5 rounded-full -translate-x-1/3 -translate-y-1/3 blur-2xl">
                </div>
                <div
                    class="absolute bottom-0 right-0 w-80 h-80 bg-indigo-500 opacity-20 rounded-full translate-x-1/3 translate-y-1/3 blur-3xl">
                </div>
            </div>
        </div>
    </section>
    <!-- JAVASCRIPT FOR SLIDER (Tetap sama) -->
    <script>
        let currentSlide = 0;
        const slides = document.querySelectorAll('.slide');
        const dots = document.querySelectorAll('.dot');
        const totalSlides = slides.length;
        let slideInterval;

        function updateSlider() {
            slides.forEach((slide, index) => {
                if (index === currentSlide) {
                    slide.classList.remove('opacity-0', 'z-0');
                    slide.classList.add('opacity-100', 'z-10');
                } else {
                    slide.classList.remove('opacity-100', 'z-10');
                    slide.classList.add('opacity-0', 'z-0');
                }
            });

            dots.forEach((dot, index) => {
                if (index === currentSlide) {
                    dot.classList.remove('bg-white/50', 'w-1.5', 'md:w-2');
                    dot.classList.add('bg-white', 'w-6', 'md:w-8');
                } else {
                    dot.classList.remove('bg-white', 'w-6', 'md:w-8');
                    dot.classList.add('bg-white/50', 'w-1.5', 'md:w-2');
                }
            });
        }

        function moveSlide(step) {
            currentSlide = (currentSlide + step + totalSlides) % totalSlides;
            updateSlider();
            resetTimer();
        }

        function goToSlide(index) {
            currentSlide = index;
            updateSlider();
            resetTimer();
        }

        function startTimer() {
            slideInterval = setInterval(() => {
                moveSlide(1);
            }, 5000);
        }

        function resetTimer() {
            clearInterval(slideInterval);
            startTimer();
        }
        if (totalSlides > 0) {
            startTimer();
        }

        function scrollFeatured(direction) {
            const container = document.getElementById('scrollContainerFeatured');
            const scrollAmount = 350; // Jarak scroll per klik (lebar card + gap)

            if (direction === 1) {
                container.scrollLeft += scrollAmount;
            } else {
                container.scrollLeft -= scrollAmount;
            }
        }
    </script>

@endsection

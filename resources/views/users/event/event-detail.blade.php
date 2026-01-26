@extends('layouts.user')

@section('content')

    <!-- Background Header (Hiasan) -->
    <div class="bg-custom-purple h-64 w-full absolute top-0 z-0"></div>

    <div class="container mx-auto px-4 relative z-10 pt-10 pb-20">

        <!-- Breadcrumb -->
        <nav class="flex text-sm text-white/80 mb-6 font-medium">
            <a href="{{ route('landingPage') }}" class="hover:text-white">Home</a>
            <span class="mx-2">/</span>
            <span class="hover:text-white">{{ $event->category->name }}</span>
            <span class="mx-2">/</span>
            <span class="text-white truncate max-w-xs">{{ $event->name }}</span>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <!-- LEFT COLUMN: Main Content -->
            <div class="lg:col-span-2 space-y-8">

                <!-- Event Poster & Header Card -->
                <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
                    <!-- Poster Image -->
                    <div class="relative w-full h-[300px] md:h-[450px] bg-gray-200">
                        @if ($event->poster_path)
                            <img src="{{ Storage::url($event->poster_path) }}" class="w-full h-full object-cover"
                                alt="{{ $event->name }}">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-gray-400 font-bold text-xl">No
                                Image Available</div>
                        @endif

                        <!-- Badges -->
                        <div class="absolute top-4 left-4 flex gap-2">
                            <span
                                class="bg-white/90 backdrop-blur text-[#4838CC] px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wide shadow-sm">
                                {{ $event->category->name }}
                            </span>
                            @if ($event->discount_percentage > 0)
                                <span class="bg-[#C85250] text-white px-3 py-1 rounded-full text-xs font-bold shadow-sm">
                                    {{ $event->discount_percentage }}% OFF
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Title & Organizer -->
                    <div class="p-6 md:p-8">
                        <h1 class="text-2xl md:text-4xl font-bold text-gray-900 mb-4 leading-tight">{{ $event->name }}</h1>

                        <div class="flex items-center gap-4 text-sm text-gray-500 border-b border-gray-100 pb-6 mb-6">
                            <!-- Author -->
                            <div class="flex items-center gap-2">
                                <div
                                    class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center text-[#4838CC]">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                                <span class="font-semibold text-gray-700">{{ $event->author->name ?? 'Organizer' }}</span>
                            </div>

                            <span class="text-gray-300">•</span>
                        </div>

                        <!-- Description -->
                        <div>
                            <h3 class="text-lg font-bold text-gray-800 mb-3">Deskripsi Acara</h3>
                            <div class="prose max-w-none text-gray-600 leading-relaxed">
                                {!! nl2br(e($event->details)) !!}
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- RIGHT COLUMN: Sticky Sidebar (Ticket Card) -->
            <div class="lg:col-span-1">
                <div class="sticky top-28 space-y-6">

                    <!-- Ticket Card -->
                    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-6">
                        <h3 class="text-lg font-bold text-gray-800 mb-4">Dapatkan Tiket</h3>

                        <!-- Date Info -->
                        <!-- WAKTU & TEMPAT -->
                        <div class="flex items-start gap-3 mb-6 bg-blue-50 p-3 rounded-lg">
                            <div class="bg-white p-2 rounded text-[#4838CC]">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>

                            <div class="space-y-1">
                                <p class="text-xs text-gray-500 font-semibold uppercase">
                                    Waktu & Tempat
                                </p>

                                <!-- TANGGAL -->
                                <p class="text-sm font-bold text-gray-800">
                                    {{ $event->date_label }}
                                </p>

                                <!-- JAM -->
                                <p class="text-xs text-gray-600">
                                    {{ \Carbon\Carbon::parse($event->start_time)->format('H:i') }}
                                    -
                                    {{ \Carbon\Carbon::parse($event->end_time)->format('H:i') }}
                                    WIB
                                </p>

                                <!-- LOKASI -->
                                @if ($event->location_type === 'online')
                                    <span
                                        class="inline-block text-[11px] font-bold bg-green-100 text-green-700 px-2 py-0.5 rounded">
                                        EVENT ONLINE
                                    </span>

                                    @if ($event->online_link)
                                        <a href="{{ $event->online_link }}" target="_blank"
                                            class="block text-sm text-blue-600 underline font-semibold mt-1">
                                            Join Event
                                        </a>
                                    @endif
                                @else
                                    <p class="text-sm font-bold text-gray-800">
                                        {{ $event->offline_place_name }}
                                    </p>

                                    <p class="text-xs text-gray-600">
                                        {{ $event->offline_address }}
                                    </p>

                                    @if ($event->offline_maps_link)
                                        <a href="{{ $event->offline_maps_link }}" target="_blank"
                                            class="inline-block text-xs text-blue-600 underline mt-1">
                                            Lihat di Google Maps
                                        </a>
                                    @endif
                                @endif
                            </div>
                        </div>

                        {{-- GOOGLE MAP EMBED (OFFLINE ONLY) --}}
                        @if ($event->location_type === 'offline' && $event->offline_maps_link)
                            <div class="mt-4 rounded-xl overflow-hidden border border-gray-200">
                                <iframe src="{{ $event->offline_maps_link }}&output=embed" class="w-full h-[220px]"
                                    style="border:0;" allowfullscreen="" loading="lazy"
                                    referrerpolicy="no-referrer-when-downgrade">
                                </iframe>
                            </div>
                        @endif


                        <!-- Price Info -->
                        <div class="mb-6">
                            <p class="text-sm text-gray-500 mb-1">Harga</p>
                            @if ($event->price == 0)
                                <span class="text-3xl font-bold text-[#00C851]">FREE</span>
                            @else
                                <div class="flex items-end gap-2">
                                    <span class="text-3xl font-bold text-[#4838CC]">
                                        IDR {{ number_format($event->price, 0, ',', '.') }}
                                    </span>
                                    @if ($event->discount_percentage > 0)
                                        @php
                                            $originalPrice = $event->price / (1 - $event->discount_percentage / 100);
                                        @endphp
                                        <span class="text-sm text-gray-400 line-through mb-1">
                                            IDR {{ number_format($originalPrice, 0, ',', '.') }}
                                        </span>
                                    @endif
                                </div>
                            @endif
                        </div>

                        <!-- Action Button -->
                        <form action="{{ route('payment.checkout', $event->id) }}" method="POST">
                            @csrf

                            @auth
                                <button type="submit"
                                    class="w-full bg-[#4838CC] hover:bg-[#3b2db0] text-white font-bold py-3.5 rounded-xl shadow-lg shadow-indigo-200 transition transform hover:-translate-y-0.5 mb-3">
                                    Buy Ticket Now
                                </button>
                            @else
                                <!-- Jika belum login, arahkan ke login -->
                                <a href="{{ route('login') }}"
                                    class="block text-center w-full bg-[#4838CC] hover:bg-[#3b2db0] text-white font-bold py-3.5 rounded-xl shadow-lg shadow-indigo-200 transition transform hover:-translate-y-0.5 mb-3">
                                    Login to Buy Ticket
                                </a>
                            @endauth

                        </form>

                        <p class="text-xs text-center text-gray-400">Pembayaran aman melalui QRIS / Transfer Bank</p>
                    </div>

                </div>
            </div>

        </div>

        <!-- RELATED EVENTS -->
        <div class="mt-16">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Rekomendasi Acara</h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
                @forelse($relatedEvents as $relEvent)
                    <a href="{{ route('event.detail', $relEvent->id) }}" class="flex flex-col group">
                        <!-- Image Box -->
                        <div class="bg-white rounded-xl h-40 w-full mb-3 overflow-hidden shadow-md relative">
                            @if ($relEvent->poster_path)
                                <img src="{{ Storage::url($relEvent->poster_path) }}"
                                    class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                            @else
                                <div
                                    class="w-full h-full flex items-center justify-center bg-gray-200 text-gray-400 text-xs">
                                    No Image</div>
                            @endif
                        </div>

                        <!-- Content -->
                        <h3
                            class="text-gray-900 font-bold text-xs uppercase tracking-wide truncate mb-1 group-hover:text-[#4838CC] transition">
                            {{ $relEvent->name }}
                        </h3>
                        <div class="text-gray-600 text-xs font-semibold mb-1">
                            <span class="text-[#4838CC]">IDR {{ number_format($relEvent->price, 0, ',', '.') }}</span>
                        </div>
                    </a>
                @empty
                    <div class="col-span-full text-center py-10">
                        <p class="text-gray-400">No related events found.</p>
                    </div>
                @endforelse
            </div>
        </div>

    </div>

@endsection

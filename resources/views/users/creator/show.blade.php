@extends('layouts.user')

@section('content')

    <!-- HEADER PROFILE BACKGROUND -->
    <div class="bg-[#152955] h-64 w-full relative overflow-hidden">
        <!-- Abstract Decoration -->
        <div class="absolute top-0 right-0 w-64 h-64 bg-blue-500 opacity-10 rounded-full translate-x-1/2 -translate-y-1/2 blur-3xl"></div>
        <div class="absolute bottom-0 left-0 w-40 h-40 bg-purple-500 opacity-10 rounded-full -translate-x-1/2 translate-y-1/2 blur-2xl"></div>
    </div>

    <!-- MAIN CONTENT -->
    <div class="container mx-auto px-4 relative z-10 -mt-24 pb-20">
        
        <!-- CREATOR PROFILE CARD -->
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-8 max-w-4xl mx-auto text-center mb-12">
            <div class="relative inline-block">
                <div class="w-32 h-32 rounded-full border-4 border-white shadow-md overflow-hidden bg-gray-100 mx-auto">
                    @if($creator->photo_path)
                        <img src="{{ Storage::url($creator->photo_path) }}" class="w-full h-full object-cover">
                    @else
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($creator->name) }}&background=4838CC&color=fff&size=128" class="w-full h-full object-cover">
                    @endif
                </div>
                <!-- Verified Badge (Optional Hiasan) -->
                <div class="absolute bottom-1 right-1 bg-blue-500 text-white p-1.5 rounded-full border-2 border-white" title="Verified Creator">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                </div>
            </div>

            <h1 class="text-3xl font-bold text-gray-900 mt-4">{{ $creator->name }}</h1>
            <p class="text-gray-500 mt-1">Bergabung sejak {{ $creator->created_at->format('M Y') }}</p>

            <!-- Stats -->
            <div class="flex justify-center gap-8 mt-6 pt-6 border-t border-gray-100">
                <div class="text-center">
                    <span class="block text-2xl font-bold text-[#4838CC]">{{ $creator->events_count }}</span>
                    <span class="text-xs text-gray-500 uppercase tracking-wide">Total Event</span>
                </div>
                <div class="text-center">
                    <span class="block text-2xl font-bold text-[#4838CC]">{{ $upcomingEvents->count() }}</span>
                    <span class="text-xs text-gray-500 uppercase tracking-wide">Aktif</span>
                </div>
                <div class="text-center">
                    <span class="block text-2xl font-bold text-gray-400">{{ $pastEvents->count() }}</span>
                    <span class="text-xs text-gray-400 uppercase tracking-wide">Selesai</span>
                </div>
            </div>
        </div>

        <!-- 1. EVENT AKTIF / AKAN DATANG -->
        <div class="mb-12">
            <div class="flex items-center gap-3 mb-6">
                <h2 class="text-2xl font-bold text-gray-900">Event Akan Datang</h2>
                <span class="bg-green-100 text-green-700 text-xs font-bold px-2.5 py-0.5 rounded-full">Active</span>
            </div>

            @if($upcomingEvents->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach($upcomingEvents as $event)
                        <a href="{{ route('event.detail', $event->id) }}" class="group bg-white rounded-xl shadow-sm hover:shadow-xl border border-gray-100 overflow-hidden transition-all duration-300 transform hover:-translate-y-1">
                            <div class="relative h-48 bg-gray-200 overflow-hidden">
                                @if($event->poster_path)
                                    <img src="{{ Storage::url($event->poster_path) }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-400">No Image</div>
                                @endif
                                <div class="absolute top-3 left-3 bg-white/90 backdrop-blur px-2 py-1 rounded text-[10px] font-bold uppercase text-[#4838CC] shadow-sm">
                                    {{ $event->category->name ?? 'Event' }}
                                </div>
                            </div>
                            <div class="p-4">
                                <div class="flex items-center gap-2 text-xs text-gray-500 mb-2">
                                    <svg class="w-3 h-3 text-[#4838CC]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    {{ $event->date_label }}
                                </div>
                                <h3 class="text-base font-bold text-gray-900 mb-3 line-clamp-2 group-hover:text-[#4838CC] transition">
                                    {{ $event->name }}
                                </h3>
                                <div class="text-[#4838CC] font-bold text-lg">
                                    {{ $event->price == 0 ? 'Gratis' : 'Rp ' . number_format($event->price, 0, ',', '.') }}
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @else
                <div class="bg-gray-50 border border-dashed border-gray-300 rounded-xl p-8 text-center text-gray-500">
                    Kreator ini belum memiliki event aktif saat ini.
                </div>
            @endif
        </div>

        <!-- 2. EVENT LEWAT / SELESAI -->
        <div class="opacity-75 grayscale hover:grayscale-0 transition duration-500">
            <div class="flex items-center gap-3 mb-6">
                <h2 class="text-2xl font-bold text-gray-600">Event Telah Lewat</h2>
                <span class="bg-gray-200 text-gray-600 text-xs font-bold px-2.5 py-0.5 rounded-full">Ended</span>
            </div>

            @if($pastEvents->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach($pastEvents as $event)
                        <div class="group bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden relative cursor-not-allowed">
                            
                            <!-- Overlay "Ended" -->
                            <div class="absolute inset-0 bg-black/40 z-10 flex items-center justify-center">
                                <span class="bg-black/60 text-white font-bold border-2 border-white px-4 py-1 rounded uppercase tracking-widest text-sm transform -rotate-12">
                                    Selesai
                                </span>
                            </div>

                            <div class="relative h-48 bg-gray-200 overflow-hidden">
                                @if($event->poster_path)
                                    <img src="{{ Storage::url($event->poster_path) }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-400">No Image</div>
                                @endif
                            </div>
                            <div class="p-4">
                                <h3 class="text-base font-bold text-gray-600 line-clamp-2 mb-2">
                                    {{ $event->name }}
                                </h3>
                                <p class="text-xs text-gray-500">Berakhir pada: {{ $event->date->format('d M Y') }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-400 italic">Belum ada riwayat event.</p>
            @endif
        </div>

    </div>
@endsection
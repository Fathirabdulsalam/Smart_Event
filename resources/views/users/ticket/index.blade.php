@extends('layouts.user')

@section('content')
    <!-- Background Header -->
    <div class="bg-[#4838CC] h-48 w-full absolute top-0 z-0"></div>

    <div class="container mx-auto px-4 relative z-10 pt-8 pb-20">
        <div class="flex flex-col lg:flex-row gap-8">
            
            <!-- SIDEBAR -->
            @include('users.partials.sidebar')

            <!-- MAIN CONTENT -->
            <div class="w-full lg:w-3/4">
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 min-h-[500px]">
                    
                    <!-- Header -->
                    <div class="flex justify-between items-center mb-6 border-b border-gray-100 pb-4">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-800">My Tickets</h1>
                            <p class="text-gray-500 text-sm">Manage your bookings and view your e-tickets.</p>
                        </div>
                        <!-- Tombol Cari Event (Redirect ke All Events) -->
                        <a href="{{ route('allEvents') }}" class="bg-indigo-50 text-[#4838CC] px-4 py-2 rounded-lg font-bold text-sm hover:bg-indigo-100 transition">
                            Browse Events
                        </a>
                    </div>

                    <!-- Ticket List -->
                    @if($tickets->count() > 0)
                        <div class="space-y-4">
                            @foreach($tickets as $ticket)
                                <div class="flex flex-col md:flex-row gap-4 border border-gray-100 rounded-xl p-4 hover:shadow-md transition bg-white items-stretch">
                                    
                                    <!-- Image Poster -->
                                    <div class="w-full md:w-32 h-32 md:h-auto bg-gray-200 rounded-lg overflow-hidden flex-shrink-0 relative">
                                        @if($ticket->event->poster_path)
                                            <img src="{{ Storage::url($ticket->event->poster_path) }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-gray-400 text-xs">IMG</div>
                                        @endif
                                        
                                        <!-- Category Badge -->
                                        <div class="absolute top-0 left-0 bg-black/50 backdrop-blur text-white text-[10px] px-2 py-0.5 rounded-br-lg font-bold">
                                            {{ $ticket->event->category->name ?? 'Event' }}
                                        </div>
                                    </div>
                                    
                                    <!-- Event Details -->
                                    <div class="flex-1 flex flex-col justify-between py-1">
                                        <div>
                                            <!-- Status Label -->
                                            <div class="flex justify-between items-start mb-1">
                                                @php
                                                    $statusColor = match($ticket->status) {
                                                        'active' => 'bg-green-100 text-green-700',
                                                        'pending' => 'bg-yellow-100 text-yellow-700',
                                                        'cancelled' => 'bg-red-100 text-red-700',
                                                        default => 'bg-gray-100 text-gray-600'
                                                    };
                                                    
                                                    // Ambil Invoice Link jika pending
                                                    $paymentLink = $ticket->transaction ? $ticket->transaction->checkout_link : null;
                                                @endphp
                                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $statusColor }}">
                                                    {{ $ticket->status }}
                                                </span>
                                                <span class="text-xs text-gray-400">Order ID: #{{ $ticket->id }}</span>
                                            </div>

                                            <!-- Title -->
                                            <h3 class="font-bold text-gray-800 text-lg line-clamp-1 mb-1">{{ $ticket->event->name }}</h3>
                                            
                                            <!-- Date & Author -->
                                            <div class="text-xs text-gray-500 space-y-1">
                                                <div class="flex items-center gap-1">
                                                    <svg class="w-3 h-3 text-[#4838CC]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                    {{ $ticket->event->date->format('l, d F Y') }}
                                                </div>
                                                <div class="flex items-center gap-1">
                                                    <svg class="w-3 h-3 text-[#4838CC]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                                    {{ $ticket->event->author->name ?? 'Organizer' }}
                                                </div>
                                                <div class="flex items-center gap-1">
                                                    <svg class="w-3 h-3 text-[#4838CC]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                                    {{ $ticket->event->location->name ?? 'Online/Offline' }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Action Buttons (Logic Status) -->
                                    <div class="flex flex-col justify-center items-end gap-2 min-w-[140px] border-t md:border-t-0 md:border-l border-gray-100 pt-4 md:pt-0 md:pl-4">
                                        
                                        @if($ticket->status == 'active')
                                            <!-- JIKA SUDAH BAYAR -->
                                            <a href="{{ route('event.detail', $ticket->event->id) }}" class="w-full bg-[#4838CC] hover:bg-[#3b2db0] text-white text-xs font-bold py-2.5 px-4 rounded-lg text-center transition shadow-md">
                                                View E-Ticket
                                            </a>
                                            <button class="w-full border border-gray-200 hover:bg-gray-50 text-gray-600 text-xs font-bold py-2.5 px-4 rounded-lg transition">
                                                Invoice
                                            </button>
                                        
                                        @elseif($ticket->status == 'pending')
                                            <!-- JIKA BELUM BAYAR -->
                                            <div class="text-right mb-1">
                                                <span class="text-xs text-gray-500">Total Bill:</span>
                                                <div class="text-base font-bold text-red-500">
                                                    Rp {{ number_format($ticket->transaction->amount ?? 0, 0, ',', '.') }}
                                                </div>
                                            </div>
                                            
                                            @if($paymentLink)
                                                <a href="{{ $paymentLink }}" target="_blank" class="w-full bg-[#FF6B00] hover:bg-[#e65a00] text-white text-xs font-bold py-2.5 px-4 rounded-lg text-center transition shadow-md animate-pulse">
                                                    Pay Now
                                                </a>
                                            @else
                                                <button class="w-full bg-gray-300 text-white text-xs font-bold py-2.5 px-4 rounded-lg cursor-not-allowed">
                                                    Processing...
                                                </button>
                                            @endif
                                            
                                        @else
                                            <!-- JIKA CANCEL/EXPIRED -->
                                            <button class="w-full bg-gray-100 text-gray-400 text-xs font-bold py-2.5 px-4 rounded-lg cursor-not-allowed">
                                                Not Available
                                            </button>
                                        @endif
                                        
                                    </div>

                                </div>
                            @endforeach
                        </div>
                        <div class="mt-6">{{ $tickets->links() }}</div>
                    @else
                        <!-- Empty State -->
                        <div class="text-center py-20 bg-gray-50 rounded-2xl border border-dashed border-gray-300">
                            <div class="bg-indigo-50 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-10 h-10 text-[#4838CC]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path></svg>
                            </div>
                            <h3 class="text-lg font-bold text-gray-800">You don't have any tickets yet</h3>
                            <p class="text-gray-500 text-sm mt-1 mb-6">Explore exciting events and book your first ticket now!</p>
                            <a href="{{ route('allEvents') }}" class="inline-block bg-[#4838CC] text-white font-bold py-2.5 px-6 rounded-full hover:bg-[#3b2db0] transition shadow-md">
                                Browse Events
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
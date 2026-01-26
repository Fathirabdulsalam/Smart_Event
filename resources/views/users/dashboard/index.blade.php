@extends('layouts.user')

@section('content')
    <!-- Background Header Decoration -->
    <div class="bg-[#4838CC] h-48 w-full absolute top-0 z-0"></div>

    <div class="container mx-auto px-4 relative z-10 pt-8 pb-20">

        <div class="flex flex-col lg:flex-row gap-8">

            <!-- ======================= -->
            <!-- LEFT SIDEBAR: MENU      -->
            <!-- ======================= -->
            @include('users.partials.sidebar')

            <!-- =========================== -->
            <!-- RIGHT CONTENT: DASHBOARD    -->
            <!-- =========================== -->
            <div class="w-full lg:w-3/4 space-y-6">

                <!-- Welcome Banner -->
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-8 flex justify-between items-center relative overflow-hidden">
                    <div class="relative z-10">
                        <h1 class="text-3xl font-bold text-gray-800 mb-2">Welcome Back, {{ explode(' ', $user->name)[0] }}! 👋</h1>
                        <p class="text-gray-500">You have <span class="text-[#4838CC] font-bold">{{ $activeTickets->count() }} upcoming events</span> to attend.</p>
                    </div>
                    <div class="absolute right-0 top-0 h-full w-1/3 bg-gradient-to-l from-indigo-50 to-transparent"></div>
                </div>

                <!-- Stats Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Stats 1 -->
                    <div class="bg-[#4838CC] text-white rounded-2xl p-6 shadow-lg flex items-center justify-between">
                        <div>
                            <p class="text-indigo-200 text-sm font-medium mb-1">Active Tickets</p>
                            <h3 class="text-3xl font-bold">{{ $activeTickets->count() }}</h3>
                        </div>
                        <div class="bg-white/20 p-3 rounded-full">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path></svg>
                        </div>
                    </div>
                    <!-- Stats 2 -->
                    <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-100 flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm font-medium mb-1">Past Events</p>
                            <h3 class="text-3xl font-bold text-gray-800">{{ $pastTickets }}</h3>
                        </div>
                        <div class="bg-gray-100 p-3 rounded-full text-gray-500">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                    </div>
                </div>

                <!-- 1. YOUR UPCOMING TICKETS -->
                <div>
                    <h3 class="text-xl font-bold text-gray-800 mb-4">Your Upcoming Tickets</h3>
                    @if ($activeTickets->count() > 0)
                        <div class="space-y-4">
                            @foreach ($activeTickets as $registration)
                                <div class="bg-white rounded-2xl shadow-md border border-gray-100 p-4 flex flex-col sm:flex-row gap-6 hover:shadow-lg transition">
                                    <!-- Content Tiket (Sama seperti sebelumnya) -->
                                    <div class="w-full sm:w-48 h-32 flex-shrink-0 bg-gray-200 rounded-xl overflow-hidden relative group">
                                        @if ($registration->event->poster_path)
                                            <img src="{{ Storage::url($registration->event->poster_path) }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-gray-400 font-bold">Ticket</div>
                                        @endif
                                        <div class="absolute top-2 left-2 bg-white/90 backdrop-blur px-2 py-1 rounded text-[10px] font-bold uppercase text-[#4838CC]">
                                            {{ $registration->event->category->name ?? 'Event' }}
                                        </div>
                                    </div>
                                    <div class="flex-1 flex flex-col justify-center">
                                        <h4 class="text-lg font-bold text-gray-900 mb-2 line-clamp-1">{{ $registration->event->name }}</h4>
                                        <div class="space-y-1 text-sm text-gray-600">
                                            <div class="flex items-center gap-2">
                                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                <span>{{ $registration->event->date->format('l, d F Y') }}</span>
                                            </div>
                                            <div class="flex items-center gap-2">
                                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                                <span>{{ $registration->event->author->name ?? 'Organizer' }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex flex-col justify-center items-end gap-2 border-t sm:border-t-0 sm:border-l border-gray-100 pt-4 sm:pt-0 sm:pl-6 mt-4 sm:mt-0">
                                        <div class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-bold flex items-center gap-1 mb-2">Paid</div>
                                        <a href="{{ route('event.detail', $registration->event->id) }}" class="w-full sm:w-auto bg-[#4838CC] hover:bg-[#3b2db0] text-white text-sm font-bold py-2 px-6 rounded-lg text-center transition">View E-Ticket</a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-10 text-center">
                            <h3 class="text-lg font-bold text-gray-800">No active tickets yet</h3>
                            <p class="text-gray-500 text-sm mt-1 mb-6">Looks like you haven't booked any upcoming events.</p>
                            <a href="{{ route('allEvents') }}" class="inline-block border-2 border-[#4838CC] text-[#4838CC] font-bold py-2 px-6 rounded-lg hover:bg-indigo-50 transition">Browse Events</a>
                        </div>
                    @endif
                </div>



            </div>
        </div>
    </div>
@endsection
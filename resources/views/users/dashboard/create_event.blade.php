@extends('layouts.user')

@section('content')
    <div class="bg-custom-purple h-48 w-full absolute top-0 z-0"></div>

    <div class="container mx-auto px-4 relative z-10 pt-8 pb-20">
        <div class="max-w-4xl mx-auto">
            
            <!-- Breadcrumb -->
            <div class="mb-6 text-white/80 text-sm font-medium">
                <a href="{{ route('user.dashboard') }}" class="hover:text-white">Dashboard</a>
                <span class="mx-2">/</span>
                <span class="text-white">Create Event</span>
            </div>

            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                <div class="p-8 border-b border-gray-100">
                    <h1 class="text-2xl font-bold text-gray-800">Create New Event</h1>
                    <p class="text-gray-500 mt-1">Fill in the details below to publish your event.</p>
                </div>

                <form action="{{ route('user.event.store') }}" method="POST" enctype="multipart/form-data" class="p-8 space-y-6">
                    @csrf

                    <!-- 1. Poster -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Event Poster</label>
                        <div class="w-full">
                            <label class="cursor-pointer flex flex-col items-center justify-center w-full h-40 border-2 border-gray-300 border-dashed rounded-xl bg-gray-50 hover:bg-gray-100 transition overflow-hidden relative">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6 text-center" id="placeholder">
                                    <svg class="w-8 h-8 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    <p class="text-sm text-gray-500 font-medium">Click to upload poster</p>
                                    <p class="text-xs text-gray-400">PNG, JPG up to 2MB</p>
                                </div>
                                <img id="preview" class="hidden absolute inset-0 w-full h-full object-cover">
                                <input type="file" name="poster" class="hidden" accept="image/*" required onchange="previewImage(this)">
                            </label>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Name -->
                        <div class="col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Event Name</label>
                            <input type="text" name="name" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-[#4838CC] focus:border-[#4838CC] outline-none" required placeholder="e.g. Grand Concert 2024">
                        </div>

                        <!-- Date & Time -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Date</label>
                            <input type="date" name="date" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-[#4838CC] outline-none" required>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Start Time</label>
                                <input type="time" name="start_time" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-[#4838CC] outline-none" required>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">End Time</label>
                                <input type="time" name="end_time" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-[#4838CC] outline-none" required>
                            </div>
                        </div>

                        <!-- Price -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Ticket Price (IDR)</label>
                            <input type="number" name="price" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-[#4838CC] outline-none" placeholder="0 for Free" required>
                        </div>

                        <!-- Category -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Category</label>
                            <select name="category_id" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-[#4838CC] outline-none bg-white">
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <!-- Location (Master Data) -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Location</label>
                            <select name="master_location_id" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-[#4838CC] outline-none bg-white">
                                @foreach($locations as $loc)
                                    <option value="{{ $loc->id }}">{{ $loc->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Tipe Event (Online/Offline) -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Event Type</label>
                            <select name="master_type_id" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-[#4838CC] outline-none bg-white">
                                @foreach($types as $type)
                                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Jenis Event -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Event Kind</label>
                            <select name="master_event_kind_id" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-[#4838CC] outline-none bg-white">
                                @foreach($kinds as $kind)
                                    <option value="{{ $kind->id }}">{{ $kind->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Ticket Category -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Ticket Type</label>
                            <select name="master_ticket_category_id" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-[#4838CC] outline-none bg-white">
                                @foreach($ticketCategories as $tc)
                                    <option value="{{ $tc->id }}">{{ $tc->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Zone -->
                        <div class="col-span-2">
                             <label class="block text-sm font-semibold text-gray-700 mb-1">Time Zone</label>
                            <select name="master_zone_id" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-[#4838CC] outline-none bg-white">
                                @foreach($zones as $zone)
                                    <option value="{{ $zone->id }}">{{ $zone->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Details -->
                        <div class="col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Description</label>
                            <textarea name="details" rows="5" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-[#4838CC] outline-none resize-none" placeholder="Describe your event..." required></textarea>
                        </div>
                    </div>

                    <!-- Hidden Fields (Defaults) -->
                    <input type="hidden" name="status" value="active">
                    <input type="hidden" name="author_id" value="{{ auth()->id() }}">

                    <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                        <a href="{{ route('user.dashboard') }}" class="px-6 py-2.5 rounded-lg border border-gray-300 text-gray-600 font-medium hover:bg-gray-50 transition">Cancel</a>
                        <button type="submit" class="px-8 py-2.5 rounded-lg bg-[#4838CC] text-white font-bold hover:bg-[#3b2db0] shadow-md transition transform hover:-translate-y-0.5">Publish Event</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function previewImage(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('preview').src = e.target.result;
                    document.getElementById('preview').classList.remove('hidden');
                    document.getElementById('placeholder').classList.add('hidden');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
@endsection
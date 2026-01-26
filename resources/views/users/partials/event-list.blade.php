<!-- SECTION 1: Exciting Learning Events (Gray Background) -->
<section class="bg-[#E5E5E5] py-12">
    <div class="container mx-auto px-4">
        
        <!-- Header -->
        <div class="flex justify-between items-end mb-8">
            <div>
                <h2 class="text-2xl md:text-3xl font-bold text-black drop-shadow-sm">Exciting Learning Events Tailored Just for You</h2>
                <p class="text-black/80 mt-2 text-sm md:text-base">Time to pick your favorite event! Boost your skills with our top learning recommendations.</p>
            </div>
            <a href="#" class="bg-[#567DF4] hover:bg-blue-600 text-white px-6 py-2 rounded-lg text-sm font-medium transition shadow-md">View All</a>
        </div>

        <!-- Grid Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-6">
            @forelse($excitingEvents as $event)
                <!-- MODIFIKASI: Ubah div menjadi a href ke detail -->
                <a href="{{ route('event.detail', $event->id) }}" class="flex flex-col group block">
                    <!-- Image Box -->
                    <div class="bg-white rounded-xl h-48 w-full mb-3 overflow-hidden shadow-md relative">
                        @if($event->poster_path)
                            <img src="{{ Storage::url($event->poster_path) }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-500 ease-in-out">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-gray-200 text-gray-400 text-xs">No Image</div>
                        @endif
                        
                        <!-- Discount Badge (Overlay) -->
                        @if($event->discount_percentage > 0)
                            <div class="absolute top-2 right-2 bg-[#C85250] text-white text-[10px] font-bold px-2 py-1 rounded shadow-sm">
                                {{ $event->discount_percentage }}% OFF
                            </div>
                        @endif
                    </div>
                    
                    <!-- Content (Text Black karena background abu-abu) -->
                    <h3 class="text-black font-bold text-xs uppercase tracking-wide truncate mb-1 group-hover:text-[#4838CC] transition">
                        {{ $event->name }}
                    </h3>
                    
                    <div class="text-gray-800 text-xs font-semibold mb-1">
                        From <span class="text-[#4838CC]">IDR {{ number_format($event->price, 0, ',', '.') }}</span>
                    </div>
                    
                    <div class="text-gray-500 text-[10px]">
                        {{ $event->category->name ?? 'General' }} • {{ $event->author->name ?? 'Admin' }}
                    </div>
                </a>
            @empty
                <div class="col-span-full text-center py-10">
                    <p class="text-gray-500 font-medium">No exciting events found at the moment.</p>
                </div>
            @endforelse
        </div>
    </div>
</section>

<!-- SECTION 2: Latest Events (Purple Background) -->
<section class="bg-custom-purple py-12 border-t border-white/10">
    <div class="container mx-auto px-4">
        
        <!-- Header -->
        <div class="flex justify-between items-end mb-8">
            <div>
                <h2 class="text-2xl md:text-3xl font-bold text-white drop-shadow-md">Latest Events on SMART EVENT.ID</h2>
                <p class="text-indigo-200 mt-2 text-sm md:text-base">Always ready for something new? Don't miss these freshly launched events!</p>
            </div>
            <a href="{{ route('allEvents') }}" class="bg-[#567DF4] hover:bg-blue-600 text-white px-6 py-2 rounded-lg text-sm font-medium transition shadow-md border border-white/20">View All</a>
        </div>

        <!-- Grid Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-6">
            @forelse($latestEvents as $event)
                <!-- Card Item -->
                <div class="flex flex-col group cursor-pointer">
                    <!-- Image Box -->
                    <div class="bg-white rounded-xl h-48 w-full mb-3 overflow-hidden shadow-lg relative border-2 border-transparent group-hover:border-[#567DF4] transition">
                        @if($event->poster_path)
                            <img src="{{ Storage::url($event->poster_path) }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-500 ease-in-out">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-gray-200 text-gray-400 text-xs">No Image</div>
                        @endif

                        @if($event->discount_percentage > 0)
                            <div class="absolute top-2 right-2 bg-[#C85250] text-white text-[10px] font-bold px-2 py-1 rounded shadow-sm">
                                {{ $event->discount_percentage }}% OFF
                            </div>
                        @endif
                    </div>
                    
                    <!-- Content (Text White karena background ungu) -->
                    <h3 class="text-white font-bold text-xs uppercase tracking-wide truncate mb-1 group-hover:text-[#567DF4] transition">
                        {{ $event->name }}
                    </h3>
                    
                    <div class="text-indigo-100 text-xs font-semibold mb-1">
                        From IDR {{ number_format($event->price, 0, ',', '.') }}
                    </div>

                    <div class="text-indigo-300 text-[10px]">
                        {{ $event->created_at->diffForHumans() }}
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-10">
                    <p class="text-indigo-200 font-medium">No latest events yet.</p>
                </div>
            @endforelse
        </div>
    </div>
</section>
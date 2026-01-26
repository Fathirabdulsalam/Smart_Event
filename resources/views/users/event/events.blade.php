@extends('layouts.user')

@section('content')

    <!-- HEADER BACKGROUND -->
    <div class="bg-custom-purple py-12">
        <div class="container mx-auto px-4 text-center">
            <h1 class="text-3xl md:text-4xl font-bold text-white mb-2">Jelajahi Semua Acara</h1>
            <p class="text-indigo-200">Temukan acara terbaik yang sesuai dengan kebutuhan Anda.</p>
        </div>
    </div>

    <!-- MAIN CONTENT -->
    <div class="container mx-auto px-4 py-12">
        <div class="flex flex-col lg:flex-row gap-8">
            
            <!-- LEFT SIDEBAR: FILTERS -->
            <aside class="w-full lg:w-1/4 space-y-8">
                
                <!-- Search (Mobile Only - Optional if navbar hidden) -->
                <div class="lg:hidden">
                    <form action="{{ route('allEvents') }}" method="GET">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search events..." class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-[#4838CC] outline-none">
                    </form>
                </div>

                <!-- Category Filter -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="font-bold text-gray-800 text-lg">Kategori</h3>
                        @if(request('category'))
                            <a href="{{ route('allEvents') }}" class="text-xs text-red-500 hover:underline">Reset</a>
                        @endif
                    </div>
                    
                    <ul class="space-y-2">
                        <!-- All Categories Link -->
                        <li>
                            <a href="{{ route('allEvents', array_merge(request()->query(), ['category' => null, 'page' => null])) }}" 
                               class="flex justify-between items-center px-3 py-2 rounded-lg transition {{ !request('category') ? 'bg-indigo-50 text-[#4838CC] font-semibold' : 'text-gray-600 hover:bg-gray-50' }}">
                                <span>Semua Acara</span>
                            </a>
                        </li>

                        <!-- Dynamic Categories -->
                        @foreach($categories as $cat)
                            <li>
                                <a href="{{ route('allEvents', array_merge(request()->query(), ['category' => $cat->id, 'page' => null])) }}" 
                                   class="flex justify-between items-center px-3 py-2 rounded-lg transition {{ request('category') == $cat->id ? 'bg-indigo-50 text-[#4838CC] font-semibold' : 'text-gray-600 hover:bg-gray-50' }}">
                                    <span>{{ $cat->name }}</span>
                                    <span class="text-xs bg-gray-100 text-gray-500 py-0.5 px-2 rounded-full">{{ $cat->events_count }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>

            </aside>

            <!-- RIGHT CONTENT: EVENT GRID -->
            <div class="w-full lg:w-3/4">
                
                <!-- Toolbar (Sort & Count) -->
                <div class="flex flex-col sm:flex-row justify-between items-center mb-6 gap-4">
                    <p class="text-gray-600">Menampilkan <span class="font-bold text-gray-900">{{ $events->firstItem() ?? 0 }}-{{ $events->lastItem() ?? 0 }}</span> dari {{ $events->total() }} hasil</p>
                    
                    <!-- Sorting Form -->
                    <form id="sortForm" action="{{ route('allEvents') }}" method="GET" class="flex items-center gap-2">
                        <!-- Keep existing search/category params -->
                        @if(request('search')) <input type="hidden" name="search" value="{{ request('search') }}"> @endif
                        @if(request('category')) <input type="hidden" name="category" value="{{ request('category') }}"> @endif

                        <label for="sort" class="text-sm text-gray-500">Urutkan:</label>
                        <select name="sort" onchange="document.getElementById('sortForm').submit()" class="border border-gray-300 rounded-lg text-sm px-3 py-2 focus:ring-[#4838CC] focus:border-[#4838CC] outline-none cursor-pointer bg-white">
                            <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Terbaru</option>
                            <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Terlama</option>
                            <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Harga: Termurah ke Termahal</option>
                            <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Harga: Termahal ke Termurah</option>
                        </select>
                    </form>
                </div>

                <!-- Event Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse($events as $event)
                        <a href="{{ route('event.detail', $event->id) }}" class="flex flex-col group block bg-white rounded-xl shadow-sm hover:shadow-lg border border-gray-100 overflow-hidden transition duration-300">
                            <!-- Image -->
                            <div class="h-48 w-full relative overflow-hidden bg-gray-100">
                                @if($event->poster_path)
                                    <img src="{{ Storage::url($event->poster_path) }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-400 text-xs">No Image</div>
                                @endif

                                <!-- Badges -->
                                <div class="absolute top-2 right-2 flex flex-col items-end gap-1">
                                    @if($event->discount_percentage > 0)
                                        <span class="bg-[#C85250] text-white text-[10px] font-bold px-2 py-1 rounded shadow-sm">{{ $event->discount_percentage }}% OFF</span>
                                    @endif
                                </div>
                                <div class="absolute top-2 left-2">
                                    <span class="bg-white/90 backdrop-blur text-gray-800 text-[10px] font-bold px-2 py-1 rounded shadow-sm">
                                        {{ $event->category->name ?? 'General' }}
                                    </span>
                                </div>
                            </div>
                            
                            <!-- Content -->
                            <div class="p-4 flex flex-col flex-grow">
                                <div class="flex justify-between items-start mb-2">
                                    <div class="text-[10px] text-[#4838CC] font-bold bg-indigo-50 px-2 py-0.5 rounded">
                                        {{ $event->date->format('d M Y') }}
                                    </div>
                                    <div class="text-[10px] text-gray-400 flex items-center gap-1">
                                        {{ $event->views }}
                                    </div>
                                </div>

                                <h3 class="text-gray-900 font-bold text-sm uppercase tracking-wide leading-snug mb-3 group-hover:text-[#4838CC] transition line-clamp-2">
                                    {{ $event->name }}
                                </h3>
                                
                                <div class="mt-auto">
                                    <div class="text-xs text-gray-500 mb-1">Harga mulai dari</div>
                                    <div class="flex items-center gap-2">
                                        <span class="text-[#4838CC] font-bold text-lg">
                                            {{ $event->price == 0 ? 'Free' : 'IDR ' . number_format($event->price, 0, ',', '.') }}
                                        </span>
                                        @if($event->discount_percentage > 0)
                                            @php $original = $event->price / (1 - ($event->discount_percentage/100)); @endphp
                                            <span class="text-[10px] text-gray-400 line-through">{{ number_format($original, 0, ',', '.') }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </a>
                    @empty
                        <!-- Empty State -->
                        <div class="col-span-full flex flex-col items-center justify-center py-16 text-center">
                            <div class="bg-gray-100 rounded-full p-4 mb-4">
                                <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <h3 class="text-lg font-bold text-gray-800">No Events Found</h3>
                            <p class="text-gray-500">We couldn't find any events matching your criteria.</p>
                            <a href="{{ route('allEvents') }}" class="mt-4 text-[#4838CC] font-semibold hover:underline">Clear Filters</a>
                        </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                <div class="mt-10">
                    {{ $events->onEachSide(1)->links() }}
                </div>

            </div>
        </div>
    </div>

@endsection
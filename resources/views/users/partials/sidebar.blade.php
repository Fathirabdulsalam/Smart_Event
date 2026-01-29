<aside class="w-full lg:w-1/4 space-y-6">

    <!-- User Profile Card -->
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 text-center">
        <div class="relative w-24 h-24 mx-auto mb-4">
            @if (auth()->user()->photo_path)
                <img src="{{ Storage::url(auth()->user()->photo_path) }}"
                    class="w-full h-full rounded-full object-cover border-4 border-indigo-50">
            @else
                <img src="https://ui-avatars.com/api/?name={{ auth()->user()->name }}&background=4838CC&color=fff"
                    class="w-full h-full rounded-full object-cover border-4 border-indigo-50">
            @endif
        </div>
        <h2 class="text-xl font-bold text-gray-800">{{ auth()->user()->name }}</h2>
        <p class="text-gray-500 text-sm">{{ auth()->user()->email }}</p>
    </div>

    <!-- Navigation Menu -->
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
        <nav class="flex flex-col">

            <!-- Dashboard -->
            <a href="{{ route('user.dashboard') }}"
                class="flex items-center gap-3 px-6 py-4 transition {{ request()->routeIs('user.dashboard') ? 'bg-indigo-50 text-[#4838CC] font-bold border-l-4 border-[#4838CC]' : 'text-gray-600 hover:bg-gray-50 hover:text-[#4838CC]' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2-2v-2z">
                    </path>
                </svg>
                Dashboard
            </a>

            <!-- Manage Events -->
            <a href="{{ route('user.events.index') }}"
                class="flex items-center gap-3 px-6 py-4 transition {{ request()->routeIs('user.events.*') || request()->routeIs('user.event.*') ? 'bg-indigo-50 text-[#4838CC] font-bold border-l-4 border-[#4838CC]' : 'text-gray-600 hover:bg-gray-50 hover:text-[#4838CC]' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                    </path>
                </svg>
                Kelola Event
            </a>

            <!-- Manage Articles -->
            <a href="{{ route('user.articles.index') }}"
                class="flex items-center gap-3 px-6 py-4 transition {{ request()->routeIs('user.articles.index') || request()->routeIs('user.articles.index') ? 'bg-indigo-50 text-[#4838CC] font-bold border-l-4 border-[#4838CC]' : 'text-gray-600 hover:bg-gray-50 hover:text-[#4838CC]' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z">
                    </path>
                </svg>
                Kelola Artikel
            </a>

            <a href="{{ route('user.tickets.index') }}"
                class="flex items-center gap-3 px-6 py-4 transition {{ request()->routeIs('user.tickets.*') ? 'bg-indigo-50 text-[#4838CC] font-bold border-l-4 border-[#4838CC]' : 'text-gray-600 hover:bg-gray-50 hover:text-[#4838CC]' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z">
                    </path>
                </svg>
                Tiket Saya
            </a>

            <!-- Purchase History -->
            <a href="{{ route('user.transactions.index') }}"
                class="flex items-center gap-3 px-6 py-4 transition {{ request()->routeIs('user.transactions.*') ? 'bg-indigo-50 text-[#4838CC] font-bold border-l-4 border-[#4838CC]' : 'text-gray-600 hover:bg-gray-50 hover:text-[#4838CC]' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01">
                    </path>
                </svg>
                History Pembelian
            </a>

            <!-- Settings (Profile) -->
            <a href="{{ route('user.profile.edit') }}"
                class="flex items-center gap-3 px-6 py-4 transition {{ request()->routeIs('user.profile.*') ? 'bg-indigo-50 text-[#4838CC] font-bold border-l-4 border-[#4838CC]' : 'text-gray-600 hover:bg-gray-50 hover:text-[#4838CC]' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                    </path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                Pengaturan
            </a>

            <!-- Logout -->
            <form method="POST" action="{{ route('logoutUser') }}" class="border-t border-gray-100">
                @csrf
                <button type="submit"
                    class="flex w-full items-center gap-3 px-6 py-4 text-red-500 hover:bg-red-50 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                        </path>
                    </svg>
                    Keluar
                </button>
            </form>
        </nav>
    </div>
</aside>

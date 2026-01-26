<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Smart Event</title>
    @vite('resources/css/app.css')
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Alpine.js (Untuk Dropdown Sidebar) -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        /* Warna Ungu Brand (Untuk Section Body) */
        .bg-custom-purple {
            background-color: #4838CC;
        }

        /* Warna Navbar Gelap (Sesuai Gambar Referensi) */
        .bg-dark-navy {
            background-color: #4838CC;
        }

        .bg-dark-input {
            background-color: #3b2fac;
        }
    </style>
</head>

<body class="bg-gray-50 text-gray-800 flex flex-col min-h-screen">

    <!-- NAVBAR (Dark Theme) -->
    <nav class="bg-dark-navy py-3 px-6 shadow-md sticky top-0 z-50">
        <div class="container mx-auto flex flex-wrap justify-between items-center gap-4">

            <!-- LEFT SIDE: Logo & Search -->
            <div class="flex items-center gap-8 flex-1">
                <!-- 1. Logo -->
                <a href="{{ route('landingPage') }}" class="flex items-center gap-2 flex-shrink-0">
                    <!-- Pastikan logo Anda terlihat di background gelap (gunakan logo putih jika ada) -->
                    <img src="{{ asset('img/logo-company 1.png') }}" class="h-8 brightness-0 invert"
                        alt="Smart Event Logo">
                </a>

                <!-- 2. Search Bar (Di Navbar) -->
                <div class="hidden lg:block w-full max-w-lg">
                    <form action="{{ route('allEvents') }}" method="GET" class="relative">
                        <input type="text" name="search" value="{{ request('search') }}"
                            class="block w-full pl-4 pr-10 py-2.5 text-sm text-white placeholder-gray-300 border-none rounded-md bg-dark-input focus:ring-2 focus:ring-blue-500 focus:bg-[#2A4480] transition"
                            placeholder="Cari event seru di sini">
                        <button type="submit" class="absolute inset-y-0 right-0 flex items-center pr-3">
                            <div class=" p-1.5 rounded-md">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                        </button>
                    </form>
                </div>
            </div>

            <!-- RIGHT SIDE: Menu & Auth -->
            <div class="flex items-center gap-6 flex-shrink-0 text-sm font-medium text-white">

                <!-- Menu Links -->
                <!-- Menu Links -->
                <div class="hidden md:flex items-center gap-6">

                    <!-- Jelajah Event -->
                    <a href="{{ route('landingPage') }}"
                        class="flex items-center gap-2 hover:text-blue-300 transition {{ request()->routeIs('events.all') ? 'text-blue-300 font-bold' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                            <polyline points="9 22 9 12 15 12 15 22"></polyline>
                        </svg>
                        Beranda
                    </a>

                    <!-- Buat Event -->
                    <a href="{{ !Auth::check()
                        ? route('login')
                        : (in_array(auth()->user()->role, ['admin', 'super_admin'])
                            ? route('events.index')
                            : route('user.events.index')) }}"
                        class="flex items-center gap-2 hover:text-blue-300 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                            </path>
                        </svg>
                        Buat Event
                    </a>

                    <!-- Jelajah Event -->
                    <a href="{{ route('allEvents') }}"
                        class="flex items-center gap-2 hover:text-blue-300 transition {{ request()->routeIs('events.all') ? 'text-blue-300 font-bold' : '' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z">
                            </path>
                        </svg>
                        Jelajah Event
                    </a>

                    <!-- Artikel -->
                    <a href="{{ route('articles.all') }}"
                        class="flex items-center gap-2 hover:text-blue-300 transition {{ request()->routeIs('articles.user.*') ? 'text-blue-300 font-bold' : '' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z">
                            </path>
                        </svg>
                        Artikel
                    </a>
                </div>
                <!-- MENU BARU: FAQ -->
                <a href="{{ route('faq') }}"
                    class="flex items-center gap-2 hover:text-blue-300 transition {{ request()->routeIs('faq') ? 'text-blue-300 font-bold' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                        </path>
                    </svg>
                    FAQ
                </a>

                <!-- Auth Buttons -->
                @auth
                    <!-- Logged In -->
                    <div class="relative" id="userDropdownWrapper">
                        <button onclick="toggleUserMenu()" id="userDropdownBtn"
                            class="flex items-center gap-3 focus:outline-none pl-4 border-l border-gray-600">
                            <div class="text-right hidden md:block">
                                <div class="text-white font-bold">{{ auth()->user()->name }}</div>
                            </div>
                            <div
                                class="w-9 h-9 rounded-full bg-white/20 flex items-center justify-center overflow-hidden border border-white/50">
                                @if (auth()->user()->photo_path)
                                    <img src="{{ Storage::url(auth()->user()->photo_path) }}"
                                        class="w-full h-full object-cover">
                                @else
                                    <span
                                        class="text-white font-bold text-xs">{{ substr(auth()->user()->name, 0, 2) }}</span>
                                @endif
                            </div>
                            <svg id="chevronIcon" class="w-4 h-4 text-gray-400 transition-transform duration-200"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                                </path>
                            </svg>
                        </button>

                        <!-- Dropdown Menu -->
                        <div id="userDropdownMenu"
                            class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-2 border border-gray-100 z-50 transform origin-top-right transition-all">

                            <!-- Header Mobile Only (Optional) -->
                            <div class="px-4 py-2 border-b border-gray-100 lg:hidden">
                                <div class="text-sm font-medium text-gray-900">{{ auth()->user()->name }}</div>
                                <div class="text-xs text-gray-500">{{ auth()->user()->email }}</div>
                            </div>

                            <!-- LOGIC DASHBOARD LINK -->
                            @if (in_array(auth()->user()->role, ['admin', 'super_admin']))
                                {{-- Jika Admin, masuk ke Dashboard Admin --}}
                                <a href="{{ route('dashboardAdmin') }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-[#4838CC]">
                                    Admin Dashboard
                                </a>
                            @else
                                {{-- Jika User Biasa/Creator, masuk ke Dashboard User --}}
                                <a href="{{ route('user.dashboard') }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-[#4838CC]">
                                    Dashboard
                                </a>
                            @endif

                            <div class="border-t border-gray-100 my-1"></div>

                            <!-- Logout Form (Gunakan route logout yang umum) -->
                            <form method="POST"
                                action="{{ in_array(auth()->user()->role, ['admin', 'super_admin']) ? route('logoutAdmin') : route('logoutUser') }}">
                                @csrf
                                <button type="submit"
                                    class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 flex items-center gap-2">
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <!-- Guest (Buttons Sesuai Gambar) -->
                    <div class="flex items-center gap-3 pl-4 border-l border-gray-600">
                        <a href="{{ route('register') }}"
                            class="text-white border border-white px-5 py-1.5 rounded-md font-semibold hover:bg-white hover:text-[#152955] transition">
                            Daftar
                        </a>
                        <a href="{{ route('login') }}"
                            class="bg-[#292078] text-white px-5 py-1.5 rounded-md font-semibold hover:bg-blue-700 transition shadow-lg shadow-blue-900/50">
                            Masuk
                        </a>
                    </div>
                @endauth
            </div>
        </div>
    </nav>

    <!-- CONTENT -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- FOOTER -->
    <!-- FOOTER -->
    <footer class="bg-dark-navy py-12 border-t border-gray-800 text-white">
        <div class="container mx-auto px-4 text-center">

            <!-- 1. Social Media Icons (Dynamic) -->
            <div class="flex justify-center items-center gap-6 mb-8">
                @if (isset($socialMedias))
                    @foreach ($socialMedias as $sosmed)
                        <a href="{{ $sosmed->link_url }}" target="_blank" title="{{ $sosmed->name }}"
                            class="w-10 h-10 rounded-full border border-white bg-white/5 flex items-center justify-center text-white hover:bg-white hover:text-[#4838CC] transition-all duration-300 transform hover:-translate-y-1">

                            @switch($sosmed->platform)
                                @case('instagram')
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <rect x="2" y="2" width="20" height="20" rx="5" ry="5">
                                        </rect>
                                        <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path>
                                        <line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line>
                                    </svg>
                                @break

                                @case('facebook')
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path>
                                    </svg>
                                @break

                                @case('twitter')
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z" />
                                    </svg>
                                @break

                                @case('linkedin')
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path
                                            d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z">
                                        </path>
                                        <rect x="2" y="9" width="4" height="12"></rect>
                                        <circle cx="4" cy="4" r="2"></circle>
                                    </svg>
                                @break

                                @case('youtube')
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path
                                            d="M22.54 6.42a2.78 2.78 0 0 0-1.94-2C18.88 4 12 4 12 4s-6.88 0-8.6.46a2.78 2.78 0 0 0-1.94 2A29 29 0 0 0 1 11.75a29 29 0 0 0 .46 5.33A2.78 2.78 0 0 0 3.4 19c1.72.46 8.6.46 8.6.46s6.88 0 8.6-.46a2.78 2.78 0 0 0 1.94-2 29 29 0 0 0 .46-5.33 29 29 0 0 0-.46-5.33z">
                                        </path>
                                        <polygon points="9.75 15.02 15.5 11.75 9.75 8.48 9.75 15.02"></polygon>
                                    </svg>
                                @break

                                @case('tiktok')
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M9 12a4 4 0 1 0 4 4V4a5 5 0 0 0 5 5"></path>
                                    </svg>
                                @break

                                @default
                                    <!-- Fallback Icon (Link) -->
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1">
                                        </path>
                                    </svg>
                            @endswitch

                        </a>
                    @endforeach
                @endif
            </div>
            <!-- 2. Links (Syarat | FAQ | Tentang) -->
            <div
                class="flex flex-wrap justify-center items-center gap-4 md:gap-6 text-sm font-medium text-gray-400 mb-8">
                <a href="{{ route('terms') }}" class="text-white transition-colors">Syarat & Ketentuan</a>
                <span class="text-white hidden md:inline">|</span>

                <a href="{{ route('faq') }}" class="text-white transition-colors">FAQ</a>
                <span class="text-white hidden md:inline">|</span>

                <a href="{{ route('about') }}" class="text-white transition-colors">Tentang Kami</a>
            </div>

            <!-- 3. Copyright -->
            <p class="text-white text-xs md:text-sm border-t border-gray-800 pt-8">
                &copy; {{ date('Y') }} Smart Event ID. All rights reserved.
            </p>
        </div>
    </footer>

    <!-- SCRIPT DROPDOWN -->
    <script>
        function toggleUserMenu() {
            const menu = document.getElementById('userDropdownMenu');
            const chevron = document.getElementById('chevronIcon');
            menu.classList.toggle('hidden');
            if (menu.classList.contains('hidden')) {
                chevron.classList.remove('rotate-180');
            } else {
                chevron.classList.add('rotate-180');
            }
        }

        window.addEventListener('click', function(e) {
            const wrapper = document.getElementById('userDropdownWrapper');
            const menu = document.getElementById('userDropdownMenu');
            const chevron = document.getElementById('chevronIcon');
            if (wrapper && !wrapper.contains(e.target)) {
                if (!menu.classList.contains('hidden')) {
                    menu.classList.add('hidden');
                    if (chevron) chevron.classList.remove('rotate-180');
                }
            }
        });
    </script>

    @stack('scripts')

</body>

</html>

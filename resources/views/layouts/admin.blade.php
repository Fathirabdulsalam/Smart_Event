<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Smart Event Admin</title>

    @vite('resources/css/app.css')

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Alpine.js & Collapse Plugin -->
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        .bg-primary-purple {
            background-color: #6C5DD3;
        }

        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body class="bg-gray-50 text-gray-800">

    <div class="flex h-screen overflow-hidden">

        <!-- SIDEBAR -->
        <aside class="w-64 bg-white border-r border-gray-200 hidden md:flex flex-col flex-shrink-0">
            <!-- Logo -->
            <div class="h-16 flex items-center px-6 border-b border-gray-100">
                <div class="flex items-center gap-2 font-bold text-xl text-gray-800">
                    <a href="{{ route('landingPage') }}">
                        <img src="{{ asset('img/logo-company 1.png') }}" class="w-25" alt="Logo">
                    </a>
                </div>
            </div>

            <!-- Menu -->
            <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">

                <!-- 1. DASHBOARD -->
                <a href="{{ route('dashboardAdmin') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('dashboardAdmin') ? 'bg-primary-purple text-white shadow-md' : 'text-gray-500 hover:bg-gray-50' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z">
                        </path>
                    </svg>
                    <span class="font-medium">Dashboard</span>
                </a>

                <!-- 2. DATA MASTER (DROPDOWN GROUP) -->
                <!-- Logic Open: Jika route saat ini diawali 'admin/master' ATAU route adalah kategori -->
                <div x-data="{ open: {{ request()->is('admin/master*') || request()->routeIs('categories.*') ? 'true' : 'false' }} }">
                    <button @click="open = !open"
                        class="flex items-center justify-between w-full px-4 py-3 text-gray-500 hover:bg-gray-50 rounded-lg transition-colors group">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 {{ request()->is('admin/master*') || request()->routeIs('categories.*') ? 'text-[#6C5DD3]' : 'group-hover:text-[#6C5DD3]' }}"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                                </path>
                            </svg>
                            <span
                                class="font-medium {{ request()->is('admin/master*') || request()->routeIs('categories.*') ? 'text-gray-800 font-bold' : 'group-hover:text-gray-800' }}">Data
                                Master</span>
                        </div>
                        <svg class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': open }"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                            </path>
                        </svg>
                    </button>

                    <!-- Submenu Items -->
                    <div x-show="open" x-collapse x-cloak class="pl-11 mt-1 space-y-1">

                        <!-- Master Tipe -->
                        <a href="{{ route('master.types.index') }}"
                            class="block py-2 text-sm {{ request()->routeIs('master.types.*') ? 'text-[#6C5DD3] font-bold' : 'text-gray-500 hover:text-[#6C5DD3]' }} transition-colors">
                            Tipe Event
                        </a>

                        <!-- Master Kategori & Sub -->
                        <a href="{{ route('categories.index') }}"
                            class="block py-2 text-sm {{ request()->routeIs('categories.*') ? 'text-[#6C5DD3] font-bold' : 'text-gray-500 hover:text-[#6C5DD3]' }}">
                            Kategori
                        </a>

                        <!-- Master Jenis Event -->
                        <a href="{{ route('master.event-kinds.index') }}"
                            class="block py-2 text-sm {{ request()->routeIs('master.event-kinds.*') ? 'text-[#6C5DD3] font-bold' : 'text-gray-500 hover:text-[#6C5DD3]' }} transition-colors">
                            Jenis Event
                        </a>

                        <!-- Master Zona -->
                        <a href="{{ route('master.zones.index') }}"
                            class="block py-2 text-sm {{ request()->routeIs('master.zones.*') ? 'text-[#6C5DD3] font-bold' : 'text-gray-500 hover:text-[#6C5DD3]' }} transition-colors">
                            Zona Waktu
                        </a>

                        <!-- Master Lokasi -->
                        <a href="{{ route('master.locations.index') }}"
                            class="block py-2 text-sm {{ request()->routeIs('master.locations.*') ? 'text-[#6C5DD3] font-bold' : 'text-gray-500 hover:text-[#6C5DD3]' }} transition-colors">
                            Lokasi
                        </a>

                        <!-- Master Kategori Tiket -->
                        <a href="{{ route('master.ticket-categories.index') }}"
                            class="block py-2 text-sm {{ request()->routeIs('master.ticket-categories.*') ? 'text-[#6C5DD3] font-bold' : 'text-gray-500 hover:text-[#6C5DD3]' }} transition-colors">
                            Kategori Tiket
                        </a>

                        <!-- Master Slide -->
                        <a href="{{ route('master.slides.index') }}"
                            class="block py-2 text-sm {{ request()->routeIs('master.slides.*') ? 'text-[#6C5DD3] font-bold' : 'text-gray-500 hover:text-[#6C5DD3]' }} transition-colors">
                            Slide (Banner)
                        </a>

                        <a href="{{ route('social-medias.index') }}"
                            class="block py-2 text-sm {{ request()->routeIs('master.social-medias.*') ? 'text-[#6C5DD3] font-bold' : 'text-gray-500 hover:text-[#6C5DD3]' }} transition-colors">
                            Social Media
                        </a>
                    </div>
                </div>

                <!-- SEPARATOR: TRANSAKSI -->
                <div class="pt-4 pb-2 px-4 text-xs font-bold text-gray-400 uppercase tracking-wider">
                    TRANSAKSI
                </div>

                <!-- Buat Event -->
                <a href="{{ route('events.index') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('events.*') ? 'bg-primary-purple text-white shadow-md' : 'text-gray-500 hover:bg-gray-50' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                        </path>
                    </svg>
                    <span>Event</span>
                </a>

                <!-- Buat Iklan -->
                <a href="{{ route('advertisements.index') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('advertisements.*') ? 'bg-primary-purple text-white shadow-md' : 'text-gray-500 hover:bg-gray-50' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6 3 3 0 000 6v6a1 1 0 001 1h1a1 1 0 001-1v-6h1a1 1 0 001-1z">
                        </path>
                    </svg>
                    <span>Iklan</span>
                </a>

                <!-- SEPARATOR: PENGGUNA & SISTEM -->
                <div class="pt-4 pb-2 px-4 text-xs font-bold text-gray-400 uppercase tracking-wider">
                    PENGGUNA & SISTEM
                </div>

                <!-- Kelola Akun -->
                <a href="{{ route('authors.index') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('authors.*') ? 'bg-primary-purple text-white shadow-md' : 'text-gray-500 hover:bg-gray-50' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                        </path>
                    </svg>
                    <span>Kelola Akun</span>
                </a>


                <a href="{{ route('faqs.index') }}"
                    class="flex items-center gap-3 px-4 py-3 text-gray-500 hover:bg-gray-50 rounded-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                        </path>
                    </svg>
                    <span>FAQ</span>
                </a>

                 <a href="{{ route('master.pages.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('master.pages.*') ? 'bg-primary-purple text-white shadow-md' : 'text-gray-500 hover:bg-gray-50' }}">
                    <!-- Ikon Dokumen -->
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    <span>Syarat dan Ketentuan</span>
                </a>

            </nav>
        </aside>

        <!-- MAIN CONTENT WRAPPER -->
        <div class="flex-1 flex flex-col h-screen overflow-hidden">

            <!-- TOPBAR -->
            <header class="h-20 bg-white shadow-sm flex items-center justify-between px-8 z-20 relative">
                <!-- Search -->
                <div class="relative w-96">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </span>
                    <input type="text" placeholder="Cari data..."
                        class="w-full bg-gray-100 text-gray-700 border-none rounded-full py-2 pl-10 pr-4 focus:ring-2 focus:ring-indigo-300 focus:bg-white transition">
                </div>

                <!-- User Dropdown & Icons -->
                <div class="flex items-center gap-6">
                    <button class="text-gray-500 hover:text-indigo-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                            </path>
                        </svg>
                        <span
                            class="absolute top-0 right-0 block h-2 w-2 rounded-full ring-2 ring-white bg-red-500"></span>
                    </button>

                    <!-- User Profile Dropdown -->
                    <div class="relative" id="userDropdownWrapper">
                        <button onclick="toggleUserMenu()"
                            class="flex items-center gap-3 pl-4 border-l focus:outline-none">
                            <img class="h-10 w-10 rounded-full object-cover border-2 border-indigo-100"
                                src="{{ auth()->user()->photo_path ? Storage::url(auth()->user()->photo_path) : 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) . '&background=6C5DD3&color=fff' }}"
                                alt="User">
                            <div class="text-sm font-medium text-gray-700 hidden lg:block text-left">
                                <div>{{ auth()->user()->name }}</div>
                                <div class="text-xs text-gray-400">{{ ucfirst(auth()->user()->role ?? 'Admin') }}
                                </div>
                            </div>
                            <svg id="userMenuChevron" class="w-4 h-4 text-gray-400 transition-transform duration-200"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>

                        <div id="userDropdownMenu"
                            class="hidden absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg py-2 border border-gray-100 z-50 transform origin-top-right transition-all">
                            <a href="{{ route('profile.index') }}"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-[#6C5DD3]">Profil</a>
                            <div class="border-t border-gray-100 my-1"></div>
                            <form method="POST" action="{{ route('logoutAdmin') }}">
                                @csrf
                                <button type="submit"
                                    class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 flex items-center gap-2">
                                    Keluar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 p-8">
                @yield('content')
            </main>

        </div>
    </div>

    <!-- Scripts -->
    <script>
        function toggleUserMenu() {
            const menu = document.getElementById('userDropdownMenu');
            const chevron = document.getElementById('userMenuChevron');
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
            const chevron = document.getElementById('userMenuChevron');
            if (wrapper && !wrapper.contains(e.target)) {
                if (!menu.classList.contains('hidden')) {
                    menu.classList.add('hidden');
                    chevron.classList.remove('rotate-180');
                }
            }
        });
    </script>

    @stack('scripts')
</body>

</html>

@extends('layouts.admin')

@section('content')
    <div class="space-y-6">
        <!-- Notification -->
        @if (session('success') && !str_contains(session('success'), 'dihapus'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                <strong class="font-bold">Success!</strong> <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                <strong class="font-bold">Error!</strong>
                <ul class="list-disc pl-5 text-xs mt-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Toolbar -->
        <div
            class="bg-white rounded-xl p-4 shadow-sm border border-gray-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <form action="{{ route('events.index') }}" method="GET" class="flex items-center gap-4 flex-1">
                <div class="relative flex-1 max-w-xs">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Event"
                        class="w-full bg-gray-100 border-none text-gray-700 text-sm rounded-lg py-2.5 pl-10 pr-4 focus:ring-2 focus:ring-[#6C5DD3]">
                </div>
            </form>

            <div class="flex flex-wrap items-center gap-3">
                <button onclick="openCreateModal()"
                    class="flex items-center gap-2 bg-[#6C5DD3] hover:bg-[#5b4ec2] text-white text-sm font-medium px-5 py-2.5 rounded-lg transition shadow-md shadow-indigo-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Tambah Event
                </button>
            </div>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="text-gray-400 text-xs border-b border-gray-100">
                            <th class="py-4 pl-6 pr-4 w-10">#</th>
                            <th class="py-4 px-4 font-normal">Nama Event</th>
                            <th class="py-4 px-4 font-normal">Tanggal</th>
                            <th class="py-4 px-4 font-normal">Kategori</th>
                            <th class="py-4 px-4 font-normal">Harga</th>
                            <th class="py-4 px-4 font-normal">Status</th>
                            <th class="py-4 px-6 text-right font-normal">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($events as $event)
                            <tr class="hover:bg-gray-50 group transition-colors">
                                <td class="py-4 pl-6 pr-4 text-sm text-gray-500">
                                    {{ $loop->iteration + ($events->currentPage() - 1) * $events->perPage() }}
                                </td>
                                <td class="py-4 px-4">
                                    <div class="flex items-center gap-3">
                                        <div class="h-10 w-10 rounded-lg bg-gray-200 overflow-hidden flex-shrink-0">
                                            @if ($event->poster_path)
                                                <img src="{{ Storage::url($event->poster_path) }}"
                                                    class="h-full w-full object-cover">
                                            @else
                                                <div
                                                    class="h-full w-full flex items-center justify-center text-xs text-gray-500">
                                                    IMG</div>
                                            @endif
                                        </div>
                                        <div>
                                            <div class="font-bold text-gray-700 text-sm">{{ $event->name }}</div>
                                            <div class="text-xs text-gray-400 mt-0.5">
                                                {{ $event->author->name ?? 'Unknown' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <!-- Kolom Date & Time -->
                                <td class="py-4 px-4">
                                    {{-- <div class="text-sm font-semibold text-gray-700">
                                        {{ $event->date ? $event->date->format('d M Y') : '-' }}</div> --}}
                                    <div class="text-sm font-semibold text-gray-700">
                                        {{ $event->date_label }}
                                    </div>

                                </td>

                                <td class="py-4 px-4 text-sm text-gray-700">{{ $event->category->name ?? '-' }}</td>

                                <td class="py-4 px-4 text-sm font-bold text-gray-800">
                                    {{ $event->price == 0 ? 'Free' : 'Rp ' . number_format($event->price, 0, ',', '.') }}
                                </td>
                                <td class="py-4 px-4">
                                    <span
                                        class="flex items-center gap-2 px-3 py-1.5 rounded-lg border border-gray-200 bg-white text-xs font-medium text-gray-600 w-fit">
                                        <span class="w-2 h-2 rounded-full bg-green-500"></span>
                                        {{ ucfirst($event->status) }}
                                    </span>
                                </td>
                                <td class="py-4 px-6 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <button
                                            onclick="openEditModal(
                                        '{{ $event->id }}',
                                        '{{ addslashes($event->name) }}',
                                        '{{ $event->date ? $event->date->format('Y-m-d') : '' }}',
                                        '{{ $event->end_date ? $event->end_date->format('Y-m-d') : '' }}',
                                        '{{ $event->start_time ? \Carbon\Carbon::parse($event->start_time)->format('H:i') : '' }}',
                                        '{{ $event->end_time ? \Carbon\Carbon::parse($event->end_time)->format('H:i') : '' }}',
                                        '{{ (int) $event->price }}',
                                        '{{ $event->discount_percentage }}',
                                        '{{ $event->author_id }}',
                                        '{{ $event->category_id }}',
                                        '{{ $event->master_type_id }}',
                                        '{{ $event->master_event_kind_id }}',
                                        '{{ $event->master_zone_id }}',
                                        '{{ $event->master_location_id }}',
                                        '{{ $event->master_ticket_category_id }}',
                                        '{{ addslashes(str_replace(["\r", "\n"], ' ', $event->details)) }}',
                                        '{{ $event->poster_path ? Storage::url($event->poster_path) : '' }}' 
                                    )"
                                            class="flex items-center gap-1 px-3 py-1.5 bg-[#6C5DD3] hover:bg-[#5b4ec2] text-white text-xs font-medium rounded-md transition shadow-sm">
                                            Edit
                                        </button>
                                        <button onclick="openDeleteModal('{{ $event->id }}')"
                                            class="flex items-center gap-1 px-3 py-1.5 bg-red-500 hover:bg-red-700 text-white text-xs font-medium rounded-md transition shadow-sm">
                                            Hapus
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-8 text-gray-500">Tidak ada event yang ditemukan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4 border-t border-gray-100">
                {{ $events->withQueryString()->links() }}
            </div>
        </div>
    </div>

    <!-- 1. CREATE EVENT MODAL -->
    <div id="createEventModal" class="relative z-50 hidden" role="dialog" aria-modal="true">
        <div class="fixed inset-0 backdrop-brightness-50 backdrop-blur-sm transition-opacity"></div>
        <div class="fixed inset-0 z-10 overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4">
                <div
                    class="relative transform rounded-xl bg-white text-left shadow-xl transition-all sm:w-full sm:max-w-3xl border border-gray-200">
                    <div class="flex justify-between items-center px-6 py-4 border-b border-gray-100">
                        <h3 class="text-xl font-bold text-[#6C5DD3]">Buat Event</h3>
                        <button onclick="closeModal('createEventModal')" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    <form action="{{ route('events.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="px-6 py-6 space-y-4">

                            <!-- Poster Input -->
                            <div class="w-full">
                                <label
                                    class="relative cursor-pointer flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg bg-gray-50 hover:bg-gray-100 transition overflow-hidden">
                                    <div id="create-placeholder"
                                        class="flex flex-col items-center justify-center pt-5 pb-6">
                                        <div
                                            class="w-10 h-10 border border-gray-400 rounded flex items-center justify-center text-gray-500 mb-2">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 4v16m8-8H4"></path>
                                            </svg>
                                        </div>
                                        <p class="text-sm text-gray-500 font-medium">Masukkan Poster</p>
                                    </div>
                                    <img id="create-preview" class="hidden absolute inset-0 w-full h-full object-cover">
                                    <input type="file" name="poster" class="hidden" accept="image/*" required
                                        onchange="previewImage(this, 'create-preview', 'create-placeholder')">
                                </label>
                            </div>

                            <!-- Grid Inputs -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="col-span-2">
                                    <label class="block text-xs font-semibold text-gray-600 mb-1">Nama Event</label>
                                    <input type="text" name="name"
                                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-[#6C5DD3]"
                                        required>
                                </div>

                                <!-- Grid Tanggal & Waktu -->
                                <div class="col-span-2 grid grid-cols-2 md:grid-cols-4 gap-4">
                                    <!-- Tanggal Mulai -->
                                    <div>
                                        <label class="block text-xs font-semibold text-gray-600 mb-1">Tanggal Mulai</label>
                                        <input type="date" name="date"
                                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-[#6C5DD3]"
                                            required>
                                    </div>

                                    <!-- Tanggal Selesai -->
                                    <div>
                                        <label class="block text-xs font-semibold text-gray-600 mb-1">Tanggal
                                            Selesai</label>
                                        <input type="date" name="end_date"
                                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-[#6C5DD3]"
                                            placeholder="Opsional">
                                        <p class="text-[9px] text-gray-400 mt-1">*Kosongkan jika 1 hari</p>
                                    </div>

                                    <!-- Jam Mulai -->
                                    <div>
                                        <label class="block text-xs font-semibold text-gray-600 mb-1">Jam Mulai</label>
                                        <input type="time" name="start_time"
                                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-[#6C5DD3]"
                                            required>
                                    </div>

                                    <!-- Jam Selesai -->
                                    <div>
                                        <label class="block text-xs font-semibold text-gray-600 mb-1">Jam Selesai</label>
                                        <input type="time" name="end_time"
                                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-[#6C5DD3]"
                                            required>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold text-gray-600 mb-1">Zona Waktu</label>
                                    <select name="master_zone_id"
                                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-[#6C5DD3]"
                                        required>
                                        @foreach ($zones as $zone)
                                            <option value="{{ $zone->id }}">{{ $zone->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-600 mb-1">Kategori</label>
                                    <select name="category_id"
                                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-[#6C5DD3]"
                                        required>
                                        @foreach ($categories as $cat)
                                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-600 mb-1">Penyelenggara</label>
                                    <select name="author_id"
                                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-[#6C5DD3]"
                                        required>
                                        @foreach ($authors as $author)
                                            <option value="{{ $author->id }}">{{ $author->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <!-- Master Data Baru -->
                                <div>
                                    <label class="block text-xs font-semibold text-gray-600 mb-1">Tipe Event</label>
                                    <select name="master_type_id"
                                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-[#6C5DD3]"
                                        required>
                                        @foreach ($types as $type)
                                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-600 mb-1">Jenis Event</label>
                                    <select name="master_event_kind_id"
                                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-[#6C5DD3]"
                                        required>
                                        @foreach ($kinds as $kind)
                                            <option value="{{ $kind->id }}">{{ $kind->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-600 mb-1">Lokasi</label>
                                    <select name="master_location_id"
                                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-[#6C5DD3]"
                                        required>
                                        @foreach ($locations as $loc)
                                            <option value="{{ $loc->id }}">{{ $loc->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-600 mb-1">Kategori Tiket</label>
                                    <select name="master_ticket_category_id"
                                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-[#6C5DD3]"
                                        required>
                                        @foreach ($ticketCategories as $tc)
                                            <option value="{{ $tc->id }}">{{ $tc->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-600 mb-1">Harga Tiket</label>
                                    <input type="number" name="price"
                                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-[#6C5DD3]"
                                        required>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-600 mb-1">Diskon (%)</label>
                                    <select name="discount_percentage"
                                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-[#6C5DD3] bg-white">
                                        <option value="0">0%</option>
                                        <option value="10">10%</option>
                                        <option value="20">20%</option>
                                        <option value="50">50%</option>
                                    </select>
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1">Deskripsi Event</label>
                                <textarea name="details" rows="3"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-[#6C5DD3] resize-none" required></textarea>
                            </div>
                            <div class="flex justify-end pt-2">
                                <button type="submit"
                                    class="bg-[#6C5DD3] text-white font-medium py-2 px-8 rounded-lg hover:bg-[#5b4ec2]">Simpan</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- 2. EDIT EVENT MODAL -->
    <div id="editEventModal" class="relative z-50 hidden" role="dialog" aria-modal="true">
        <div class="fixed inset-0 backdrop-brightness-50 backdrop-blur-sm transition-opacity"></div>
        <div class="fixed inset-0 z-10 overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4">
                <div
                    class="relative transform rounded-xl bg-white text-left shadow-xl transition-all sm:w-full sm:max-w-3xl border border-gray-200">
                    <div class="flex justify-between items-center px-6 py-4 border-b border-gray-100">
                        <h3 class="text-xl font-bold text-[#6C5DD3]">Edit Event</h3>
                        <button onclick="closeModal('editEventModal')" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    <form id="editForm" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="px-6 py-6 space-y-4">

                            <!-- Poster Edit Preview -->
                            <div class="w-full">
                                <label
                                    class="relative cursor-pointer flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg bg-gray-50 hover:bg-gray-100 transition overflow-hidden">
                                    <div id="edit-placeholder"
                                        class="flex flex-col items-center justify-center pt-2 pb-2">
                                        <div
                                            class="w-8 h-8 border border-gray-400 rounded flex items-center justify-center text-gray-500 mb-2">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                </path>
                                            </svg>
                                        </div>
                                        <p class="text-sm text-gray-500 font-medium">Click to replace Poster</p>
                                    </div>
                                    <img id="edit-preview" class="hidden absolute inset-0 w-full h-full object-cover">
                                    <input type="file" name="poster" class="hidden" accept="image/*"
                                        onchange="previewImage(this, 'edit-preview', 'edit-placeholder')">
                                </label>
                            </div>

                            <!-- Inputs Grid -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="col-span-2">
                                    <label class="block text-sm font-semibold text-gray-600 mb-1">Nama Event</label>
                                    <input type="text" name="name" id="edit_name"
                                        class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-[#6C5DD3] outline-none"
                                        required>
                                </div>

                                <!-- BARIS TANGGAL & WAKTU (3 KOLOM) -->
                                <div class="col-span-2 grid grid-cols-3 gap-4">
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-600 mb-1">Tanggal</label>
                                        <input type="date" name="date" id="edit_date"
                                            class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-[#6C5DD3] outline-none text-gray-600"
                                            required>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold text-gray-600 mb-1">Tanggal
                                            Selesai</label>
                                        <!-- Tambahkan id="edit_end_date" di modal edit -->
                                        <input type="date" name="end_date" id="edit_end_date"
                                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-[#6C5DD3]"
                                            placeholder="Opsional">
                                        <p class="text-[10px] text-gray-400 mt-1">*Kosongkan jika event cuma 1 hari</p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-600 mb-1">Jam Mulai</label>
                                        <input type="time" name="start_time" id="edit_start_time"
                                            class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-[#6C5DD3] outline-none"
                                            required>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-600 mb-1">Jam Selesai</label>
                                        <input type="time" name="end_time" id="edit_end_time"
                                            class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-[#6C5DD3] outline-none"
                                            required>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-600 mb-1">Zona Waktu</label>
                                    <select name="master_zone_id" id="edit_zone"
                                        class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-[#6C5DD3]"
                                        required>
                                        @foreach ($zones as $zone)
                                            <option value="{{ $zone->id }}">{{ $zone->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-600 mb-1">Kategori</label>
                                    <select name="category_id" id="edit_category"
                                        class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-[#6C5DD3]"
                                        required>
                                        @foreach ($categories as $cat)
                                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-600 mb-1">Author</label>
                                    <select name="author_id" id="edit_author"
                                        class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-[#6C5DD3]"
                                        required>
                                        @foreach ($authors as $author)
                                            <option value="{{ $author->id }}">{{ $author->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-600 mb-1">Tipe Event</label>
                                    <select name="master_type_id" id="edit_type"
                                        class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-[#6C5DD3]"
                                        required>
                                        @foreach ($types as $type)
                                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-600 mb-1">Jenis Event</label>
                                    <select name="master_event_kind_id" id="edit_kind"
                                        class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-[#6C5DD3]"
                                        required>
                                        @foreach ($kinds as $kind)
                                            <option value="{{ $kind->id }}">{{ $kind->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-600 mb-1">Lokasi</label>
                                    <select name="master_location_id" id="edit_location"
                                        class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-[#6C5DD3]"
                                        required>
                                        @foreach ($locations as $loc)
                                            <option value="{{ $loc->id }}">{{ $loc->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-600 mb-1">Kategori Tiket</label>
                                    <select name="master_ticket_category_id" id="edit_ticket_cat"
                                        class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-[#6C5DD3]"
                                        required>
                                        @foreach ($ticketCategories as $tc)
                                            <option value="{{ $tc->id }}">{{ $tc->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-600 mb-1">Harga Tiket</label>
                                    <input type="number" name="price" id="edit_price"
                                        class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-[#6C5DD3]"
                                        required>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-600 mb-1">Diskon (%)</label>
                                    <select name="discount_percentage" id="edit_discount"
                                        class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-[#6C5DD3] bg-white">
                                        <option value="0">0%</option>
                                        <option value="10">10%</option>
                                        <option value="20">20%</option>
                                        <option value="50">50%</option>
                                    </select>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-600 mb-1">Detail Event</label>
                                <textarea name="details" id="edit_details" rows="3"
                                    class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-[#6C5DD3] resize-none" required></textarea>
                            </div>
                            <div class="flex justify-end pt-2">
                                <button type="submit"
                                    class="bg-[#6C5DD3] text-white font-medium py-2 px-8 rounded-lg hover:bg-[#5b4ec2]">Update</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- 3. DELETE MODAL -->
    <div id="deleteModal" class="relative z-50 hidden" role="dialog" aria-modal="true">
        <div class="fixed inset-0 backdrop-brightness-50 backdrop-blur-sm transition-opacity"></div>
        <div class="fixed inset-0 z-10 overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4 text-center">
                <div
                    class="relative transform overflow-hidden rounded-xl bg-white text-left shadow-2xl transition-all sm:w-full sm:max-w-md p-6">
                    <div class="text-center mt-2">
                        <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-2xl bg-[#FFCE50] mb-5">
                            <svg class="h-10 w-10 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-[#6C5DD3] leading-snug px-4">Delete This Event?</h3>
                        <p class="text-sm text-[#6C5DD3] opacity-80 mt-2 mb-6">This action cannot be undone. The event data
                            will be removed.</p>
                        <div class="flex items-center justify-center gap-4 mt-6">
                            <button onclick="closeModal('deleteModal')"
                                class="w-32 inline-flex justify-center rounded-lg border border-transparent px-4 py-2 bg-[#6C5DD3] text-base font-medium text-white hover:bg-[#5b4ec2]">Cancel</button>
                            <form id="deleteForm" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="w-32 inline-flex justify-center rounded-lg border border-transparent px-4 py-2 bg-[#6C5DD3] text-base font-medium text-white hover:bg-[#5b4ec2]">Delete</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 4. SUCCESS MODAL -->
    <div id="successModal" class="relative z-50 hidden" role="dialog" aria-modal="true">
        <div class="fixed inset-0 backdrop-brightness-50 backdrop-blur-sm transition-opacity"></div>
        <div class="fixed inset-0 z-10 overflow-y-auto flex items-center justify-center p-4 text-center">
            <div
                class="relative transform rounded-xl bg-white text-left shadow-2xl transition-all sm:w-full sm:max-w-sm p-8 border border-gray-200">
                <div class="text-center">
                    <div
                        class="mx-auto flex h-24 w-24 items-center justify-center rounded-full bg-[#00C851] mb-6 shadow-md">
                        <div class="h-20 w-20 rounded-full border-2 border-white flex items-center justify-center">
                            <svg class="h-10 w-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                stroke-width="3">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                    </div>
                    <h3 class="text-2xl font-bold text-[#6C5DD3] mb-2">Success!</h3>
                </div>
                <div class="mt-8">
                    <button onclick="closeModal('successModal')"
                        class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2.5 bg-[#6C5DD3] text-base font-medium text-white hover:bg-[#5b4ec2] transition">OK</button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            // --- Modal Control Functions ---
            function openCreateModal() {
                resetPreview('create-preview', 'create-placeholder');
                document.getElementById('createEventModal').classList.remove('hidden');
            }

            function closeModal(modalId) {
                document.getElementById(modalId).classList.add('hidden');
            }

            // --- Open Edit Modal & Populate Data ---
            function openEditModal(id, name, date, endDate, start, end, price, discount, authorId, catId, typeId, kindId,
                zoneId, locId,
                ticketId, details, posterUrl) {
                document.getElementById('edit_name').value = name;
                document.getElementById('edit_date').value = date;
                document.getElementById('edit_end_date').value = endDate;
                document.getElementById('edit_start_time').value = start;
                document.getElementById('edit_end_time').value = end;
                document.getElementById('edit_price').value = parseInt(price);
                document.getElementById('edit_discount').value = discount;
                document.getElementById('edit_author').value = authorId;
                document.getElementById('edit_category').value = catId;
                document.getElementById('edit_type').value = typeId;
                document.getElementById('edit_kind').value = kindId;
                document.getElementById('edit_zone').value = zoneId;
                document.getElementById('edit_location').value = locId;
                document.getElementById('edit_ticket_cat').value = ticketId;
                document.getElementById('edit_details').value = details;

                // Set Preview Image jika ada gambar lama
                if (posterUrl) {
                    const preview = document.getElementById('edit-preview');
                    const placeholder = document.getElementById('edit-placeholder');
                    preview.src = posterUrl;
                    preview.classList.remove('hidden');
                    placeholder.classList.add('hidden');
                } else {
                    resetPreview('edit-preview', 'edit-placeholder');
                }

                document.getElementById('editForm').action = '/admin/dashboard/events/' + id;
                document.getElementById('editEventModal').classList.remove('hidden');
            }

            function openDeleteModal(id) {
                document.getElementById('deleteForm').action = '/admin/dashboard/events/' + id;
                document.getElementById('deleteModal').classList.remove('hidden');
            }

            // --- Helper: Preview Image ---
            function previewImage(input, previewId, placeholderId) {
                const preview = document.getElementById(previewId);
                const placeholder = document.getElementById(placeholderId);

                if (input.files && input.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        preview.src = e.target.result;
                        preview.classList.remove('hidden');
                        placeholder.classList.add('hidden');
                    }
                    reader.readAsDataURL(input.files[0]);
                }
            }

            function resetPreview(previewId, placeholderId) {
                const preview = document.getElementById(previewId);
                const placeholder = document.getElementById(placeholderId);
                preview.src = "";
                preview.classList.add('hidden');
                placeholder.classList.remove('hidden');
            }

            document.addEventListener('keydown', function(event) {
                if (event.key === "Escape") {
                    document.querySelectorAll('[role="dialog"]').forEach(modal => modal.classList.add('hidden'));
                }
            });

            @if ($errors->any() && !request()->isMethod('put'))
                openCreateModal();
            @endif

            // Trigger Success Modal for Deleted items
            @if (session('success') && str_contains(session('success'), 'dihapus'))
                document.getElementById('successModal').classList.remove('hidden');
            @endif
        </script>
    @endpush
@endsection
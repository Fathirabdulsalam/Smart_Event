@extends('layouts.user')
@section('content')
<!-- Background Header -->
<div class="bg-[#4838CC] h-48 w-full absolute top-0 z-0"></div>
<div class="container mx-auto px-4 relative z-10 pt-8 pb-20">
    <div class="flex flex-col lg:flex-row gap-8">
        <!-- Sidebar -->
        @include('users.partials.sidebar')
        <!-- Main Content Area -->
        <div class="w-full lg:w-3/4">
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 min-h-[500px]">
                <!-- Notification -->
                @if (session('success') && !str_contains(session('success'), 'dihapus'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                        <strong class="font-bold">Berhasil!</strong> <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                @endif
                @if ($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                        <strong class="font-bold">Terjadi Kesalahan!</strong>
                        <ul class="list-disc pl-5 text-xs mt-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Header -->
                <div class="flex justify-between items-center mb-6 border-b border-gray-100 pb-4">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">Kelola Event</h1>
                        <p class="text-gray-500 text-sm">Buat dan kelola event kamu di sini.</p>
                    </div>
                    <button onclick="openCreateModal()"
                        class="bg-[#4838CC] hover:bg-[#3b2db0] text-white px-5 py-2.5 rounded-lg font-bold text-sm shadow-md transition flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Buat Event
                    </button>
                </div>

                <!-- Event List -->
                @if ($events->count() > 0)
                    <div class="space-y-4">
                        @foreach ($events as $event)
                            <div class="flex flex-col md:flex-row gap-4 border border-gray-100 rounded-xl p-4 hover:shadow-md transition bg-white items-center">
                                <!-- Image -->
                                <div class="w-full md:w-32 h-32 md:h-24 bg-gray-100 rounded-lg overflow-hidden flex-shrink-0 relative">
                                    @if ($event->poster_path)
                                        <img src="{{ Storage::url($event->poster_path) }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-gray-400 text-xs">Tidak Ada Gambar</div>
                                    @endif
                                    <div class="absolute top-0 left-0 bg-black/50 text-white text-[10px] px-2 py-0.5 rounded-br-lg capitalize">
                                        {{ $event->status }}
                                    </div>
                                </div>
                                <!-- Info -->
                                <div class="flex-1 w-full flex flex-col justify-center">
                                    <div class="flex justify-between items-start">
                                        <h3 class="font-bold text-gray-800 text-lg line-clamp-1">{{ $event->name }}</h3>
                                        <span class="text-[10px] bg-indigo-50 text-[#4838CC] px-2 py-1 rounded font-bold">
                                            {{ $event->category->name ?? '-' }}
                                        </span>
                                    </div>
                                    <div class="text-sm text-gray-500 mt-1 space-y-1">
                                        <div class="flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                            {{ $event->date->format('d M Y') }}
                                            @if ($event->end_date && $event->end_date != $event->date)
                                                - {{ $event->end_date->format('d M Y') }}
                                            @endif
                                        </div>
                                        <div class="flex items-center gap-1 text-xs">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            {{ \Carbon\Carbon::parse($event->start_time)->format('H:i') }} -
                                            {{ \Carbon\Carbon::parse($event->end_time)->format('H:i') }}
                                        </div>
                                        <div class="font-semibold text-gray-700">
                                            {{ $event->price == 0 ? 'Gratis' : 'Rp ' . number_format($event->price, 0, ',', '.') }}
                                        </div>
                                    </div>
                                </div>
                                <!-- Actions -->
                                <div class="flex gap-2 min-w-[120px] justify-end">
                                    <a href="{{ route('event.detail', $event->id) }}" target="_blank"
                                        class="p-2 bg-gray-100 text-gray-600 rounded-lg hover:bg-gray-200 text-center transition" title="Pratinjau">
                                        <svg class="w-4 h-4 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </a>
                                    <button
                                        onclick="openEditModal{{ $event->id }}()"
                                        class="p-2 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition text-center" title="Edit">
                                        <svg class="w-4 h-4 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                        </svg>
                                    </button>
                                    <button onclick="openDeleteModal('{{ $event->id }}')"
                                        class="p-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition text-center" title="Hapus">
                                        <svg class="w-4 h-4 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            {{-- Script khusus untuk edit event ini --}}
                            <script>
                            function openEditModal{{ $event->id }}() {
                                // Set semua field dasar
                                document.getElementById('edit_name').value = "{{ addslashes($event->name) }}";
                                document.getElementById('edit_date').value = "{{ $event->date->format('Y-m-d') }}";
                                document.getElementById('edit_end_date').value = "{{ $event->end_date ? $event->end_date->format('Y-m-d') : '' }}";
                                document.getElementById('edit_start_time').value = "{{ \Carbon\Carbon::parse($event->start_time)->format('H:i') }}";
                                document.getElementById('edit_end_time').value = "{{ \Carbon\Carbon::parse($event->end_time)->format('H:i') }}";
                                document.getElementById('edit_price').value = "{{ (int) $event->price }}";
                                document.getElementById('edit_category').value = "{{ $event->category_id }}";
                                document.getElementById('edit_type').value = "{{ $event->master_type_id }}";
                                document.getElementById('edit_kind').value = "{{ $event->master_event_kind_id }}";
                                document.getElementById('edit_zone').value = "{{ $event->master_zone_id }}";
                                document.getElementById('edit_location').value = "{{ $event->master_location_id ?? '' }}";
                                document.getElementById('edit_ticket_cat').value = "{{ $event->master_ticket_category_id }}";
                                document.getElementById('edit_details').value = "{{ addslashes(str_replace(["\r", "\n"], ' ', $event->details)) }}";
                                document.getElementById('edit_description').value = "{{ addslashes($event->description ?? '') }}";
                                document.getElementById('edit_terms').value = "{{ addslashes($event->terms ?? '') }}";
                                document.getElementById('edit_contact_name').value = "{{ addslashes($event->contact_name ?? '') }}";
                                document.getElementById('edit_contact_email').value = "{{ $event->contact_email ?? '' }}";
                                document.getElementById('edit_contact_phone').value = "{{ $event->contact_phone ?? '' }}";
                                document.getElementById('edit_sale_start_date').value = "{{ $event->sale_start_date ? $event->sale_start_date->format('Y-m-d') : '' }}";
                                document.getElementById('edit_sale_end_date').value = "{{ $event->sale_end_date ? $event->sale_end_date->format('Y-m-d') : '' }}";
                                document.getElementById('edit_sale_start_time').value = "{{ $event->sale_start_time ? \Carbon\Carbon::parse($event->sale_start_time)->format('H:i') : '' }}";
                                document.getElementById('edit_sale_end_time').value = "{{ $event->sale_end_time ? \Carbon\Carbon::parse($event->sale_end_time)->format('H:i') : '' }}";

                                // Online/Offline
                                document.getElementById('edit_online_link').value = "{{ $event->online_link ?? '' }}";
                                document.getElementById('edit_offline_place').value = "{{ $event->offline_place_name ?? '' }}";
                                document.getElementById('edit_offline_address').value = "{{ $event->offline_address ?? '' }}";
                                document.getElementById('edit_offline_maps').value = "{{ $event->offline_maps_link ?? '' }}";

                                // Poster
                                const editPreview = document.getElementById('edit-preview');
                                const editPlaceholder = document.getElementById('edit-placeholder');
                                const posterUrl = "{{ $event->poster_path ? Storage::url($event->poster_path) : '' }}";
                                if (posterUrl) {
                                    editPreview.src = posterUrl;
                                    editPreview.classList.remove('hidden');
                                    editPlaceholder.classList.add('hidden');
                                } else {
                                    editPreview.classList.add('hidden');
                                    editPlaceholder.classList.remove('hidden');
                                }

                                // Set form action
                                document.getElementById('editForm').action = "/user/event/{{ $event->id }}";

                                // Show modal
                                document.getElementById('editEventModal').classList.remove('hidden');

                                // Setup type toggle
                                setupTypeToggle('edit');

                                // Load tickets
                                let ticketHtml = '';
                                @foreach($event->tickets as $i => $ticket)
                                    ticketHtml += `
                                    <div class="ticket-item grid grid-cols-1 md:grid-cols-4 gap-2">
                                        <input type="text" name="tickets[{{ $i }}][name]" value="{{ addslashes($ticket->name) }}" class="border rounded px-2 py-1 text-sm" required>
                                        <input type="number" name="tickets[{{ $i }}][quantity]" value="{{ $ticket->quantity }}" min="1" class="border rounded px-2 py-1 text-sm" required>
                                        <input type="number" name="tickets[{{ $i }}][price]" value="{{ $ticket->price }}" min="0" class="border rounded px-2 py-1 text-sm" required>
                                        <textarea name="tickets[{{ $i }}][description]" class="border rounded px-2 py-1 text-sm resize-none" rows="1">{{ addslashes($ticket->description ?? '') }}</textarea>
                                    </div>`;
                                @endforeach

                                if (ticketHtml === '') {
                                    ticketHtml = `
                                    <div class="ticket-item grid grid-cols-1 md:grid-cols-4 gap-2">
                                        <input type="text" name="tickets[0][name]" placeholder="Nama Tiket" class="border rounded px-2 py-1 text-sm" required>
                                        <input type="number" name="tickets[0][quantity]" placeholder="Jumlah" min="1" class="border rounded px-2 py-1 text-sm" required>
                                        <input type="number" name="tickets[0][price]" placeholder="Harga (IDR)" min="0" class="border rounded px-2 py-1 text-sm" required>
                                        <textarea name="tickets[0][description]" placeholder="Deskripsi (opsional)" class="border rounded px-2 py-1 text-sm resize-none" rows="1"></textarea>
                                    </div>`;
                                    window.editTicketIndex = 1;
                                } else {
                                    window.editTicketIndex = {{ $event->tickets->count() }};
                                }

                                ticketHtml += `<button type="button" onclick="addTicketForEdit()" class="text-[#6C5DD3] text-sm font-medium mt-2">+ Tambah Tiket</button>`;
                                document.getElementById('edit_tickets_container').innerHTML = ticketHtml;
                            }
                            </script>
                        @endforeach
                    </div>
                    <div class="mt-6">{{ $events->links() }}</div>
                @else
                    <div class="text-center py-16">
                        <p class="text-gray-500">Kamu belum membuat event apa pun.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- CREATE EVENT MODAL -->
<div id="createEventModal" class="relative z-[9999] hidden" role="dialog" aria-modal="true">
    <div class="fixed inset-0 backdrop-brightness-50 backdrop-blur-sm transition-opacity"></div>
    <div class="fixed inset-0 z-[10000] overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="relative transform rounded-xl bg-white shadow-xl w-full max-w-3xl border border-gray-200">
                <div class="flex justify-between items-center px-6 py-4 border-b border-gray-100">
                    <h3 class="text-xl font-bold text-[#6C5DD3]">Buat Event Baru</h3>
                    <button onclick="closeModal('createEventModal')" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <form action="{{ route('user.event.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="px-6 py-6 space-y-5 max-h-[70vh] overflow-y-auto">
                        <!-- POSTER -->
                        <div>
                            <label class="relative cursor-pointer flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg bg-gray-50 hover:bg-gray-100 transition overflow-hidden">
                                <div id="create-placeholder" class="flex flex-col items-center justify-center pt-5 pb-6">
                                    <p class="text-sm text-gray-500 font-medium">Unggah Poster</p>
                                </div>
                                <img id="create-preview" class="hidden absolute inset-0 w-full h-full object-cover">
                                <input type="file" name="poster" accept="image/*" required class="hidden"
                                    onchange="previewImage(this, 'create-preview', 'create-placeholder')">
                            </label>
                        </div>

                        <!-- NAMA EVENT -->
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Nama Event</label>
                            <input type="text" name="name" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                        </div>

                        <!-- TANGGAL & WAKTU EVENT -->
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1">Tgl Mulai Event</label>
                                <input type="date" name="date" required class="w-full border rounded-lg px-3 py-2 text-sm">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1">Tgl Selesai Event</label>
                                <input type="date" name="end_date" class="w-full border rounded-lg px-3 py-2 text-sm">
                                <p class="text-[10px] text-gray-400 mt-1">Opsional</p>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1">Jam Mulai</label>
                                <input type="time" name="start_time" required class="w-full border rounded-lg px-3 py-2 text-sm">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1">Jam Selesai</label>
                                <input type="time" name="end_time" required class="w-full border rounded-lg px-3 py-2 text-sm">
                            </div>
                        </div>

                        <!-- PERIODE PENJUALAN -->
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1">Jual Mulai Tgl</label>
                                <input type="date" name="sale_start_date" class="w-full border rounded-lg px-3 py-2 text-sm">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1">Jual Sampai Tgl</label>
                                <input type="date" name="sale_end_date" class="w-full border rounded-lg px-3 py-2 text-sm">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1">Jam Mulai Jual</label>
                                <input type="time" name="sale_start_time" class="w-full border rounded-lg px-3 py-2 text-sm">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1">Jam Akhir Jual</label>
                                <input type="time" name="sale_end_time" class="w-full border rounded-lg px-3 py-2 text-sm">
                            </div>
                        </div>

                        <!-- KATEGORI & MASTER -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1">Kategori</label>
                                <select name="category_id" class="w-full border rounded-lg px-3 py-2 text-sm">
                                    @foreach ($categories as $cat)
                                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1">Tipe Event</label>
                                <select name="master_type_id" id="create_type" class="w-full border rounded-lg px-3 py-2 text-sm">
                                    @foreach ($types as $t)
                                        <option value="{{ $t->id }}">{{ $t->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1">Jenis Event</label>
                                <select name="master_event_kind_id" class="w-full border rounded-lg px-3 py-2 text-sm">
                                    @foreach ($kinds as $k)
                                        <option value="{{ $k->id }}">{{ $k->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1">Zona</label>
                                <select name="master_zone_id" class="w-full border rounded-lg px-3 py-2 text-sm">
                                    @foreach ($zones as $z)
                                        <option value="{{ $z->id }}">{{ $z->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1">Kategori Tiket</label>
                                <select name="master_ticket_category_id" class="w-full border rounded-lg px-3 py-2 text-sm">
                                    @foreach ($ticketCategories as $tc)
                                        <option value="{{ $tc->id }}">{{ $tc->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1">Lokasi (Offline)</label>
                                <select name="master_location_id" class="w-full border rounded-lg px-3 py-2 text-sm">
                                    <option value="">Pilih Lokasi</option>
                                    @foreach ($locations as $l)
                                        <option value="{{ $l->id }}">{{ $l->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- HARGA (legacy) -->
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Harga Default (IDR)</label>
                            <input type="number" name="price" value="0" class="w-full border rounded-lg px-3 py-2 text-sm">
                            <p class="text-[10px] text-gray-400 mt-1">Catatan: Harga utama akan diambil dari detail tiket.</p>
                        </div>

                        <!-- ONLINE -->
                        <div id="create_online_fields" class="hidden">
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Link Event Online</label>
                            <input type="url" name="online_link" class="w-full border rounded-lg px-3 py-2 text-sm"
                                placeholder="https://zoom.us / https://meet.google.com">
                        </div>

                        <!-- OFFLINE -->
                        <div id="create_offline_fields" class="hidden space-y-3">
                            <input type="text" name="offline_place_name" class="w-full border rounded-lg px-3 py-2 text-sm" placeholder="Nama Tempat">
                            <textarea name="offline_address" class="w-full border rounded-lg px-3 py-2 text-sm resize-none" placeholder="Alamat Lengkap"></textarea>
                            <input type="url" name="offline_maps_link" class="w-full border rounded-lg px-3 py-2 text-sm" placeholder="Link Google Maps">
                        </div>

                        <!-- DESKRIPSI & KETENTUAN -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1">Deskripsi Event</label>
                                <textarea name="description" rows="3" class="w-full border rounded-lg px-3 py-2 text-sm resize-none"></textarea>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1">Syarat & Ketentuan</label>
                                <textarea name="terms" rows="3" class="w-full border rounded-lg px-3 py-2 text-sm resize-none"></textarea>
                            </div>
                        </div>

                        <!-- INFO KONTAK -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1">Nama Narahubung</label>
                                <input type="text" name="contact_name" class="w-full border rounded-lg px-3 py-2 text-sm">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1">Email</label>
                                <input type="email" name="contact_email" class="w-full border rounded-lg px-3 py-2 text-sm">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1">No. Ponsel</label>
                                <input type="text" name="contact_phone" class="w-full border rounded-lg px-3 py-2 text-sm">
                            </div>
                        </div>

                        <!-- DETAIL -->
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Detail Tambahan</label>
                            <textarea name="details" rows="2" class="w-full border rounded-lg px-3 py-2 text-sm resize-none"></textarea>
                        </div>

                        <!-- TIKET (DYNAMIC) -->
                        <div>
                            <div class="flex justify-between items-center mb-2">
                                <label class="block text-xs font-semibold text-gray-600">Detail Tiket</label>
                                <button type="button" onclick="addTicketForCreate()" class="text-[#6C5DD3] text-sm font-medium">+ Tambah Tiket</button>
                            </div>
                            <div id="create_tickets_container" class="space-y-3">
                                <div class="ticket-item grid grid-cols-1 md:grid-cols-4 gap-2">
                                    <input type="text" name="tickets[0][name]" placeholder="Nama Tiket" class="border rounded px-2 py-1 text-sm" required>
                                    <input type="number" name="tickets[0][quantity]" placeholder="Jumlah" min="1" class="border rounded px-2 py-1 text-sm" required>
                                    <input type="number" name="tickets[0][price]" placeholder="Harga (IDR)" min="0" class="border rounded px-2 py-1 text-sm" required>
                                    <textarea name="tickets[0][description]" placeholder="Deskripsi (opsional)" class="border rounded px-2 py-1 text-sm resize-none" rows="1"></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- SUBMIT -->
                        <div class="flex justify-end pt-2">
                            <button type="submit" class="bg-[#6C5DD3] text-white px-8 py-2 rounded-lg hover:bg-[#5b4ec2]">
                                Simpan Event
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- EDIT EVENT MODAL -->
<div id="editEventModal" class="relative z-[9999] hidden" role="dialog" aria-modal="true">
    <div class="fixed inset-0 backdrop-brightness-50 backdrop-blur-sm transition-opacity"></div>
    <div class="fixed inset-0 z-[10000] overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="relative transform rounded-xl bg-white shadow-xl w-full max-w-3xl border border-gray-200">
                <div class="flex justify-between items-center px-6 py-4 border-b border-gray-100">
                    <h3 class="text-xl font-bold text-[#6C5DD3]">Edit Event</h3>
                    <button onclick="closeModal('editEventModal')" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <form id="editForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="px-6 py-6 space-y-5 max-h-[70vh] overflow-y-auto">
                        <!-- POSTER -->
                        <div>
                            <label class="relative cursor-pointer flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg bg-gray-50 hover:bg-gray-100 transition overflow-hidden">
                                <div id="edit-placeholder" class="flex flex-col items-center justify-center pt-5 pb-6">
                                    <p class="text-sm text-gray-500 font-medium">Klik untuk ganti poster</p>
                                </div>
                                <img id="edit-preview" class="hidden absolute inset-0 w-full h-full object-cover">
                                <input type="file" name="poster" accept="image/*" class="hidden"
                                    onchange="previewImage(this, 'edit-preview', 'edit-placeholder')">
                            </label>
                        </div>

                        <!-- Form fields -->
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Nama Event</label>
                            <input type="text" name="name" id="edit_name" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div><label class="block text-xs font-semibold text-gray-600 mb-1">Tgl Mulai Event</label>
                                <input type="date" name="date" id="edit_date" required class="w-full border rounded-lg px-3 py-2 text-sm"></div>
                            <div><label class="block text-xs font-semibold text-gray-600 mb-1">Tgl Selesai Event</label>
                                <input type="date" name="end_date" id="edit_end_date" class="w-full border rounded-lg px-3 py-2 text-sm"></div>
                            <div><label class="block text-xs font-semibold text-gray-600 mb-1">Jam Mulai</label>
                                <input type="time" name="start_time" id="edit_start_time" required class="w-full border rounded-lg px-3 py-2 text-sm"></div>
                            <div><label class="block text-xs font-semibold text-gray-600 mb-1">Jam Selesai</label>
                                <input type="time" name="end_time" id="edit_end_time" required class="w-full border rounded-lg px-3 py-2 text-sm"></div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div><label class="block text-xs font-semibold text-gray-600 mb-1">Jual Mulai Tgl</label>
                                <input type="date" name="sale_start_date" id="edit_sale_start_date" class="w-full border rounded-lg px-3 py-2 text-sm"></div>
                            <div><label class="block text-xs font-semibold text-gray-600 mb-1">Jual Sampai Tgl</label>
                                <input type="date" name="sale_end_date" id="edit_sale_end_date" class="w-full border rounded-lg px-3 py-2 text-sm"></div>
                            <div><label class="block text-xs font-semibold text-gray-600 mb-1">Jam Mulai Jual</label>
                                <input type="time" name="sale_start_time" id="edit_sale_start_time" class="w-full border rounded-lg px-3 py-2 text-sm"></div>
                            <div><label class="block text-xs font-semibold text-gray-600 mb-1">Jam Akhir Jual</label>
                                <input type="time" name="sale_end_time" id="edit_sale_end_time" class="w-full border rounded-lg px-3 py-2 text-sm"></div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1">Kategori</label>
                                <select name="category_id" id="edit_category" class="w-full border rounded-lg px-3 py-2 text-sm">
                                    @foreach ($categories as $cat)
                                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1">Tipe Event</label>
                                <select name="master_type_id" id="edit_type" class="w-full border rounded-lg px-3 py-2 text-sm">
                                    @foreach ($types as $t)
                                        <option value="{{ $t->id }}">{{ $t->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1">Jenis Event</label>
                                <select name="master_event_kind_id" id="edit_kind" class="w-full border rounded-lg px-3 py-2 text-sm">
                                    @foreach ($kinds as $k)
                                        <option value="{{ $k->id }}">{{ $k->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1">Zona</label>
                                <select name="master_zone_id" id="edit_zone" class="w-full border rounded-lg px-3 py-2 text-sm">
                                    @foreach ($zones as $z)
                                        <option value="{{ $z->id }}">{{ $z->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1">Kategori Tiket</label>
                                <select name="master_ticket_category_id" id="edit_ticket_cat" class="w-full border rounded-lg px-3 py-2 text-sm">
                                    @foreach ($ticketCategories as $tc)
                                        <option value="{{ $tc->id }}">{{ $tc->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1">Lokasi (Offline)</label>
                                <select name="master_location_id" id="edit_location" class="w-full border rounded-lg px-3 py-2 text-sm">
                                    <option value="">Pilih Lokasi</option>
                                    @foreach ($locations as $l)
                                        <option value="{{ $l->id }}">{{ $l->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Harga Default (IDR)</label>
                            <input type="number" name="price" id="edit_price" value="0" class="w-full border rounded-lg px-3 py-2 text-sm">
                        </div>

                        <div id="edit_online_fields" class="hidden">
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Link Event Online</label>
                            <input type="url" name="online_link" id="edit_online_link" class="w-full border rounded-lg px-3 py-2 text-sm">
                        </div>

                        <div id="edit_offline_fields" class="hidden space-y-3">
                            <input type="text" name="offline_place_name" id="edit_offline_place" class="w-full border rounded-lg px-3 py-2 text-sm" placeholder="Nama Tempat">
                            <textarea name="offline_address" id="edit_offline_address" class="w-full border rounded-lg px-3 py-2 text-sm resize-none" placeholder="Alamat Lengkap"></textarea>
                            <input type="url" name="offline_maps_link" id="edit_offline_maps" class="w-full border rounded-lg px-3 py-2 text-sm" placeholder="Link Google Maps">
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1">Deskripsi Event</label>
                                <textarea name="description" id="edit_description" rows="3" class="w-full border rounded-lg px-3 py-2 text-sm resize-none"></textarea>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1">Syarat & Ketentuan</label>
                                <textarea name="terms" id="edit_terms" rows="3" class="w-full border rounded-lg px-3 py-2 text-sm resize-none"></textarea>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1">Nama Narahubung</label>
                                <input type="text" name="contact_name" id="edit_contact_name" class="w-full border rounded-lg px-3 py-2 text-sm">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1">Email</label>
                                <input type="email" name="contact_email" id="edit_contact_email" class="w-full border rounded-lg px-3 py-2 text-sm">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1">No. Ponsel</label>
                                <input type="text" name="contact_phone" id="edit_contact_phone" class="w-full border rounded-lg px-3 py-2 text-sm">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Detail Tambahan</label>
                            <textarea name="details" id="edit_details" rows="2" class="w-full border rounded-lg px-3 py-2 text-sm resize-none"></textarea>
                        </div>

                        <!-- TIKET SECTION (EDIT) -->
                        <div>
                            <div class="flex justify-between items-center mb-2">
                                <label class="block text-xs font-semibold text-gray-600">Detail Tiket</label>
                                <button type="button" onclick="addTicketForEdit()" class="text-[#6C5DD3] text-sm font-medium">+ Tambah Tiket</button>
                            </div>
                            <div id="edit_tickets_container" class="space-y-3">
                                <!-- Tiket akan diisi via JS -->
                            </div>
                        </div>

                        <div class="flex justify-end pt-2">
                            <button type="submit" class="bg-[#6C5DD3] text-white px-8 py-2 rounded-lg hover:bg-[#5b4ec2]">
                                Perbarui Event
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- DELETE MODAL -->
<div id="deleteModal" class="relative z-[9999] hidden" role="dialog" aria-modal="true">
    <div class="fixed inset-0 backdrop-brightness-50 backdrop-blur-sm transition-opacity"></div>
    <div class="fixed inset-0 z-[10000] overflow-y-auto flex items-center justify-center p-4 text-center">
        <div class="relative transform rounded-xl bg-white text-left shadow-2xl sm:w-full sm:max-w-md p-6">
            <div class="text-center mt-2">
                <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-2xl bg-[#FFCE50] mb-5">
                    <svg class="h-10 w-10 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-[#6C5DD3] leading-snug px-4">Hapus Event Ini?</h3>
                <p class="text-sm text-[#6C5DD3] opacity-80 mt-2 mb-6">Tindakan ini tidak dapat dibatalkan. Data event akan dihapus permanen.</p>
                <div class="flex items-center justify-center gap-4 mt-6">
                    <button onclick="closeModal('deleteModal')" class="w-32 inline-flex justify-center rounded-lg border border-transparent px-4 py-2 bg-gray-300 text-base font-medium text-gray-700 hover:bg-gray-400">Batal</button>
                    <form id="deleteForm" method="POST">
                        @csrf @method('DELETE')
                        <button type="submit" class="w-32 inline-flex justify-center rounded-lg border border-transparent px-4 py-2 bg-[#6C5DD3] text-base font-medium text-white hover:bg-[#5b4ec2]">Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- SUCCESS MODAL -->
<div id="successModal" class="relative z-[9999] hidden" role="dialog" aria-modal="true">
    <div class="fixed inset-0 backdrop-brightness-50 backdrop-blur-sm transition-opacity"></div>
    <div class="fixed inset-0 z-[10000] overflow-y-auto flex items-center justify-center p-4 text-center">
        <div class="relative transform rounded-xl bg-white text-left shadow-2xl sm:w-full sm:max-w-sm p-8 border border-gray-200">
            <div class="text-center">
                <div class="mx-auto flex h-24 w-24 items-center justify-center rounded-full bg-[#00C851] mb-6 shadow-md">
                    <svg class="h-10 w-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-[#6C5DD3] mb-2">Berhasil!</h3>
            </div>
            <div class="mt-8">
                <button onclick="closeModal('successModal')" class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2.5 bg-[#6C5DD3] text-base font-medium text-white hover:bg-[#5b4ec2] transition">OK</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function qs(id) { return document.getElementById(id); }

// Toggle Online/Offline Fields
function setupTypeToggle(modalPrefix) {
    const typeSelect = qs(`${modalPrefix}_type`);
    const onlineBox = qs(`${modalPrefix}_online_fields`);
    const offlineBox = qs(`${modalPrefix}_offline_fields`);
    const locationSelect = qs(`${modalPrefix}_location`);

    function toggle() {
        onlineBox.classList.add('hidden');
        offlineBox.classList.add('hidden');
        if (locationSelect) {
            locationSelect.disabled = false;
            locationSelect.parentElement.classList.remove('hidden');
        }

        if (typeSelect.value == 1) { // Online
            onlineBox.classList.remove('hidden');
            if (locationSelect) {
                locationSelect.disabled = true;
                locationSelect.required = false;
                locationSelect.parentElement.classList.add('hidden');
            }
        } else if (typeSelect.value == 2) { // Offline
            offlineBox.classList.remove('hidden');
            if (locationSelect) {
                locationSelect.disabled = false;
                locationSelect.required = true;
                locationSelect.parentElement.classList.remove('hidden');
            }
        }
    }

    typeSelect.addEventListener('change', toggle);
    toggle();
}

// Create Modal Ticket
let createTicketIndex = 1;
function addTicketForCreate() {
    const container = qs('create_tickets_container');
    container.innerHTML += `
        <div class="ticket-item grid grid-cols-1 md:grid-cols-4 gap-2">
            <input type="text" name="tickets[${createTicketIndex}][name]" placeholder="Nama Tiket" class="border rounded px-2 py-1 text-sm" required>
            <input type="number" name="tickets[${createTicketIndex}][quantity]" placeholder="Jumlah" min="1" class="border rounded px-2 py-1 text-sm" required>
            <input type="number" name="tickets[${createTicketIndex}][price]" placeholder="Harga (IDR)" min="0" class="border rounded px-2 py-1 text-sm" required>
            <textarea name="tickets[${createTicketIndex}][description]" placeholder="Deskripsi (opsional)" class="border rounded px-2 py-1 text-sm resize-none" rows="1"></textarea>
        </div>
    `;
    createTicketIndex++;
}

// Edit Modal Ticket
let editTicketIndex = 0;
function addTicketForEdit() {
    const container = qs('edit_tickets_container');
    const addButton = container.querySelector('button');
    const html = `
        <div class="ticket-item grid grid-cols-1 md:grid-cols-4 gap-2 mt-2">
            <input type="text" name="tickets[${editTicketIndex}][name]" placeholder="Nama Tiket" class="border rounded px-2 py-1 text-sm" required>
            <input type="number" name="tickets[${editTicketIndex}][quantity]" placeholder="Jumlah" min="1" class="border rounded px-2 py-1 text-sm" required>
            <input type="number" name="tickets[${editTicketIndex}][price]" placeholder="Harga (IDR)" min="0" class="border rounded px-2 py-1 text-sm" required>
            <textarea name="tickets[${editTicketIndex}][description]" placeholder="Deskripsi (opsional)" class="border rounded px-2 py-1 text-sm resize-none" rows="1"></textarea>
        </div>
    `;
    if (addButton) {
        addButton.parentNode.insertBefore(document.createRange().createContextualFragment(html), addButton);
    } else {
        container.insertAdjacentHTML('beforeend', html);
    }
    editTicketIndex++;
}

// Modals
window.openCreateModal = function() {
    qs('createEventModal').classList.remove('hidden');
    setupTypeToggle('create');
};

window.openDeleteModal = function(id) {
    qs('deleteForm').action = `/user/event/${id}`;
    qs('deleteModal').classList.remove('hidden');
};

// Image Preview
window.previewImage = function(input, previewId, placeholderId) {
    const preview = qs(previewId);
    const placeholder = qs(placeholderId);
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            preview.src = e.target.result;
            preview.classList.remove('hidden');
            placeholder.classList.add('hidden');
        };
        reader.readAsDataURL(input.files[0]);
    }
};

// Close Modal
function closeModal(modalId) {
    qs(modalId).classList.add('hidden');
}

// ESC to close
document.addEventListener('keydown', e => {
    if (e.key === 'Escape') {
        document.querySelectorAll('[role="dialog"]').forEach(m => m.classList.add('hidden'));
    }
});

// Auto-open on error/success
@if ($errors->any() && !request()->isMethod('put'))
    openCreateModal();
@endif
@if (session('success') && str_contains(session('success'), 'dihapus'))
    qs('successModal')?.classList.remove('hidden');
@endif
</script>
@endpush
@endsection
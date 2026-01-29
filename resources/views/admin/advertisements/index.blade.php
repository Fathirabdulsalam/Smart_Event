@extends('layouts.admin')

@section('content')
    <div class="space-y-6">

        <!-- Notification -->
        @if(session('success') && !str_contains(session('success'), 'dihapus'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                <strong class="font-bold">Success!</strong> <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                <strong class="font-bold">Error!</strong>
                <ul class="list-disc pl-5 text-xs mt-1">
                    @foreach($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                </ul>
            </div>
        @endif

        <!-- SECTION 1: Top Toolbar -->
        <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <!-- Search -->
            <form action="{{ route('advertisements.index') }}" method="GET" class="flex items-center gap-4 flex-1">
                <div class="relative flex-1 max-w-xs">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search Advertisement" class="w-full bg-gray-100 border-none text-gray-700 text-sm rounded-lg py-2.5 pl-10 pr-4 focus:ring-2 focus:ring-[#6C5DD3]">
                </div>
            </form>

            <div class="flex flex-wrap items-center gap-3">
                <button onclick="openCreateModal()" class="flex items-center gap-2 bg-[#6C5DD3] hover:bg-[#5b4ec2] text-white text-sm font-medium px-5 py-2.5 rounded-lg transition shadow-md shadow-indigo-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Add Advertisement
                </button>
            </div>
        </div>

        <!-- SECTION 2: Table -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="text-gray-400 text-xs border-b border-gray-100">
                            <th class="py-4 pl-6 pr-4 w-10">#</th>
                            <th class="py-4 px-4 font-normal">Banner Preview</th> <!-- Kolom Baru -->
                            <th class="py-4 px-4 font-normal">Nama Event</th>
                            <th class="py-4 px-4 font-normal">Tanggal Mulai</th>
                            <th class="py-4 px-4 font-normal">Tanggal Selesai</th>
                            <th class="py-4 px-4 font-normal">Status</th>
                            <th class="py-4 px-6 text-right font-normal">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($advertisements as $ad)
                        <tr class="hover:bg-gray-50 group transition-colors">
                            <td class="py-4 pl-6 pr-4 text-sm text-gray-500">
                                {{ $loop->iteration + ($advertisements->currentPage() - 1) * $advertisements->perPage() }}
                            </td>
                            <!-- Banner Preview -->
                            <td class="py-4 px-4">
                                <div class="h-12 w-24 bg-gray-200 rounded-lg overflow-hidden border border-gray-300">
                                    @if($ad->banner_path)
                                        <img src="{{ Storage::url($ad->banner_path) }}" class="h-full w-full object-cover">
                                    @else
                                        <div class="h-full w-full flex items-center justify-center text-xs text-gray-500">No Img</div>
                                    @endif
                                </div>
                            </td>
                            <td class="py-4 px-4 font-bold text-gray-800">
                                {{ $ad->event->name ?? 'Deleted Event' }}
                            </td>
                            <td class="py-4 px-4 text-sm font-semibold text-gray-800">
                                {{ $ad->start_date->format('d-m-Y') }}
                            </td>
                            <td class="py-4 px-4 text-sm font-semibold text-gray-800">
                                {{ $ad->end_date->format('d-m-Y') }}
                            </td>
                            <td class="py-4 px-4">
                                <span class="flex items-center gap-2 px-3 py-1.5 rounded-lg border border-gray-200 bg-white text-xs font-medium text-gray-600 w-fit">
                                    <span class="w-2 h-2 rounded-full {{ $ad->status == 'active' ? 'bg-green-500' : ($ad->status == 'pending' ? 'bg-yellow-500' : 'bg-red-500') }}"></span>
                                    {{ ucfirst($ad->status) }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-right">
                                <div class="flex items-center justify-end gap-3">
                                    <!-- Edit Button -->
                                    <button onclick="openEditModal(
                                        '{{ $ad->id }}',
                                        '{{ $ad->event_id }}',
                                        '{{ $ad->start_date->format('Y-m-d') }}',
                                        '{{ $ad->end_date->format('Y-m-d') }}',
                                        '{{ $ad->status }}',
                                        '{{ $ad->banner_path ? Storage::url($ad->banner_path) : '' }}'
                                    )" class="flex items-center gap-1 px-4 py-1.5 bg-[#6C5DD3] hover:bg-[#5b4ec2] text-white text-xs font-medium rounded-md transition shadow-sm">
                                        Edit
                                    </button>
                                    
                                    <!-- Delete Button -->
                                    <button onclick="openDeleteModal('{{ $ad->id }}')" class="text-gray-400 hover:text-red-500 transition p-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="7" class="text-center py-8 text-gray-500">No advertisements found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $advertisements->withQueryString()->links() }}
            </div>
        </div>
    </div>

    <!-- 1. CREATE MODAL -->
    <div id="createModal" class="relative z-50 hidden" role="dialog" aria-modal="true">
        <div class="fixed inset-0 backdrop-brightness-50 backdrop-blur-sm transition-opacity"></div>
        <div class="fixed inset-0 z-10 overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4">
                <div class="relative transform rounded-xl bg-white text-left shadow-xl transition-all sm:w-full sm:max-w-lg border border-gray-200">
                    <form action="{{ route('advertisements.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="bg-white px-6 py-6 space-y-6">
                            <h3 class="text-xl font-bold text-[#6C5DD3] border-b pb-4">Add Advertisement</h3>
                            
                            <!-- Upload Banner Input -->
                            <div class="w-full">
                                <label class="relative cursor-pointer flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg bg-gray-50 hover:bg-gray-100 transition overflow-hidden">
                                    <div id="create-placeholder" class="flex flex-col items-center justify-center pt-5 pb-6">
                                        <div class="w-10 h-10 border border-gray-400 rounded flex items-center justify-center text-gray-500 mb-2">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        </div>
                                        <p class="text-sm text-gray-500 font-medium">Upload Banner Image</p>
                                        <p class="text-xs text-gray-400">Rec: 1200x200 px</p>
                                    </div>
                                    <img id="create-preview" class="hidden absolute inset-0 w-full h-full object-cover">
                                    <input type="file" name="banner" class="hidden" accept="image/*" required onchange="previewImage(this, 'create-preview', 'create-placeholder')">
                                </label>
                            </div>

                            <!-- Form Fields -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-600 mb-2">Select Event</label>
                                <select name="event_id" class="w-full border border-gray-300 rounded-md px-4 py-2 focus:ring-2 focus:ring-[#6C5DD3] outline-none" required>
                                    <option value="">-- Choose Event --</option>
                                    @foreach($events as $event)
                                        <option value="{{ $event->id }}">{{ $event->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-600 mb-2">Start Date</label>
                                    <input type="date" name="start_date" class="w-full border border-gray-300 rounded-md px-4 py-2 focus:ring-2 focus:ring-[#6C5DD3] text-gray-600" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-600 mb-2">End Date</label>
                                    <input type="date" name="end_date" class="w-full border border-gray-300 rounded-md px-4 py-2 focus:ring-2 focus:ring-[#6C5DD3] text-gray-600" required>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-600 mb-2">Status</label>
                                <select name="status" class="w-full border border-gray-300 rounded-md px-4 py-2 focus:ring-2 focus:ring-[#6C5DD3] outline-none">
                                    <option value="active">Active</option>
                                    <option value="pending">Pending</option>
                                    <option value="expired">Expired</option>
                                </select>
                            </div>

                            <div class="flex justify-end gap-2 pt-2">
                                <button type="button" onclick="closeModal('createModal')" class="bg-gray-100 text-gray-600 font-medium py-2 px-6 rounded-md hover:bg-gray-200 transition">Cancel</button>
                                <button type="submit" class="bg-[#6C5DD3] text-white font-medium py-2 px-8 rounded-md hover:bg-[#5b4ec2] transition shadow-md shadow-indigo-200">Save</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- 2. EDIT MODAL -->
    <div id="editModal" class="relative z-50 hidden" role="dialog" aria-modal="true">
        <div class="fixed inset-0 backdrop-brightness-50 backdrop-blur-sm transition-opacity"></div>
        <div class="fixed inset-0 z-10 overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4">
                <div class="relative transform rounded-xl bg-white text-left shadow-xl transition-all sm:w-full sm:max-w-lg border border-gray-200">
                    <form id="editForm" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="bg-white px-6 py-6 space-y-6">
                            <h3 class="text-xl font-bold text-[#6C5DD3] border-b pb-4">Edit Advertisement</h3>

                            <!-- Edit Banner Preview -->
                            <div class="w-full">
                                <label class="relative cursor-pointer flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg bg-gray-50 hover:bg-gray-100 transition overflow-hidden">
                                    <div id="edit-placeholder" class="flex flex-col items-center justify-center pt-5 pb-6">
                                        <p class="text-sm text-gray-500 font-medium">Click to replace Banner</p>
                                    </div>
                                    <img id="edit-preview" class="hidden absolute inset-0 w-full h-full object-cover">
                                    <!-- Input tidak required saat edit -->
                                    <input type="file" name="banner" class="hidden" accept="image/*" onchange="previewImage(this, 'edit-preview', 'edit-placeholder')">
                                </label>
                            </div>

                            <!-- Fields -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-600 mb-2">Event</label>
                                <select name="event_id" id="edit_event_id" class="w-full border border-gray-300 rounded-md px-4 py-2 focus:ring-2 focus:ring-[#6C5DD3] outline-none" required>
                                    @foreach($events as $event)
                                        <option value="{{ $event->id }}">{{ $event->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-600 mb-2">Start Date</label>
                                    <input type="date" name="start_date" id="edit_start_date" class="w-full border border-gray-300 rounded-md px-4 py-2 focus:ring-2 focus:ring-[#6C5DD3] text-gray-600" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-600 mb-2">End Date</label>
                                    <input type="date" name="end_date" id="edit_end_date" class="w-full border border-gray-300 rounded-md px-4 py-2 focus:ring-2 focus:ring-[#6C5DD3] text-gray-600" required>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-600 mb-2">Status</label>
                                <select name="status" id="edit_status" class="w-full border border-gray-300 rounded-md px-4 py-2 focus:ring-2 focus:ring-[#6C5DD3] outline-none">
                                    <option value="active">Active</option>
                                    <option value="pending">Pending</option>
                                    <option value="expired">Expired</option>
                                </select>
                            </div>

                            <div class="flex justify-end gap-2 pt-2">
                                <button type="button" onclick="closeModal('editModal')" class="bg-gray-100 text-gray-600 font-medium py-2 px-6 rounded-md hover:bg-gray-200 transition">Cancel</button>
                                <button type="submit" class="bg-[#6C5DD3] text-white font-medium py-2 px-8 rounded-md hover:bg-[#5b4ec2] transition shadow-md shadow-indigo-200">Update</button>
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
                <div class="relative transform overflow-hidden rounded-xl bg-white text-left shadow-2xl transition-all sm:w-full sm:max-w-md p-6">
                    <div class="text-center mt-2">
                        <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-2xl bg-[#FFCE50] mb-5">
                            <svg class="h-10 w-10 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                        </div>
                        <h3 class="text-xl font-bold text-[#6C5DD3] leading-snug px-4">Delete This Advertisement?</h3>
                        <p class="text-sm text-[#6C5DD3] opacity-80 mt-2 mb-6">This action cannot be undone.</p>
                        <div class="flex items-center justify-center gap-4 mt-6">
                            <button onclick="closeModal('deleteModal')" class="w-32 inline-flex justify-center rounded-lg border border-transparent px-4 py-2 bg-[#6C5DD3] text-base font-medium text-white hover:bg-[#5b4ec2]">Cancel</button>
                            <form id="deleteForm" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-32 inline-flex justify-center rounded-lg border border-transparent px-4 py-2 bg-[#6C5DD3] text-base font-medium text-white hover:bg-[#5b4ec2]">Delete</button>
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
            <div class="relative transform rounded-xl bg-white text-left shadow-2xl transition-all sm:w-full sm:max-w-sm p-8 border border-gray-200">
                <div class="text-center">
                    <div class="mx-auto flex h-24 w-24 items-center justify-center rounded-full bg-[#00C851] mb-6 shadow-md">
                        <svg class="h-10 w-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <h3 class="text-2xl font-bold text-[#6C5DD3] mb-2">Success!</h3>
                </div>
                <div class="mt-8">
                    <button onclick="closeModal('successModal')" class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2.5 bg-[#6C5DD3] text-base font-medium text-white hover:bg-[#5b4ec2] transition">OK</button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function openCreateModal() { 
            resetPreview('create-preview', 'create-placeholder');
            document.getElementById('createModal').classList.remove('hidden'); 
        }

        function closeModal(modalId) { 
            document.getElementById(modalId).classList.add('hidden'); 
        }

        function openEditModal(id, eventId, start, end, status, imgUrl) {
            document.getElementById('edit_event_id').value = eventId;
            document.getElementById('edit_start_date').value = start;
            document.getElementById('edit_end_date').value = end;
            document.getElementById('edit_status').value = status;
            
            // Handle Preview
            if(imgUrl && imgUrl.trim() !== '') {
                const preview = document.getElementById('edit-preview');
                const placeholder = document.getElementById('edit-placeholder');
                preview.src = imgUrl;
                preview.classList.remove('hidden');
                placeholder.classList.add('hidden');
            } else {
                resetPreview('edit-preview', 'edit-placeholder');
            }

            // Set Form URL
            document.getElementById('editForm').action = '/admin/dashboard/advertisement/' + id; // Perhatikan rute plural
            document.getElementById('editModal').classList.remove('hidden');
        }

        function openDeleteModal(id) {
            document.getElementById('deleteForm').action = '/admin/dashboard/advertisement/' + id; // Perhatikan rute plural
            document.getElementById('deleteModal').classList.remove('hidden');
        }

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

        @if($errors->any() && !request()->isMethod('put'))
            openCreateModal();
        @endif

        @if(session('success') && str_contains(session('success'), 'dihapus'))
            document.getElementById('successModal').classList.remove('hidden');
        @endif
    </script>
    @endpush
@endsection
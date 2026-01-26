@extends('layouts.admin')

@section('content')
    <div class="space-y-6">
        
        <!-- Notifikasi (Flash Message Standard) -->
        <!-- Tampilkan alert hijau hanya jika bukan pesan hapus (karena hapus pakai modal success khusus) -->
        @if(session('success') && !str_contains(session('success'), 'dihapus'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">{{ session('success') }}</div>
        @endif

        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                <ul class="list-disc pl-5 text-xs">
                    @foreach($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                </ul>
            </div>
        @endif

        <!-- Header -->
        <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100 flex justify-between items-center">
            <h2 class="text-xl font-bold text-gray-800">Master Lokasi</h2>
            <button onclick="openModal('createModal')" class="flex items-center gap-2 bg-[#6C5DD3] hover:bg-[#5b4ec2] text-white text-sm font-medium px-5 py-2.5 rounded-lg transition shadow-md">
                Add Location
            </button>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-50 text-gray-500 text-xs uppercase">
                    <tr>
                        <th class="py-4 px-6 w-10">#</th>
                        <th class="py-4 px-4">Location Name</th>
                        <th class="py-4 px-4">Type</th>
                        <th class="py-4 px-6 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($locations as $loc)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="py-4 px-6 text-gray-500">{{ $loop->iteration }}</td>
                        <td class="py-4 px-4 font-bold text-gray-800">{{ $loc->name }}</td>
                        <td class="py-4 px-4">
                            <span class="px-3 py-1 rounded-full text-xs font-bold {{ $loc->type == 'Virtual' ? 'bg-blue-100 text-blue-700' : 'bg-orange-100 text-orange-700' }}">
                                {{ $loc->type }}
                            </span>
                        </td>
                        <td class="py-4 px-6 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <button onclick="openEditModal('{{ $loc->id }}', '{{ $loc->name }}', '{{ $loc->type }}')" class="flex items-center gap-1 px-3 py-1.5 bg-[#6C5DD3] hover:bg-[#5b4ec2] text-white text-xs font-medium rounded-md transition shadow-sm">
                                    Edit
                                </button>
                                <button onclick="openDeleteModal('{{ $loc->id }}')" class="flex items-center gap-1 px-3 py-1.5 bg-red-500 hover:bg-red-700 text-white text-xs font-medium rounded-md transition shadow-sm">
                                    Delete
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="text-center py-8 text-gray-500">No locations found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="mt-4">{{ $locations->links() }}</div>
    </div>

    <!-- ============================================== -->
    <!-- 1. CREATE MODAL -->
    <!-- ============================================== -->
    <div id="createModal" class="relative z-50 hidden" role="dialog" aria-modal="true">
        <div class="fixed inset-0 backdrop-brightness-50 backdrop-blur-sm transition-opacity"></div>
        <div class="fixed inset-0 z-10 overflow-y-auto flex items-center justify-center p-4">
            <div class="relative transform rounded-xl bg-white text-left shadow-xl transition-all sm:w-full sm:max-w-md border border-gray-200">
                <div class="bg-white px-6 py-6">
                    <h3 class="text-lg font-bold text-[#6C5DD3] mb-4 border-b pb-2">Add Location</h3>
                    <form action="{{ route('master.locations.store') }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                            <input type="text" name="name" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-[#6C5DD3] outline-none transition" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                            <select name="type" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-[#6C5DD3] outline-none transition">
                                <option value="Physical">Physical</option>
                                <option value="Virtual">Virtual</option>
                            </select>
                        </div>
                        <div class="flex justify-end gap-2 mt-6">
                            <button type="button" onclick="closeModal('createModal')" class="px-4 py-2 bg-gray-100 text-gray-600 rounded-lg hover:bg-gray-200 transition">Cancel</button>
                            <button type="submit" class="px-4 py-2 bg-[#6C5DD3] text-white rounded-lg hover:bg-[#5b4ec2] transition shadow-md">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- ============================================== -->
    <!-- 2. EDIT MODAL -->
    <!-- ============================================== -->
    <div id="editModal" class="relative z-50 hidden" role="dialog" aria-modal="true">
        <div class="fixed inset-0 backdrop-brightness-50 backdrop-blur-sm transition-opacity"></div>
        <div class="fixed inset-0 z-10 overflow-y-auto flex items-center justify-center p-4">
            <div class="relative transform rounded-xl bg-white text-left shadow-xl transition-all sm:w-full sm:max-w-md border border-gray-200">
                <div class="bg-white px-6 py-6">
                    <h3 class="text-lg font-bold text-[#6C5DD3] mb-4 border-b pb-2">Edit Location</h3>
                    <form id="editForm" method="POST" class="space-y-4">
                        @csrf @method('PUT')
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                            <input type="text" name="name" id="edit_name" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-[#6C5DD3] outline-none transition" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                            <select name="type" id="edit_type" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-[#6C5DD3] outline-none transition">
                                <option value="Physical">Physical</option>
                                <option value="Virtual">Virtual</option>
                            </select>
                        </div>
                        <div class="flex justify-end gap-2 mt-6">
                            <button type="button" onclick="closeModal('editModal')" class="px-4 py-2 bg-gray-100 text-gray-600 rounded-lg hover:bg-gray-200 transition">Cancel</button>
                            <button type="submit" class="px-4 py-2 bg-[#6C5DD3] text-white rounded-lg hover:bg-[#5b4ec2] transition shadow-md">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- ============================================== -->
    <!-- 3. DELETE MODAL -->
    <!-- ============================================== -->
    <div id="deleteModal" class="relative z-50 hidden" role="dialog" aria-modal="true">
        <div class="fixed inset-0 backdrop-brightness-50 backdrop-blur-sm transition-opacity"></div>
        <div class="fixed inset-0 z-10 overflow-y-auto flex items-center justify-center p-4">
            <div class="relative transform rounded-xl bg-white text-left shadow-2xl transition-all sm:w-full sm:max-w-md p-6 border border-gray-200">
                <div class="text-center mt-2">
                    <!-- Icon Warning Kuning -->
                    <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-2xl bg-[#FFCE50] mb-5">
                        <svg class="h-10 w-10 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                    </div>
                    <h3 class="text-xl font-bold text-[#6C5DD3] leading-snug px-4">Delete This Location?</h3>
                    <p class="text-sm text-[#6C5DD3] opacity-80 mt-2 mb-6">This action cannot be undone.</p>
                    <div class="flex items-center justify-center gap-4 mt-6">
                        <button onclick="closeModal('deleteModal')" class="w-32 inline-flex justify-center rounded-lg border border-transparent px-4 py-2 bg-[#6C5DD3] text-base font-medium text-white hover:bg-[#5b4ec2] transition">Cancel</button>
                        <form id="deleteForm" method="POST">
                            @csrf @method('DELETE')
                            <button type="submit" class="w-32 inline-flex justify-center rounded-lg border border-transparent px-4 py-2 bg-[#6C5DD3] text-base font-medium text-white hover:bg-[#5b4ec2] transition">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ============================================== -->
    <!-- 4. SUCCESS MODAL -->
    <!-- ============================================== -->
    <div id="successModal" class="relative z-50 hidden" role="dialog" aria-modal="true">
        <div class="fixed inset-0 backdrop-brightness-50 backdrop-blur-sm transition-opacity"></div>
        <div class="fixed inset-0 z-10 overflow-y-auto flex items-center justify-center p-4">
            <div class="relative transform rounded-xl bg-white text-left shadow-2xl transition-all sm:w-full sm:max-w-sm p-8 border border-gray-200">
                <div class="text-center">
                    <div class="mx-auto flex h-24 w-24 items-center justify-center rounded-full bg-[#00C851] mb-6 shadow-md">
                        <div class="h-20 w-20 rounded-full border-2 border-white flex items-center justify-center">
                            <svg class="h-10 w-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
                        </div>
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
        function openModal(id){ document.getElementById(id).classList.remove('hidden'); }
        function closeModal(id){ document.getElementById(id).classList.add('hidden'); }
        
        function openEditModal(id, name, type) {
            document.getElementById('edit_name').value = name;
            document.getElementById('edit_type').value = type;
            // Pastikan route sesuai
            document.getElementById('editForm').action = '/admin/dashboard/master/locations/' + id;
            document.getElementById('editModal').classList.remove('hidden');
        }

        function openDeleteModal(id) {
            // Pastikan route sesuai
            document.getElementById('deleteForm').action = '/admin/dashboard/master/locations/' + id;
            document.getElementById('deleteModal').classList.remove('hidden');
        }

        document.addEventListener('keydown', function(event) {
            if (event.key === "Escape") {
                document.querySelectorAll('[role="dialog"]').forEach(modal => modal.classList.add('hidden'));
            }
        });

        // Trigger Success Modal for Deleted items
        @if(session('success') && str_contains(session('success'), 'dihapus'))
            openModal('successModal');
        @endif
    </script>
    @endpush
@endsection
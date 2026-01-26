@extends('layouts.admin')

@section('content')
    <div class="space-y-6">

        <!-- Notifikasi (Hide jika 'dihapus' karena pakai modal) -->
        @if(session('success') && !str_contains(session('success'), 'dihapus'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <strong class="font-bold">Berhasil!</strong>
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                <strong class="font-bold">Error!</strong>
                <span class="block sm:inline">Periksa inputan anda kembali.</span>
            </div>
        @endif

        <!-- SECTION 1: Top Toolbar -->
        <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
            
            <!-- Left: Search Form -->
            <form action="{{ route('registrations.index') }}" method="GET" class="flex items-center gap-4 flex-1">
                <div class="flex items-center gap-1 bg-gray-100 p-1 rounded-lg">
                    <!-- Icon placeholder buttons -->
                    <button type="button" class="p-1.5 rounded-md hover:bg-white hover:shadow-sm text-gray-500 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                    </button>
                    <button type="button" class="p-1.5 rounded-md bg-white shadow-sm text-gray-800 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    </button>
                </div>

                <div class="relative flex-1 max-w-xs">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search Author Name" class="w-full bg-gray-100 border-none text-gray-700 text-sm rounded-lg py-2.5 pl-10 pr-4 focus:ring-2 focus:ring-[#6C5DD3] focus:bg-white transition placeholder-gray-400">
                </div>
            </form>

            <!-- Right: Actions -->
            <div class="flex flex-wrap items-center gap-3">
                <button onclick="openCreateModal()" class="flex items-center gap-2 bg-[#6C5DD3] hover:bg-[#5b4ec2] text-white text-sm font-medium px-5 py-2.5 rounded-lg transition shadow-md shadow-indigo-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Add Author
                </button>
            </div>
        </div>

        <!-- SECTION 2: Data Table -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="text-gray-400 text-xs border-b border-gray-100">
                            <th class="py-4 pl-6 pr-4 w-10">#</th>
                            <th class="py-4 px-4 font-normal">Author Name</th>
                            <th class="py-4 px-4 font-normal">Category</th>
                            <th class="py-4 px-4 font-normal">Join Date</th>
                            <th class="py-4 px-4 font-normal">Status</th>
                            <th class="py-4 px-6 text-right font-normal">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($registrations as $reg)
                        <tr class="hover:bg-gray-50 group transition-colors">
                            <td class="py-4 pl-6 pr-4 text-sm text-gray-500">
                                {{ $loop->iteration + ($registrations->currentPage() - 1) * $registrations->perPage() }}
                            </td>
                            
                            <!-- Author Info -->
                            <td class="py-4 px-4">
                                <div class="flex items-center gap-3">
                                    <div class="h-10 w-10 rounded-lg bg-indigo-100 overflow-hidden flex-shrink-0 flex items-center justify-center">
                                        <svg class="h-6 w-6 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                    </div>
                                    <div>
                                        <div class="font-bold text-gray-700 text-sm">{{ $reg->user->name ?? 'Unknown' }}</div>
                                        <div class="text-xs text-gray-400 mt-0.5">{{ $reg->user->email ?? '-' }}</div>
                                    </div>
                                </div>
                            </td>
                            
                            <!-- Category -->
                            <td class="py-4 px-4 text-sm font-semibold text-gray-700">
                                {{ $reg->category->name ?? 'General' }}
                            </td>

                            <!-- Date -->
                            <td class="py-4 px-4 text-sm font-medium text-gray-800">
                                {{ $reg->created_at->format('d M Y') }}
                            </td>

                            <!-- Status -->
                            <td class="py-4 px-4">
                                <span class="flex items-center gap-2 px-3 py-1.5 rounded-lg border border-gray-200 bg-white text-xs font-medium text-gray-600 w-fit">
                                    <span class="w-2 h-2 rounded-full {{ $reg->status == 'active' ? 'bg-green-500' : ($reg->status == 'pending' ? 'bg-yellow-500' : 'bg-red-500') }}"></span>
                                    {{ ucfirst($reg->status) }}
                                </span>
                            </td>

                            <!-- Actions -->
                            <td class="py-4 px-6 text-right">
                                <div class="flex items-center justify-end gap-3">
                                    <!-- Edit Button (Pass Data via Data Attributes) -->
                                    <button onclick="openEditModal(
                                        '{{ $reg->id }}', 
                                        '{{ $reg->user_id }}', 
                                        '{{ $reg->category_id }}', 
                                        '{{ $reg->status }}'
                                    )" class="flex items-center gap-1 px-4 py-1.5 bg-[#6C5DD3] hover:bg-[#5b4ec2] text-white text-xs font-medium rounded-md transition shadow-sm">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                        Edit
                                    </button>
                                    <!-- Delete Button -->
                                    <button onclick="openDeleteModal('{{ $reg->id }}')" class="text-gray-500 hover:text-red-500 transition p-1">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-8 text-gray-500">No author registrations found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $registrations->withQueryString()->links() }}
            </div>
        </div>
    </div>

    <!-- ============================================== -->
    <!-- 1. CREATE MODAL -->
    <!-- ============================================== -->
    <div id="createModal" class="relative z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 backdrop-brightness-50 backdrop-blur-sm transition-opacity"></div>
        <div class="fixed inset-0 z-10 overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-gray-200">
                    <form action="{{ route('registrations.store') }}" method="POST">
                        @csrf
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4" id="modal-title">Add New Author</h3>
                            <div class="space-y-4">
                                <!-- User Select -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">User (Author)</label>
                                    <select name="user_id" class="w-full bg-gray-50 border border-gray-200 text-gray-700 text-sm rounded-lg py-2.5 px-4 focus:ring-2 focus:ring-[#6C5DD3] outline-none">
                                        <option value="">-- Select User --</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                                        @endforeach
                                    </select>
                                </div>
                                <!-- Category Select -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Specialization</label>
                                    <select name="category_id" class="w-full bg-gray-50 border border-gray-200 text-gray-700 text-sm rounded-lg py-2.5 px-4 focus:ring-2 focus:ring-[#6C5DD3] outline-none">
                                        <option value="">-- Select Category --</option>
                                        @foreach($categories as $cat)
                                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <!-- Status -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                                    <select name="status" class="w-full bg-gray-50 border border-gray-200 text-gray-700 text-sm rounded-lg py-2.5 px-4 focus:ring-2 focus:ring-[#6C5DD3] outline-none">
                                        <option value="pending">Pending</option>
                                        <option value="active">Active</option>
                                        <option value="rejected">Rejected</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-[#6C5DD3] text-base font-medium text-white hover:bg-[#5b4ec2] sm:ml-3 sm:w-auto sm:text-sm">Save</button>
                            <button type="button" onclick="closeModal('createModal')" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- ============================================== -->
    <!-- 2. EDIT MODAL -->
    <!-- ============================================== -->
    <div id="editModal" class="relative z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 backdrop-brightness-50 backdrop-blur-sm transition-opacity"></div>
        <div class="fixed inset-0 z-10 overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-gray-200">
                    <!-- Form Action akan di-set lewat JS -->
                    <form id="editForm" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Edit Author</h3>
                            <div class="space-y-4">
                                <!-- User Select -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">User (Author)</label>
                                    <select name="user_id" id="edit_user_id" class="w-full bg-gray-50 border border-gray-200 text-gray-700 text-sm rounded-lg py-2.5 px-4 focus:ring-2 focus:ring-[#6C5DD3] outline-none">
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                                        @endforeach
                                    </select>
                                </div>
                                <!-- Category Select -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Specialization</label>
                                    <select name="category_id" id="edit_category_id" class="w-full bg-gray-50 border border-gray-200 text-gray-700 text-sm rounded-lg py-2.5 px-4 focus:ring-2 focus:ring-[#6C5DD3] outline-none">
                                        @foreach($categories as $cat)
                                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <!-- Status -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                                    <select name="status" id="edit_status" class="w-full bg-gray-50 border border-gray-200 text-gray-700 text-sm rounded-lg py-2.5 px-4 focus:ring-2 focus:ring-[#6C5DD3] outline-none">
                                        <option value="pending">Pending</option>
                                        <option value="active">Active</option>
                                        <option value="rejected">Rejected</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-[#6C5DD3] text-base font-medium text-white hover:bg-[#5b4ec2] sm:ml-3 sm:w-auto sm:text-sm">Update</button>
                            <button type="button" onclick="closeModal('editModal')" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- ============================================== -->
    <!-- 3. DELETE MODAL -->
    <!-- ============================================== -->
    <div id="deleteModal" class="relative z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 backdrop-brightness-50 backdrop-blur-sm transition-opacity"></div>
        <div class="fixed inset-0 z-10 overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4 text-center">
                <div class="relative transform overflow-hidden rounded-xl bg-white text-left shadow-2xl transition-all sm:w-full sm:max-w-md p-6">
                    <div class="text-center mt-2">
                        <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-2xl bg-[#FFCE50] mb-5">
                            <svg class="h-10 w-10 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-[#6C5DD3] leading-snug px-4">Are You Sure You Want to Delete This Data?</h3>
                        <p class="text-sm text-[#6C5DD3] opacity-80 mt-2 mb-6">Once you delete this data, it will be gone forever!</p>
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

    <!-- ============================================== -->
    <!-- 4. SUCCESS MODAL -->
    <!-- ============================================== -->
    <div id="successModal" class="relative z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 backdrop-brightness-50 backdrop-blur-sm transition-opacity"></div>
        <div class="fixed inset-0 z-10 overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4 text-center">
                <div class="relative transform overflow-hidden rounded-xl bg-white text-left shadow-2xl transition-all sm:w-full sm:max-w-sm p-8">
                    <div class="text-center">
                        <div class="mx-auto flex h-24 w-24 items-center justify-center rounded-full bg-[#00C851] mb-6 shadow-md">
                             <div class="h-20 w-20 rounded-full border-2 border-white flex items-center justify-center">
                                <svg class="h-10 w-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
                             </div>
                        </div>
                        <h3 class="text-2xl font-bold text-[#6C5DD3] mb-2">Delete Data Success!</h3>
                    </div>
                    <div class="mt-8">
                        <button onclick="closeModal('successModal')" class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2.5 bg-[#6C5DD3] text-base font-medium text-white hover:bg-[#5b4ec2]">OK</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Open Specific Modal
        function openCreateModal() {
            document.getElementById('createModal').classList.remove('hidden');
        }

        function openEditModal(id, userId, catId, status) {
            // Populate form
            document.getElementById('edit_user_id').value = userId;
            document.getElementById('edit_category_id').value = catId;
            document.getElementById('edit_status').value = status;
            
            // Set Action URL
            let form = document.getElementById('editForm');
            form.action = '/admin/registrations/' + id; 

            document.getElementById('editModal').classList.remove('hidden');
        }

        function openDeleteModal(id) {
            let form = document.getElementById('deleteForm');
            form.action = '/admin/dashboard/registrations/' + id;
            document.getElementById('deleteModal').classList.remove('hidden');
        }

        // Close Any Modal by ID
        function closeModal(modalId) {
            document.getElementById(modalId).classList.add('hidden');
        }

        // Close on ESC
        document.addEventListener('keydown', function(event) {
            if (event.key === "Escape") {
                closeModal('createModal');
                closeModal('editModal');
                closeModal('deleteModal');
                closeModal('successModal');
            }
        });

        // Error Validation: Re-open create modal if error occurs (optional logic)
        @if($errors->any() && !request()->isMethod('put'))
            openCreateModal();
        @endif

        // Success Delete Trigger
        @if(session('success') && str_contains(session('success'), 'dihapus'))
            document.getElementById('successModal').classList.remove('hidden');
        @endif
    </script>
    @endpush
@endsection
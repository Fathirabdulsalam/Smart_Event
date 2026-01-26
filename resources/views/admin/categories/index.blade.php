@extends('layouts.admin')

@section('content')
    <div class="space-y-6">

        <!-- Notifikasi (Flash Messages) -->
        @if (session('success') && !str_contains(session('success'), 'dihapus'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <strong class="font-bold">Berhasil!</strong>
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <strong class="font-bold">Error!</strong>
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <strong class="font-bold">Error Validasi!</strong>
                <ul class="list-disc pl-5 text-xs">
                    @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                </ul>
            </div>
        @endif

        <!-- SECTION 1: Top Toolbar -->
        <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
            
            <!-- Left: Search Form -->
            <form action="{{ route('categories.index') }}" method="GET" class="flex items-center gap-4 flex-1">
                <div class="relative flex-1 max-w-xs">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search Category" class="w-full bg-gray-100 border-none text-gray-700 text-sm rounded-lg py-2.5 pl-10 pr-4 focus:ring-2 focus:ring-[#6C5DD3] focus:bg-white transition placeholder-gray-400">
                </div>
            </form>

            <!-- Right: Actions & Add Button -->
            <div class="flex flex-wrap items-center gap-3">
                <button onclick="openModal()" class="flex items-center gap-2 bg-[#6C5DD3] hover:bg-[#5b4ec2] text-white text-sm font-medium px-5 py-2.5 rounded-lg transition shadow-md shadow-indigo-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Add Category
                </button>
            </div>
        </div>


        <!-- SECTION 3: Data Table -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="text-gray-400 text-xs border-b border-gray-100">
                            <th class="py-4 pl-6 pr-4 w-10">#</th>
                            <th class="py-4 px-4 font-normal">Image</th>
                            <th class="py-4 px-4 font-normal">Category Name</th>
                            <th class="py-4 px-4 font-normal">Description</th>
                            <th class="py-4 px-4 font-normal text-center">Total Events</th>
                            <th class="py-4 px-4 font-normal">Created At</th>
                            <th class="py-4 px-6 text-right font-normal">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($categories as $category)
                            <tr class="hover:bg-gray-50 group transition-colors">
                                <td class="py-4 pl-6 pr-4 text-sm text-gray-500">
                                    {{ $loop->iteration + ($categories->currentPage() - 1) * $categories->perPage() }}
                                </td>
                                <td class="py-4 px-4">
                                    @if($category->thumbnail)
                                        <img src="{{ Storage::url($category->thumbnail) }}" class="h-10 w-10 rounded object-cover border border-gray-200">
                                    @else
                                        <div class="h-10 w-10 bg-gray-100 rounded flex items-center justify-center text-xs text-gray-400">No Img</div>
                                    @endif
                                </td>
                                <td class="py-4 px-4 font-bold text-gray-800">{{ $category->name }}</td>
                                <td class="py-4 px-4 text-sm text-gray-500 truncate max-w-xs">
                                    {{ Str::limit($category->description, 30) ?? '-' }}
                                </td>
                                <td class="py-4 px-4 text-center">
                                    <span class="bg-indigo-50 text-indigo-700 px-2 py-1 rounded text-xs font-bold">{{ $category->events_count }}</span>
                                </td>
                                <td class="py-4 px-4 text-sm font-semibold text-gray-700">
                                    {{ $category->created_at->format('d-m-Y') }}
                                </td>
                                <td class="py-4 px-6 text-right">
                                    <div class="flex items-center justify-end gap-3">
                                        <!-- Edit -->
                                        <button onclick="openEditModal('{{ $category->id }}', '{{ addslashes($category->name) }}', '{{ addslashes($category->description) }}', '{{ $category->thumbnail ? Storage::url($category->thumbnail) : '' }}')" class="flex items-center gap-1 px-4 py-1.5 bg-[#6C5DD3] hover:bg-[#5b4ec2] text-white text-xs font-medium rounded-md transition shadow-sm">
                                            Edit
                                        </button>
                                        <!-- Delete -->
                                        <button onclick="openDeleteModal('{{ $category->id }}')" class="text-gray-400 hover:text-red-500 transition p-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="text-center py-8 text-gray-500">No categories found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $categories->withQueryString()->links() }}
            </div>
        </div>
    </div>

    <!-- ============================================== -->
    <!-- 1. CREATE MODAL -->
    <!-- ============================================== -->
    <div id="createCategoryModal" class="relative z-50 hidden" role="dialog" aria-modal="true"><div class="fixed inset-0 backdrop-brightness-50 backdrop-blur-sm transition-opacity"></div>
        <div class="fixed inset-0 z-10 overflow-y-auto flex items-center justify-center p-4">
            <div class="relative transform rounded-xl bg-white text-left shadow-xl w-full max-w-lg p-6 border border-gray-200">
                <h3 class="text-lg font-bold text-[#6C5DD3] mb-4">Add Category</h3>
                <form action="{{ route('categories.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="space-y-4">
                        <!-- Image Input -->
                        <div class="w-full">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Thumbnail</label>
                            <label class="cursor-pointer flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg bg-gray-50 hover:bg-gray-100 transition overflow-hidden relative">
                                <div id="create-placeholder" class="flex flex-col items-center justify-center pt-5 pb-6">
                                    <p class="text-sm text-gray-500">Upload Image</p>
                                </div>
                                <img id="create-preview" class="hidden absolute inset-0 w-full h-full object-cover">
                                <input type="file" name="thumbnail" class="hidden" accept="image/*" required onchange="previewImage(this, 'create-preview', 'create-placeholder')">
                            </label>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                            <input type="text" name="name" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-[#6C5DD3] outline-none" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                            <textarea name="description" rows="3" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-[#6C5DD3] outline-none"></textarea>
                        </div>
                    </div>
                    <div class="flex justify-end gap-2 mt-6">
                        <button type="button" onclick="closeModal('createCategoryModal')" class="px-4 py-2 bg-gray-100 rounded-lg">Cancel</button>
                        <button type="submit" class="px-4 py-2 bg-[#6C5DD3] text-white rounded-lg">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- ============================================== -->
    <!-- 2. EDIT MODAL -->
    <!-- ============================================== -->
    <div id="editCategoryModal" class="relative z-50 hidden" role="dialog" aria-modal="true"><div class="fixed inset-0 backdrop-brightness-50 backdrop-blur-sm transition-opacity"></div>
        <div class="fixed inset-0 z-10 overflow-y-auto flex items-center justify-center p-4">
            <div class="relative transform rounded-xl bg-white text-left shadow-xl w-full max-w-lg p-6">
                <h3 class="text-lg font-bold text-[#6C5DD3] mb-4">Edit Category</h3>
                <form id="editCategoryForm" method="POST" enctype="multipart/form-data">
                    @csrf @method('PUT')
                    <div class="space-y-4">
                        <!-- Image Input -->
                        <div class="w-full">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Change Thumbnail</label>
                            <label class="cursor-pointer flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg bg-gray-50 hover:bg-gray-100 transition overflow-hidden relative">
                                <div id="edit-placeholder" class="flex flex-col items-center justify-center pt-5 pb-6">
                                    <p class="text-sm text-gray-500">Click to replace</p>
                                </div>
                                <img id="edit-preview" class="hidden absolute inset-0 w-full h-full object-cover">
                                <input type="file" name="thumbnail" class="hidden" accept="image/*" onchange="previewImage(this, 'edit-preview', 'edit-placeholder')">
                            </label>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                            <input type="text" name="name" id="edit_name" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-[#6C5DD3] outline-none" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                            <textarea name="description" id="edit_description" rows="3" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-[#6C5DD3] outline-none"></textarea>
                        </div>
                    </div>
                    <div class="flex justify-end gap-2 mt-6">
                        <button type="button" onclick="closeModal('editCategoryModal')" class="px-4 py-2 bg-gray-100 rounded-lg">Cancel</button>
                        <button type="submit" class="px-4 py-2 bg-[#6C5DD3] text-white rounded-lg">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- ============================================== -->
    <!-- 3. DELETE MODAL -->
    <!-- ============================================== -->
    <div id="deleteModal" class="relative z-50 hidden" role="dialog" aria-modal="true"><div class="fixed inset-0 backdrop-brightness-50 backdrop-blur-sm transition-opacity"></div>
        <div class="fixed inset-0 z-10 overflow-y-auto flex items-center justify-center p-4">
            <div class="relative transform rounded-xl bg-white text-left shadow-2xl w-full max-w-md p-6 border border-gray-200">
                <div class="text-center mt-2">
                    <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-2xl bg-[#FFCE50] mb-5">
                        <svg class="h-10 w-10 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                    </div>
                    <h3 class="text-xl font-bold text-[#6C5DD3] leading-snug px-4">Delete This Data?</h3>
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

    <!-- 4. SUCCESS MODAL -->
    <div id="successModal" class="relative z-50 hidden" role="dialog" aria-modal="true"><div class="fixed inset-0 backdrop-brightness-50 backdrop-blur-sm transition-opacity"></div>
        <div class="fixed inset-0 z-10 overflow-y-auto flex items-center justify-center p-4 text-center">
            <div class="relative transform rounded-xl bg-white text-left shadow-2xl w-full max-w-sm p-8 border border-gray-200">
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
        function openModal() { 
            resetPreview('create-preview', 'create-placeholder');
            document.getElementById('createCategoryModal').classList.remove('hidden'); 
        }
        function closeModal(id) { document.getElementById(id || 'createCategoryModal').classList.add('hidden'); }
        
        function openEditModal(id, name, desc, imgUrl) {
            document.getElementById('edit_name').value = name;
            document.getElementById('edit_description').value = desc;
            
            if(imgUrl) {
                const preview = document.getElementById('edit-preview');
                const placeholder = document.getElementById('edit-placeholder');
                preview.src = imgUrl;
                preview.classList.remove('hidden');
                placeholder.classList.add('hidden');
            } else {
                resetPreview('edit-preview', 'edit-placeholder');
            }

            document.getElementById('editCategoryForm').action = '/admin/dashboard/categories/' + id + '/update';
            document.getElementById('editCategoryModal').classList.remove('hidden');
        }

        function openDeleteModal(id) {
            document.getElementById('deleteForm').action = '/admin/dashboard/categories/' + id;
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

        // Trigger Success Modal
        @if(session('success') && str_contains(session('success'), 'dihapus'))
            document.getElementById('successModal').classList.remove('hidden');
        @endif
    </script>
    @endpush
@endsection
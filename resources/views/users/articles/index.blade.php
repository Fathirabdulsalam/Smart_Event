@extends('layouts.user')

@section('content')
    <div class="bg-[#4838CC] h-48 w-full absolute top-0 z-0"></div>

    <div class="container mx-auto px-4 relative z-10 pt-8 pb-20">
        <div class="flex flex-col lg:flex-row gap-8">
            
            @include('users.partials.sidebar')

            <div class="w-full lg:w-3/4">
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 min-h-[500px]">
                    
                    @if(session('success') && !str_contains(session('success'), 'dihapus'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                            <strong class="font-bold">Success!</strong> <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    <!-- Header -->
                    <div class="flex justify-between items-center mb-6 border-b border-gray-100 pb-4">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-800">Manage Articles</h1>
                            <p class="text-gray-500 text-sm">Write and share your stories.</p>
                        </div>
                        <button onclick="openCreateModal()" class="bg-[#4838CC] hover:bg-[#3b2db0] text-white px-5 py-2.5 rounded-lg font-bold text-sm shadow-md transition flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            Write Article
                        </button>
                    </div>

                    <!-- Article List -->
                    @if($articles->count() > 0)
                        <div class="space-y-4">
                            @foreach($articles as $article)
                                <div class="flex flex-col md:flex-row gap-4 border border-gray-100 rounded-xl p-4 hover:shadow-md transition bg-white items-center">
                                    <div class="w-full md:w-24 h-24 md:h-16 bg-gray-100 rounded-lg overflow-hidden flex-shrink-0 relative">
                                        @if($article->thumbnail_path)
                                            <img src="{{ Storage::url($article->thumbnail_path) }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-gray-400 text-xs">IMG</div>
                                        @endif
                                    </div>
                                    
                                    <div class="flex-1 w-full">
                                        <div class="flex justify-between items-start">
                                            <h3 class="font-bold text-gray-800 text-base line-clamp-1">{{ $article->title }}</h3>
                                            <span class="text-[10px] font-bold px-2 py-1 rounded capitalize {{ $article->status == 'published' ? 'bg-green-50 text-green-600' : 'bg-gray-100 text-gray-500' }}">{{ $article->status }}</span>
                                        </div>
                                        <!-- Tampilkan Kategori -->
                                        <div class="text-xs font-semibold text-[#4838CC] mt-1">{{ $article->category->name ?? 'Uncategorized' }}</div>
                                        
                                        <p class="text-xs text-gray-500 mt-1 line-clamp-1">{{ Str::limit(strip_tags($article->content), 80) }}</p>
                                    </div>

                                    <div class="flex gap-2">
                                        <button onclick="openEditModal(
                                            '{{ $article->id }}',
                                            '{{ addslashes($article->title) }}',
                                            '{{ $article->category_id }}', // Parameter baru
                                            '{{ $article->status }}',
                                            '{{ addslashes(str_replace(array("\r", "\n"), " ", $article->content)) }}',
                                            '{{ $article->thumbnail_path ? Storage::url($article->thumbnail_path) : '' }}'
                                        )" class="p-2 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition" title="Edit">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                        </button>
                                        
                                        <button onclick="openDeleteModal('{{ $article->id }}')" class="p-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition" title="Delete">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-6">{{ $articles->links() }}</div>
                    @else
                        <div class="text-center py-16">
                            <p class="text-gray-500">You haven't written any articles yet.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL CREATE -->
    <div id="createArticleModal" class="relative z-[9999] hidden" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-gray-900 bg-opacity-50 backdrop-blur-sm transition-opacity"></div>
        <div class="fixed inset-0 z-[10000] overflow-y-auto flex items-center justify-center p-4">
            <div class="relative transform rounded-xl bg-white text-left shadow-xl w-full max-w-2xl border border-gray-200">
                <div class="flex justify-between items-center px-6 py-4 border-b border-gray-100">
                    <h3 class="text-xl font-bold text-[#6C5DD3]">Write New Article</h3>
                    <button onclick="closeModal('createArticleModal')" class="text-gray-400 hover:text-gray-600">X</button>
                </div>
                <form action="{{ route('user.article.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="px-6 py-6 space-y-4">
                        <!-- Thumbnail -->
                        <div class="w-full">
                            <label class="relative cursor-pointer flex flex-col items-center justify-center w-full h-40 border-2 border-gray-300 border-dashed rounded-lg bg-gray-50 hover:bg-gray-100 transition overflow-hidden">
                                <div id="create-placeholder" class="flex flex-col items-center justify-center pt-5 pb-6">
                                    <p class="text-sm text-gray-500 font-medium">Click to upload thumbnail</p>
                                </div>
                                <img id="create-preview" class="hidden absolute inset-0 w-full h-full object-cover">
                                <input type="file" name="thumbnail" class="hidden" accept="image/*" required onchange="previewImage(this, 'create-preview', 'create-placeholder')">
                            </label>
                        </div>
                        <!-- Inputs -->
                        <input type="text" name="title" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-[#6C5DD3]" required placeholder="Article Title">
                        
                        <!-- Dropdown Kategori (CREATE) -->
                        <select name="category_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-[#6C5DD3] bg-white" required>
                            <option value="">-- Select Category --</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>

                        <textarea name="content" rows="6" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-[#6C5DD3] resize-none" required placeholder="Write something amazing..."></textarea>
                        
                        <select name="status" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-[#6C5DD3] bg-white">
                            <option value="published">Publish Now</option>
                            <option value="draft">Save as Draft</option>
                        </select>
                        <div class="flex justify-end pt-2">
                            <button type="submit" class="bg-[#6C5DD3] text-white font-medium py-2 px-8 rounded-lg hover:bg-[#5b4ec2]">Publish</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL EDIT -->
    <div id="editArticleModal" class="relative z-[9999] hidden" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-gray-900 bg-opacity-50 backdrop-blur-sm transition-opacity"></div>
        <div class="fixed inset-0 z-[10000] overflow-y-auto flex items-center justify-center p-4">
            <div class="relative transform rounded-xl bg-white text-left shadow-xl w-full max-w-2xl border border-gray-200">
                <div class="flex justify-between items-center px-6 py-4 border-b border-gray-100">
                    <h3 class="text-xl font-bold text-[#6C5DD3]">Edit Article</h3>
                    <button onclick="closeModal('editArticleModal')" class="text-gray-400 hover:text-gray-600">X</button>
                </div>
                <form id="editForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="px-6 py-6 space-y-4">
                        <!-- Thumbnail -->
                        <div class="w-full">
                            <label class="relative cursor-pointer flex flex-col items-center justify-center w-full h-40 border-2 border-gray-300 border-dashed rounded-lg bg-gray-50 hover:bg-gray-100 transition overflow-hidden">
                                <div id="edit-placeholder" class="flex flex-col items-center justify-center pt-2 pb-2">
                                    <p class="text-sm text-gray-500 font-medium">Click to replace Thumbnail</p>
                                </div>
                                <img id="edit-preview" class="hidden absolute inset-0 w-full h-full object-cover">
                                <input type="file" name="thumbnail" class="hidden" accept="image/*" onchange="previewImage(this, 'edit-preview', 'edit-placeholder')">
                            </label>
                        </div>
                        
                        <input type="text" name="title" id="edit_title" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-[#6C5DD3]" required>
                        
                        <!-- Dropdown Kategori (EDIT) -->
                        <select name="category_id" id="edit_category_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-[#6C5DD3] bg-white" required>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>

                        <textarea name="content" id="edit_content" rows="6" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-[#6C5DD3] resize-none" required></textarea>
                        
                        <select name="status" id="edit_status" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-[#6C5DD3] bg-white">
                            <option value="published">Published</option>
                            <option value="draft">Draft</option>
                        </select>

                        <div class="flex justify-end pt-2">
                            <button type="submit" class="bg-[#6C5DD3] text-white font-medium py-2 px-8 rounded-lg hover:bg-[#5b4ec2]">Update</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

     <div id="deleteModal" class="relative z-[9999] hidden" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-gray-900 bg-opacity-50 backdrop-blur-sm transition-opacity"></div>
        <div class="fixed inset-0 z-[10000] overflow-y-auto flex items-center justify-center p-4">
            <div class="relative transform rounded-xl bg-white text-left shadow-2xl w-full max-w-md p-6">
                <div class="text-center mt-2">
                    <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-2xl bg-[#FFCE50] mb-5">
                        <svg class="h-10 w-10 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                    </div>
                    <h3 class="text-xl font-bold text-[#6C5DD3] leading-snug px-4">Delete This Article?</h3>
                    <p class="text-sm text-[#6C5DD3] opacity-80 mt-2 mb-6">This action cannot be undone.</p>
                    <div class="flex items-center justify-center gap-4 mt-6">
                        <button type="button" onclick="closeDeleteModal()" class="w-32 inline-flex justify-center rounded-lg border border-transparent px-4 py-2 bg-[#6C5DD3] text-base font-medium text-white hover:bg-[#5b4ec2]">Cancel</button>
                        
                        <!-- Form Delete -->
                        <!-- Route mengarah ke User Article Destroy -->
                        <form id="deleteForm" method="POST">
                            @csrf @method('DELETE')
                            <button type="submit" class="w-32 inline-flex justify-center rounded-lg border border-transparent px-4 py-2 bg-[#6C5DD3] text-base font-medium text-white hover:bg-[#5b4ec2]">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // ... (Kode previewImage, resetPreview, closeModal sama seperti sebelumnya) ...

        window.openCreateModal = function() { 
            resetPreview('create-preview', 'create-placeholder');
            document.getElementById('createArticleModal').classList.remove('hidden'); 
        }

        window.openEditModal = function(id, title, catId, status, content, imgUrl) {
            document.getElementById('edit_title').value = title;
            document.getElementById('edit_content').value = content;
            document.getElementById('edit_status').value = status;
            document.getElementById('edit_category_id').value = catId; // Set Kategori

            // ... (Logic preview image dan form action sama) ...
            if(imgUrl && imgUrl.trim() !== '') {
                const preview = document.getElementById('edit-preview');
                const placeholder = document.getElementById('edit-placeholder');
                preview.src = imgUrl;
                preview.classList.remove('hidden');
                placeholder.classList.add('hidden');
            } else {
                resetPreview('edit-preview', 'edit-placeholder');
            }

            document.getElementById('editForm').action = '/user/article/' + id;
            document.getElementById('editArticleModal').classList.remove('hidden');
        }

        // ... (Sisa script sama) ...
        window.openDeleteModal = function(id) {
            document.getElementById('deleteForm').action = '/user/article/' + id;
            document.getElementById('deleteModal').classList.remove('hidden');
        }

        window.previewImage = function(input, previewId, placeholderId) {
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

        window.resetPreview = function(previewId, placeholderId) {
            const preview = document.getElementById(previewId);
            const placeholder = document.getElementById(placeholderId);
            preview.src = "";
            preview.classList.add('hidden');
            placeholder.classList.remove('hidden');
        }

        window.closeModal = function(modalId) { 
            document.getElementById(modalId).classList.add('hidden'); 
        }

        function openDeleteModal(id) {
            // Set URL Action Form Delete
            document.getElementById('deleteForm').action = '/user/articles/' + id;
            document.getElementById('deleteModal').classList.remove('hidden');
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
        }

        // Close on ESC
        document.addEventListener('keydown', function(event) {
            if (event.key === "Escape") {
                closeDeleteModal();
            }
        });
    </script>
    @endpush
@endsection
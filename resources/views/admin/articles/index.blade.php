@extends('layouts.admin')

@section('content')
    <div class="space-y-6">
        @if(session('success'))
            <div class="bg-green-100 border-green-400 text-green-700 px-4 py-3 rounded relative">{{ session('success') }}</div>
        @endif

        <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100 flex justify-between items-center">
            <h2 class="text-xl font-bold text-gray-800">Manajemen Artikel</h2>
            <button onclick="openModal('createModal')" class="flex items-center gap-2 bg-[#6C5DD3] hover:bg-[#5b4ec2] text-white text-sm font-medium px-5 py-2.5 rounded-lg transition shadow-md">
                Tulis Artikel
            </button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($articles as $article)
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition">
                    <img src="{{ Storage::url($article->thumbnail_path) }}" class="h-40 w-full object-cover">
                    <div class="p-4">
                        <div class="flex justify-between items-start mb-2">
                            <span class="px-2 py-1 bg-indigo-50 text-[#6C5DD3] text-xs font-bold rounded uppercase">{{ $article->status }}</span>
                            <span class="text-xs text-gray-400">{{ $article->created_at->format('d M Y') }}</span>
                        </div>
                        <h3 class="font-bold text-gray-800 text-lg mb-2 line-clamp-2">{{ $article->title }}</h3>
                        <p class="text-sm text-gray-500 line-clamp-3 mb-4">{{ Str::limit($article->content, 100) }}</p>
                        
                        <div class="flex justify-end gap-2 border-t pt-3">
                            <button onclick="openEditModal('{{ $article->id }}', ...)" class="text-indigo-600 text-sm font-medium">Edit</button>
                            <button onclick="openDeleteModal('{{ $article->id }}')" class="text-red-500 text-sm font-medium">Delete</button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-3 text-center py-10 text-gray-500">Belum ada artikel.</div>
            @endforelse
        </div>
        
        <div class="mt-4">{{ $articles->links() }}</div>
    </div>

    <!-- CREATE MODAL ARTIKEL -->
    <div id="createModal" class="relative z-50 hidden" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-gray-900 bg-opacity-50 backdrop-blur-sm"></div>
        <div class="fixed inset-0 z-10 overflow-y-auto flex items-center justify-center p-4">
            <div class="bg-white rounded-xl shadow-xl w-full max-w-2xl p-6">
                <h3 class="text-lg font-bold text-[#6C5DD3] mb-4">Tulis Artikel Baru</h3>
                <form action="{{ route('articles.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Judul</label>
                        <input type="text" name="title" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-[#6C5DD3] outline-none" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Konten</label>
                        <textarea name="content" rows="6" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-[#6C5DD3] outline-none resize-none" required></textarea>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Thumbnail</label>
                            <input type="file" name="thumbnail" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <select name="status" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-[#6C5DD3] outline-none">
                                <option value="published">Published</option>
                                <option value="draft">Draft</option>
                            </select>
                        </div>
                    </div>
                    <div class="flex justify-end gap-2 mt-4">
                        <button type="button" onclick="document.getElementById('createModal').classList.add('hidden')" class="px-4 py-2 bg-gray-100 rounded-lg">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-[#6C5DD3] text-white rounded-lg">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function openModal(id) { document.getElementById(id).classList.remove('hidden'); }
        // Implementasi edit & delete logic
    </script>
    @endpush
    {{-- @include('admin.partials.delete_modal_script', ['routePrefix' => 'articles']) --}}
@endsection
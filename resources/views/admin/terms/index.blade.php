@extends('layouts.admin')

@section('content')
    <div class="space-y-6">
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                {{ session('success') }}</div>
        @endif

        <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100 flex justify-between items-center">
            <h2 class="text-xl font-bold text-gray-800">Master Halaman (Syarat, Kebijakan, dll)</h2>
            <button onclick="openModal('createModal')"
                class="flex items-center gap-2 bg-[#6C5DD3] hover:bg-[#5b4ec2] text-white text-sm font-medium px-5 py-2.5 rounded-lg transition shadow-md">
                Tambah Halaman
            </button>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-50 text-gray-500 text-xs uppercase">
                    <tr>
                        <th class="py-4 px-6 w-10">#</th>
                        <th class="py-4 px-4">Judul Halaman</th>
                        <th class="py-4 px-4">Status</th>
                        <th class="py-4 px-6 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($pages as $page)
                        <tr class="hover:bg-gray-50">
                            <td class="py-4 px-6 text-gray-500">{{ $loop->iteration }}</td>
                            <td class="py-4 px-4 font-bold text-gray-800">{{ $page->title }}</td>
                            <td class="py-4 px-4">
                                <span
                                    class="px-2 py-1 rounded text-xs font-bold {{ $page->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                    {{ $page->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-right">
                                <!-- PERBAIKAN 1: Kirim is_active (1 atau 0) ke JS -->
                                <button onclick="openEditModal(
                                    '{{ $page->id }}', 
                                    '{{ addslashes($page->title) }}', 
                                    '{{ base64_encode($page->content) }}',
                                    '{{ $page->is_active ? '1' : '0' }}' 
                                )" class="text-indigo-600 font-medium text-sm mr-2">Edit</button>

                                <button onclick="openDeleteModal('{{ $page->id }}')" class="text-red-500 font-medium text-sm">Delete</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-8 text-gray-500">Belum ada halaman.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="px-6 py-4">{{ $pages->links() }}</div>
        </div>
    </div>

    <!-- CREATE MODAL -->
    <div id="createModal" class="relative z-50 hidden" role="dialog" aria-modal="true">
        <div class="fixed inset-0 backdrop-brightness-50 backdrop-blur-sm transition-opacity"></div>
        <div class="fixed inset-0 z-10 overflow-y-auto flex items-center justify-center p-4">
            <div class="bg-white rounded-xl shadow-xl w-full max-w-2xl p-6">
                <h3 class="text-lg font-bold text-[#6C5DD3] mb-4">Tambah Halaman Baru</h3>
                <form action="{{ route('master.pages.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Judul</label>
                        <input type="text" name="title"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-[#6C5DD3] outline-none"
                            required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Konten</label>
                        <!-- CKEditor Target -->
                        <textarea name="content" id="create_content"></textarea>
                    </div>
                    <div class="flex justify-end gap-2 mt-4">
                        <button type="button" onclick="closeModal('createModal')"
                            class="px-4 py-2 bg-gray-100 rounded-lg">Cancel</button>
                        <button type="submit" class="px-4 py-2 bg-[#6C5DD3] text-white rounded-lg">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- EDIT MODAL -->
    <div id="editModal" class="relative z-50 hidden" role="dialog" aria-modal="true">
        <div class="fixed inset-0 backdrop-brightness-50 backdrop-blur-sm transition-opacity"></div>
        <div class="fixed inset-0 z-10 overflow-y-auto flex items-center justify-center p-4">
            <div class="bg-white rounded-xl shadow-xl w-full max-w-2xl p-6">
                <h3 class="text-lg font-bold text-[#6C5DD3] mb-4">Edit Halaman</h3>
                <form id="editForm" method="POST" class="space-y-4">
                    @csrf @method('PUT')
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Judul</label>
                        <input type="text" name="title" id="edit_title"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-[#6C5DD3] outline-none"
                            required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select name="is_active" id="edit_status"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-[#6C5DD3] outline-none">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Konten</label>
                        <textarea name="content" id="edit_content"></textarea>
                    </div>
                    <div class="flex justify-end gap-2 mt-4">
                        <button type="button" onclick="closeModal('editModal')"
                            class="px-4 py-2 bg-gray-100 rounded-lg">Cancel</button>
                        <button type="submit" class="px-4 py-2 bg-[#6C5DD3] text-white rounded-lg">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Delete -->
    <div id="deleteModal" class="relative z-50 hidden" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-gray-900 bg-opacity-50 backdrop-blur-sm"></div>
        <div class="fixed inset-0 z-10 overflow-y-auto flex items-center justify-center p-4">
            <div class="bg-white rounded-xl shadow-xl w-full max-w-md p-6 text-center">
                <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-[#FFCE50] mb-4">
                    <svg class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-[#6C5DD3] mb-2">Hapus Halaman?</h3>
                <p class="text-gray-500 mb-6 text-sm">Data yang dihapus tidak bisa dikembalikan.</p>
                <div class="flex justify-center gap-3">
                    <button onclick="closeModal('deleteModal')"
                        class="px-4 py-2 bg-gray-100 rounded-lg text-gray-700">Batal</button>
                    <form id="deleteForm" method="POST">
                        @csrf @method('DELETE')
                        <button type="submit"
                            class="px-4 py-2 bg-[#6C5DD3] text-white rounded-lg hover:bg-[#5b4ec2]">Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <!-- CDN CKEditor 5 (Versi Terbaru) -->
    <script src="https://cdn.ckeditor.com/ckeditor5/40.1.0/classic/ckeditor.js"></script>

    <style>
        /* CSS Wajib agar editor punya tinggi yang pas dan tidak tertutup modal */
        .ck-editor__editable_inline {
            min-height: 250px; /* Tinggi minimal area ketik */
        }
        
        /* Mengatur Z-Index agar toolbar editor muncul di atas modal */
        :root {
            --ck-z-default: 10050;
            --ck-z-modal: 10050;
        }
    </style>

    <script>
        // Variabel global untuk menyimpan instance editor
        let editorCreateInstance;
        let editorEditInstance;

        // 1. Inisialisasi Editor saat halaman dimuat
        document.addEventListener("DOMContentLoaded", function() {
            
            // Setup Editor untuk Modal Create
            if(document.querySelector('#create_content')) {
                ClassicEditor
                    .create(document.querySelector('#create_content'))
                    .then(editor => {
                        editorCreateInstance = editor;
                    })
                    .catch(error => { console.error(error); });
            }

            // Setup Editor untuk Modal Edit
            if(document.querySelector('#edit_content')) {
                ClassicEditor
                    .create(document.querySelector('#edit_content'))
                    .then(editor => {
                        editorEditInstance = editor;
                    })
                    .catch(error => { console.error(error); });
            }
        });

        // 2. Fungsi Buka Modal Create
        function openModal() {
            // Kosongkan editor
            if(editorCreateInstance) {
                editorCreateInstance.setData('');
            }
            document.getElementById('createModal').classList.remove('hidden'); 
        }

        // 3. Fungsi Buka Modal Edit
        function openEditModal(id, title, encodedContent, status) {
            document.getElementById('edit_title').value = title;
            document.getElementById('edit_status').value = status;
            
            // Decode Base64 ke HTML String dan masukkan ke Editor
            if(editorEditInstance) {
                try {
                    // atob() untuk decode base64
                    const decodedData = atob(encodedContent);
                    editorEditInstance.setData(decodedData);
                } catch (e) {
                    console.error("Gagal decode data", e);
                    editorEditInstance.setData("");
                }
            }

            document.getElementById('editForm').action = '/admin/dashboard/master/pages/' + id;
            document.getElementById('editModal').classList.remove('hidden');
        }

        function closeModal(id) { 
            document.getElementById(id || 'createModal').classList.add('hidden'); 
        }

        function openDeleteModal(id) {
            document.getElementById('deleteForm').action = '/admin/dashboard/master/pages/' + id;
            document.getElementById('deleteModal').classList.remove('hidden');
        }
    </script>
@endpush
@endsection

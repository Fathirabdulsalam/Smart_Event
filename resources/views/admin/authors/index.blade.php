@extends('layouts.admin')

@section('content')
    <div class="space-y-6">

        <!-- Notifikasi -->
        @if (session('success') && !str_contains(session('success'), 'dihapus'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                <strong class="font-bold">Berhasil!</strong>
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                <strong class="font-bold">Error!</strong>
                <span class="block sm:inline">Gagal menyimpan data. Periksa inputan modal.</span>
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
            <form action="{{ route('authors.index') }}" method="GET" class="flex items-center gap-4 flex-1">
                <div class="relative flex-1 max-w-xs">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search Author..."
                        class="w-full bg-gray-100 border-none text-gray-700 text-sm rounded-lg py-2.5 pl-10 pr-4 focus:ring-2 focus:ring-[#6C5DD3] transition">
                </div>
            </form>

            <button onclick="openCreateModal()"
                class="flex items-center gap-2 bg-[#6C5DD3] hover:bg-[#5b4ec2] text-white text-sm font-medium px-5 py-2.5 rounded-lg transition shadow-md shadow-indigo-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Add Author
            </button>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="text-gray-400 text-xs border-b border-gray-100">
                            <th class="py-4 pl-6 pr-4">Profile</th>
                            <th class="py-4 px-4">Name / Username</th>
                            <th class="py-4 px-4">Category</th>
                            <th class="py-4 px-4">Documents</th>
                            <th class="py-4 px-4">Address</th>
                            <th class="py-4 px-6 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($authors as $author)
                            <tr class="hover:bg-gray-50 group transition-colors">
                                <td class="py-4 pl-6 pr-4">
                                    @if ($author->photo_path)
                                        <img src="{{ Storage::url($author->photo_path) }}"
                                            class="h-10 w-10 rounded-full object-cover border border-gray-200"
                                            alt="Profile">
                                    @else
                                        <div
                                            class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-500 font-bold">
                                            {{ substr($author->name, 0, 1) }}</div>
                                    @endif
                                </td>
                                <td class="py-4 px-4">
                                    <div class="font-bold text-gray-700 text-sm">{{ $author->name }}</div>
                                    <div class="text-xs text-gray-400 mt-0.5">@ {{ $author->username ?? '-' }}</div>
                                </td>
                                <td class="py-4 px-4">
                                    <span
                                        class="bg-indigo-50 text-indigo-700 px-2 py-1 rounded text-xs font-medium">{{ $author->category->name ?? '-' }}</span>
                                </td>
                                <td class="py-4 px-4">
                                    <div class="flex gap-2">
                                        @if ($author->ktp_path)
                                            <a href="{{ Storage::url($author->ktp_path) }}" target="_blank"
                                                class="text-xs border border-gray-200 px-2 py-1 rounded hover:bg-gray-50 text-gray-600 flex items-center gap-1">KTP</a>
                                        @endif
                                        @if ($author->npwp_path)
                                            <a href="{{ Storage::url($author->npwp_path) }}" target="_blank"
                                                class="text-xs border border-gray-200 px-2 py-1 rounded hover:bg-gray-50 text-gray-600 flex items-center gap-1">NPWP</a>
                                        @endif
                                    </div>
                                </td>
                                <td class="py-4 px-4 text-sm text-gray-600 truncate max-w-xs">
                                    {{ Str::limit($author->address, 30) }}
                                </td>
                                <td class="py-4 px-6 text-right">
                                    <div class="flex items-center justify-end gap-3">
                                        <button
                                            onclick="openEditModal(
    '{{ $author->id }}', 
    '{{ addslashes($author->name) }}', 
    '{{ addslashes($author->username) }}', 
    '{{ $author->category_id }}', 
    '{{ addslashes($author->address) }}'
)"
                                            class="flex items-center gap-1 px-4 py-1.5 bg-[#6C5DD3] hover:bg-[#5b4ec2] text-white text-xs font-medium rounded-md transition shadow-sm">
                                            Edit
                                        </button>
                                        <button onclick="openDeleteModal('{{ $author->id }}')"
                                            class="text-gray-500 hover:text-red-500 transition p-1">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                </path>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-8 text-gray-500">No authors found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $authors->withQueryString()->links() }}
            </div>
        </div>
    </div>

    <!-- ============================================== -->
    <!-- 1. REGISTER AUTHOR MODAL (SESUAI GAMBAR) -->
    <!-- ============================================== -->
    <div id="createModal" class="relative z-50 hidden" role="dialog" aria-modal="true">
        <!-- Backdrop -->
        <div class="fixed inset-0 backdrop-brightness-50 backdrop-blur-sm transition-opacity"></div>

        <!-- Modal Container -->
        <div class="fixed inset-0 z-10 overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4">

                <!-- Modal Panel -->
                <div
                    class="relative transform rounded-xl bg-white text-left shadow-xl transition-all sm:w-full sm:max-w-4xl border border-gray-200">

                    <!-- Header -->
                    <div class="flex justify-between items-center px-8 py-6">
                        <h3 class="text-2xl font-bold text-[#6C5DD3]">Register Author Account</h3>
                        <button onclick="closeModal('createModal')" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <!-- Form Content -->
                    <form action="{{ route('authors.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="px-8 pb-8">
                            <!-- 2 Column Grid -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-6">

                                <!-- LEFT COLUMN -->
                                <div class="space-y-5">
                                    <!-- Nama -->
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-600 mb-2">Nama</label>
                                        <input type="text" name="name"
                                            class="w-full border border-gray-300 rounded-md px-4 py-2 focus:ring-2 focus:ring-[#6C5DD3] focus:border-transparent outline-none"
                                            required>
                                    </div>

                                    <!-- Nama Author (Username) -->
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-600 mb-2">Nama Author</label>
                                        <input type="text" name="username"
                                            class="w-full border border-gray-300 rounded-md px-4 py-2 focus:ring-2 focus:ring-[#6C5DD3] focus:border-transparent outline-none"
                                            required>
                                    </div>

                                    <!-- Foto Profile (File Input Custom Style) -->
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-600 mb-2">Foto Profile</label>
                                        <div
                                            class="flex items-center border border-gray-300 rounded-md overflow-hidden bg-white">
                                            <input type="file" name="photo"
                                                class="block w-full text-sm text-gray-500
                                                file:mr-4 file:py-2 file:px-6
                                                file:rounded-none file:border-0
                                                file:text-sm file:font-medium
                                                file:bg-gray-50 file:text-gray-700
                                                hover:file:bg-gray-100
                                                file:border-r file:border-gray-300
                                                cursor-pointer"
                                                accept="image/*" required>
                                        </div>
                                    </div>

                                    <!-- Email & Password (Disisipkan agar valid, bisa di hide jika perlu tapi wajib diisi) -->
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-xs font-semibold text-gray-400 mb-1">Email
                                                (Required)</label>
                                            <input type="email" name="email"
                                                class="w-full border border-gray-300 rounded-md px-3 py-1.5 text-sm focus:ring-[#6C5DD3] outline-none"
                                                required>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-semibold text-gray-400 mb-1">Password
                                                (Required)</label>
                                            <input type="password" name="password"
                                                class="w-full border border-gray-300 rounded-md px-3 py-1.5 text-sm focus:ring-[#6C5DD3] outline-none"
                                                required>
                                        </div>
                                    </div>
                                </div>

                                <!-- RIGHT COLUMN -->
                                <div class="space-y-5">
                                    <!-- Kategori -->
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-600 mb-2">Kategori</label>
                                        <select name="category_id"
                                            class="w-full border border-gray-300 rounded-md px-4 py-2 focus:ring-2 focus:ring-[#6C5DD3] focus:border-transparent outline-none bg-white"
                                            required>
                                            <option value="">Pilih Kategori</option>
                                            @foreach ($categories as $cat)
                                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- KTP -->
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-600 mb-2">KTP</label>
                                        <div
                                            class="flex items-center border border-gray-300 rounded-md overflow-hidden bg-white">
                                            <input type="file" name="ktp"
                                                class="block w-full text-sm text-gray-500
                                                file:mr-4 file:py-2 file:px-6
                                                file:rounded-none file:border-0
                                                file:text-sm file:font-medium
                                                file:bg-gray-50 file:text-gray-700
                                                hover:file:bg-gray-100
                                                file:border-r file:border-gray-300
                                                cursor-pointer"
                                                required>
                                        </div>
                                    </div>

                                    <!-- NPWP -->
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-600 mb-2">NPWP</label>
                                        <div
                                            class="flex items-center border border-gray-300 rounded-md overflow-hidden bg-white">
                                            <input type="file" name="npwp"
                                                class="block w-full text-sm text-gray-500
                                                file:mr-4 file:py-2 file:px-6
                                                file:rounded-none file:border-0
                                                file:text-sm file:font-medium
                                                file:bg-gray-50 file:text-gray-700
                                                hover:file:bg-gray-100
                                                file:border-r file:border-gray-300
                                                cursor-pointer"
                                                required>
                                        </div>
                                    </div>
                                </div>

                                <!-- FULL WIDTH -->
                                <div class="col-span-1 md:col-span-2">
                                    <label class="block text-sm font-semibold text-gray-600 mb-2">Address</label>
                                    <textarea name="address" rows="4"
                                        class="w-full border border-gray-300 rounded-md px-4 py-2 focus:ring-2 focus:ring-[#6C5DD3] focus:border-transparent outline-none resize-none"
                                        required></textarea>
                                </div>

                            </div>

                            <!-- Footer Button -->
                            <div class="mt-8 flex justify-end">
                                <button type="submit"
                                    class="bg-[#6C5DD3] text-white font-medium py-2 px-8 rounded-md hover:bg-[#5b4ec2] transition shadow-md shadow-indigo-200">
                                    Kirim
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <!-- ============================================== -->
    <!-- 2. EDIT AUTHOR MODAL -->
    <!-- ============================================== -->
    <div id="editModal" class="relative z-50 hidden" role="dialog" aria-modal="true">
        <!-- Backdrop -->
        <div class="fixed inset-0 backdrop-brightness-50 backdrop-blur-sm transition-opacity"></div>

        <!-- Modal Container -->
        <div class="fixed inset-0 z-10 overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4">

                <!-- Modal Panel -->
                <div
                    class="relative transform rounded-xl bg-white text-left shadow-xl transition-all sm:w-full sm:max-w-4xl border border-gray-200">

                    <!-- Header -->
                    <div class="flex justify-between items-center px-8 py-6">
                        <h3 class="text-2xl font-bold text-[#6C5DD3]">Edit Author Account</h3>
                        <button onclick="closeModal('editModal')" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <!-- Form Content -->
                    <form id="editForm" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT') <!-- Method PUT wajib untuk Update -->

                        <div class="px-8 pb-8">
                            <!-- Alert Info -->
                            <div class="mb-6 bg-blue-50 border-l-4 border-blue-500 text-blue-700 p-3 text-sm rounded">
                                <p>Biarkan input file kosong jika tidak ingin mengubah dokumen yang sudah ada.</p>
                            </div>

                            <!-- 2 Column Grid -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-6">

                                <!-- LEFT COLUMN -->
                                <div class="space-y-5">
                                    <!-- Nama -->
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-600 mb-2">Nama</label>
                                        <input type="text" name="name" id="edit_name"
                                            class="w-full border border-gray-300 rounded-md px-4 py-2 focus:ring-2 focus:ring-[#6C5DD3] focus:border-transparent outline-none"
                                            required>
                                    </div>

                                    <!-- Nama Author (Username) -->
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-600 mb-2">Nama Author
                                            (Username)</label>
                                        <!-- Biasanya username tidak boleh diedit sembarangan, tapi jika boleh, tambahkan value -->
                                        <input type="text" name="username" id="edit_username"
                                            class="w-full border border-gray-300 rounded-md px-4 py-2 focus:ring-2 focus:ring-[#6C5DD3] focus:border-transparent outline-none bg-gray-100"
                                            readonly title="Username cannot be changed">
                                    </div>

                                    <!-- Foto Profile -->
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-600 mb-2">Update Foto
                                            Profile</label>
                                        <div
                                            class="flex items-center border border-gray-300 rounded-md overflow-hidden bg-white">
                                            <input type="file" name="photo"
                                                class="block w-full text-sm text-gray-500
                                            file:mr-4 file:py-2 file:px-6
                                            file:rounded-none file:border-0
                                            file:text-sm file:font-medium
                                            file:bg-gray-50 file:text-gray-700
                                            hover:file:bg-gray-100
                                            file:border-r file:border-gray-300
                                            cursor-pointer"
                                                accept="image/*">
                                        </div>
                                    </div>
                                </div>

                                <!-- RIGHT COLUMN -->
                                <div class="space-y-5">
                                    <!-- Kategori -->
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-600 mb-2">Kategori</label>
                                        <select name="category_id" id="edit_category_id"
                                            class="w-full border border-gray-300 rounded-md px-4 py-2 focus:ring-2 focus:ring-[#6C5DD3] focus:border-transparent outline-none bg-white"
                                            required>
                                            <option value="">Pilih Kategori</option>
                                            @foreach ($categories as $cat)
                                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- KTP -->
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-600 mb-2">Update KTP</label>
                                        <div
                                            class="flex items-center border border-gray-300 rounded-md overflow-hidden bg-white">
                                            <input type="file" name="ktp"
                                                class="block w-full text-sm text-gray-500
                                            file:mr-4 file:py-2 file:px-6
                                            file:rounded-none file:border-0
                                            file:text-sm file:font-medium
                                            file:bg-gray-50 file:text-gray-700
                                            hover:file:bg-gray-100
                                            file:border-r file:border-gray-300
                                            cursor-pointer">
                                        </div>
                                    </div>

                                    <!-- NPWP -->
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-600 mb-2">Update NPWP</label>
                                        <div
                                            class="flex items-center border border-gray-300 rounded-md overflow-hidden bg-white">
                                            <input type="file" name="npwp"
                                                class="block w-full text-sm text-gray-500
                                            file:mr-4 file:py-2 file:px-6
                                            file:rounded-none file:border-0
                                            file:text-sm file:font-medium
                                            file:bg-gray-50 file:text-gray-700
                                            hover:file:bg-gray-100
                                            file:border-r file:border-gray-300
                                            cursor-pointer">
                                        </div>
                                    </div>
                                </div>

                                <!-- FULL WIDTH -->
                                <div class="col-span-1 md:col-span-2">
                                    <label class="block text-sm font-semibold text-gray-600 mb-2">Address</label>
                                    <textarea name="address" id="edit_address" rows="4"
                                        class="w-full border border-gray-300 rounded-md px-4 py-2 focus:ring-2 focus:ring-[#6C5DD3] focus:border-transparent outline-none resize-none"
                                        required></textarea>
                                </div>

                            </div>

                            <!-- Footer Button -->
                            <div class="mt-8 flex justify-end gap-3">
                                <button type="button" onclick="closeModal('editModal')"
                                    class="bg-white border border-gray-300 text-gray-700 font-medium py-2 px-6 rounded-md hover:bg-gray-50 transition">
                                    Cancel
                                </button>
                                <button type="submit"
                                    class="bg-[#6C5DD3] text-white font-medium py-2 px-8 rounded-md hover:bg-[#5b4ec2] transition shadow-md shadow-indigo-200">
                                    Update
                                </button>
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
                        <h3 class="text-xl font-bold text-[#6C5DD3] leading-snug px-4">Are You Sure You Want to Delete This
                            Author?</h3>
                        <p class="text-sm text-[#6C5DD3] opacity-80 mt-2 mb-6">This will remove the user account and all
                            documents permanently.</p>
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
        <div class="fixed inset-0 z-10 overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4 text-center">
                <div
                    class="relative transform overflow-hidden rounded-xl bg-white text-left shadow-2xl transition-all sm:w-full sm:max-w-sm p-8">
                    <div class="text-center">
                        <div
                            class="mx-auto flex h-24 w-24 items-center justify-center rounded-full bg-[#00C851] mb-6 shadow-md">
                            <div class="h-20 w-20 rounded-full border-2 border-white flex items-center justify-center">
                                <svg class="h-10 w-10 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24" stroke-width="3">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                        </div>
                        <h3 class="text-2xl font-bold text-[#6C5DD3] mb-2">Delete Data Success!</h3>
                    </div>
                    <div class="mt-8">
                        <button onclick="closeModal('successModal')"
                            class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2.5 bg-[#6C5DD3] text-base font-medium text-white hover:bg-[#5b4ec2]">OK</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function openCreateModal() {
                document.getElementById('createModal').classList.remove('hidden');
            }

            // Logic Edit Modal bisa disesuaikan nanti
            function openEditModal(id, name, username, categoryId, address) {
                // 1. Isi nilai input form
                document.getElementById('edit_name').value = name;
                document.getElementById('edit_username').value = username;
                document.getElementById('edit_category_id').value = categoryId;
                document.getElementById('edit_address').value = address;

                // 2. Update Action URL Form
                document.getElementById('editForm').action = '/admin/dashboard/authors/' + id + '/update';

                // 3. Tampilkan Modal
                document.getElementById('editModal').classList.remove('hidden');
            }

            function openDeleteModal(id) {
                document.getElementById('deleteForm').action = '/admin/dashboard/authors/' + id;
                document.getElementById('deleteModal').classList.remove('hidden');
            }


            function closeModal(modalId) {
                document.getElementById(modalId).classList.add('hidden');
            }

            document.addEventListener('keydown', function(event) {
                if (event.key === "Escape") {
                    document.querySelectorAll('[role="dialog"]').forEach(modal => modal.classList.add('hidden'));
                }
            });

            @if ($errors->any() && !request()->isMethod('put'))
                openCreateModal();
            @endif

            @if (session('success') && str_contains(session('success'), 'dihapus'))
                document.getElementById('successModal').classList.remove('hidden');
            @endif
        </script>
    @endpush
@endsection

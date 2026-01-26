@extends('layouts.admin')

@section('content')
    <div class="flex flex-col items-center justify-center min-h-[calc(100vh-100px)] py-6">
        
        <!-- Logo Header (Sesuai Gambar) -->
        <div class="mb-6 text-center">
            <h1 class="text-3xl font-bold text-black">Edit Profil</h1>
        </div>

        <!-- Card Container -->
        <div class="w-full max-w-5xl bg-white rounded-3xl shadow-xl p-8 md:p-12">
            
            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
                    
                    <!-- KOLOM KIRI (FORM INPUT) -->
                    <div class="lg:col-span-2 space-y-6">
                        <h3 class="text-lg font-bold text-black mb-6">Data Pribadi</h3>

                        <!-- Nama Lengkap -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
                            <label class="text-gray-600 font-medium text-sm md:text-base">Nama Lengkap</label>
                            <div class="md:col-span-2">
                                <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-[#6C5DD3] focus:border-transparent outline-none transition shadow-sm" required>
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
                            <label class="text-gray-600 font-medium text-sm md:text-base">Email</label>
                            <div class="md:col-span-2">
                                <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-[#6C5DD3] focus:border-transparent outline-none transition shadow-sm" required>
                            </div>
                        </div>

                        <!-- Nomor Handphone -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
                            <label class="text-gray-600 font-medium text-sm md:text-base">Nomor Handphone</label>
                            <div class="md:col-span-2">
                                <input type="text" name="phone_number" value="{{ old('phone_number', $user->phone_number) }}" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-[#6C5DD3] focus:border-transparent outline-none transition shadow-sm">
                            </div>
                        </div>

                        <!-- Alamat -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
                            <label class="text-gray-600 font-medium text-sm md:text-base">Alamat</label>
                            <div class="md:col-span-2">
                                <input type="text" name="address" value="{{ old('address', $user->address) }}" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-[#6C5DD3] focus:border-transparent outline-none transition shadow-sm">
                            </div>
                        </div>

                        <!-- Username -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
                            <label class="text-gray-600 font-medium text-sm md:text-base">Username</label>
                            <div class="md:col-span-2">
                                <input type="text" name="username" value="{{ old('username', $user->username) }}" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-[#6C5DD3] focus:border-transparent outline-none transition shadow-sm">
                            </div>
                        </div>

                        <!-- Password -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
                            <label class="text-gray-600 font-medium text-sm md:text-base">Password</label>
                            <div class="md:col-span-2">
                                <input type="password" name="password" placeholder="Kosongkan jika tidak ingin mengubah" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-[#6C5DD3] focus:border-transparent outline-none transition shadow-sm">
                            </div>
                        </div>
                    </div>

                    <!-- KOLOM KANAN (FOTO PROFILE) -->
                    <div class="lg:col-span-1 flex flex-col items-center">
                        <div class="relative group cursor-pointer mb-4" onclick="document.getElementById('fileInput').click()">
                            <!-- Preview Image -->
                            <img id="previewImage" 
                                 src="{{ $user->photo_path ? Storage::url($user->photo_path) : asset('img/default-avatar.png') }}" 
                                 onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=0D8ABC&color=fff&size=128'"
                                 class="w-32 h-32 rounded-full object-cover border-4 border-white shadow-lg">
                            
                        </div>
                        
                        <h4 class="font-bold text-lg mb-8">Ganti Foto</h4>

                        <!-- Custom File Upload Button -->
                        <div class="flex flex-col items-center gap-2 cursor-pointer" onclick="document.getElementById('fileInput').click()">
                            <div class="flex items-center gap-2 text-gray-700 hover:text-[#6C5DD3] transition">
                                <!-- Icon Plus Blue -->
                                <div class="w-6 h-6 bg-[#6C5DD3] rounded-full flex items-center justify-center text-white">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                </div>
                                <span class="font-medium text-sm">Upload Foto Profil</span>
                            </div>
                            <input type="file" name="photo" id="fileInput" class="hidden" accept="image/*" onchange="previewFile(this)">
                        </div>

                        <!-- Buttons Area -->
                        <div class="w-full mt-10 flex flex-col gap-4">
                            <!-- Tombol Simpan -->
                            <button type="submit" 
                                class="w-full bg-[#4E46E5] hover:bg-[#4338ca] text-white font-bold py-3 px-6 rounded-full shadow-md hover:shadow-lg transition-all duration-300 transform hover:-translate-y-0.5 active:scale-95 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#4E46E5]">
                                Simpan Perubahan
                            </button>
                            
                            <!-- Tombol Batal -->
                            <a href="{{ route('profile.index') }}" 
                               class="w-full flex justify-center items-center bg-white border-2 border-[#4E46E5] text-[#4E46E5] font-bold py-3 px-6 rounded-full hover:bg-indigo-50 transition-colors duration-300">
                                Batal
                            </a>
                        </div>
                    </div>

                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        function previewFile(input) {
            var file = input.files[0];
            if(file){
                var reader = new FileReader();
                reader.onload = function(){
                    document.getElementById('previewImage').src = reader.result;
                }
                reader.readAsDataURL(file);
            }
        }
    </script>
    @endpush
@endsection
@extends('layouts.user')

@section('content')
    <!-- Background Header -->
    <div class="bg-[#4838CC] h-48 w-full absolute top-0 z-0"></div>

    <div class="container mx-auto px-4 relative z-10 pt-8 pb-20">
        <div class="flex flex-col lg:flex-row gap-8">
            
            <!-- SIDEBAR -->
            @include('users.partials.sidebar')

            <!-- MAIN CONTENT -->
            <div class="w-full lg:w-3/4">
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-8 min-h-[500px]">
                    
                    <!-- Header -->
                    <div class="border-b border-gray-100 pb-6 mb-6">
                        <h1 class="text-2xl font-bold text-gray-800">Edit Profile</h1>
                        <p class="text-gray-500 text-sm mt-1">Manage your account information and password.</p>
                    </div>

                    <!-- Alert Notification -->
                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6">
                            <ul class="list-disc pl-5 text-sm">
                                @foreach($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Form -->
                    <form action="{{ route('user.profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                            
                            <!-- KOLOM KIRI (INPUT DATA) -->
                            <div class="lg:col-span-2 space-y-5">
                                
                                <!-- Nama Lengkap -->
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Lengkap</label>
                                    <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-[#4838CC] focus:border-[#4838CC] outline-none transition" required>
                                </div>

                                <!-- Username & Email (Grid) -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">Username</label>
                                        <input type="text" name="username" value="{{ old('username', $user->username) }}" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-[#4838CC] focus:border-[#4838CC] outline-none transition bg-gray-50" title="Username">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
                                        <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-[#4838CC] focus:border-[#4838CC] outline-none transition" required>
                                    </div>
                                </div>

                                <!-- Phone -->
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nomor WhatsApp / HP</label>
                                    <input type="text" name="phone_number" value="{{ old('phone_number', $user->phone_number) }}" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-[#4838CC] focus:border-[#4838CC] outline-none transition" placeholder="0812...">
                                </div>

                                <!-- Alamat -->
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Alamat Lengkap</label>
                                    <textarea name="address" rows="3" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-[#4838CC] focus:border-[#4838CC] outline-none resize-none transition">{{ old('address', $user->address) }}</textarea>
                                </div>

                                <div class="border-t border-gray-100 my-6"></div>

                                <!-- Ganti Password (Optional) -->
                                <div>
                                    <h3 class="text-sm font-bold text-gray-800 mb-4">Ganti Password <span class="text-gray-400 font-normal">(Kosongkan jika tidak ingin mengubah)</span></h3>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                        <div>
                                            <label class="block text-xs font-semibold text-gray-600 mb-1">Password Baru</label>
                                            <input type="password" name="password" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-[#4838CC] outline-none transition" placeholder="******">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-semibold text-gray-600 mb-1">Konfirmasi Password</label>
                                            <input type="password" name="password_confirmation" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-[#4838CC] outline-none transition" placeholder="******">
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <!-- KOLOM KANAN (FOTO PROFILE) -->
                            <div class="lg:col-span-1">
                                <div class="bg-gray-50 rounded-xl p-6 text-center border border-gray-200">
                                    <div class="relative w-32 h-32 mx-auto mb-4 group cursor-pointer" onclick="document.getElementById('photoInput').click()">
                                        <!-- Preview Image -->
                                        @if($user->photo_path)
                                            <img id="previewImage" src="{{ Storage::url($user->photo_path) }}" class="w-full h-full rounded-full object-cover border-4 border-white shadow-md">
                                        @else
                                            <img id="previewImage" src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=4838CC&color=fff" class="w-full h-full rounded-full object-cover border-4 border-white shadow-md">
                                        @endif
                                        
                                        <!-- Overlay Camera Icon -->
                                        <div class="absolute inset-0 bg-black/40 rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                        </div>
                                    </div>
                                    
                                    <h4 class="font-bold text-gray-800 text-sm mb-1">Foto Profil</h4>
                                    <p class="text-xs text-gray-500 mb-4">Besar file: maks. 2MB<br>Format: JPG, PNG, JPEG</p>
                                    
                                    <input type="file" name="photo" id="photoInput" class="hidden" accept="image/*" onchange="previewFile(this)">
                                    
                                    <button type="button" onclick="document.getElementById('photoInput').click()" class="text-[#4838CC] text-xs font-bold border border-[#4838CC] px-4 py-2 rounded-full hover:bg-indigo-50 transition">
                                        Pilih Foto
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Button Actions -->
                        <div class="mt-8 pt-6 border-t border-gray-100 flex flex-col-reverse sm:flex-row justify-end gap-3">
                            <a href="{{ route('user.dashboard') }}" class="px-6 py-3 rounded-xl border border-gray-300 text-gray-600 font-bold text-center hover:bg-gray-50 transition">
                                Batal
                            </a>
                            <button type="submit" class="px-8 py-3 rounded-xl bg-[#4838CC] text-white font-bold hover:bg-[#3b2db0] shadow-md transition transform hover:-translate-y-0.5">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <!-- Script Preview Image -->
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
@endsection
@extends('layouts.admin')

@section('content')
    <div class="max-w-4xl mx-auto space-y-6">

        <!-- Header -->
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Konfigurasi Sistem</h1>
                <p class="text-gray-500 text-sm mt-1">Atur batasan dan aturan transaksi tiket event.</p>
            </div>
        </div>

        <!-- Notifikasi -->
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                <strong class="font-bold">Berhasil!</strong> <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        <!-- Form Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            
            <form action="{{ route('configuration.update') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="p-8 space-y-8">
                    
                    <!-- 1. Maksimal Tiket -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-start border-b border-gray-100 pb-8">
                        <div class="md:col-span-1">
                            <h3 class="text-base font-semibold text-gray-800">Batasan Tiket</h3>
                            <p class="text-sm text-gray-500 mt-1">Mengatur jumlah maksimal tiket yang dapat dibeli dalam satu kali transaksi.</p>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah Maksimal (Pcs)</label>
                            <div class="relative max-w-xs">
                                <input type="number" name="max_ticket_per_trx" 
                                    value="{{ $configs['max_ticket_per_trx'] ?? 5 }}" 
                                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-[#6C5DD3] focus:border-transparent outline-none transition" 
                                    min="1" required>
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-gray-400 text-sm">
                                    Tiket
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 2. Validasi Email -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-start border-b border-gray-100 pb-8">
                        <div class="md:col-span-1">
                            <h3 class="text-base font-semibold text-gray-800">Aturan Akun Email</h3>
                            <p class="text-sm text-gray-500 mt-1">Membatasi penggunaan satu email untuk satu kali transaksi pada event yang sama.</p>
                        </div>
                        <div class="md:col-span-2">
                            <div class="flex items-center gap-4">
                                <label class="inline-flex items-center cursor-pointer">
                                    <input type="radio" name="one_email_one_trx" value="true" class="form-radio text-[#6C5DD3] w-5 h-5 focus:ring-[#6C5DD3]" 
                                        {{ ($configs['one_email_one_trx'] ?? 'false') == 'true' ? 'checked' : '' }}>
                                    <span class="ml-2 text-gray-700 font-medium">Aktif (Batasi)</span>
                                </label>
                                <label class="inline-flex items-center cursor-pointer">
                                    <input type="radio" name="one_email_one_trx" value="false" class="form-radio text-gray-400 w-5 h-5 focus:ring-gray-400"
                                        {{ ($configs['one_email_one_trx'] ?? 'false') == 'false' ? 'checked' : '' }}>
                                    <span class="ml-2 text-gray-700 font-medium">Non-Aktif (Bebas)</span>
                                </label>
                            </div>
                            <p class="text-xs text-gray-400 mt-2 italic">* Jika aktif, user tidak bisa checkout lagi untuk event yang sama menggunakan email yang sama.</p>
                        </div>
                    </div>

                    <!-- 3. Validasi Data Pemesan -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-start">
                        <div class="md:col-span-1">
                            <h3 class="text-base font-semibold text-gray-800">Detail Pemegang Tiket</h3>
                            <p class="text-sm text-gray-500 mt-1">Apakah setiap tiket harus memiliki data nama/identitas yang berbeda?</p>
                        </div>
                        <div class="md:col-span-2">
                            <div class="flex items-center gap-4">
                                <label class="inline-flex items-center cursor-pointer">
                                    <input type="radio" name="one_ticket_one_person" value="true" class="form-radio text-[#6C5DD3] w-5 h-5 focus:ring-[#6C5DD3]"
                                        {{ ($configs['one_ticket_one_person'] ?? 'false') == 'true' ? 'checked' : '' }}>
                                    <span class="ml-2 text-gray-700 font-medium">Wajib Isi Per Tiket</span>
                                </label>
                                <label class="inline-flex items-center cursor-pointer">
                                    <input type="radio" name="one_ticket_one_person" value="false" class="form-radio text-gray-400 w-5 h-5 focus:ring-gray-400"
                                        {{ ($configs['one_ticket_one_person'] ?? 'false') == 'false' ? 'checked' : '' }}>
                                    <span class="ml-2 text-gray-700 font-medium">Satu Data untuk Semua</span>
                                </label>
                            </div>
                            <p class="text-xs text-gray-400 mt-2 italic">* Pilih "Wajib" jika event membutuhkan sertifikat nama atau check-in per orang.</p>
                        </div>
                    </div>
                    <hr class="border-gray-100">

                    <!-- 4. SOCIAL MEDIA LINKS -->
                    {{-- <div>
                        <h3 class="text-base font-semibold text-gray-800 mb-4">Akun Sosial Media</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            
                            <!-- Instagram -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Instagram URL</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                                        <i class="bx bxl-instagram text-lg"></i> <!-- Pastikan ada boxicons/svg -->
                                    </div>
                                    <input type="url" name="sosmed_instagram" 
                                        value="{{ $configs['sosmed_instagram'] ?? '' }}" 
                                        class="w-full border border-gray-300 rounded-lg pl-10 pr-4 py-2.5 focus:ring-2 focus:ring-[#6C5DD3] focus:border-transparent outline-none transition" 
                                        placeholder="https://instagram.com/username">
                                </div>
                            </div>

                            <!-- Twitter / X -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Twitter / X URL</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                                        <i class="bx bxl-twitter text-lg"></i>
                                    </div>
                                    <input type="url" name="sosmed_twitter" 
                                        value="{{ $configs['sosmed_twitter'] ?? '' }}" 
                                        class="w-full border border-gray-300 rounded-lg pl-10 pr-4 py-2.5 focus:ring-2 focus:ring-[#6C5DD3] focus:border-transparent outline-none transition" 
                                        placeholder="https://twitter.com/username">
                                </div>
                            </div>

                            <!-- LinkedIn -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">LinkedIn URL</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                                        <i class="bx bxl-linkedin text-lg"></i>
                                    </div>
                                    <input type="url" name="sosmed_linkedin" 
                                        value="{{ $configs['sosmed_linkedin'] ?? '' }}" 
                                        class="w-full border border-gray-300 rounded-lg pl-10 pr-4 py-2.5 focus:ring-2 focus:ring-[#6C5DD3] focus:border-transparent outline-none transition" 
                                        placeholder="https://linkedin.com/company/username">
                                </div>
                            </div>

                            <!-- Facebook -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Facebook URL</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                                        <i class="bx bxl-facebook text-lg"></i>
                                    </div>
                                    <input type="url" name="sosmed_facebook" 
                                        value="{{ $configs['sosmed_facebook'] ?? '' }}" 
                                        class="w-full border border-gray-300 rounded-lg pl-10 pr-4 py-2.5 focus:ring-2 focus:ring-[#6C5DD3] focus:border-transparent outline-none transition" 
                                        placeholder="https://facebook.com/username">
                                </div>
                            </div>

                        </div>
                    </div> --}}

                </div>

                <!-- Footer Action -->
                <div class="bg-gray-50 px-8 py-5 border-t border-gray-100 flex justify-end">
                    <button type="submit" class="flex items-center gap-2 bg-[#6C5DD3] hover:bg-[#5b4ec2] text-white text-sm font-bold px-6 py-2.5 rounded-lg shadow-md shadow-indigo-200 transition transform hover:-translate-y-0.5">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Simpan Perubahan
                    </button>
                </div>

            </form>
        </div>
    </div>
@endsection
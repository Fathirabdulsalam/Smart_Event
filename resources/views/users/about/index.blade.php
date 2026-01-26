@extends('layouts.user')

@section('content')
    <!-- HEADER -->
    <div class="bg-[#4838CC] py-20 relative overflow-hidden">
        <div class="absolute bottom-0 right-0 w-96 h-96 bg-indigo-500 opacity-20 rounded-full translate-x-1/2 translate-y-1/2 blur-3xl"></div>
        <div class="container mx-auto px-4 text-center relative z-10">
            <h1 class="text-3xl md:text-5xl font-bold text-white mb-6">Tentang Kami</h1>
            <p class="text-indigo-100 text-lg max-w-3xl mx-auto leading-relaxed">
                Smart Event ID adalah platform manajemen event terdepan yang menghubungkan kreator event dengan audiens mereka melalui teknologi yang mudah, aman, dan inovatif.
            </p>
        </div>
    </div>

    <!-- MISSION SECTION -->
    <div class="container mx-auto px-4 py-16">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
            <div class="space-y-6">
                <div class="inline-block px-4 py-1.5 bg-indigo-50 text-[#4838CC] font-bold text-sm rounded-full">
                    
                </div>
                <h2 class="text-3xl font-bold text-gray-900">Membawa Pengalaman Event ke Level Selanjutnya</h2>
                <p class="text-gray-600 leading-relaxed">
                    Dimulai dari sebuah ide sederhana untuk memudahkan pembelian tiket konser, kini kami berkembang menjadi ekosistem event terlengkap. Kami percaya bahwa setiap momen berharga harus dirayakan tanpa hambatan teknis.
                </p>
            </div>
            <div class="relative">
                <div class="absolute inset-0 bg-[#4838CC] rounded-2xl transform rotate-3 opacity-10"></div>
                <img src="https://images.unsplash.com/photo-1540575467063-178a50c2df87?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80" 
                     class="relative rounded-2xl shadow-xl w-full h-[400px] object-cover" 
                     alt="Event Crowd">
            </div>
        </div>
    </div>

    <!-- TEAM SECTION -->
    {{-- <div class="bg-gray-50 py-16">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900">Bertemu dengan Tim</h2>
                <p class="text-gray-500 mt-2">Orang-orang hebat di balik layar.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-8">
                <!-- Team Member 1 -->
                <div class="bg-white p-6 rounded-2xl shadow-sm text-center border border-gray-100 hover:shadow-md transition">
                    <img src="https://ui-avatars.com/api/?name=CEO+Founder&background=4838CC&color=fff&size=128" class="w-24 h-24 rounded-full mx-auto mb-4 object-cover">
                    <h3 class="font-bold text-gray-800">John Doe</h3>
                    <p class="text-sm text-[#4838CC] font-medium">CEO & Founder</p>
                </div>
                <!-- Team Member 2 -->
                <div class="bg-white p-6 rounded-2xl shadow-sm text-center border border-gray-100 hover:shadow-md transition">
                    <img src="https://ui-avatars.com/api/?name=Tech+Lead&background=4838CC&color=fff&size=128" class="w-24 h-24 rounded-full mx-auto mb-4 object-cover">
                    <h3 class="font-bold text-gray-800">Jane Smith</h3>
                    <p class="text-sm text-[#4838CC] font-medium">Tech Lead</p>
                </div>
                <!-- Team Member 3 -->
                <div class="bg-white p-6 rounded-2xl shadow-sm text-center border border-gray-100 hover:shadow-md transition">
                    <img src="https://ui-avatars.com/api/?name=Product&background=4838CC&color=fff&size=128" class="w-24 h-24 rounded-full mx-auto mb-4 object-cover">
                    <h3 class="font-bold text-gray-800">Michael Roy</h3>
                    <p class="text-sm text-[#4838CC] font-medium">Product Manager</p>
                </div>
                <!-- Team Member 4 -->
                <div class="bg-white p-6 rounded-2xl shadow-sm text-center border border-gray-100 hover:shadow-md transition">
                    <img src="https://ui-avatars.com/api/?name=Marketing&background=4838CC&color=fff&size=128" class="w-24 h-24 rounded-full mx-auto mb-4 object-cover">
                    <h3 class="font-bold text-gray-800">Emily Rose</h3>
                    <p class="text-sm text-[#4838CC] font-medium">Head of Marketing</p>
                </div>
            </div>
        </div>
    </div> --}}
@endsection
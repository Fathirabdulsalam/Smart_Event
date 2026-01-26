@extends('layouts.admin')

@section('content')
    <div class="max-w-4xl mx-auto">
        <!-- Notifikasi -->
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <!-- Header Background / Cover (Optional aesthetics) -->
            <div class="h-32 bg-gradient-to-r from-[#6C5DD3] to-[#8E85E0]"></div>

            <div class="px-8 pb-8">
                <div class="flex flex-col md:flex-row items-start md:items-end -mt-12 mb-6 gap-6">
                    <!-- Profile Photo -->
                    <div class="relative">
                        <img src="{{ $user->photo_path ? Storage::url($user->photo_path) : 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&background=6C5DD3&color=fff' }}" 
                             class="w-32 h-32 rounded-full border-4 border-white shadow-md object-cover bg-white">
                    </div>
                    
                    <!-- Name & Role -->
                    <div class="flex-1 mb-2">
                        <h1 class="text-2xl font-bold text-gray-800">{{ $user->name }}</h1>
                        <p class="text-gray-500 font-medium">@ {{ $user->username ?? 'username' }} • <span class="text-[#6C5DD3]">{{ ucfirst($user->role ?? 'Admin') }}</span></p>
                    </div>

                    <!-- Edit Button -->
                    <div class="mb-4">
                        <a href="{{ route('profile.edit') }}" class="inline-flex items-center gap-2 bg-[#6C5DD3] hover:bg-[#5b4ec2] text-white px-6 py-2.5 rounded-lg font-medium transition shadow-md shadow-indigo-200">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                            Edit Profil
                        </a>
                    </div>
                </div>

                <!-- Details Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 border-t border-gray-100 pt-8">
                    <div>
                        <h3 class="text-gray-400 text-xs font-bold uppercase tracking-wider mb-1">Email Address</h3>
                        <p class="text-gray-700 font-medium">{{ $user->email }}</p>
                    </div>
                    <div>
                        <h3 class="text-gray-400 text-xs font-bold uppercase tracking-wider mb-1">Phone Number</h3>
                        <p class="text-gray-700 font-medium">{{ $user->phone_number ?? '-' }}</p>
                    </div>
                    <div class="md:col-span-2">
                        <h3 class="text-gray-400 text-xs font-bold uppercase tracking-wider mb-1">Address</h3>
                        <p class="text-gray-700 font-medium leading-relaxed">{{ $user->address ?? '-' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
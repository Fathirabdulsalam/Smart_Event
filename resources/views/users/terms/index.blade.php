@extends('layouts.user')

@section('content')
    <!-- HEADER -->
    <div class="bg-[#4838CC] py-16 relative overflow-hidden">
        <div class="absolute top-0 left-0 w-64 h-64 bg-white opacity-5 rounded-full -translate-x-1/2 -translate-y-1/2 blur-3xl"></div>
        <div class="container mx-auto px-4 text-center relative z-10">
            <h1 class="text-3xl md:text-5xl font-bold text-white mb-4">Syarat & Ketentuan</h1>
            <p class="text-indigo-200 text-lg max-w-2xl mx-auto">
                Panduan dan aturan penggunaan layanan Smart Event ID.
            </p>
        </div>
    </div>

    <!-- CONTENT WITH TABS -->
    <!-- x-data: Mengatur state tab aktif (default: 'buyer') -->
    <div class="container mx-auto px-4 py-16 max-w-4xl" x-data="{ tab: 'buyer' }">
        
        <!-- Tab Navigation -->
        <div class="flex justify-center mb-10">
            <div class="bg-gray-100 p-1 rounded-full inline-flex">
                <!-- Tab Pembeli -->
                <button @click="tab = 'buyer'" 
                    :class="tab === 'buyer' ? 'bg-[#4838CC] text-white shadow-md' : 'text-gray-500 hover:text-gray-700'"
                    class="px-8 py-3 rounded-full text-sm font-bold transition-all duration-300 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                    Untuk Pembeli
                </button>
                
                <!-- Tab Kreator -->
                <button @click="tab = 'creator'" 
                    :class="tab === 'creator' ? 'bg-[#4838CC] text-white shadow-md' : 'text-gray-500 hover:text-gray-700'"
                    class="px-8 py-3 rounded-full text-sm font-bold transition-all duration-300 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                    Untuk Event Creator
                </button>
            </div>
        </div>

        <!-- Content Box -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 md:p-12 text-gray-700 leading-relaxed min-h-[400px]">
            
            <!-- Konten Pembeli -->
            <div x-show="tab === 'buyer'" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-2"
                 x-transition:enter-end="opacity-100 translate-y-0">
                
                <div class="mb-6 border-b border-gray-100 pb-4">
                    <h2 class="text-2xl font-bold text-gray-900">Ketentuan Pembelian Tiket</h2>
                    <p class="text-sm text-gray-500 mt-1">Terakhir diperbarui: {{ $buyerTerms ? $buyerTerms->updated_at->format('d F Y') : '-' }}</p>
                </div>

                <div class="wysiwyg-content">
                    @if($buyerTerms)
                        {!! $buyerTerms->content !!}
                    @else
                        <div class="text-center py-10 text-gray-400">
                            <svg class="w-12 h-12 mx-auto mb-3 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            <p>Konten syarat pembeli belum tersedia.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Konten Kreator -->
            <div x-show="tab === 'creator'" style="display: none;"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-2"
                 x-transition:enter-end="opacity-100 translate-y-0">
                
                <div class="mb-6 border-b border-gray-100 pb-4">
                    <h2 class="text-2xl font-bold text-gray-900">Ketentuan Penyelenggara Event</h2>
                    <p class="text-sm text-gray-500 mt-1">Terakhir diperbarui: {{ $creatorTerms ? $creatorTerms->updated_at->format('d F Y') : '-' }}</p>
                </div>

                <div class="wysiwyg-content">
                    @if($creatorTerms)
                        {!! $creatorTerms->content !!}
                    @else
                        <div class="text-center py-10 text-gray-400">
                            <svg class="w-12 h-12 mx-auto mb-3 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            <p>Konten syarat kreator belum tersedia.</p>
                        </div>
                    @endif
                </div>
            </div>

        </div>

        <!-- Footer Info -->
        <div class="mt-8 text-center">
            <p class="text-gray-500 text-sm">
                Masih memiliki pertanyaan? Kunjungi <a href="{{ route('faq') }}" class="text-[#4838CC] font-bold hover:underline">Halaman FAQ</a> atau hubungi kami.
            </p>
        </div>
    </div>

    <!-- Style untuk format konten dari CKEditor Admin -->
    <style>
        .wysiwyg-content h2 { font-size: 1.5rem; font-weight: bold; color: #111827; margin-top: 1.5rem; margin-bottom: 0.75rem; }
        .wysiwyg-content h3 { font-size: 1.25rem; font-weight: bold; color: #374151; margin-top: 1.25rem; margin-bottom: 0.5rem; }
        .wysiwyg-content p { margin-bottom: 1rem; line-height: 1.7; color: #4B5563; }
        .wysiwyg-content ul { list-style-type: disc; padding-left: 1.5rem; margin-bottom: 1rem; }
        .wysiwyg-content ol { list-style-type: decimal; padding-left: 1.5rem; margin-bottom: 1rem; }
        .wysiwyg-content a { color: #4838CC; text-decoration: underline; }
        .wysiwyg-content strong { color: #111827; }
    </style>
@endsection
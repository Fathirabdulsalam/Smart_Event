@extends('layouts.user')

@section('content')
    <!-- HEADER -->
    <div class="bg-[#4838CC] py-16 relative overflow-hidden">
        <div class="container mx-auto px-4 text-center relative z-10">
            <h1 class="text-3xl md:text-5xl font-bold text-white mb-4">Pusat Bantuan</h1>
            <p class="text-indigo-200 text-lg max-w-2xl mx-auto">
                Temukan solusi untuk masalah Anda di bawah ini.
            </p>
        </div>
    </div>

    <!-- FAQ CONTENT -->
    <div class="container mx-auto px-4 py-16 max-w-4xl">
        
        @php
            // Definisi Style untuk Kategori
            $styles = [
                'General' => ['color' => 'text-[#4838CC]', 'bg' => 'bg-indigo-100', 'label' => 'Umum'],
                'Ticket'  => ['color' => 'text-green-600', 'bg' => 'bg-green-100', 'label' => 'Tiket & Pembayaran'],
                'Creator' => ['color' => 'text-orange-600', 'bg' => 'bg-orange-100', 'label' => 'Event Creator'],
            ];
        @endphp

        <!-- Loop Groups -->
        @foreach($faqs as $category => $items)
            @if(count($items) > 0)
                <div class="mb-10">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center gap-2">
                        <span class="w-8 h-8 rounded-lg {{ $styles[$category]['bg'] ?? 'bg-gray-100' }} {{ $styles[$category]['color'] ?? 'text-gray-600' }} flex items-center justify-center text-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </span>
                        {{ $styles[$category]['label'] ?? $category }}
                    </h2>
                    <div class="space-y-4">
                        @foreach($items as $faq)
                            @include('users.partials.faq-item', [
                                'question' => $faq->question, 
                                'answer' => $faq->answer
                            ])
                        @endforeach
                    </div>
                </div>
            @endif
        @endforeach

        <!-- Contact Support -->
        <div class="bg-gray-50 border border-gray-200 rounded-2xl p-8 text-center mt-12">
            <h3 class="text-xl font-bold text-gray-800 mb-2">Masih butuh bantuan?</h3>
            <p class="text-gray-500 mb-6">Tim support kami siap membantu Anda.</p>
            <a href="https://wa.me/6281234567890" target="_blank" class="inline-flex items-center gap-2 bg-[#25D366] hover:bg-[#128C7E] text-white font-bold py-3 px-8 rounded-full transition shadow-lg">
                Hubungi WhatsApp
            </a>
        </div>

    </div>

    <script>
        function toggleAccordion(id) {
            const content = document.getElementById('content-' + id);
            const icon = document.getElementById('icon-' + id);
            if (content.classList.contains('hidden')) {
                content.classList.remove('hidden');
                icon.classList.add('rotate-180');
            } else {
                content.classList.add('hidden');
                icon.classList.remove('rotate-180');
            }
        }
    </script>
@endsection
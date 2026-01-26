@extends('layouts.user')

@section('content')
    <!-- HEADER -->
    <div class="bg-[#4838CC] py-16 relative overflow-hidden">
        <div class="container mx-auto px-4 text-center relative z-10">
            <h1 class="text-3xl md:text-5xl font-bold text-white mb-4">{{ $page->title }}</h1>
        </div>
    </div>

    <!-- CONTENT -->
    <div class="container mx-auto px-4 py-16 max-w-4xl">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 md:p-12 text-gray-700 leading-relaxed wysiwyg-content">
            <!-- Menampilkan HTML dari database -->
            {!! $page->content !!}
        </div>
    </div>

    <style>
        /* Styling tambahan untuk konten dari CKEditor */
        .wysiwyg-content h2 { font-size: 1.5rem; font-weight: bold; margin-top: 1.5rem; margin-bottom: 0.5rem; color: #1f2937; }
        .wysiwyg-content p { margin-bottom: 1rem; }
        .wysiwyg-content ul { list-style: disc; margin-left: 1.5rem; margin-bottom: 1rem; }
        .wysiwyg-content ol { list-style: decimal; margin-left: 1.5rem; margin-bottom: 1rem; }
    </style>
@endsection
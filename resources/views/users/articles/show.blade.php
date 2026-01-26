@extends('layouts.user')

@section('content')
    <div class="container mx-auto px-4 py-10 max-w-5xl">

        <!-- Breadcrumb -->
        <div class="text-sm text-gray-500 mb-6 flex items-center gap-2">
            <a href="{{ route('landingPage') }}" class="hover:text-[#4838CC]">Home</a>
            <span>/</span>
            <a href="{{ route('articles.all') }}" class="hover:text-[#4838CC]">Artikel</a>
            <span>/</span>
            <span class="text-gray-800 truncate max-w-xs">{{ $article->title }}</span>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">

            <!-- Main Content -->
            <div class="lg:col-span-2">
                <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4 leading-tight">{{ $article->title }}</h1>

                <div class="flex items-center gap-4 text-sm text-gray-500 mb-6 border-b border-gray-100 pb-6">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center text-[#4838CC] font-bold">
                            {{ substr($article->author->name ?? 'A', 0, 1) }}
                        </div>
                        <span>{{ $article->author->name ?? 'Admin' }}</span>
                    </div>
                    <span>•</span>
                    <span>{{ $article->created_at->format('d F Y') }}</span>
                </div>

                <div class="mb-8 rounded-2xl overflow-hidden shadow-sm">
                    @if ($article->thumbnail_path)
                        <img src="{{ Storage::url($article->thumbnail_path) }}" class="w-full h-auto object-cover">
                    @endif
                </div>

                 <div class="prose max-w-none text-gray-700 leading-relaxed text-lg break-words text-justify">
                    {!! nl2br(e($article->content)) !!}
                </div>
            </div>

            <!-- Sidebar (Related) -->
            <div class="lg:col-span-1">
                <div class="sticky top-24">
                    <h3 class="font-bold text-gray-800 text-lg mb-4">Artikel Lainnya</h3>
                    <div class="space-y-4">
                        @foreach ($relatedArticles as $related)
                            <a href="{{ route('articles.show', $related->slug) }}" class="flex gap-4 group items-start">
                                <div class="w-24 h-24 bg-gray-200 rounded-lg overflow-hidden flex-shrink-0 border border-gray-100">
                                    @if ($related->thumbnail_path)
                                        <img src="{{ Storage::url($related->thumbnail_path) }}"
                                            class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                                    @endif
                                </div>
                                <div class="flex flex-col justify-center h-24">
                                    <h4 class="font-bold text-gray-800 text-sm line-clamp-2 group-hover:text-[#4838CC] transition mb-1 leading-snug">
                                        {{ $related->title }}
                                    </h4>
                                    
                                    <!-- TAMBAHAN: Nama Author di Sidebar -->
                                    <div class="text-xs text-[#4838CC] font-medium mb-1">
                                        Oleh: {{ $related->author->name ?? 'Admin' }}
                                    </div>

                                    <span class="text-[10px] text-gray-400">{{ $related->created_at->format('d M Y') }}</span>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
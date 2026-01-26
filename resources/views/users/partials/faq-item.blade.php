@props(['question', 'answer'])
@php
    $id = Str::random(10); // ID unik untuk script JS
@endphp

<div class="border border-gray-200 rounded-xl bg-white overflow-hidden hover:shadow-md transition duration-300">
    <button onclick="toggleAccordion('{{ $id }}')" class="w-full flex justify-between items-center px-6 py-5 text-left focus:outline-none bg-white">
        <span class="font-bold text-gray-800 text-lg">{{ $question }}</span>
        <svg id="icon-{{ $id }}" class="w-5 h-5 text-[#4838CC] transform transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
    </button>
    <div id="content-{{ $id }}" class="hidden px-6 pb-6 text-gray-600 leading-relaxed text-sm bg-gray-50 border-t border-gray-100 pt-4">
        {!! $answer !!}
    </div>
</div>
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
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 min-h-[500px]">
                    
                    <!-- Header -->
                    <div class="flex justify-between items-center mb-6 border-b border-gray-100 pb-4">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-800">Riwayat Transaksi</h1>
                            <p class="text-gray-500 text-sm">Lacak pembayaran dan tagihan tiket Anda.</p>
                        </div>
                        <div class="text-right">
                            <span class="text-xs text-gray-400 font-semibold uppercase tracking-wider">Total Pengeluaran</span>
                            <div class="text-xl font-bold text-[#4838CC]">
                                Rp {{ number_format($transactions->where('payment_status', 'PAID')->sum('amount'), 0, ',', '.') }}
                            </div>
                        </div>
                    </div>

                    <!-- Transaction List -->
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead class="bg-gray-50 text-gray-500 text-xs uppercase font-semibold">
                                <tr>
                                    <th class="py-4 px-4 rounded-tl-lg">ID Tagihan</th>
                                    <th class="py-4 px-4">Nama Event</th>
                                    <th class="py-4 px-4">Tanggal</th>
                                    <th class="py-4 px-4">Total</th>
                                    <th class="py-4 px-4">Status</th>
                                    <th class="py-4 px-4 text-right rounded-tr-lg">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse($transactions as $trx)
                                    <tr class="hover:bg-gray-50 transition">
                                        <!-- Invoice ID -->
                                        <td class="py-4 px-4">
                                            <span class="font-mono text-xs font-bold text-gray-600 bg-gray-200 px-2 py-1 rounded">
                                                #{{ substr($trx->external_id, 0, 15) }}...
                                            </span>
                                        </td>
                                        
                                        <!-- Event Name -->
                                        <td class="py-4 px-4">
                                            <div class="font-bold text-gray-800 text-sm line-clamp-1 w-40">
                                                {{ $trx->registration->event->name ?? 'Event Dihapus' }}
                                            </div>
                                            <div class="text-[10px] text-gray-400 mt-0.5">
                                                {{ $trx->registration->event->category->name ?? '-' }}
                                            </div>
                                        </td>

                                        <!-- Date (Format Indonesia) -->
                                        <td class="py-4 px-4 text-sm text-gray-600">
                                            {{-- Menggunakan locale('id') agar nama bulan jadi bahasa Indonesia --}}
                                            {{ $trx->created_at->locale('id')->translatedFormat('d F Y') }}
                                            <div class="text-[10px] text-gray-400">
                                                {{ $trx->created_at->format('H:i') }} WIB
                                            </div>
                                        </td>

                                        <!-- Amount -->
                                        <td class="py-4 px-4 text-sm font-bold text-gray-800">
                                            Rp {{ number_format($trx->amount, 0, ',', '.') }}
                                        </td>

                                        <!-- Status (Terjemahan Visual) -->
                                        <td class="py-4 px-4">
                                            @php
                                                $statusData = match($trx->payment_status) {
                                                    'PAID' => ['class' => 'bg-green-100 text-green-700', 'label' => 'BERHASIL'],
                                                    'PENDING' => ['class' => 'bg-yellow-100 text-yellow-700', 'label' => 'MENUNGGU'],
                                                    'EXPIRED' => ['class' => 'bg-gray-100 text-gray-500', 'label' => 'KADALUARSA'],
                                                    'FAILED' => ['class' => 'bg-red-100 text-red-700', 'label' => 'GAGAL'],
                                                    default => ['class' => 'bg-gray-100 text-gray-600', 'label' => $trx->payment_status]
                                                };
                                            @endphp
                                            <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase {{ $statusData['class'] }}">
                                                {{ $statusData['label'] }}
                                            </span>
                                        </td>

                                        <!-- Action -->
                                        <td class="py-4 px-4 text-right">
                                            @if($trx->payment_status == 'PENDING')
                                                <!-- Jika Pending -->
                                                <a href="{{ $trx->checkout_link }}" target="_blank" class="inline-block bg-[#FF6B00] hover:bg-[#e65a00] text-white text-xs font-bold py-1.5 px-3 rounded transition shadow-sm">
                                                    Bayar
                                                </a>
                                            @elseif($trx->payment_status == 'PAID')
                                                <!-- Jika Paid -->
                                                <a href="{{ route('event.detail', $trx->registration->event->id) }}" class="inline-block bg-indigo-50 hover:bg-indigo-100 text-[#4838CC] text-xs font-bold py-1.5 px-3 rounded transition">
                                                    E-Tiket
                                                </a>
                                            @else
                                                <span class="text-xs text-gray-400">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-12">
                                            <div class="flex flex-col items-center justify-center">
                                                <div class="bg-gray-100 p-4 rounded-full mb-3">
                                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                                                </div>
                                                <p class="text-gray-500 text-sm">Belum ada riwayat transaksi.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-6">
                        {{ $transactions->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
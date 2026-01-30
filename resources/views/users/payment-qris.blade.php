@extends('layouts.user')

@section('content')
<div class="container mx-auto px-4 py-20 text-center">
    <h1 class="text-2xl font-bold mb-4">
        Scan QRIS untuk Pembayaran
    </h1>

    @if($qrisUrl)
        <img src="{{ $qrisUrl }}" 
             alt="QRIS Pembayaran" 
             class="w-72 h-72 mx-auto border rounded-xl shadow-md">
    @else
        <div class="text-red-500">QRIS tidak tersedia.</div>
    @endif

    <p class="mt-4 font-semibold">
        Total: IDR {{ number_format($transaction->total_amount, 0, ',', '.') }}
    </p>

    @if($expiredTime)
        <p class="text-sm text-red-500 mt-2">
            Berlaku sampai:
            {{ \Carbon\Carbon::createFromFormat('YmdHis', $expiredTime)->format('d M Y H:i') }}
        </p>
    @endif

    <p class="mt-6 text-gray-500 text-sm">
        Jangan tutup halaman ini. Sistem akan otomatis memeriksa pembayaran setiap 5 detik.
    </p>

    <!-- Opsional: loading indicator -->
    <div id="checking" class="mt-4 text-sm text-blue-500 hidden">
        Memeriksa status pembayaran...
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const transactionCode = '{{ $transaction->transaction_code }}';
        // Ganti URL ini kalau route checkStatus lu beda
        const checkUrl = '/payment/check/' + transactionCode;

        const checkPayment = async () => {
            const checkingEl = document.getElementById('checking');
            if (checkingEl) checkingEl.classList.remove('hidden');

            try {
                const response = await fetch(checkUrl);
                const data = await response.json();

                if (data.paid === true) {
                    window.location.href = '/user/tickets';
                }
            } catch (error) {
                console.error('Gagal mengecek status:', error);
            } finally {
                if (checkingEl) checkingEl.classList.add('hidden');
            }
        };

        // Cek pertama kali setelah 1 detik, lalu ulangi tiap 5 detik
        setTimeout(() => {
            checkPayment();
            setInterval(checkPayment, 5000);
        }, 1000);
    });
</script>
@endpush
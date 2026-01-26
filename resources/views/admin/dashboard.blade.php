@extends('layouts.admin')

@section('content')
    <!-- Top Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        
        <!-- Card 1: Average Sales -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <h3 class="text-gray-500 font-semibold mb-2">Average Sales</h3>
            <div class="flex items-baseline gap-1 mb-4">
                <span class="text-xs font-semibold text-gray-500">IDR</span>
                <!-- Format number: 1.000.000 -->
                <span class="text-3xl font-bold text-gray-800">{{ number_format($avgSales / 1000, 0, ',', '.') }}K</span>
            </div>
            <div class="flex items-center justify-between">
                <div class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-bold flex items-center gap-1">
                    <span>+500K</span>
                    <span class="font-normal text-green-600">From last month</span>
                </div>
                <div class="bg-green-100 text-green-600 rounded-full p-1 text-xs font-bold px-2">
                    ↑ 20%
                </div>
            </div>
        </div>

        <!-- Card 2: Net Income -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <h3 class="text-gray-500 font-semibold mb-2">Net Income</h3>
            <div class="flex items-baseline gap-1 mb-4">
                <span class="text-xs font-semibold text-gray-500">IDR</span>
                <span class="text-3xl font-bold text-gray-800">{{ number_format($netIncome / 1000, 0, ',', '.') }}K</span>
            </div>
            <div class="flex items-center justify-between">
                <div class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-bold flex items-center gap-1">
                    <span>+500K</span>
                    <span class="font-normal text-green-600">From last month</span>
                </div>
                <div class="bg-green-100 text-green-600 rounded-full p-1 text-xs font-bold px-2">
                    ↑ 20%
                </div>
            </div>
        </div>

        <!-- Card 3: Total Order -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <h3 class="text-gray-500 font-semibold mb-2">Total Order</h3>
            <div class="mb-4">
                <span class="text-3xl font-bold text-gray-800">{{ number_format($totalOrders, 0, ',', '.') }}</span>
            </div>
            <div class="flex items-center justify-between">
                <div class="bg-red-100 text-red-600 px-3 py-1 rounded-full text-xs font-bold flex items-center gap-1">
                    <span>+100</span>
                    <span class="font-normal">From last month</span>
                </div>
                <div class="bg-red-100 text-red-600 rounded-full p-1 text-xs font-bold px-2">
                    ↓ 5%
                </div>
            </div>
        </div>
    </div>

    <!-- Content Split -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Left Section: Total Event & Table (Takes up 2 cols) -->
        <div class="lg:col-span-2 bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <!-- Header Section -->
            <div class="flex justify-between items-start mb-6 border-b border-gray-100 pb-4">
                <div>
                    <h3 class="text-gray-500 font-semibold">Total Event</h3>
                    <div class="text-4xl font-bold text-gray-800 mt-1">{{ number_format($totalEvents) }}</div>
                </div>
                <!-- Link to Events Page -->
                <a href="{{ route('events.index') }}" class="text-gray-400 hover:text-[#6C5DD3] transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                </a>
            </div>

            <!-- Filter Dropdown (Visual Only) -->
            <div class="mb-4">
                <button class="flex items-center gap-2 text-sm text-gray-600 border border-gray-300 rounded px-3 py-1 hover:bg-gray-50">
                    Recent Events
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>
            </div>

            <!-- Table Recent Events -->
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-gray-600">
                    <thead class="text-xs font-bold text-gray-500 uppercase border-b border-gray-200">
                        <tr>
                            <th class="py-3">Event Name</th>
                            <th class="py-3">Category</th>
                            <th class="py-3">Price</th>
                            <th class="py-3 text-right">Views</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($recentEvents as $event)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="py-4 font-medium text-gray-800">
                                {{ $event->name }} <br>
                                <span class="text-xs text-gray-400">{{ $event->date->format('d M Y') }}</span>
                            </td>
                            <td class="py-4">{{ $event->category->name ?? '-' }}</td>
                            <td class="py-4 font-semibold text-gray-800">
                                {{ $event->price == 0 ? 'Free' : number_format($event->price/1000, 0) . 'K' }}
                            </td>
                            <td class="py-4 text-right font-bold text-gray-600">{{ number_format($event->views) }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="py-4 text-center text-gray-500">No events found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Right Section: Chart (Takes up 1 col) -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 flex flex-col h-full">
            <div class="flex justify-between items-center mb-6">
                <h3 class="font-bold text-gray-800 text-lg">Event Categories</h3>
            </div>

            <!-- Chart Container -->
            <div class="relative flex-1 flex items-center justify-center">
                <canvas id="categoryChart"></canvas>
            </div>
        </div>

    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('categoryChart').getContext('2d');
        
        // Data dari Controller (Blade to JS)
        const labels = @json($chartLabels);
        const data = @json($chartValues);

        // Warna Chart Custom (Ungu, Pink, Biru, Orange, Teal)
        const backgroundColors = [
            '#847FF7', '#FF948E', '#4BCBEB', '#FFCE50', '#00C851'
        ];

        const myChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: labels,
                datasets: [{
                    data: data,
                    backgroundColor: backgroundColors,
                    borderWidth: 0,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '60%', 
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            usePointStyle: true,
                            pointStyle: 'circle',
                            padding: 20,
                            font: { size: 12 }
                        }
                    }
                }
            }
        });
    });
</script>
@endpush
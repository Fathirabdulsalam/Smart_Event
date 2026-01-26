<?php

namespace App\Http\Controllers\Admin;

use App\Models\Event;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DashboardAdminController extends Controller
{
    public function index()
    {
        // 1. Total Events
        $totalEvents = Event::count();

        // 2. Total Registrations / Orders (Pesanan Tiket)
        // Asumsi model Registration adalah peserta yang mendaftar ke event
        // Jika belum ada model Order/Payment, kita pakai dummy dulu atau hitung user non-admin
        $totalOrders = 20000; // Placeholder, ganti dengan Registration::count() atau Payment::count()

        // 3. Average Sales & Net Income (Placeholder logic)
        // Nanti diganti dengan sum('amount') dari tabel Payment
        $avgSales = 1000000; 
        $netIncome = 1000000;

        // 4. Chart Data (Contoh: Kategori Tiket)
        // Kita hitung jumlah event per kategori
        $chartData = Event::selectRaw('categories.name as category, count(*) as total')
            ->join('categories', 'events.category_id', '=', 'categories.id')
            ->groupBy('categories.name')
            ->get();
        
        $chartLabels = $chartData->pluck('category');
        $chartValues = $chartData->pluck('total');

        // 5. Recent Events (Tabel di Dashboard)
        $recentEvents = Event::with('category')->latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'totalEvents',
            'totalOrders',
            'avgSales',
            'netIncome',
            'chartLabels',
            'chartValues',
            'recentEvents'
        ));
    }  

}

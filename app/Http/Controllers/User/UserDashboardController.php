<?php

namespace App\Http\Controllers\User;

use App\Models\Registration;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class UserDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // 1. Ambil Tiket Aktif (Event yang akan datang)
        $activeTickets = Registration::with(['event.category', 'event.author'])
            ->where('user_id', $user->id)
            ->where('status', 'active')
            ->whereHas('event', function ($q) {
                $q->where('date', '>=', now()); // Event belum lewat
            })
            ->latest()
            ->get();

        // 2. Ambil Riwayat Event (Event yang sudah lewat)
        $pastTickets = Registration::with('event')
            ->where('user_id', $user->id)
            ->whereHas('event', function ($q) {
                $q->where('date', '<', now());
            })
            ->count();

        return view('users.dashboard.index', compact('user', 'activeTickets', 'pastTickets'));
    }
    
    // Method untuk halaman My Tickets secara spesifik (opsional, jika ingin dipisah)
    public function myTickets()
    {
        // ... logic similar to above
        return view('user.dashboard.tickets');
    }
}

<?php

namespace App\Http\Controllers\User;

use App\Models\Registration;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class UserTicketController extends Controller
{
    public function index()
    {
        // Ambil semua registrasi milik user, urutkan dari yang terbaru
        $tickets = Registration::with(['event.category', 'event.author', 'transaction'])
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('users.ticket.index', compact('tickets'));
    }
}

<?php

namespace App\Http\Controllers\User;

use App\Models\Transaction; 
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class UserTicketController extends Controller
{
    public function index()
    {
        $tickets = Transaction::with([
                'event.category',
                'event.author',
                'event.location',
                'ticket' 
            ])
            ->where('user_id', Auth::id())
            ->where('status', 'success') 
            ->latest()
            ->paginate(10);

        return view('users.ticket.index', compact('tickets'));
    }
}

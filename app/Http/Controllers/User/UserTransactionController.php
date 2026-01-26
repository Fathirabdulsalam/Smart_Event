<?php

namespace App\Http\Controllers\User;

use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class UserTransactionController extends Controller
{
    public function index()
    {
        // Ambil transaksi dimana registration milik user yang sedang login
        $transactions = Transaction::whereHas('registration', function($q) {
                $q->where('user_id', Auth::id());
            })
            ->with(['registration.event']) // Eager load event info
            ->latest() // Urutkan terbaru
            ->paginate(10);

        return view('users.transactions.index', compact('transactions'));
    }
}

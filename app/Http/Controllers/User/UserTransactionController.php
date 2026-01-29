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
        // Ambil transaksi langsung berdasarkan user_id
        $transactions = Transaction::with(['event', 'ticket'])
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('users.transactions.index', compact('transactions'));
    }
}
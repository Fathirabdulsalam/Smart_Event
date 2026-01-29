<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;

class MidtransCallbackController extends Controller
{
    public function handle(Request $request)
    {
        $serverKey = config('midtrans.server_key');

        $signature = hash(
            'sha512',
            $request->order_id .
            $request->status_code .
            $request->gross_amount .
            $serverKey
        );

        if ($signature !== $request->signature_key) {
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        $trx = Transaction::where('order_id', $request->order_id)->first();

        if (!$trx) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        $trx->update([
            'status' => $request->transaction_status,
            'payment_type' => $request->payment_type,
            'raw_response' => $request->all(),
        ]);

        // 🔥 INI INTINYA
        if ($request->transaction_status === 'settlement') {
            return response()->json(['message'=> 'YES YES AJA SIH'],0);
        }

        return response()->json(['message' => 'OK']);
    }

}

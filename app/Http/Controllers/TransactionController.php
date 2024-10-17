<?php

// app/Http/Controllers/TransactionController.php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\CartItem;
use Illuminate\Http\Request;

class TransactionController extends Controller
{

    // Menampilkan daftar transaksi
    public function index()
    {
        $transactions = Transaction::all();
        return view('transactions.index', compact('transactions'));
    }

    // Menampilkan detail transaksi
    public function show($id)
    {
        $transaction = Transaction::with('details.product')->findOrFail($id);
        return view('transactions.show', compact('transaction'));
    }

    public function processTransaction(Request $request)
    {
        $transaction = new Transaction();
        $transaction->total_amount = $request->total_amount;
        $transaction->paid_amount = $request->paid_amount;
        $transaction->change_amount = $request->change_amount;
        $transaction->save();

        $cartItems = CartItem::all();

        foreach ($cartItems as $item) {
            $transactionDetail = new TransactionDetail();
            $transactionDetail->transaction_id = $transaction->id;
            $transactionDetail->product_id = $item->product_id;
            $transactionDetail->quantity = $item->quantity;
            $transactionDetail->price = $item->product->price;
            $transactionDetail->save();

            // Optional: Kurangi stok produk jika diperlukan
            $item->product->decrement('stock', $item->quantity);

            // Hapus item dari keranjang
            $item->delete();
        }

        return response()->json(['success' => true, 'message' => 'Transaksi berhasil diproses 2.']);
        return redirect()->route('transactions.index');
    }

    public function showInvoice($id)
    {
        // Ambil transaksi berdasarkan ID
        $transaction = Transaction::with('details.product')->find($id);

        if (!$transaction) {
            abort(404, 'Transaction not found');
        }

        return view('transactions.invoice', compact('transaction'));
    }
}

<!-- resources/views/transactions/show.blade.php -->

@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Detail Transaksi ID: {{ $transaction->id }}</h2>
    <p>Total Pembayaran: Rp {{ number_format($transaction->total_amount, 2, ',', '.') }}</p>
    <p>Jumlah Dibayar: Rp {{ number_format($transaction->paid_amount, 2, ',', '.') }}</p>
    <p>Kembalian: Rp {{ number_format($transaction->change_amount, 2, ',', '.') }}</p>

    <h3>Detail Item</h3>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Nama Produk</th>
                <th>Quantity</th>
                <th>Harga Satuan</th>
                <th>Total Harga</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transaction->details as $detail)
            <tr>
                <td>{{ $detail->product->name }}</td>
                <td>{{ $detail->quantity }}</td>
                <td>Rp {{ number_format($detail->price, 2, ',', '.') }}</td>
                <td>Rp {{ number_format($detail->price * $detail->quantity, 2, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <a href="{{ route('transaction.invoice', ['id' => $transaction->id]) }}" class="btn btn-success" target="_blank">Cetak Invoice</a>

    <a href="{{ route('transactions.index') }}" class="btn btn-secondary">Kembali ke Daftar Transaksi</a>
</div>
@endsection

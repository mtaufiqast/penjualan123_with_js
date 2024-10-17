<!-- resources/views/transactions/index.blade.php -->

@extends('layouts.app')

@section('content')
<div class="container">
    <a href="/" class="btn btn-warning mt-3">Transaksi lagi</a>
    <h2>Daftar Transaksi</h2>
    
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID Transaksi</th>
                <th>Total Pembayaran</th>
                <th>Jumlah Dibayar</th>
                <th>Kembalian</th>
                <th>Tanggal</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transactions as $transaction)
            <tr>
                <td>{{ $transaction->id }}</td>
                <td>Rp {{ number_format($transaction->total_amount, 2, ',', '.') }}</td>
                <td>Rp {{ number_format($transaction->paid_amount, 2, ',', '.') }}</td>
                <td>Rp {{ number_format($transaction->change_amount, 2, ',', '.') }}</td>
                <td>{{ $transaction->created_at->format('d-m-Y H:i') }}</td>
                <td>
                    <a href="{{ route('transactions.show', $transaction->id) }}" class="btn btn-primary">Lihat Detail</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

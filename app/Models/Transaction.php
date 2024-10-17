<?php

// app/Models/Transaction.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    // Jika tabel tidak mengikuti konvensi Laravel
    protected $table = 'transactions';

    // Tentukan kolom yang bisa diisi secara massal
    protected $fillable = ['total_amount', 'paid_amount', 'change_amount'];

    public function details()
    {
        return $this->hasMany(TransactionDetail::class);
    }
}

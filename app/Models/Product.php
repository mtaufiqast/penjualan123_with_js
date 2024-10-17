<?php

// app/Models/Product.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['kode_product', 'name', 'price', 'stock'];

    protected $guarded = [];
}

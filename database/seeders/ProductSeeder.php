<?php

// database/seeders/ProductSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run()
    {
        Product::create(['kode_product' => 'P001', 'name' => 'Produk A', 'price' => 10000, 'stock' => 50]);
        Product::create(['kode_product' => 'P002', 'name' => 'Produk B', 'price' => 15000, 'stock' => 30]);
        Product::create(['kode_product' => 'P003', 'name' => 'Produk C', 'price' => 20000, 'stock' => 20]);
    }
}

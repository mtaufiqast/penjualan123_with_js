<?php

// app/Http/Controllers/CartController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\CartItem;
use Illuminate\Support\Facades\Log;

class CartController extends Controller
{
    public function addProduct(Request $request)
    {
        $kodeProduct = $request->kode_product;

        // Cari produk berdasarkan kode produk
        $product = Product::where('kode_product', $kodeProduct)->first();

        if ($product) {
            // Tambahkan produk ke keranjang
            $cartItem = CartItem::where('product_id', $product->id)->first();

            if ($cartItem) {
                // Jika produk sudah ada di keranjang, tambahkan kuantitas
                $cartItem->quantity += 1;
                $cartItem->save();
            } else {
                // Jika produk belum ada di keranjang, tambahkan produk baru
                CartItem::create([
                    'product_id' => $product->id,
                    'quantity' => 1
                ]);
            }

            return response()->json(['success' => true, 'message' => 'Produk berhasil ditambahkan ke keranjang']);
        } else {
            return response()->json(['error' => true, 'message' => 'Produk tidak ditemukan'], 404);
        }
    }


    // public function addProduct(Request $request)
    // {
    //     $productId = $request->input('product_id');
    //     Log::info('Product ID received: ' . $productId); // Debugging: lihat apakah ID diterima

    //     // Cari produk berdasarkan ID produk
    //     $product = Product::find($productId);

    //     if ($product) {
    //         // Tambahkan produk ke keranjang
    //         $cartItem = CartItem::where('product_id', $product->id)->first();

    //         if ($cartItem) {
    //             // Jika produk sudah ada di keranjang, tambahkan kuantitas
    //             $cartItem->quantity += 1;
    //             $cartItem->save();
    //         } else {
    //             // Jika produk belum ada di keranjang, tambahkan produk baru
    //             CartItem::create([
    //                 'product_id' => $product->id,
    //                 'quantity' => 1
    //             ]);
    //         }

    //         return response()->json(['success' => true, 'message' => 'Produk berhasil ditambahkan ke keranjang']);
    //     } else {
    //         return response()->json(['success' => false, 'message' => 'Produk tidak ditemukan'], 404);
    //     }
    // }


    public function getCartItems()
    {
        $cartItems = CartItem::with('product')->get();

        return $cartItems->map(function ($item) {
            return [
                'id' => $item->id,
                'product' => $item->product->name,
                'quantity' => $item->quantity,
                'price' => $item->product->price,
            ];
        });
    }

    // app/Http/Controllers/CartController.php

    public function removeCartItem($id)
    {
        $cartItem = CartItem::find($id);

        if ($cartItem) {
            $cartItem->delete();
            return response()->json(['success' => true, 'message' => 'Item berhasil dihapus dari keranjang']);
        } else {
            return response()->json(['success' => false, 'message' => 'Item tidak ditemukan'], 404);
        }
    }

    public function removeCartItemAll()
    {
        $cartItems = CartItem::all();

        foreach ($cartItems as $item) {

            // Hapus item dari keranjang
            $item->delete();
        }

        return response()->json(['success' => true, 'message' => 'Semua item berhasil dihapus dari keranjang']);
        return redirect()->route('cart');
    }


    public function search(Request $request)
    {
        $query = $request->get('q');
        $products = Product::where('kode_product', 'LIKE', "%{$query}%")
            ->orWhere('name', 'LIKE', "%{$query}%")
            ->get();

        return response()->json($products);
    }
}

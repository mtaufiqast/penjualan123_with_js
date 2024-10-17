<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\CartController;
use App\Http\Controllers\TransactionController;


//Route::post('/cart/add', [CartController::class, 'addProduct'])->name('cart.add');
Route::post('/cart/add', [CartController::class, 'addProduct'])->name('cart.add');

Route::get('/cart/items', [CartController::class, 'getCartItems']);

Route::delete('/cart/item/{id}/remove', [CartController::class, 'removeCartItem'])->name('cart.remove');

// Route::get('/cart/item/all', [CartController::class, 'removeCartItemAll'])->name('cart.all');
// Route::post('/cart/remove-all', [CartController::class, 'removeCartItemAll']);
Route::post('/cart/remove-all', [CartController::class, 'removeCartItemAll']);

Route::get('/cart/search', [CartController::class, 'search'])->name('cart.search');

Route::post('/transaction/process', [TransactionController::class, 'processTransaction'])->name('transaction.process');

Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');

Route::get('/transactions/{id}', [TransactionController::class, 'show'])->name('transactions.show');

Route::get('/transactions/{id}/invoice', [TransactionController::class, 'showInvoice'])->name('transaction.invoice');



Route::get('/', function () {
    return view('cart');
});

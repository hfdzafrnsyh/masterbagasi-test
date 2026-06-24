<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Cart\CartController;
use App\Http\Controllers\Order\OrderController;
use App\Http\Controllers\Product\ProductController;
use App\Http\Controllers\Voucher\VoucherController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


// Route::post('/login' , [])

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('/login' , [AuthController::class , 'login'])->name('login');
Route::post('/register' , [AuthController::class , 'register'])->name('register');


Route::middleware(['auth:sanctum' , 'Role:users'])->group(function(){

    //product
       Route::get('/product' , [ProductController::class , 'getProduct']);
       Route::get('/product/{id}/detail' , [ProductController::class , 'detailProduct']);


       //cart
       Route::post('/cart/add' , [CartController::class , 'addToCart']);
       Route::get('/cart/{id}/quantity/reduced' , [CartController::class , 'reducedProduct']);
       Route::get('/cart/{id}/quantity/added' , [CartController::class , 'addedProduct']);
       Route::get('/cart' , [CartController::class , 'getCart']);
       Route::delete('/cart/{id}/delete' , [CartController::class , 'delete']);
       Route::get('/cart/{id}/checked' , [CartController::class , 'checkedStatus']);
       Route::get('/cart/checkout',[CartController::class , 'cartCheckout']);  

       //check out
       Route::get('/checkout/product' , [OrderController::class , 'checkoutProduct']);
      
       //pesanan
       Route::post('/order/cart' , [OrderController::class , 'addOrderWithCart']);
       Route::post('/order/add' , [OrderController::class , 'addOrder']);
       Route::get('/order' , [OrderController::class , 'getOrder']);


        Route::post('/logout' , [AuthController::class , 'logout']);

});

Route::middleware(['auth:sanctum' , 'Role:admin'])->group(function(){

       Route::post('/product/add' , [ProductController::class , 'addProduct']);
       //voucher
       Route::post('/voucher/add' , [VoucherController::class , 'addVoucher']);
    


       Route::post('/logout' , [AuthController::class , 'logout']);
});

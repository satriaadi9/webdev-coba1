<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\FirstController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\StoreController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function(){
    //diisi route yg hanya bisa diakses oleh user yg sudah login

    Route::post('/add-to-cart/{product:id}',
    [StoreController::class, 'addToCart'])
    ->name('add_to_cart');

    Route::get('/view-cart', [StoreController::class, 'cart'])
    ->name('view_cart');

    Route::post('/delete-cart/{id}', [StoreController::class, 'removeFromCart'])
    ->name('remove_from_cart');

    Route::post('/checkout', [StoreController::class, 'checkout'])->name('checkout');

    Route::middleware(['role:customer,admin,owner'])->group(function(){
        Route::get('/', [HomeController::class, 'show'])
        ->name('home.show');

        Route::get('/home', [HomeController::class, 'show'])
        ->name('home');

        Route::get('/store',[StoreController::class, 'show'])
        ->name('store');
    });
    
    Route::middleware(['role:admin,owner'])->group(function(){
        //view
        Route::get('/product/insert-form',
        [StoreController::class, 'product_insert_form'])
        ->name('product_insert_form');

        //ini untuk submit data dari view
        Route::post('/product/insert',
        [StoreController::class, 'insert_product'])
        ->name('insert_product');

        Route::get('/product/edit-form/{product:id}',
        [StoreController::class, 'product_edit_form'])
        ->name('product_edit_form');

        Route::put('/product/edit/{product:id}',
        [StoreController::class, 'edit_product'])
        ->name('edit_product');

        Route::delete('/product/delete/{product:id}',
        [StoreController::class, 'delete_product'])
        ->name('delete_product');
    });

    Route::post('/logout',[AuthController::class,'logout'])
    ->name('logout');
});


Route::get('/login', [AuthController::class,'show'])
->name('login.show')->middleware('guest');

Route::post('/login_auth', [AuthController::class, 'login_auth'])
->name('login.auth');

Route::get('/home/sum', [FirstController::class, 'sum'])
->name('home.sum');

Route::get('/home/sum/{param1}/{param2}', [FirstController::class, 'sum2'])
->name('home.sum2');

Route::get('/welcome', function(){
    return view('welcome');
});

Route::get('/hi', function(){
    return "Hi jugaaaaaaaaaaaaa!";
})->name('hi');

Route::get('/halo', function(){
    return "Haloooo jugaaaaaaaaaaaaa!";
})->name('halo');

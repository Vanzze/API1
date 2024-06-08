<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get("/hello", function () {
    return response()-> json([
        "message" => 'Hello World'
    ]);
});

Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::post('/create-product', [ProductController::class, 'create'])->name('create-product');
Route::get('/products', [ProductController::class, 'getAllProducts'])->name('getAllProducts');
Route::get('/product/{id}', [ProductController::class, 'getProductById'])->name('getProductById');
Route::delete('/product/{id}', [ProductController::class, 'deleteProduct'])->name('deleteProduct');
Route::post('/update-product/{id}', [ProductController::class, 'updateProduct'])->name('updateProduct');
Route::get('/search-product', [ProductController::class, 'searchProduct'])->name('searchProduct');

Route::post('/category', [CategoryController::class, 'create'])->name('createcategory');
Route::get('/category', [CategoryController::class, 'getAllCategory'])->name('getAllCategory');
Route::get('/category/{id}', [CategoryController::class, 'getCategoryById'])->name('getCategoryById');
Route::delete('/category/{id}', [CategoryController::class, 'deleteCategory'])->name('deleteCategory');
Route::put('/update-category/{id}', [CategoryController::class, 'updateCategory'])->name('updateCategory');



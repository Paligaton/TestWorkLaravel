<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MainController;


Route::get('/', [MainController::class, 'index']);

Route::get('/check', [MainController::class, 'index']);


Route::get('/product', [MainController::class, 'product']);

Route::resource('products', 'MainController');
<?php

use App\Http\Controllers\POSController;
use Illuminate\Support\Facades\Route;

Route::get('/', [POSController::class, 'index'])->name('pos.index');
Route::post('/checkout', [POSController::class, 'checkout'])->name('pos.checkout');

<?php

use App\Http\Middleware\CheckTemporaryPasswordMiddleware;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::middleware([CheckTemporaryPasswordMiddleware::class])->group(function () {
    Route::get('/', function () {
        return view('welcome');
    })->name('welcome');
});

require __DIR__.'/auth.php';
require __DIR__.'/passport.php';

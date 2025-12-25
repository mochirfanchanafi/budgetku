<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Main\PageController;
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

Auth::routes();

Route::group(['middleware' => ['custom.token']], function() {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::get('/', [PageController::class, 'page'])->name('page');
    Route::get('/getmodal', [PageController::class, 'getmodal'])->name('getmodal');
});
<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();

//Routers Web
Route::get('/', [App\Http\Controllers\Web\HomeController::class, 'index'])->name('home');

//Routers App
Route::prefix('/app')->middleware(['auth', 'client'])->group(function(){
    Route::get('/dashboard', [App\Http\Controllers\Clients\AppDashboardController::class, 'index'])->name('app.dash');
});

//Routers Admin
Route::prefix('/admin')->middleware(['auth', 'admin'])->group(function(){
    Route::get('/dashboard', [App\Http\Controllers\Admin\AdminDashboardController::class, 'index'])->name('admin.dash');
});
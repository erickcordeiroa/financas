<?php

use App\Http\Controllers\Clients\AppController;
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
    //Filter invoices
    Route::any('/invoice/search', [\App\Http\Controllers\Clients\AppController::class, 'search'])->name('app.search');

    Route::get('/dashboard', [App\Http\Controllers\Clients\AppDashboardController::class, 'index'])->name('app.dash');
    Route::post('/launch', [App\Http\Controllers\Clients\AppController::class, 'launch'])->name('app.launch');
    Route::get('/income', [App\Http\Controllers\Clients\AppController::class, 'income'])->name('app.income');
    Route::get('/expense', [App\Http\Controllers\Clients\AppController::class, 'expense'])->name('app.expense');
    Route::get('/fixed', [App\Http\Controllers\Clients\AppController::class, 'fixed'])->name('app.fixed');

    //Update Invoice
    Route::get('/invoice/{id}', [App\Http\Controllers\Clients\AppController::class, 'invoice'])->name('app.invoice');
    Route::put('/invoice/{id}', [App\Http\Controllers\Clients\AppController::class, 'updateInvoice'])->name('app.update.invoice');

});

//Routers Admin
Route::prefix('/admin')->middleware(['auth', 'admin'])->group(function(){
    Route::get('/dashboard', [App\Http\Controllers\Admin\AdminDashboardController::class, 'index'])->name('admin.dash');
});
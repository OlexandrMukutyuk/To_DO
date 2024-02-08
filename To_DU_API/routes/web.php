<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;

Auth::routes();

Route::get('/', [HomeController::class, 'welcome'])->name('welcome');

Route::group(['middleware' => ['role:user|admin']], function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/create.task', [HomeController::class, 'createTask'])->name('create.task');
    Route::post('/store.task', [HomeController::class, 'storeTask'])->name('store.task');
    Route::put('/update.task/{id}', [HomeController::class, 'updateTask'])->name('update.task');
});


Route::group(['middleware' => ['role:admin']], function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin');
});

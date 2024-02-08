<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;

Route::get('/word', [UserController::class, 'index'])->name('word');

Route::post('/login', [UserController::class, 'login'])->name('api.login');
Route::get('/get.task', [UserController::class, 'getTask'])->name('api.get');
Route::post('/create.task', [UserController::class, 'storeTask'])->name('api.create');
Route::put('/update.task', [UserController::class, 'updateTask'])->name('update.task');

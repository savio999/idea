<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisteredUserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::middleware("guest")->group(function() {
    Route::get("/register", [RegisteredUserController::class, 'create'])->name('register');
    Route::post("/register", [RegisteredUserController::class, 'store'])->name('register.store');
    Route::get("/login", [LoginController::class, 'login'])->name('login');
    Route::post("/login", [LoginController::class, 'store'])->name('login.store');
});

Route::middleware("auth")->group(function(){
    Route::post("/logout", [LoginController::class, 'logout'])->name('logout');
});


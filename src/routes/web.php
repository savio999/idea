<?php

use App\Http\Controllers\IdeaController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisteredUserController;
use App\Http\Controllers\StepController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('ideas.index');
})->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store'])->name('register.store');
    Route::get('/login', [LoginController::class, 'login'])->name('login');
    Route::post('/login', [LoginController::class, 'store'])->name('login.store');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('/ideas', [IdeaController::class, 'index'])->name('ideas.index');
    Route::get('/idea/show/{idea}', [IdeaController::class, 'show'])->name('ideas.show');
    Route::get('/idea/edit/{idea}', [IdeaController::class, 'edit'])->name('ideas.edit');
    Route::delete('/idea/{idea}', [IdeaController::class, 'destroy'])->name('ideas.destroy');
    Route::post('/ideas', [IdeaController::class, 'store'])->name('ideas.store');
    Route::patch('/ideas/{idea}/steps/{step}',[StepController::class,'update'])->name('step.update');
});

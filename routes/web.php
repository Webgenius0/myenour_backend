<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\Auth\LoginController;
use App\Http\Controllers\ResetController;

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/',function (){
//     if (Auth::check()){
//         return redirect()->route('dashboard');
//     }else{
//         return redirect()->route('login');
//     }
// });
Route::get('/reset', [ResetController::class, 'RunMigrations'])->name('reset');

Route::get('/login', [LoginController::class, 'create'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
Route::get('/dashboard', function () {
    return view('backend.partials.dashboard');
})->name('dashboard');



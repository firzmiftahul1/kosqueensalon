<?php

use Illuminate\Support\Facades\Route;
use App\Mail\Tesmail;
use Illuminate\Support\Facades\Mail;

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/tes-email', function () {
    Mail::to('test@example.com')->send(new Tesmail());
    return 'Email berhasil dikirim!';
});
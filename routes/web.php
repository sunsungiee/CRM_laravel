<?php

use App\Http\Controllers\ContactController;
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

Route::get('/', function () {
    // здесь будет страница "рабочий стол"
    return view('welcome');
});

Route::get('/contacts', [ContactController::class, 'index'])->name("contact.main");

Route::post('/contacts', [ContactController::class, 'store'])->name("contact.store");

Route::get('/contacts/{contact}/edit', [ContactController::class, 'edit'])->name("contact.edit");

Route::patch('/contacts/{contact}', [ContactController::class, 'update'])->name("contact.update");



Route::get('/analytics', function () {
    // здесь будет страница "аналитика"
    return view('welcome');
});

Route::get('/tasks', function () {
    // здесь будет страница "текущие задачи"
    return view('welcome');
});

Route::get('/archive', function () {
    // здесь будет страница "архив"
    return view('welcome');
});

Route::get('/deals', function () {
    // здесь будет страница "сделки"
    return view('welcome');
})->name("deal.main");

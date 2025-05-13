<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\TaskController;
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

// авторизация
Route::get("/login", [AuthController::class, "showLoginForm"])->name("showLoginForm")->middleware('guest');
Route::post("/login", [AuthController::class, "login"])->name("login");
// регистрация
Route::get("/register", [AuthController::class, "showRegisterForm"])->name("showRegisterForm")->middleware('guest');
Route::post("/register", [AuthController::class, "register"])->name("register");
// выход
Route::post("/logout", [AuthController::class, "logout"])->name("logout");
// страница задачи
Route::get("/tasks", [TaskController::class, "index"])->name("task.main")->middleware('auth');
Route::post("/tasks", [TaskController::class, "store"])->name("task.store")->middleware('auth');
Route::get("/tasks/{task}/edit", [TaskController::class, "edit"])->name("task.edit")->middleware('auth');
Route::patch("/tasks/{task}", [TaskController::class, "update"])->name("task.update")->middleware('auth');
// удаление задачи
Route::delete("/tasks/{task}", [TaskController::class, "destroy"])->name("task.delete")->middleware('auth');

Route::get('/run_task', [TaskController::class, 'runScheduler'])->name('run_task');
Route::get("/taks/archive", [TaskController::class, "showArchive"])->name("task.archive")->middleware("auth");


// страница контакты
Route::get('/contacts', [ContactController::class, 'index'])->name("contact.main")->middleware('auth');
Route::post('/contacts', [ContactController::class, 'store'])->name("contact.store")->middleware('auth');
Route::get('/contacts/{contact}/edit', [ContactController::class, 'edit'])->name("contact.edit")->middleware('auth');
Route::patch('/contacts/{contact}', [ContactController::class, 'update'])->name("contact.update")->middleware('auth');
Route::delete("/contacts/{contact}", [ContactController::class, "destroy"])->name("contact.delete")->middleware('auth');

Route::get('/analytics', function () {
    // здесь будет страница "аналитика"
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

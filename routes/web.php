<?php

use App\Http\Controllers\RegisterController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TodoController;
use App\Http\Controllers\ClientController;


// **************************** КЛИЕНТСКАЯ (пользовательская) ЧАСТЬ********************************************

Route::get('/', [ClientController::class, 'home'])->name('home');  // МАРШРУТ ГЛАВНОЙ СТРАНИЦЫ БРОНИРОВАНИЯ

Route::get('/client', [ClientController::class, 'client'])->name('client_main');  // МАРШРУТ ГЛАВНОЙ СТРАНИЦЫ БРОНИРОВАНИЯ (алтернативный)

Route::get('/hall', [ClientController::class, 'halls'])->name('client_hall');   // МАРШРУТ ВЫБОРА СЕАНСА (конкретные дата, время и зал)

Route::get('/payment', [ClientController::class, 'payment'])->name('client_payment');   // МАРШРУТ ОПЛАТЫ БИЛЕТОВ (БРОНИРОВАНИЕ)

Route::get('/ticket',  [ClientController::class, 'ticket'])->name('client_ticket');

Route::get('/btnDatePush/{sessions_date}/start_element/{start_element}', [ClientController::class, 'btnDatePush'])->name('btnDatePush');

Route::get('/btnTimePush/{film_start}/film/{film_name}/hall/{hall_name}/date/{film_date}/tickets/{tickets_table}', [ClientController::class, 'btnTimePush'])->name('btnTimePush');

Route::get('/chooseTickets', [ClientController::class, 'chooseTickets'])->name('chooseTickets');  // МАРШРУТ ВЫБОРА МЕСТ В ЗАЛЕ

Route::post('/getTicketCode', [ClientController::class, 'getTicketCode'])->name('getTicketCode');   // МАРШРУТ ПОЛУЧЕНИЯ QR-кода



// ****************************** РЕГИСТРАЦИЯ АДМИНА *********************************************************

Route::name('user.')->group(function () {
   
    Route::get('/admin_main', [TodoController::class, 'showAdminMain'])->middleware('auth')->name('private');

    Route::get('/auth/app_login', [\App\Http\Controllers\LoginController::class, 'loginGet'])->name('login');

    Route::post('/auth/app_login', [\App\Http\Controllers\LoginController::class, 'login']);  

    Route::post('/auth/register/changePass/{email}', [RegisterController::class, 'updatePassword'])->name('updatePassword');

    Route::get('/auth/logout', [\App\Http\Controllers\LoginController::class, 'logout'])->name('logout');

    Route::get('/auth/app_register', [RegisterController::class, 'registerGet'])->name('register');

    Route::post('/auth/app_register', [RegisterController::class, 'register']); 
});



// ********************************** АДМИНИСТРИРОВАНИЕ ЗАЛОВ и СЕТКИ СЕАНСОВ ********************************

Route::get('/admin', [TodoController::class, 'showAdminMain'])->name('admin_main')->middleware('auth'); // маршрут СТРАНИЦЫ АДМИНИСТРИРОВАНИЯ

Route::get('/login', [\App\Http\Controllers\LoginController::class, 'admin_login'])->name('admin_login');    // МАРШРУТ ВХОДА НА СТРАНИЦУ АДМИНИСТРИРОВАНИЯ

Route::get('/register', [RegisterController::class, 'adminRegister'])->name('admin_register'); // МАРШРУТ РЕГИСТРАЦИИ администратора

Route::post('/addHall', [TodoController::class, 'addHall'])->name('addHall');
Route::get('/delHall', [TodoController::class, 'delHall'])->name('delHall');

Route::post('/sizeHall', [TodoController::class, 'sizeHall'])->name('sizeHall');
Route::post('/planeHall', [TodoController::class, 'planeHall'])->name('planeHall');

Route::post('/billingHall', [TodoController::class, 'billingHall'])->name('billingHall');
Route::get('/btnPush/{hall_name}/section/{scroll_to_section}', [TodoController::class, 'btnPush'])->name('btnPush');

Route::post('/addFilm', [TodoController::class, 'addFilm'])->name('addFilm');
Route::get('/delFilm', [TodoController::class, 'delFilm'])->name('delFilm');

Route::post('/addSessionsPlan', [TodoController::class, 'addSessionsPlan'])->name('addSessionsPlan');
Route::get('/delSessionsPlan', [TodoController::class, 'delSessionsPlan'])->name('delSessionsPlan');

Route::post('/infoFilmSession', [TodoController::class, 'infoFilmSession'])->name('infoFilmSession');
Route::post('/changeFilmSession', [TodoController::class, 'changeFilmSession'])->name('changeFilmSession');

Route::get('/changeSaleStatus', [TodoController::class, 'changeSaleStatus'])->name('changeSaleStatus');


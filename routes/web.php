<?php

use App\Http\Controllers\RegisterController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TodoController;
use App\Http\Controllers\ClientController;
use App\Models\Film;
use App\Models\Hall;

Route::get('/', function () {
    //return 'welcome';
    return view('/ToDo/home');
})->name('home');




Route::get('/admin', function () {
    return view('/layouts/app_admin', ['dataHalls' => Hall::paginate(), 'dataFilms' => Film::paginate()]);
})->name('admin_main');


Route::get('/client', function () {
    $hall_blocked = false;     // маркер
    return view('/layouts/app_client', ['dataHalls' => Hall::paginate(), 'dataFilms' => Film::paginate(), 'hall_blocked' => $hall_blocked]);
})->name('client_main');

Route::get('/hall', function () {
    return view('/inc/app_hall', ['dataHalls' => Hall::paginate(), 'dataFilms' => Film::paginate()]);
})->name('client_hall');

Route::get('/payment', function () {
    return view('/inc/app_payment', ['dataHalls' => Hall::paginate(), 'dataFilms' => Film::paginate()]);
})->name('client_payment');

Route::get('/ticket', function () {
    return view('/inc/app_ticket', ['dataHalls' => Hall::paginate(), 'dataFilms' => Film::paginate()]);
})->name('client_ticket');



Route::get('/todo/list', [TodoController::class, 'show'])->name('list');

Route::get('/todo/create', function () {
    //return view('welcome');
    return view('/ToDo/create');
})->name('create');

Route::post('/todo/create/submit',[TodoController::class, 'add'])->name('submit');

Route::get('/todo/{id}', [TodoController::class, 'edit'])->name('edit');

Route::get('/todo/{id}/update', [TodoController::class, 'update'])->name('update');

Route::post('/todo/{id}/update', [TodoController::class, 'updateSubmit'])->name('updateSubmit');

Route::get('/todo/{id}/delete', [TodoController::class, 'delete'])->name('delete');


route::name('user.')->group(function () {
    route::view('/ToDo/private', '/ToDo/private')->middleware('auth')->name('private');

    Route::get('/auth/login', function () {
        if(Auth::check()) {
            return redirect(route('user.private'));
        }
        return view('/auth/login');
    })->name('login');

    route::post('/auth/login', [\App\Http\Controllers\LoginController::class, 'login']);

    route::post('/auth/register/changePass/{email}', [RegisterController::class, 'updatePassword'])->name('updatePassword');

    route::get('/auth/logout', function (Request $request){
        if(Auth::check()|| Auth::viaRemember()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect(route('home'));
        }
        return redirect(route('user.login'))->withErrors( // ..если попытка выхода провалилась - редирект и вывод сообщ. об ошибке
            'LogoutError!!!');
    })->name('logout');

    Route::get('/auth/register', function () {
        if(Auth::check()|| Auth::viaRemember()) {
            return redirect(route('user.private'));
        }
        return view('/auth/register');
    })->name('register');

    Route::post('/auth/register', [RegisterController::class, 'register']);
});

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

Route::get('/btnDatePush/{sessions_date}', [ClientController::class, 'btnDatePush'])->name('btnDatePush');
Route::get('/btnTimePush/{film_start}/film/{film_name}/hall/{hall_name}/date/{film_date}/tickets/{tickets_table}', [ClientController::class, 'btnTimePush'])->name('btnTimePush');

Route::get('/chooseTickets', function (Request $request) {

    $film_name = $request->input('film_name');
    $hall_name = $request->input('hall_name');
    $film_date = $request->input('film_date');
    $session_time = $request->input('session_time');
    $total_cost = $request->input('total_cost');
    $arr = json_decode($request->input('arr'));
    
    return view('/inc/app_payment', ['choose_array' => $arr, 'film_name' => $film_name, 'hall_name' => $hall_name, 'film_date' => $film_date, 'session_time' => $session_time, 'total_cost' => $total_cost]);
})->name('chooseTickets');

Route::post('/getTicketCode', [ClientController::class, 'getTicketCode'])->name('getTicketCode');
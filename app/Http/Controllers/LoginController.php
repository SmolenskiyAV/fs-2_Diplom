<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login(Request $request){                    // метод "Вход пользователя"
        if(Auth::check() || Auth::viaRemember()){               // проверка: если юзер зарегистрирован.. (в т.ч. через куки и токен)
           
            return redirect()->intended('/admin');  // ..то переход на страницу до редиректа, а если такой страницы нет - то на стр. admin
        }

        $formFields = $request->only(['email', 'password']);    // извлечение из запроса только двух параметров

        if(Auth::attempt($formFields, $request->get('remember')=='remember-me')){ // залогинивание и проверка: если попытка аутентификации успешна.. Вторым параметром передаём чекбокс "Запомнить меня"
           
            $request->session()->regenerate();                     // регенерация новой сессии для аутентифицированного пользователя
           
            return redirect()->intended('/admin');  // ..то переход на страницу до редиректа, а если такой страницы нет - то на стр. admin
        }
        return redirect(route('user.login'))->withErrors([ // ..если попытка аутентификации провалилась - редирект и вывод сообщ. об ошибке
            'email' => 'Не удалось аутентифицироваться!'
        ]);
    }
}

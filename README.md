# Дипломный проект по профессии «Веб-разработчик»

Дипломный проект представляет собой создание сайта для бронирования онлайн билетов в кинотеатр и разработка информационной системы для администрирования залов, сеансов и предварительного бронирования билетов.

### Студенту даются компоненты системы - вёрстка (основные страницы, модальные окна и стили для них).

## Задачи

* Разработать сайт бронирования билетов онлайн.
* Разработать административную часть сайта.
* Регистрация из административной части сайта не является обязательной. Разрешается добавить эту функциональность по своему усмотрению или просто заносить в базу данных пользователей вручную при помощи миграций.

## Сущности

1. **Кинозал**. Помещение, в котором демонстрируются фильмы. Режим работы определяется расписанием на день. Зал — прямоугольное помещение, состоит из N х M различных зрительских мест.
2. **Зрительское место**. Место в кинозале. Есть два вида: VIP и обычное.
3. **Фильм**. Информация о фильме заполняется администратором. Фильм связан с сеансом в кинозале.
4. **Сеанс**. Временной промежуток, во время которого в кинозале будет показываться фильм. На сеанс могут быть забронированы билеты.
5. **Билет**. QR-код c уникальным кодом бронирования, в котором обязательно указаны место, ряд, сеанс. Билет действителен строго на свой сеанс. Для генерации QR-кода использовать один из популярных сервисов.

## Роли пользователей системы

* Администратор — авторизованный пользователь.
* Гость — неавторизованный посетитель сайта.

### Возможности администратора

* Создание или редактирование залов.
* Создание или редактирование списка фильмов.
* Настройка цен.
* Создание или редактирование расписания сеансов фильмов.

### Возможности гостя

* Просмотр расписания.
* Просмотр списка фильмов.
* Выбор места в кинозале.
* Бронирование билета.

## Этапы разработки

1. Продумывание архитектуры будущего веб-приложения. Вы можете базироваться на основе фреймворков (Laravel, Yii2), использовать свободные библиотеки для сборки собственного приложения либо написать всё самостоятельно. Проанализируйте задание, составьте план. Когда определитесь, что и как хотите делать, обсудите с дипломным руководителем.
2. Программирование административной и гостевой частей.

### Что в итоге должно получиться

В результате работы должен получиться git-репозиторий, содержащий в себе необходимые файлы проекта и файл ReadMe. В нём должна быть инструкция, как запустить ваш проект, технические особенности: версия php, процедура миграции базы данных и другое.

---
---
&nbsp;
# **Описание проекта**
## Исполнитель: Смоленский А.В.

&nbsp;
### Рабочее окружение и компоненты проекта:
- ОС Ubuntu 20.04
- Laravel Framework 9.51.0
- PHP 8.2.7 (cli) (built: Jun  8 2023 15:27:12) (NTS)
- браузеры, на которых тестировался проект:
  - Firefox 114.0.2 (64-bit)
  - Chromium Версия 114.0.5735.106 (Официальная сборка), snap (64 бит)
- Composer version 2.5.1 2022-12-22 15:33:54
- БД Sqlite 3.31.1 (один файл "database.sqlite")
- npm ver.9.5.0


## Запуск проекта:
- После git-клонирования (`git clone https://github.com/SmolenskiyAV/fs-2_Diplom.git`), открыть папку проекта в IDE (у меня это Visual Studio Code ver.1.79.2).  
- В терминале последовательно запустить:  
  - `npm -install`. После завершения установки всех пактов, проконтролировать запуск Vite: должно появиться сообщение, типа `vite started at http://localhost:4000`.
  - `php artisan key:generate`
  - `php artisan migrate` (ответить `yes` на запрос создания базы).
- Запустить локальный сервер `php artisan serve`
- Открыть браузер вызвать в адресной строке: `http://127.0.0.1:8000/` (страница бронирования<она будет пустая, т.к. сетка сеансов по дефолту не создана>).
- Для входа на страницу конфигурирования сетки сеансов вызвать в адресной строке браузера: `http://127.0.0.1:8000/login`.

## Главная страница бронирования билетов

Основной шаблон(страница) выбора доступных билетов *'resources/views/layouts/app_client.blade.php'*.  
Ссылка(на локальном сервере в среде разработки): ***http://127.0.0.1:8000/***  
Сразу после запуска страница бронирования будет пустая, т.к. сетка сеансов по дефолту не создана и запланированных сеансов нет:

![первоначальная страница бронирования билетов](/img_descriptions/client_begin.jpg)

После создания в БД сеансов фильмов, в верхней части экрана появятся плиткт дат - дней, на которые запланированы фильмы:

![главная страница бронирования билетов](/img_descriptions/client_today.jpg)

Левая плитка отражает сегодняшнее число по Москве и при нажатии на неё страница переключается на отображение сеансов, которые запланированы на сегодня. Если на сегодня сеансов нет - страница переключится на отображение самого раннего дня в сетке сеансов.  
Все плитки дат в верхнем меню располагаются по возрастанию слева-направо и максимально отображаемое их количестов равно пяти. Если количество дат более пяти - в правой верхней части появляется плитка-стрелка, при нажатии на котороую страница переключается на отображение следующей пятёрки запланированных дат (и так далее, пока не будет достигнут предел списка дат):

![последующая страница бронирования билетов](/img_descriptions/client_add_navs.jpg)

При нажатии на элемент "Идём в кино" - происходит возврат на домашнюю страницу к началу списка дат.  
Кнопки сеансов, с временем начала также располагаются в порядке возрастания слева-направо и при нажатии на одну из них происходит переход к странице выбора мест, далее бронирования билетоа, далее - оплаты и в конце процедуры - получение QR-кода, который будет отображён на странице.  

Вся логика выбора/бронирования билетов обрабатывается в контроллере ***'app/Http/Controllers/ClientController.php'***.  
Все <span style="color: magenta">png-файлы QRcode-ов</span>, при бронировании сохраняются в <span style="color: magenta">'public/storage/images/client/QRcodes'</span>. 

## Страница администрирования залов

Шаблон(страница) конфигурирования *'resources/views/layouts/app_admin.blade.php'*.  
Ссылка(на локальном сервере в среде разработки): ***http://127.0.0.1:8000/admin*** &ensp;(защищённый маршрут).
Конфигурирование залов/сеансов доступно только для авторизованных пользователей(администраторов).
Доступ к странице конфигурирования через вход/регистрацию.
Ссылка(на локальном сервере в среде разработки): ***http://127.0.0.1:8000/login***

![вход на страницу администрирования](/img_descriptions/admin_login.jpg)

В качестве логина должен использоваться эл.адрес.  
После успешной авторизации открывается страница администрирования залов и сетки сеансов:

![вход на страницу администрирования](/img_descriptions/admin_cfg.jpg)

Вся логика конфигурирования залов/сеансов обрабатывается в контроллере ***'app/Http/Controllers/TodoController.php'***. 

Шаблон(страница) конфигурирования ***'resources/views/layouts/app_admin.blade.php'***.

## Структура Базы Данных

В БД проекта установлены три основные таблицы:
  - 'halls'-список всех созданных залов
  - 'films'-список всех созданных фильмов
  - 'users'-список администраторов, которым разрешено редактировать залы и сетку сеансов

Остальные элементы БД привязаны к этим трём основным таблицам.

![структура базы данных](/img_descriptions/db_structure.jpg)
---
&nbsp;
Таблица 'users' представлена на схеме упрощённо, не стоит воспринимать её буквально..

При этом БД проекта намеренно осложнена. В качестве зависимых элементов во вложенной структуре активно используются отдельно создаваемые динамические, зависимые от родительских элементов, таблицы (для таблицы 'users' такие зависимости не строятся). Данный вариант автор проекта считает не оптимальным, существенно осложняющим работу запросов к БД. Но это сделано для того, чтобы в учебных целях протестировать возможность манипуляции данными из множества отдельных таблиц в БД (а также, логически разделить компоненты структуры в виде отдельных целостных элементов).

В процессе конфигурирования залов, формируются зависимые от каждого зала записи в таблице *'halls_billing'* - цены на билеты в каждом зале. Также, при создании новой записи в 'halls' создаётся динамическая зависимая таблица *'имязала_plane'*, в которой хранится конфигурация зала, определяемая администратором (таким образом план каждого зала представляет собой один логический элемент, не смешанный с подобными элементами в некоей общей таблице). При удалении зала, эта таблица, как зависимый элемент, удаляется вместе с ним.

Сетка сеансов фильмов формируется следующим образом:&ensp;сначала на определённую дату(день месяца) создаётся "СУТОЧНЫй ПЛАН" (каждый такой план<отдельная таблица> привязан к конкретному залу), внутри котрого создаются сеансы фильмов. При этом каждый сеанс фильма<отдельная таблица> привязан к конкретному фильму и к конкретному плану зала, в котором он будет проходить.

Формат имени таблицы, содержащей информацию о конкретном суточном плане:&ensp;**'имя зала * дата'**.  
При удалени записи о зале в таблице 'halls' - также удаляются все связанные с данным залом таблицы суточных планов.

Формат имени таблицы, содержащей информацию о конкретном сеансе:&ensp;**'имя зала * дата время_tickets'**.  
Каждый сеанс фильма<отдельная таблица> привязан к суточному плану, в рамках которого этот сеанс запланирован.  
При удалении суточного плана все сеансы, связанные с ним, также удаляются.   
При удалении записи о фильме в таблице *'films'* - удаляются все таблицы сеансов, связанные с данным фильмом.

Кликом по суточному плану на странице конфигурирования, <span style="color: green">отображается план зала, в котором запланирован сеанс, с указанием проданных и оставшихся свободными местами.</span>  

<span style="color: magenta">Постеры фильмов</span>, при создании записи в таблице *'films'* сохраняются в <span style="color: magenta">'public/storage/images/films'</span>.  
При удалении записи о фильме в твблице *'films'*, относящий ся к этому фильму постер также удаляется.
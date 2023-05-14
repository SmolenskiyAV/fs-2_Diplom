    <header>
        <div class="d-flex flex-column flex-md-row align-items-center pb-3 mb-4 border-bottom">
            <a href="/" class="d-flex align-items-center text-dark text-decoration-none">
                <svg width="55" height="55" viewBox="0 0 500 500" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M247.093 127.907C312.917 127.907 366.279 181.268 366.279 247.093C366.279 312.917 312.917 366.279 247.093 366.279C181.268 366.279 127.907 312.917 127.907 247.093C127.907 181.268 181.268 127.907 247.093 127.907Z" fill="white"/>
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M128.832 262.013C125.65 261.758 122.433 261.628 119.186 261.628C53.3614 261.628 6.53952e-06 314.989 0 380.814C-6.53952e-06 446.639 53.3614 500 119.186 500C185.011 500 238.372 446.639 238.372 380.814C238.372 375.761 238.058 370.782 237.447 365.894C181.084 361.38 135.78 317.655 128.832 262.013Z" fill="#5D00F5"/>
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M238.372 119.186C238.372 53.3614 185.011 6.53952e-06 119.186 0C53.3614 -6.53952e-06 6.53952e-06 53.3614 0 119.186C-6.53952e-06 185.011 53.3614 238.372 119.186 238.372C122.234 238.372 125.256 238.258 128.246 238.033C132.651 179.431 179.431 132.651 238.033 128.246C238.258 125.256 238.372 122.234 238.372 119.186Z" fill="#EB236B"/>
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M365.894 237.447C370.782 238.058 375.761 238.372 380.814 238.372C446.639 238.372 500 185.011 500 119.186C500 53.3614 446.639 1.22941e-05 380.814 0C314.989 -1.22941e-05 261.628 53.3614 261.628 119.186C261.628 122.433 261.758 125.65 262.013 128.832C317.655 135.78 361.381 181.084 365.894 237.447Z" fill="#4BD0A0"/>
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M262.632 365.275C261.969 370.361 261.628 375.548 261.628 380.814C261.628 446.639 314.989 500 380.814 500C446.639 500 500 446.639 500 380.814C500 314.989 446.639 261.628 380.814 261.628C375.548 261.628 370.361 261.969 365.275 262.632C358.323 316.029 316.029 358.323 262.632 365.275Z" fill="#0066FF"/>
                </svg>
                <title>Bootstrap</title><path fill-rule="evenodd" clip-rule="evenodd" d="M24.509 0c-6.733 0-11.715 5.893-11.492 12.284.214 6.14-.064 14.092-2.066 20.577C8.943 39.365 5.547 43.485 0 44.014v5.972c5.547.529 8.943 4.649 10.951 11.153 2.002 6.485 2.28 14.437 2.066 20.577C12.794 88.106 17.776 94 24.51 94H93.5c6.733 0 11.714-5.893 11.491-12.284-.214-6.14.064-14.092 2.066-20.577 2.009-6.504 5.396-10.624 10.943-11.153v-5.972c-5.547-.529-8.934-4.649-10.943-11.153-2.002-6.484-2.28-14.437-2.066-20.577C105.214 5.894 100.233 0 93.5 0H24.508zM80 57.863C80 66.663 73.436 72 62.543 72H44a2 2 0 01-2-2V24a2 2 0 012-2h18.437c9.083 0 15.044 4.92 15.044 12.474 0 5.302-4.01 10.049-9.119 10.88v.277C75.317 46.394 80 51.21 80 57.863zM60.521 28.34H49.948v14.934h8.905c6.884 0 10.68-2.772 10.68-7.727 0-4.643-3.264-7.207-9.012-7.207zM49.948 49.2v16.458H60.91c7.167 0 10.964-2.876 10.964-8.281 0-5.406-3.903-8.178-11.425-8.178H49.948z" fill="currentColor"></path></svg>
                <div style="box-sizing: border-box; margin-left: 5px">
                    <div>
                        <span class="fs-4" style="font-size: small">Netology Homework</span>
                    </div>
                    <div>
                        <span class="fs-4" style="font-size: small">LRV-18_3.Middleware</span>
                    </div>
                </div>
            </a>

            <nav class="d-inline-flex mt-2 mt-md-0 ms-md-auto">
                <a class="me-3 py-2 text-dark text-decoration-none hover:bg-gray-100" href="{{route('home')}}">Главная</a>
                <a class="me-3 py-2 text-dark text-decoration-none hover:bg-gray-100" href="{{route('list')}}">Список задач</a>
                <a class="me-3 py-2 text-dark text-decoration-none hover:bg-gray-100" href="{{route('create')}}">Создать задачу</a>
                @guest()
                    <a class="me-3 py-2 text-dark text-decoration-none hover:bg-gray-100" href="{{route('user.login')}}">LogIn</a>
                @endguest
                @auth()
                    <a class="me-3 py-2 text-dark text-decoration-none hover:bg-gray-100" href="{{route('user.private')}}">Личный кабинет</a>
                    <a class="me-3 py-2 text-dark text-decoration-none hover:bg-gray-100" href="{{route('user.logout')}}">LogOut</a>
                @endauth
            </nav>
    </div>
        <div class="pricing-header p-3 pb-md-4 mx-auto text-center">
            <h1 class="display-4 fw-normal">Редактор задач</h1>
            <p class="fs-5 text-muted">Quickly build an effective pricing table for your potential customers with this Bootstrap example. It’s built with default Bootstrap components and utilities with little customization.</p>
        </div>
    </header>

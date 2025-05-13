<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>CRM</title>

    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/css/burger.css', 'resources/js/burger.js'])

    <link rel="icon" href="{{ asset('favicon2.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Oswald:wght@200..700&display=swap"
        rel="stylesheet">
</head>

<body>
    <main>
        <nav class="side_bar_menu" id="side_bar_menu">
            <div class="side_bar_menu_content" id="side_bar_menu_content">
                <div class="side_bar_menu_top">
                    <div class="logo">
                        <h2 style="width: fit-content;"><a href="analytics.php" style="color: black;">CRM</a> </h2>
                    </div>
                    <ul>
                        <li><a href="analytics.php">Аналитика</a></li>
                        <li class="tasks" id="task_button"><a href="#">Задачи</a>
                            <div class="tasks_menu" id="tasks_menu">
                                <ul>
                                    <li><a href="{{ route('task.main') }}">Текущие задачи</a></li>
                                    <li><a href="{{ route('task.archive') }}">Архив задач</a></li>
                                </ul>
                            </div>
                        </li>

                        <li><a href="{{ route('deal.main') }}">Сделки</a></li>
                        <li><a href="{{ route('contact.main') }}">Контакты</a></li>
                    </ul>
                </div>
                <ul>
                    <li>
                        <a href="#">
                            {{ $user->name }}
                        </a>
                        <a style="display: block;">
                            <span style="color: gray; font-size: 12px;">
                                {{ $user->post }}
                            </span>
                        </a>
                    </li>
                    <li>
                        <form action="{{ route('logout') }}" method="post" class="logout">
                            @csrf
                            {{-- <img src="#" alt="" class="logout_icon"> --}}
                            <button type="submit">Выйти</button>
                        </form>
                    </li>
                </ul>
            </div>
        </nav>

        <div class="burger_nav" id="burger_nav">
            <div class="burger_menu_content" id="burger_menu_content">

                <div class="burger_menu_top">
                    <div class="burger_header">
                        <h2 style="width: fit-content;"><a href="analytics.php" style="color: black;">CRM</a> </h2>
                        <a href="#" id="burger_close" class="burger_btn">
                            <img src="../../assets/img/icons/close.svg" alt="Закрыть">
                        </a>
                    </div>
                    <ul class="burger_header_links">
                        <hr>

                        <a href="analytics.php">
                            <li>Аналитика</li>
                        </a>
                        <hr>
                        <a href="#">
                            <li class="tasks" id="burger_task_button">Задачи
                                <div class="tasks_menu" id="burger_tasks_menu">
                                    <ul>
                                        <hr>
                                        <a href="current_tasks.php">
                                            <li>Текущие задачи</li>
                                        </a>
                                        <hr>

                                        <a href="{{ route('task.archive') }}">
                                            <li>Архив задач</li>
                                        </a>
                                        <hr>

                                    </ul>
                                </div>
                            </li>
                        </a>
                        <hr>

                        <li><a href="deals.php">Сделки</a></li>
                        <hr>

                        <li><a href="contacts.php">Контакты</a></li>
                        <hr>
                    </ul>
                </div>
                <ul>
                    <hr>

                    <li>
                        <a href="#">
                            {{ $user->name }}
                        </a>
                        <a style="display: block;">
                            <span style="color: gray; font-size: 12px;">
                                {{ $user->post }}
                            </span>
                        </a>
                    </li>
                    <hr>
                    <form action="{{ route('logout') }}" method="post" class="logout">
                        <img src="#" alt="" class="logout_icon">
                        @csrf
                        <button type="submit">Выйти</button>
                    </form>
                    </li>
                    <hr>

                </ul>
            </div>
        </div>

        @yield('content')
        <script>
            // Запуск команды для истекших задач
            setInterval(() => {
                fetch("{{ route('run_task') }}")
                    .then(response => response.json())
                    .then(data => console.log("Команда выполнена:", data.output))
                    .catch(err => console.error("Ошибка:", err));
            }, 60 * 1000); // Каждые 60 секунд
        </script>
</body>

</html>

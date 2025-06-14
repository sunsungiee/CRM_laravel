<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRM</title>

    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/css/burger.css', 'resources/js/burger.js'])

    <link rel="icon" href="{{ asset('favicon.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Oswald:wght@200..700&display=swap"
        rel="stylesheet">

    {{-- <!-- Подключение CSS -->
    <link rel="stylesheet" href="/build/assets/app-7a763e71.css">

    <!-- Подключение Burger CSS -->
    <link rel="stylesheet" href="/build/assets/burger-14019694.css"> --}}

    <!-- Подключение CSS -->
    {{-- {!! vite_build('resources/css/app.css') !!}
    {!! vite_build('resources/css/burger.css') !!} --}}

    <!-- Подключение JS -->
</head>

<body>
    @yield('content')

    {{-- {!! vite_build('resources/js/app.js') !!}
    {!! vite_build('resources/js/burger.js') !!} --}}

</body>

</html>

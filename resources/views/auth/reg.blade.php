@extends('layouts.auth')

@section('content')
    <div class="container">
        <form action="{{ route('register') }}" method="post" class="auth_form">
            @csrf
            <h1 class="form_h">Регистрация</h1>
            @if ($errors->any())
                <div>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <input type="text" name="surname" placeholder="Фамилия">
            <input type="text" name="name" placeholder="Имя">
            <input type="text" name="post" placeholder="Должность">
            <input type="text" name="phone" id="phone" placeholder="Телефон">
            <input type="email" name="email" placeholder="Эл. почта">
            {{-- <input type="text" name="login" placeholder="Логин пользователя"> --}}
            <input type="password" name="password" placeholder="Пароль">

            <p>Или <a href="{{ route('showLoginForm') }}">авторизуйтесь</a>, если вы уже с нами!</p>
            <button type="submit">Зарегистрироваться</button>
        </form>
    </div>
@endsection

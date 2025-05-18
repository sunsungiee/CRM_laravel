@extends('layouts.auth')

@section('content')
    <div class="container">
        <form action="{{ route('login') }}" method="post" class="auth_form">
            @csrf
            <h1 class="form_h">Авторизация</h1>

            @if ($errors->any())
                <div>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <input type="email" name="email" placeholder="Эл. почта" required autofocus>
            <input type="password" name="password" placeholder="Пароль" required>
            {{-- <div class="remember_me">
                <input type="checkbox" id="remember" name="remember">
                <label for="remember">
                    Запомнить меня
                </label>
            </div> --}}
            <p><a href="{{ route('showRegisterForm') }}">Зарегистрируйтесь</a>, чтобы стать частью
                команды</p>

            <button type="submit" style="font-weight:500">Войти</button>
        </form>
    </div>
@endsection

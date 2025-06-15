@extends('layouts.auth')

@section('content')
    <div class="container">
        <form action="{{ route('login') }}" method="post" class="auth_form">
            @csrf
            <h1 class="form_h">Авторизация</h1>
            <input type="email" name="email" value="{{ old('email') }}" placeholder="Эл. почта" required autofocus>
            <input type="password" name="password" placeholder="Пароль" required>
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <p><a href="{{ route('showRegisterForm') }}">Зарегистрируйтесь</a>, чтобы стать частью
                команды</p>

            <button type="submit" style="font-weight:500">Войти</button>
        </form>
    </div>
@endsection

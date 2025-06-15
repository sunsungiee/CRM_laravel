@extends('layouts.auth')

@section('content')
    <div class="container">
        <form action="{{ route('register') }}" method="post" class="auth_form">
            @csrf
            <h1 class="form_h">Регистрация</h1>

            <input type="text" name="surname" value="{{ old('surname') }}" placeholder="Фамилия" required>
            <input type="text" name="name" value="{{ old('name') }}" placeholder="Имя" required>
            <input type="text" name="post" value="{{ old('post') }}" placeholder="Должность" required>
            <input type="text" name="phone" value="{{ old('phone') }}" id="phone" placeholder="Телефон" required>
            <input type="email" name="email" value="{{ old('email') }}" placeholder="Эл. почта" required>
            <input type="password" name="password" minlength="6" placeholder="Пароль" required>
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <p>Или <a href="{{ route('showLoginForm') }}"> авторизуйтесь </a>, если вы уже с нами!</p>



            <button type="submit">Зарегистрироваться</button>
        </form>
    </div>
@endsection

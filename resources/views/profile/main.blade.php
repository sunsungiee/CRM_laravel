@extends('layouts.header')

@section('content')
    <div class="content tasks">
        <div class="header tasks">
            <h1>Личный профиль</h1>
            <div class="header_btns">
                <button class="add_task" id="enableInputs">Изменить информацию</button>
                <a href="#" id="burger_open" class="burger_btn">
                    <img src="{{ asset('images/icons/burger.svg') }}" alt="Меню">
                </a>
            </div>
        </div>

        <hr>

        <form action="{{ route('profile.update', $user->id) }}" method="post" class="self_profile">
            @csrf
            @method('patch')

            <fieldset class="profile_fieldset">
                <legend style="padding-left: 5px">Фамилия</legend>
                <input type="text" class="form-input" name="surname" value="{{ $user->surname }}" placeholder="Фамилия"
                    disabled required>
            </fieldset>

            <fieldset class="profile_fieldset">
                <legend style="padding-left: 5px">Имя</legend>
                <input type="text" class="form-input" name="name" disabled value="{{ $user->name }}"
                    placeholder="Имя" required>
            </fieldset>

            <fieldset class="profile_fieldset">
                <legend style="padding-left: 5px">Должность</legend>

                <input type="text" class="form-input" name="post" disabled value="{{ $user->post }}"
                    placeholder="Должность" required>
            </fieldset>

            <fieldset class="profile_fieldset">
                <legend style="padding-left: 5px">Телефон</legend>

                <input type="text" class="form-input" name="phone" disabled value="{{ $user->phone }}" id="phone"
                    placeholder="Телефон" required>
            </fieldset>

            <fieldset class="profile_fieldset">
                <legend style="padding-left: 5px">Эл. почта</legend>

                <input type="email" class="form-input" name="email" disabled value="{{ $user->email }}"
                    placeholder="Эл. почта" required>
            </fieldset>

            <fieldset class="profile_fieldset">
                <legend style="padding-left: 5px">Новый пароль</legend>

                <input type="password" class="form-input" name="password" value="{{ $user->password }}" disabled minlength="6"
                    placeholder="*************" id="passwordInput"><span class="toggle-password">👁</span>
            </fieldset>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <button id="profile_btn">Сохранить</button>
        </form>
    </div>
@endsection

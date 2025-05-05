@extends('layots.header')

@section('content')
    <div class="content tasks">
        <div class="header tasks">
            <h1>Список контактов</h1>
            <div class="header_btns">
                <button class="add_task" id="add_task">Добавить</button>
                <a href="#" id="burger_open" class="burger_btn">
                    <img src="{{ asset('images/icons/burger.svg') }}" alt="Меню">
                </a>
            </div>
        </div>

        <hr>

        <form method="GET" action="{{ route('contact.main') }}" class="search_form">
            <div class="search_container">
                <input type="text" tabindex="1" name="search" placeholder="Поиск..." id="searchInput"
                    value="{{ request('search') }}">
                <button type="submit" style="display: none;">crhsnfz</button>
                <button class="reset_search" type="submit" onclick="document.getElementById('searchInput').value='';">
                    X
                </button>
            </div>
            <button class="search_btn" type="submit">
                <img src="{{ asset('images/icons/search.png') }}" alt="Искать">
            </button>
        </form>



        <table class="tasks_table" id="contacts_table">
            <thead>
                <tr>
                    <th data-column="surname">
                        <a class="sort_btn"
                            href="{{ route('contact.main', ['sort' => 'surname', 'direction' => $direction === 'asc' ? 'desc' : 'asc']) }}">Фамилия
                            @if ($sort === 'surname')
                                {{ $direction === 'asc' ? '↑' : '↓' }}
                            @endif
                        </a>
                    </th>
                    <th data-column="name">
                        <a class="sort_btn"
                            href="{{ route('contact.main', ['sort' => 'name', 'direction' => $direction === 'asc' ? 'desc' : 'asc']) }}">
                            Имя
                            @if ($sort === 'name')
                                {{ $direction === 'asc' ? '↑' : '↓' }}
                            @endif
                        </a>
                    </th>
                    <th data-column="phone">
                        <a class="sort_btn"
                            href="{{ route('contact.main', ['sort' => 'phone', 'direction' => $direction === 'asc' ? 'desc' : 'asc']) }}">
                            Номер телефона
                            @if ($sort === 'phone')
                                {{ $direction === 'asc' ? '↑' : '↓' }}
                            @endif
                        </a>
                    </th>
                    <th data-column="email">
                        <a class="sort_btn"
                            href="{{ route('contact.main', ['sort' => 'email', 'direction' => $direction === 'asc' ? 'desc' : 'asc']) }}">
                            Эл. почта
                            @if ($sort === 'email')
                                {{ $direction === 'asc' ? '↑' : '↓' }}
                            @endif
                        </a>
                    </th>
                    <th data-column="firm">
                        <a href="{{ route('contact.main', ['sort' => 'firm', 'direction' => $direction === 'asc' ? 'desc' : 'asc']) }}"
                            class="sort_btn">
                            Организация
                            @if ($sort === 'firm')
                                {{ $direction === 'asc' ? '↑' : '↓' }}
                            @endif
                        </a>
                    </th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($contacts as $contact)
                    <tr>
                        <td id="editSurname"> {{ $contact->surname }} </td>
                        <td>{{ $contact->name }} </td>
                        <td> {{ $contact->email }} </td>
                        <td> {{ $contact->phone }} </td>
                        <td>{{ $contact->firm }}</td>
                        <td class="actions">
                            {{-- кнопка "изменить" --}}
                            <button class="edit-btn" id="open_update_modal" title="Редактировать" type="button"
                                data-id="{{ $contact->id }}">
                                <img src="{{ asset('images/icons/edit.png') }}" alt="Изменить">
                            </button>
                        </td>
                        <td class="actions">
                            {{-- кнопка удалить --}}
                            <form action="" method="post">
                                <button class="btn-delete" title="Удалить" name="contact_id" value="$contact['id']">
                                    <img src="{{ asset('images/icons/close2.png') }}" alt="Удалить">
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    </div>
    </main>

    <div class="modal" id="modal">
        <div class="modal_content">
            <form action="{{ route('contact.store') }}" method="post" class="add_form">
                @csrf
                <div class="modal_header">
                    <p id="modal_header_title">Новый контакт</p>
                    <button class="close_btn" type="reset"><img src="{{ asset('images/icons/close.png') }}"
                            alt="Закрыть" id="close_modal"></button>
                </div>
                <hr style="width: 70%; margin:10px 0">


                <fieldset>
                    <legend>Фамилия</legend>
                    <input type="text" name="surname" class="add_input add" required>
                </fieldset>

                <fieldset>
                    <legend>Имя</legend>
                    <input type="text" name="name" class="add_input add" required>
                </fieldset>

                <fieldset>
                    <legend>Номер телефона</legend>
                    <input type="text" name="phone" id="phone" class="add_input add" required>
                </fieldset>

                <fieldset>
                    <legend>Эл. почта</legend>
                    <input type="email" name="email" class="add_input add" required>
                </fieldset>

                <fieldset>
                    <legend>Организация</legend>
                    <input type="text" name="firm" class="add_input add" required>
                </fieldset>
                <button class="add_btn" type="submit">Добавить</button>
            </form>
        </div>
    </div>

    <div class="modal" id="update_modal">
        <div class="modal_content">
            <form action="{{ route('contact.update', ':id') }}" method="post" id="update_form" class="add_form">
                @csrf
                @method('patch')
                <div class="modal_header">
                    <p id="modal_header_title">Обновить контакт</p>
                    <button class="close_btn" type="reset"><img src="{{ asset('images/icons/close.png') }}"
                            alt="Закрыть" id="close_update_modal"></button>
                </div>
                <hr style="width: 70%; margin:10px 0">

                <input type="hidden" id="contactId" name="id">
                <input type="hidden" id="updateRouteTemplate" value="{{ route('contact.update', ':id') }}">

                <fieldset>
                    <legend>Фамилия</legend>
                    <input type="text" name="surname" id="contact_surname" class="add_input add" required>
                </fieldset>

                <fieldset>
                    <legend>Имя</legend>
                    <input type="text" name="name" id="contact_name" class="add_input add" required>
                </fieldset>

                <fieldset>
                    <legend>Номер телефона</legend>
                    <input type="text" name="phone" id="contact_phone" class="add_input add" required>
                </fieldset>

                <fieldset>
                    <legend>Эл. почта</legend>
                    <input type="email" name="email" id="contact_email" class="add_input add" required>
                </fieldset>

                <fieldset>
                    <legend>Организация</legend>
                    <input type="text" name="firm" id="contact_firm" class="add_input add" required>
                </fieldset>
                <button class="add_btn" type="submit">Подтвердить</button>
            </form>
        </div>
    </div>
@endsection

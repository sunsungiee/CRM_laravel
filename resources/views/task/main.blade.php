@extends('layots.header')

@section('content')
    <div class="content tasks">
        <div class="header tasks">
            <h1>Текущие задачи</h1>
            <div class="header_btns">
                <button class="add_task" id="add_task">Добавить</button>
                <a href="#" id="burger_open" class="burger_btn">
                    <img src="{{ asset('images/icons/burger.svg') }}" alt="Меню">
                </a>
            </div>
        </div>

        <hr>

        <form method="GET" action="{{ route('task.main') }}" class="search_form">
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
            {{-- <thead> --}}
            {{-- <tr>
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
                </tr> --}}
            {{-- </thead> --}}
            <tbody>
                @foreach ($tasks as $task)
                    <tr>
                        <td id="editSurname"> {{ $task->surname }} </td>
                        <td>{{ $task->name }} </td>
                        <td> {{ $task->email }} </td>
                        <td> {{ $task->phone }} </td>
                        <td>{{ $task->firm }}</td>
                        <td class="actions">
                            {{-- кнопка "изменить" --}}
                            <button class="edit-btn" id="open_update_modal" title="Редактировать" type="button"
                                data-id="{{ $tasks->id }}">
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

    {{-- ДОБАВЛЕНИЕ НОВОЙ ЗАДАЧИ --}}
    <div class="modal" id="modal">
        <div class="modal_content">
            <form action="{{ route('task.store') }}" method="post" class="add_form">
                @csrf
                <div class="modal_header">
                    <p id="modal_header_title">Новая задача</p>
                    <button class="close_btn" type="reset"><img src="{{ asset('images/icons/close.png') }}"
                            alt="Закрыть" id="close_modal"></button>
                </div>
                <hr style="width: 70%; margin:10px 0">


                {{-- <input type="hidden" id="taskId" name="id"> --}}
                {{-- <input type="hidden" id="updateRouteTemplate" value="{{ route('contact.update', ':id') }}"> --}}

                <fieldset>
                    <legend>Контакт</legend>
                    <select name="contact_id" id="contact" class="add_select add">
                        {{-- <option value="" selected disabled>Выберите контакт</option> --}}
                        <option value="" selected>Без контакта</option>
                        @foreach ($contacts as $contact)
                            <option value="{{ $contact->id }}">
                                {{ $contact->surname . ' ' . $contact->name }}
                            </option>
                        @endforeach
                    </select>
                </fieldset>

                <fieldset>
                    <legend>Тема задачи</legend>
                    <input type="text" name="subject" class="add_input add" required>
                </fieldset>

                <fieldset>
                    <legend>Описание задачи</legend>
                    <textarea name="description" id="task_description" class="add_textarea add" required></textarea>
                </fieldset>

                <fieldset>
                    <legend>Дата</legend>
                    <input type="date" name="date" id="task_date" class="add_input add">
                </fieldset>

                <fieldset>
                    <legend>Время</legend>
                    <input type="time" name="time" id="task_time" class="add_input add">
                </fieldset>

                <fieldset>
                    <legend>Приоритет</legend>
                    @foreach ($priorities as $priority)
                        <input type="radio" name="priority_id" value="{{ $priority->id }}" id="{{ $priority->id }}"
                            class="add_radio add" required>
                        <label for="{{ $priority->id }}">{{ $priority->priority }}</label>
                        <br>
                    @endforeach
                </fieldset>
                <button class="add_btn" type="submit">Добавить</button>
            </form>
        </div>
    </div>

    <div class="modal" id="update_modal">
        <div class="modal_content">
            <form action="{{ route('task.update', ':id') }}" method="post" id="update_form" class="add_form">
                @csrf
                @method('patch')

                <div class="modal_header">
                    <p id="modal_header_title">Обновить контакт</p>
                    <button class="close_btn" type="reset"><img src="{{ asset('images/icons/close.png') }}"
                            alt="Закрыть" id="close_update_modal"></button>
                </div>
                <hr style="width: 70%; margin:10px 0">

                <input type="hidden" id="taskId" name="id">
                <input type="hidden" id="updateRouteTemplate" value="{{ route('contact.update', ':id') }}">

                <fieldset>
                    <legend>Контакт</legend>
                    <select name="contact_id" id="contact" class="add_select add" required>
                        <option value="" selected disabled>Выберите контакт</option>
                        <option value="0" selected>Без контакта</option>
                        @foreach ($contacts as $contact)
                            <option value="{{ $contact->id }}">
                                {{ $contact->surname . ' ' . $contact->name }}
                            </option>
                        @endforeach

                    </select>
                </fieldset>

                <fieldset>
                    <legend>Тема задачи</legend>
                    <input type="text" name="subject" class="add_input add" required>
                </fieldset>

                <fieldset>
                    <legend>Описание задачи</legend>
                    <textarea name="description" id="task_description" class="add_textarea add" required></textarea>
                </fieldset>

                <fieldset>
                    <legend>Дата</legend>
                    <input type="date" name="date" id="task_date" class="add_input add">
                </fieldset>

                <fieldset>
                    <legend>Время</legend>
                    <input type="time" name="time" id="task_time" class="add_input add">
                </fieldset>

                <fieldset>
                    <legend>Приоритет</legend>
                    @foreach ($priorities as $priority)
                        <input type="radio" name="priority_id" value="{{ $priority->id }}" id="{{ $priority->id }}"
                            class="add_radio add" required>
                        <label for="{{ $priority->id }}">{{ $priority->priority }}</label>
                        <br>
                    @endforeach
                </fieldset>
                <button class="add_btn" type="submit">Подтвердить</button>
            </form>
        </div>
    </div>
@endsection

@extends('layouts.header')

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
            <thead>
                <tr>
                    <th data-column="subject">
                        <a class="sort_btn"
                            href="{{ route('task.main', ['sort' => 'subject', 'direction' => $direction === 'asc' ? 'desc' : 'asc']) }}">Тема
                            @if ($sort === 'subject')
                                {{ $direction === 'asc' ? '↑' : '↓' }}
                            @endif
                        </a>
                    </th>
                    <th data-column="description">
                        <a class="sort_btn"
                            href="{{ route('task.main', ['sort' => 'description', 'direction' => $direction === 'asc' ? 'desc' : 'asc']) }}">
                            Описание
                            @if ($sort === 'description')
                                {{ $direction === 'asc' ? '↑' : '↓' }}
                            @endif
                        </a>
                    </th>
                    <th data-column="contact">
                        <a class="sort_btn"
                            href="{{ route('task.main', ['sort' => 'contact', 'direction' => $direction === 'asc' ? 'desc' : 'asc']) }}">
                            Клиент
                            @if ($sort === 'contact')
                                {{ $direction === 'asc' ? '↑' : '↓' }}
                            @endif
                        </a>
                    </th>
                    <th data-column="date">
                        <a class="sort_btn"
                            href="{{ route('task.main', ['sort' => 'date', 'direction' => $direction === 'asc' ? 'desc' : 'asc']) }}">
                            Дата
                            @if ($sort === 'date')
                                {{ $direction === 'asc' ? '↑' : '↓' }}
                            @endif
                        </a>
                    </th>
                    <th data-column="time">
                        <a class="sort_btn"
                            href="{{ route('task.main', ['sort' => 'time', 'direction' => $direction === 'asc' ? 'desc' : 'asc']) }}">
                            Время
                            @if ($sort === 'time')
                                {{ $direction === 'asc' ? '↑' : '↓' }}
                            @endif
                        </a>
                    </th>
                    <th data-column="priority">
                        <a href="{{ route('task.main', ['sort' => 'priority', 'direction' => $direction === 'asc' ? 'desc' : 'asc']) }}"
                            class="sort_btn">
                            Приоритет
                            @if ($sort === 'priority')
                                {{ $direction === 'asc' ? '↑' : '↓' }}
                            @endif
                        </a>
                    </th>
                    <th></th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>

            <tbody>
                @foreach ($tasks as $task)
                    <tr>
                        <td> {{ $task->subject }} </td>
                        <td> {{ $task->description }} </td>
                        <td> {{ $task->contact ? $task->contact['surname'] . ' ' . $task->contact['name'] : '' }} </td>
                        <td> {{ $task->date }} </td>
                        <td> {{ $task->time }} </td>
                        <td>{{ $task->priority['priority'] }}</td>
                        <td class="actions">
                            {{-- кнопка "Выполнено" --}}
                            <form action="{{ route('task.delete', $task->id) }}" method="post" class="delete_form">
                                @csrf
                                @method('delete')
                                <input type="hidden" value="2" name="status_id">
                                <button class="btn-done" id="btn-done" title="Пометить как выполненное"
                                    data-id="{{ $task->id }}">
                                    <img src="{{ asset('images/icons/success.png') }}" alt="Выполнено">
                                </button>
                            </form>
                        </td>
                        <td class="actions">
                            {{-- кнопка "изменить" --}}
                            <button class="edit-btn_task" id="open_update_modal" title="Редактировать" type="button"
                                data-id="{{ $task->id }}">
                                <img src="{{ asset('images/icons/edit.png') }}" alt="Изменить">
                            </button>
                        </td>
                        <td class="actions">
                            {{-- кнопка удалить --}}
                            <form action="{{ route('task.delete', $task->id) }}" method="post" class="delete_form">
                                @csrf
                                @method('delete')
                                <input type="hidden" value="4" name="status_id">
                                <button class="btn-delete" title="Удалить" name="contact_id">
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

                <fieldset>
                    <legend>Контакт</legend>
                    <select name="contact_id" id="contact" class="add_select add">
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

    {{-- ОБНОВЛЕНИЕ ЗАДАЧИ --}}
    <div class="modal" id="update_modal">
        <div class="modal_content">
            <form action="{{ route('task.update', ':id') }}" method="post" id="update_form" class="add_form">
                @csrf
                @method('patch')

                <div class="modal_header">
                    <p id="modal_header_title">Обновить задачу</p>
                    <button class="close_btn" type="reset"><img src="{{ asset('images/icons/close.png') }}"
                            alt="Закрыть" id="close_update_modal"></button>
                </div>
                <hr style="width: 70%; margin:10px 0">

                <input type="hidden" id="taskId" name="id">
                <input type="hidden" id="updateRouteTemplate" value="{{ route('contact.update', ':id') }}">

                <fieldset>
                    <legend>Контакт</legend>
                    <select name="contact_id" id="task_contact" class="add_select add" required>
                        <option value="" selected>Без контакта</option>
                        @foreach ($contacts as $contact)
                            <option class="task_contact" value="{{ $contact->id }}">
                                {{ $contact->surname . ' ' . $contact->name }}
                            </option>
                        @endforeach

                    </select>
                </fieldset>

                <fieldset>
                    <legend>Тема задачи</legend>
                    <input type="text" name="subject" id="task_subject" class="add_input add" required>
                </fieldset>

                <fieldset>
                    <legend>Описание задачи</legend>
                    <textarea name="description" id="task_description_update" class="add_textarea add" required></textarea>
                </fieldset>

                <fieldset>
                    <legend>Дата</legend>
                    <input type="date" name="date" id="task_date_update" class="add_input add">
                </fieldset>

                <fieldset>
                    <legend>Время</legend>
                    <input type="time" name="time" id="task_time_update" class="add_input add">
                </fieldset>

                <fieldset>
                    <legend>Приоритет</legend>
                    @foreach ($priorities as $priority)
                        <input type="radio" name="task_priority_id" value="{{ $priority->id }}"
                            class="task_priority_id" id="{{ $priority->id }}" class="add_radio add" required>
                        <label for="{{ $priority->id }}">{{ $priority->priority }}</label>
                        <br>
                    @endforeach
                </fieldset>
                <button class="add_btn" type="submit">Подтвердить</button>
            </form>
        </div>
    </div>
@endsection

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

        <div class="view-toggle">
            <button id="toggle-table" class="active">Таблица</button>
            <button id="toggle-kanban">Kanban</button>
        </div>

        <div class="kanban_board" id="kanban_board" style="display: none;">
            <div class="kanban_column" data-status="new">
                <h3>Новые</h3>
                <hr>
                <div class="kanban_tasks">
                    @foreach ($tasks->where('task_status', 'Новое')->filter(fn($task) => !$task->trashed()) as $task)
                        <div class="kanban_task_card">
                            <p><b>{{ $task->subject }}</b></p>
                            <p>{{ $task->description }}</p>
                            <br>
                            <p><b>Клиент:</b>
                                {{ $task->contact ? $task->contact['surname'] . ' ' . $task->contact['name'] : '' }}</p>
                            <p><b>До</b> {{ $task->formatted_date }} {{ $task->formatted_time }} </p>
                            <p><b>Приоритет: </b>{{ $task->task_priority }}</p>
                            <p><b>Исполнитель: </b> {{ $task->user['surname'] }} {{ $task->user['name'] }}</p>
                            <br>
                            <div class="kanban_actions">

                                <div class="actions">
                                    {{-- кнопка "изменить" --}}
                                    <button class="edit-btn_task" title="Редактировать" type="button"
                                        data-id="{{ $task->id }}">
                                        <img src="{{ asset('images/icons/edit.png') }}" alt="Изменить">
                                    </button>
                                </div>
                                <div class="actions">
                                    {{-- кнопка удалить --}}
                                    <form action="{{ route('task.delete', $task->id) }}" method="post"
                                        class="delete_form">
                                        @csrf
                                        @method('delete')
                                        <input type="hidden" value="4" name="status_id">
                                        <button class="btn-delete" title="Удалить" name="contact_id">
                                            <img src="{{ asset('images/icons/close2.png') }}" alt="Удалить">
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="kanban_column" data-status="in_progress">
                <h3>В процессе</h3>
                <hr>
                <div class="kanban_tasks">
                    @foreach ($tasks->where('task_status', 'В процессе')->filter(fn($task) => !$task->trashed()) as $task)
                        <div class="kanban_task_card">
                            <p><b>{{ $task->subject }}</b></p>
                            <p>{{ $task->description }}</p>
                            <br>
                            <p><b>Клиент:</b>
                                {{ $task->contact ? $task->contact['surname'] . ' ' . $task->contact['name'] : '' }}</p>
                            <p><b>До</b> {{ $task->formatted_date }} {{ $task->formatted_time }} </p>
                            <p><b>Приоритет: </b>{{ $task->task_priority }}</p>
                            <p><b>Исполнитель: </b> {{ $task->user['surname'] }} {{ $task->user['name'] }}</p>
                            <br>
                            <div class="kanban_actions">
                                <div class="actions">
                                    {{-- кнопка "Выполнено" --}}
                                    <form action="{{ route('task.delete', $task->id) }}" method="post"
                                        class="delete_form">
                                        @csrf
                                        @method('delete')
                                        <input type="hidden" value="2" name="status_id">
                                        <button class="btn-done" id="btn-done" title="Пометить как выполненное"
                                            data-id="{{ $task->id }}">
                                            <img src="{{ asset('images/icons/success.png') }}" alt="Выполнено">
                                        </button>
                                    </form>
                                </div>
                                <div class="actions">
                                    {{-- кнопка "изменить" --}}
                                    <button class="edit-btn_task" id="open_update_modal" title="Редактировать"
                                        type="button" data-id="{{ $task->id }}">
                                        <img src="{{ asset('images/icons/edit.png') }}" alt="Изменить">
                                    </button>
                                </div>
                                <div class="actions">
                                    {{-- кнопка удалить --}}
                                    <form action="{{ route('task.delete', $task->id) }}" method="post"
                                        class="delete_form">
                                        @csrf
                                        @method('delete')
                                        <input type="hidden" value="4" name="status_id">
                                        <button class="btn-delete" title="Удалить" name="contact_id">
                                            <img src="{{ asset('images/icons/close2.png') }}" alt="Удалить">
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="kanban_column" data-status="completed">
                <h3>Выполненные</h3>
                <hr>
                <div class="kanban_tasks">
                    @foreach ($tasks->where('task_status', 'Выполнено')->filter(fn($t) => $t->trashed()) as $task)
                        <div class="kanban_task_card">
                            <p><b>{{ $task->subject }}</b></p>
                            <p>{{ $task->description }}</p>
                            <br>
                            <p><b>Клиент:</b>
                                {{ $task->contact ? $task->contact['surname'] . ' ' . $task->contact['name'] : '' }}</p>
                            <p><b>До</b> {{ $task->formatted_date }} {{ $task->formatted_time }} </p>
                            <p><b>Приоритет: </b>{{ $task->task_priority }}</p>
                            <p><b>Исполнитель: </b> {{ $task->user['surname'] }} {{ $task->user['name'] }}</p>
                            <br>

                        </div>
                    @endforeach
                </div>
            </div>
        </div>

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
                    <th data-column="contact_surname">
                        <a class="sort_btn"
                            href="{{ route('task.main', ['sort' => 'contact_surname', 'direction' => $direction === 'asc' ? 'desc' : 'asc']) }}">
                            Клиент
                            @if ($sort === 'contact_surname')
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
                    <th data-column="status">
                        <a href="{{ route('task.main', ['sort' => 'status', 'direction' => $direction === 'asc' ? 'desc' : 'asc']) }}"
                            class="sort_btn">
                            Статус
                            @if ($sort === 'status')
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
                @foreach ($tasks->filter(fn($task) => !$task->trashed()) as $task)
                    <tr>
                        <td> {{ $task->subject }} </td>
                        <td> {{ $task->description }} </td>
                        <td> {{ $task->contact ? $task->contact['surname'] . ' ' . $task->contact['name'] : '' }} </td>
                        <td> {{ $task->formatted_date }} </td>
                        <td> {{ $task->formatted_time }} </td>
                        <td>{{ $task->priority['priority'] }}</td>
                        <td>{{ $task->status['status'] }}</td>
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
                            <button class="edit-btn_task" title="Редактировать" type="button"
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
                <input type="hidden" id="updateRouteTemplate" value="{{ route('task.update', ':id') }}">

                <fieldset>
                    <legend>Контакт</legend>
                    <select name="contact_id" id="task_contact" class="add_select add">
                        <option value="0" selected>Без контакта</option>
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
                        <input type="radio" name="priority_id" value="{{ $priority->id }}" class="task_priority_id"
                            id="priority{{ $priority->id }}" class="add_radio add" required>
                        <label for="priority{{ $priority->id }}">{{ $priority->priority }}</label>
                        <br>
                    @endforeach
                </fieldset>

                <fieldset>
                    <legend>Статус задачи</legend>
                    @foreach ($statuses as $status)
                        <input type="radio" name="status_id" id="status{{ $status->id }}"
                            value="{{ $status->id }}" class="task_status_id" id="{{ $status->id }}"
                            class="add_radio add" required>
                        <label for="status{{ $status->id }}">{{ $status->status }}</label>
                        <br>
                    @endforeach
                </fieldset>
                <button class="add_btn" type="submit">Подтвердить</button>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const table = document.querySelector('.tasks_table');
            const kanbanBoard = document.getElementById('kanban_board');

            const toggleTableBtn = document.getElementById('toggle-table');
            const toggleKanbanBtn = document.getElementById('toggle-kanban');

            toggleTableBtn.style.backgroundColor = "white";
            toggleTableBtn.style.color = "var(--btnhover)";
            toggleTableBtn.style.border = "1px solid var(--btnhover)";

            if (@json($tasks)) {
                const tasks = @json($tasks);


                toggleTableBtn.addEventListener('click', () => {
                    table.style.display = 'table';
                    kanbanBoard.style.display = 'none';

                    toggleTableBtn.style.backgroundColor = "white";
                    toggleTableBtn.style.color = "var(--btnhover)";
                    toggleTableBtn.style.border = "1px solid var(--btnhover)";

                    toggleKanbanBtn.style.color = "white";
                    toggleKanbanBtn.style.backgroundColor = "var(--btnhover)";
                    toggleKanbanBtn.style.border = "none";

                    toggleTableBtn.classList.add('active');
                    toggleKanbanBtn.classList.remove('active');
                });

                toggleKanbanBtn.addEventListener('click', () => {
                    table.style.display = 'none';
                    kanbanBoard.style.display = 'flex';

                    toggleKanbanBtn.style.backgroundColor = "white";
                    toggleKanbanBtn.style.color = "var(--btnhover)";
                    toggleKanbanBtn.style.border = "1px solid var(--btnhover)";

                    toggleTableBtn.style.color = "white";
                    toggleTableBtn.style.backgroundColor = "var(--btnhover)";
                    toggleTableBtn.style.border = "none";

                    toggleKanbanBtn.classList.add('active');
                    toggleTableBtn.classList.remove('active');
                });
            }
        });
    </script>
@endsection

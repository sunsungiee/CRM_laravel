@extends('layouts.header')

@section('content')
    <div class="content tasks">
        <div class="header tasks">
            <h1>Текущие сделки</h1>
            <div class="header_btns">
                <button class="add_task" id="add_task">Добавить</button>
                <a href="#" id="burger_open" class="burger_btn">
                    <img src="{{ asset('images/icons/burger.svg') }}" alt="Меню">
                </a>
            </div>
        </div>

        <hr>
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="GET" action="{{ route('deal.main') }}" class="search_form">
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
                            href="{{ route('deal.main', ['sort' => 'subject', 'direction' => $direction === 'asc' ? 'desc' : 'asc']) }}">Тема
                            @if ($sort === 'subject')
                                {{ $direction === 'asc' ? '↑' : '↓' }}
                            @endif
                        </a>
                    </th>
                    <th data-column="contact">
                        <a class="sort_btn"
                            href="{{ route('deal.main', ['sort' => 'contact', 'direction' => $direction === 'asc' ? 'desc' : 'asc']) }}">
                            Клиент
                            @if ($sort === 'contact')
                                {{ $direction === 'asc' ? '↑' : '↓' }}
                            @endif
                        </a>
                    </th>
                    <th data-column="date">
                        <a class="sort_btn"
                            href="{{ route('deal.main', ['sort' => 'end_date', 'direction' => $direction === 'asc' ? 'desc' : 'asc']) }}">
                            Дата
                            @if ($sort === 'end_date')
                                {{ $direction === 'asc' ? '↑' : '↓' }}
                            @endif
                        </a>
                    </th>
                    <th data-column="time">
                        <a class="sort_btn"
                            href="{{ route('deal.main', ['sort' => 'end_time', 'direction' => $direction === 'asc' ? 'desc' : 'asc']) }}">
                            Время
                            @if ($sort === 'end_time')
                                {{ $direction === 'asc' ? '↑' : '↓' }}
                            @endif
                        </a>
                    </th>
                    <th data-column="sum">
                        <a href="{{ route('deal.main', ['sort' => 'sum', 'direction' => $direction === 'asc' ? 'desc' : 'asc']) }}"
                            class="sort_btn">
                            Прибыль
                            @if ($sort === 'sum')
                                {{ $direction === 'asc' ? '↑' : '↓' }}
                            @endif
                        </a>
                    </th>
                    <th data-column="phase">
                        <a href="{{ route('deal.main', ['sort' => 'phase', 'direction' => $direction === 'asc' ? 'desc' : 'asc']) }}"
                            class="sort_btn">
                            Стадия сделки
                            @if ($sort === 'phase')
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
                @foreach ($deals as $deal)
                    <tr>
                        <td> {{ $deal->subject }} </td>
                        <td> {{ $deal->contact['surname'] . ' ' . $deal->contact['name'] }} </td>
                        <td> {{ $deal->formatted_date }} </td>
                        <td> {{ $deal->formatted_time }} </td>
                        <td> {{ $deal->sum }} Руб.</td>
                        <td>{{ $deal->phase['phase'] }}</td>
                        <td class="actions">
                            кнопка "Выполнено"
                            <form action="{{ route('deal.delete', $deal->id) }}" method="post" class="delete_form">
                                @csrf
                                @method('delete')
                                <input type="hidden" value="4" name="phase_id">
                                <button class="btn-done" id="btn-done" title="Пометить как выполненное"
                                    data-id="{{ $deal->id }}">
                                    <img src="{{ asset('images/icons/success.png') }}" alt="Выполнено">
                                </button>
                            </form>
                        </td>
                        <td class="actions">
                            {{-- кнопка "изменить" --}}
                            <button class="edit-btn_deal" id="open_update_modal" title="Редактировать" type="button"
                                data-id="{{ $deal->id }}">
                                <img src="{{ asset('images/icons/edit.png') }}" alt="Изменить">
                            </button>
                        </td>
                        <td class="actions">
                            {{-- кнопка удалить --}}
                            <form action="{{ route('deal.delete', $deal->id) }}" method="post" class="delete_form">
                                @csrf
                                @method('delete')
                                <input type="hidden" value="5" name="phase_id">
                                <button class="btn-delete" title="Удалить" name="contact_id">
                                    <img src="{{ asset('images/icons/close2.png') }}" alt="Удалить">
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{-- НОВАЯ СДЕЛКА --}}
        <div class="modal" id="modal">
            <div class="modal_content">
                <form action="{{ route('deal.store') }}" method="post" class="add_form">
                    @csrf
                    <div class="modal_header">
                        <p id="modal_header_title">Новая сделка</p>
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
                        <legend>Тема сделки</legend>
                        <input type="text" name="subject" class="add_input add" required>
                    </fieldset>

                    <fieldset>
                        <legend>Контрольная дата</legend>
                        <input type="date" name="end_date" id="deal_date" class="add_input add">
                    </fieldset>

                    <fieldset>
                        <legend>Контрольное время</legend>
                        <input type="time" name="end_time" id="deal_time" class="add_input add">
                    </fieldset>

                    <fieldset>
                        <legend>Сумма сделки</legend>
                        <input type="number" name="sum" max="9999999999" class="add_input add" required>
                    </fieldset>

                    <fieldset>
                        <legend>Стадия сделки</legend>
                        @foreach ($phases as $phase)
                            <input type="radio" name="phase_id" value="{{ $phase->id }}" class="deal_priority_id"
                                id="{{ $phase->id }}" class="add_radio add" required>
                            <label for="{{ $phase->id }}">{{ $phase->phase }}</label>
                            <br>
                        @endforeach
                    </fieldset>
                    <button class="add_btn" type="submit">Добавить</button>
                </form>
            </div>
        </div>

        {{-- ОБНОВЛЕНИЕ СДЕЛКИ --}}
        <div class="modal" id="update_modal">
            <div class="modal_content">
                <form action="{{ route('deal.update', ':id') }}" method="post" id="update_form" class="add_form">
                    @csrf
                    @method('patch')

                    <div class="modal_header">
                        <p id="modal_header_title">Обновить сделку</p>
                        <button class="close_btn" type="reset"><img src="{{ asset('images/icons/close.png') }}"
                                alt="Закрыть" id="close_update_modal"></button>
                    </div>
                    <hr style="width: 70%; margin:10px 0">

                    <input type="hidden" id="dealId" name="id">
                    <input type="hidden" id="updateRouteTemplate" value="{{ route('deal.update', ':id') }}">

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
                        <legend>Тема сделки</legend>
                        <input type="text" name="subject" id="deal_subject" class="add_input add" required>
                    </fieldset>

                    <fieldset>
                        <legend>Заключительная дата</legend>
                        <input type="date" name="end_date" id="deal_date_update" class="add_input add">
                    </fieldset>

                    <fieldset>
                        <legend>Заключительное время</legend>
                        <input type="time" name="end_time" id="deal_time_update" class="add_input add">
                    </fieldset>

                    <fieldset>
                        <legend>Сумма сделки</legend>
                        <input type="number" name="sum" max="9999999999" id="sum_update" class="add_input add"
                            required>
                    </fieldset>

                    <fieldset>
                        <legend>Стадия сделки</legend>
                        @foreach ($phases as $phase)
                            <input type="radio" name="phase_id" value="{{ $phase->id }}" class="deal_phase_id"
                                id="{{ $phase->id }}" class="add_radio add" required>
                            <label for="{{ $phase->id }}">{{ $phase->phase }}</label>
                            <br>
                        @endforeach
                    </fieldset>
                    <button class="add_btn" type="submit">Подтвердить</button>
                </form>
            </div>
        </div>
    @endsection

@extends('layouts.header')

@section('content')
    <div class="content tasks">
        <div class="header tasks">
            <h1>Рабочий стол</h1>
            <div class="header_btns">
                {{-- <button class="add_task" id="add_task">Добавить</button> --}}
                <a href="#" id="burger_open" class="burger_btn">
                    <img src="{{ asset('images/icons/burger.svg') }}" alt="Меню">
                </a>
            </div>
        </div>
        <hr>

        {{-- @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif --}}

        <div class="main_info">
            <section>
                <h2>Сделки в процессе</h2>
                <br>
                <table class="tasks_table another_table">
                    <tr>
                        <th>Тема</th>
                        <th>Клиент</th>
                        <th>Дата</th>
                        <th>Время</th>
                        <th>Сумма сделки</th>
                        <th>Стадия сделки</th>
                    </tr>
                    @foreach ($actualDeals as $actualDeal)
                        <tr>
                            <td>{{ $actualDeal->subject }}</td>
                            <td> {{ optional($actualDeal->contact)->surname }}
                                {{ optional($actualDeal->contact)->name }}</td>
                            <td> {{ $actualDeal->formatted_date }} </td>
                            <td> {{ $actualDeal->formatted_time }} </td>
                            <td> {{ $actualDeal->sum }} Руб.</td>
                            <td>{{ $actualDeal->phase }}</td>
                        </tr>
                    @endforeach
                </table>
            </section>

            <section>
                <h2>Сводки по сделкам за последний месяц</h2>
                <br>
                <div class="welcome_chart">
                    <canvas id="dealMonthChart"></canvas>
                </div>
            </section>

            <section>
                <h2>Горящие задачи</h2>
                <br>
                <table class="tasks_table another_table">
                    <tr>
                        <th>Тема</th>
                        <th>Описание</th>
                        <th>Клиент</th>
                        <th>Дата</th>
                        <th>Время</th>
                        <th>Приоритет</th>
                    </tr>
                    @foreach ($soonTasks as $soonTask)
                        <tr>
                            <td>{{ $soonTask->subject }}</td>
                            <td>{{ $soonTask->description }}</td>
                            <td> {{ $soonTask->contact ? $soonTask->contact['name'] . ' ' . $soonTask->contact['name'] : '' }}
                            </td>
                            <td> {{ $soonTask->formatted_date }} </td>
                            <td> {{ $soonTask->formatted_time }} </td>
                            <td>{{ $soonTask->priority['priority'] }}</td>
                        </tr>
                    @endforeach
                </table>
            </section>

            <section>
                <h2>Процент выполнения задач за месяц</h2>
                <br>
                <div class="welcome_chart">
                    <canvas id="taskMonthChart"></canvas>
                </div>
            </section>

            <section>
                <h2>Активные задачи</h2>
                <br>
                <table class="tasks_table another_table">
                    <tr>
                        <th>Тема</th>
                        <th>Описание</th>
                        <th>Клиент</th>
                        <th>Дата</th>
                        <th>Время</th>
                        <th>Приоритет</th>
                    </tr>
                    @foreach ($actualTasks as $actualTask)
                        <tr>
                            <td>{{ $actualTask->subject }}</td>
                            <td>{{ $actualTask->description }}</td>
                            <td> {{ $actualTask->contact ? $actualTask->contact['name'] . ' ' . $actualTask->contact['name'] : '' }}
                            </td>
                            <td> {{ $actualTask->formatted_date }} </td>
                            <td> {{ $actualTask->formatted_time }} </td>
                            <td>{{ $actualTask->priority['priority'] }}</td>
                        </tr>
                    @endforeach
                </table>
            </section>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>
    <script src="{{ asset('js/welcome_analytics.js') }}"></script>
@endsection

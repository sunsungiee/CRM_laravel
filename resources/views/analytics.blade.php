@extends('layouts.header')

@section('content')
    <div class="content tasks">
        <div class="header tasks">
            <h1>Аналитика</h1>
            <div class="header_btns">
                {{-- <button class="add_task" id="add_task">Добавить</button> --}}
                <a href="#" id="burger_open" class="burger_btn">
                    <img src="{{ asset('images/icons/burger.svg') }}" alt="Меню">
                </a>
            </div>
        </div>
        <hr>

        <div class="container_analytics">
            {{-- первая диаграмма --}}
            <div class="analytics_item">
                <div class="canvas dealChart">
                    <h2>Совершенные сделки за год</h2>
                    <br>
                    <div class="filter">
                        <label for="yearFilter">Выберите год:</label>
                        <select name="year" id="yearFilter">
                            @for ($y = now()->year - 5; $y <= now()->year + 1; $y++)
                                <option value="{{ $y }}" {{ $y == now()->year ? 'selected' : '' }}>
                                    {{ $y }}
                                </option>
                            @endfor
                        </select>
                    </div>
                    <br>
                    <h1>Всего <span id="all_deals">0</span></h1>
                    <br>
                    <canvas id="dealChart"></canvas>
                </div>
            </div>
            <br>
            <br>

            {{-- вторая --}}
            <div class="analytics_item double">
                <div class="canvas dealChart">
                    <h2>Процент успешных сделок <br> за год</h2>
                    <br>
                    <br>
                    <canvas id="dealPieChartYear"></canvas>
                </div>

                <div class="canvas dealChart">
                    <h2>Процент успешных сделок <br> за все время</h2>
                    <br>
                    <br>
                    <canvas id="dealPieChart"></canvas>
                </div>
            </div>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>
    <script src="{{ asset('js/analytics.js') }}"></script>
@endsection

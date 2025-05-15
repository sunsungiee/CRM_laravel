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

            <div class="analytics_item">
                <div class="canvas dealChart">
                    <h2>Сделки по месяцам</h2>
                    <canvas id="dealChart"></canvas>
                </div>

                <div class="filter">
                </div>

                <table class="table_canvas">
                    <thead>
                        <tr>
                            <th>Месяц</th>
                            <th>Совершено</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <label for="monthFilter">Выберите месяц:</label>
                                <select name="month" id="monthFilter">
                                    @foreach ($months as $short => $full)
                                        <option value="{{ $short }}">
                                            {{ $full }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td id="dataCompleted"></td>
                        </tr>
                    </tbody>
                </table>
                {{-- <button>Распечатать</button> --}}
            </div>

        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/chart.js "></script>
    <script>
        const ctx = document.getElementById('dealChart').getContext('2d');

        const labels = {!! json_encode(array_keys($stats)) !!};
        const completed = {!! json_encode(array_column($stats, 4)) !!};

        let myChart = new Chart(ctx, {
            type: 'scatter',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Совершено',
                    data: completed,
                    backgroundColor: 'rgba(75, 192, 192, 0.6)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        stepSize: 1
                    }
                }
            }
        });

        function updateTableAndChart(month) {
            fetch(`/analytics/data?month=${month}`)
                .then(response => response.json())
                .then(data => {
                    myChart.data.labels = data.labels;
                    myChart.data.datasets[0].data = data.completed;
                    myChart.update();

                    const tbody = document.querySelector('#dataTable tbody');
                    tbody.innerHTML = '';

                    data.labels.forEach(label => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                        <td>${label}</td>
                        <td>${data.stats[label][4] || 0}</td>
                        <td>${data.stats[label][5] || 0}</td>
                    `;
                        tbody.appendChild(row);
                    });
                });
        }

        document.getElementById('monthFilter').addEventListener('change', function() {
            const selectedMonth = this.value;
            updateTableAndChart(selectedMonth);
        });

        document.addEventListener('DOMContentLoaded', function() {
            const defaultMonth = '{{ now()->format('M') }}';
            updateTableAndChart(defaultMonth);
        });
    </script>
@endsection

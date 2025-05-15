function initAllTimeChart() {
    const dealCtx = document.getElementById('dealPieChart')?.getContext('2d');
    if (!dealCtx) return;

    let dealPieChart = new Chart(dealCtx, {
        type: 'pie',
        data: {
            labels: ['Совершено', 'Отменено', 'В процессе'],
            datasets: [{
                label: 'Все сделки',
                data: [0, 0, 0],
                backgroundColor: [
                    'rgba(75, 192, 192, 0.6)',   // Совершено
                    'rgba(255, 99, 132, 0.6)', // Отменено
                    'rgba(255, 206, 86, 0.6)'   // В процессе
                ],
                borderColor: [
                    'rgba(75, 192, 192, 1)',
                    'rgba(255, 99, 132, 1)',
                    'rgba(255, 206, 86, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'bottom' },
                tooltip: { enabled: true },
                title: {
                    display: true,
                },
                datalabels: {
                    formatter: function (value, ctx) {
                        const total = ctx.chart.data.datasets[0].data.reduce((acc, val) => acc + val, 0);
                        const percentage = ((value / total) * 100).toFixed(1) + '%';
                        return `${percentage}`;
                    },
                    color: '#fff', // цвет текста
                    font: {
                        weight: 'bold',
                        size: 20
                    }
                }
            }
        },
        plugins: [ChartDataLabels]

    });

    fetch('/analytics/all-time-data')
        .then(res => res.json())
        .then(data => {
            dealPieChart.data.datasets[0].data = [
                data.summary.completed,
                data.summary.canceled,
                data.summary.inProgress
            ];
            dealPieChart.update();
        })
        .catch(err => console.error('Ошибка загрузки данных за всё время:', err));
}

document.addEventListener('DOMContentLoaded', function () {
    initAllTimeChart();

    const ctx = document.getElementById('dealChart').getContext('2d');

    const lineChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: [],
            datasets: [{
                label: 'Совершено',
                data: [],
                borderColor: 'rgba(75, 192, 192, 1)',
                backgroundColor: 'rgba(75, 192, 192, 0.6)',
                fill: false,
                pointRadius: 5
            },
            {
                label: 'Сорвано',
                data: [],
                borderColor: 'rgba(255, 99, 132, 1)',
                backgroundColor: 'rgba(255, 99, 132, 0.6)',
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            },
            scales: {
                x: {
                    type: 'category',
                    ticks: {
                        autoSkip: false
                    }
                },
                y: {
                    beginAtZero: true,
                    stepSize: 1
                }
            }
        }
    });

    // вторая диаграмма
    const ctx2 = document.getElementById('dealPieChartYear').getContext('2d');

    const pieChartYear = new Chart(ctx2, {
        type: 'pie',
        data: {
            labels: [],
            datasets: [{
                label: 'Статус сделок',
                data: [],
                borderColor: [
                    'rgba(75, 192, 192, 1)',
                    'rgba(255, 99, 132, 1)',
                    'rgba(255, 206, 86, 1)'
                ],
                backgroundColor: [
                    'rgba(75, 192, 192, 0.6)', // Совершено — синий/бирюзовый
                    'rgba(255, 99, 132, 0.6)', // Отменено — красный
                    'rgba(255, 206, 86, 0.6)'  // В процессе — жёлтый
                ],
                borderWidth: 1
            },]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                },
                tooltip: {
                    enabled: true
                },
                title: {
                    display: true,
                },
                datalabels: {
                    formatter: function (value, ctx) {
                        const total = ctx.chart.data.datasets[0].data.reduce((acc, val) => acc + val, 0);
                        const percentage = ((value / total) * 100).toFixed(1) + '%';
                        return `${percentage}`;
                    },
                    color: '#fff', // цвет текста
                    font: {
                        weight: 'bold',
                        size: 20
                    }
                }
            },

        },
        plugins: [ChartDataLabels]
    });

    function updateData(year) {
        fetch(`/analytics/data?year=${year}`)
            .then(res => res.json())
            .then(data => {
                // console.log('Labels:', data.labels);
                // console.log('Completed:', data.completed);

                const totalCompleted = data.completed.reduce((sum, val) => sum + val, 0);

                document.getElementById("all_deals").textContent = totalCompleted;

                if (lineChart) {
                    lineChart.data.labels = data.labels;
                    lineChart.data.datasets[0].data = data.completed.map(Number);
                    lineChart.data.datasets[1].data = data.canceled.map(Number);
                    lineChart.update();
                }

                if (pieChartYear) {
                    const totalCompletedPie = data.completed.reduce((a, b) => a + b, 0);
                    const totalCanceledPie = data.canceled.reduce((a, b) => a + b, 0);
                    const totalInProcessPie = data.inProgress.reduce((a, b) => a + b, 0);

                    pieChartYear.data.labels = ['совершено', 'сорвано', 'в процессе'];
                    pieChartYear.data.datasets[0].data = [totalCompletedPie, totalCanceledPie, totalInProcessPie];

                    pieChartYear.update();
                }
                console.log(data);
            })
            .catch(err => console.error('Ошибка:', err));
    }

    document.getElementById('yearFilter')?.addEventListener('change', function () {
        const selectedYear = this.value;
        updateData(selectedYear);
    });

    document.getElementById('yearFilter2')?.addEventListener('change', function () {
        const selectedYear2 = this.value;
        updateData(selectedYear2);
    });

    const defaultYearInput = document.getElementById('defaultYear');
    const defaultYear = defaultYearInput ? defaultYearInput.value : new Date().getFullYear();
    updateData(defaultYear);

});

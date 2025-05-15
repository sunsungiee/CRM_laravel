document.addEventListener('DOMContentLoaded', function () {
    const ctx = document.getElementById('dealMonthChart').getContext('2d');

    const dealPieChart = new Chart(ctx, {
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
    })

    const ctx2 = document.getElementById('taskMonthChart').getContext('2d');

    const taskPieChart = new Chart(ctx2, {
        type: 'pie',
        data: {
            labels: [],
            datasets: [{
                label: 'Статус задач',
                data: [],
                borderColor: [
                    'rgba(75, 192, 192, 1)',
                    'rgba(255, 99, 132, 1)',
                    'rgba(155, 99, 132, 0.6)', // Отменено — красный
                    'rgba(255, 206, 86, 1)'
                ],
                backgroundColor: [
                    'rgba(75, 192, 192, 0.6)', // Совершено — синий/бирюзовый
                    'rgba(255, 99, 132, 0.6)', // Отменено — красный
                    'rgba(155, 99, 132, 0.6)', // Отменено — красный
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
    })

    async function updateData(month = new Date().getMonth() + 1) {
        try {
            const response = await fetch(`/data?month=${month}`);
            const data = await response.json();

            if (dealPieChart) {
                dealPieChart.data.labels = data.chart.labels;
                dealPieChart.data.datasets[0].data = data.chart.data.map(Number);

                dealPieChart.options.plugins.title.text = `Статус сделок за ${month}й месяц `;

                dealPieChart.update();
            }

        } catch (err) {
            console.error('Ошибка при обновлении данных:', err);
        }
    }

    updateData();

    async function updateChartData(month = new Date().getMonth() + 1) {
        try {
            const response = await fetch(`/tasks_data?month=${month}`);
            const data = await response.json();

            console.log('Данные от сервера:', data);

            if (!data || !data.chart || !Array.isArray(data.chart.data)) {
                console.error('Неверный формат данных');
                return;
            }

            taskPieChart.data.labels = data.chart.labels;
            taskPieChart.data.datasets[0].data = data.chart.data.map(Number);
            taskPieChart.options.plugins.title.text = `Задачи за ${month}й месяц`;
            taskPieChart.update();
        } catch (err) {
            console.error('Ошибка при получении данных:', err);
        }
    }

    updateChartData();
});



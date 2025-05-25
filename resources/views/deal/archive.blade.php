@extends('layouts.header')

@section('content')
    <div class="content tasks">
        <div class="header tasks">
            <h1>Архив сделок</h1>
            <div class="header_btns">
                <button class="add_task" onclick="printTable()" id="add_task">Распечатать</button>
                <div><button class="add_task" id="save_as">Сохранить как</button>
                    <div id="save_as_menu">
                        <div>
                            <button onclick="exportTable('xlsx')">Сохранить как Excel (.xlsx)</button>
                            <button onclick="exportTable('csv')">Сохранить как CSV (.csv)</button>
                            <button onclick="exportTable('pdf')">Сохранить как PDF (.pdf)</button>
                        </div>
                    </div>
                </div>
                <a href="#" id="burger_open" class="burger_btn">
                    <img src="{{ asset('images/icons/burger.svg') }}" alt="Меню">
                </a>

            </div>
        </div>

        <hr>

        <form method="GET" action="{{ route('deal.archive') }}" class="search_form">
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

        <table class="tasks_table" id="archive-table">

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
                    </tr>
                @endforeach
            </tbody>

        </table>

        <!-- SheetJS (xlsx) -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.0/xlsx.full.min.js "></script>

        <!-- html2canvas (для PDF) -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js "></script>

        <!-- jsPDF (для PDF) -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js "></script>

        <script>
            function exportTable(format) {
                const table = document.getElementById("archive-table");
                const workbook = XLSX.utils.table_to_book(table, {
                    sheet: "Архив"
                });

                switch (format) {
                    case 'xlsx':
                        XLSX.writeFile(workbook, `архив_${getCurrentDate()}.xlsx`);
                        break;
                    case 'csv':
                        XLSX.write(workbook, {
                            bookType: 'csv',
                            type: 'file'
                        });
                        break;
                    case 'pdf':
                        exportToPDF(table);
                        break;
                    default:
                        alert("Формат не поддерживается");
                }
            }

            function downloadFile(filename, content) {
                const a = document.createElement("a");
                a.href = URL.createObjectURL(new Blob([content]));
                a.download = filename;
                a.click();
            }

            function exportToPDF(element) {
                html2canvas(element).then(canvas => {
                    const imgData = canvas.toDataURL('image/png');
                    const pdf = new jspdf.jsPDF('p', 'pt', 'a4');
                    const imgProps = pdf.getImageProperties(imgData);
                    const pdfWidth = pdf.internal.pageSize.getWidth();
                    const pdfHeight = (imgProps.height * pdfWidth) / imgProps.width;

                    pdf.addImage(imgData, 'PNG', 0, 0, pdfWidth, pdfHeight);
                    pdf.save(`архив_${getCurrentDate()}.pdf`);
                });
            }

            function getCurrentDate() {
                const d = new Date();
                return `${d.getFullYear()}-${String(d.getMonth()+1).padStart(2, '0')}-${String(d.getDate()).padStart(2, '0')}`;
            }

            function printTable() {
                const table = document.getElementById("archive-table").outerHTML;

                // Создаем новое окно для печати
                const win = window.open('', '', 'height=800,width=1000');
                win.document.write('<html><head><title>Печать архива</title>');
                win.document.write('<style>');
                win.document.write('body { font-family: Arial, sans-serif; padding: 20px; }');
                win.document.write('table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }');
                win.document.write('th, td { border: 1px solid #000; padding: 8px; text-align: left; }');
                win.document.write('</style>');
                win.document.write('</head><body>');
                win.document.write('<h2>Архив задач</h2>');
                win.document.write(table);
                win.document.write('</body></html>');
                win.document.close();
                win.focus();

                // Открываем диалог печати
                win.print();
                win.close();
            }
        </script>
    @endsection

@extends('layots.header')

@section('content')
    @if (session('keepModalOpen'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Открываем модалку после загрузки страницы
                const updateModal = document.getElementById('update_modal');
                updateModal.style.display = 'block';

                // Заполняем форму данными
                @if (session('contact'))
                    const contact = @json(session('contact'));
                    document.getElementById('contact_surname').value = contact.surname;
                    document.getElementById('contact_name').value = contact.name;
                    document.getElementById('contact_email').value = contact.email;
                    document.getElementById('contact_phone').value = contact.phone;
                    document.getElementById('contact_firm').value = contact.firm;
                    // Заполняем остальные поля...
                @endif
            });
        </script>
    @endif

    <div class="content tasks">
        <div class="header tasks">
            <h1>Список контактов</h1>
            <div class="header_btns">
                <button class="add_task" id="add_task">Добавить</button>
                <a href="#" id="burger_open" class="burger_btn">
                    <img src="../../assets/img/icons/burger.svg" alt="Меню">
                </a>
            </div>
        </div>

        <hr>
        <table class="tasks_table" id="contacts_table">
            <thead>
                <tr>
                    <th data-column="surname">Фамилия</th>
                    <th data-column="name">Имя</th>
                    <th data-column="phone">Номер телефона</th>
                    <th data-column="email">Эл. почта</th>
                    <th data-column="firm">Организация</th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($contacts as $contact)
                    <tr>
                        <td id="editSurname"> {{ $contact->surname }} </td>
                        <td>{{ $contact->name }} </td>
                        <td> {{ $contact->email }} </td>
                        <td> {{ $contact->phone }} </td>
                        <td>{{ $contact->firm }}</td>
                        <td class="actions">
                            <button class="edit-btn" id="open_update_modal" name="contact_id" type="submit"
                                data-id="{{ $contact->id }}">
                                <i class="fas fa-trash-alt"></i> Изменить
                            </button>
                        </td>
                        <td class="actions">
                            <form action="" method="post">
                                <button class="btn-delete" name="contact_id" value="$contact['id']">
                                    <i class="fas fa-trash-alt"></i> Удалить
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

    <div class="modal" id="modal">
        <div class="modal_content">
            <form action="{{ route('contact.store') }}" method="post" class="add_form">
                @csrf
                <div class="modal_header">
                    <p id="modal_header_title">Новый контакт</p>
                    <button class="close_btn" type="reset"><img src="{{ asset('images/icons/close.png') }}" alt="Закрыть"
                            id="close_modal"></button>
                </div>
                <hr style="width: 70%; margin:10px 0">


                <fieldset>
                    <legend>Фамилия</legend>
                    <input type="text" name="surname" class="add_input add" required>
                </fieldset>

                <fieldset>
                    <legend>Имя</legend>
                    <input type="text" name="name" class="add_input add" required>
                </fieldset>

                <fieldset>
                    <legend>Номер телефона</legend>
                    <input type="text" name="phone" id="phone" class="add_input add" required>
                </fieldset>

                <fieldset>
                    <legend>Эл. почта</legend>
                    <input type="email" name="email" class="add_input add" required>
                </fieldset>

                <fieldset>
                    <legend>Организация</legend>
                    <input type="text" name="firm" class="add_input add" required>
                </fieldset>
                <button class="add_btn" type="submit">Добавить</button>
            </form>
        </div>
    </div>

    <div class="modal" id="update_modal">
        <div class="modal_content">
            <form action="{{ route('contact.update', $contact->id) }}" method="post" class="add_form">
                @csrf
                @method('patch')
                <div class="modal_header">
                    <p id="modal_header_title">Обновить контакт</p>
                    <button class="close_btn" type="reset"><img src="{{ asset('images/icons/close.png') }}" alt="Закрыть"
                            id="close_update_modal"></button>
                </div>
                <hr style="width: 70%; margin:10px 0">

                <input type="hidden" id="contactId" name="id">

                <fieldset>
                    <legend>Фамилия</legend>
                    <input type="text" name="surname" id="contact_surname" class="add_input add" required>
                </fieldset>

                <fieldset>
                    <legend>Имя</legend>
                    <input type="text" name="name" id="contact_name" class="add_input add" required>
                </fieldset>

                <fieldset>
                    <legend>Номер телефона</legend>
                    <input type="text" name="phone" id="contact_phone" class="add_input add" required>
                </fieldset>

                <fieldset>
                    <legend>Эл. почта</legend>
                    <input type="email" name="email" id="contact_email" class="add_input add" required>
                </fieldset>

                <fieldset>
                    <legend>Организация</legend>
                    <input type="text" name="firm" id="contact_firm" class="add_input add" required>
                </fieldset>
                <button class="add_btn" type="submit">Добавить</button>
            </form>
        </div>
    </div>
@endsection

import './bootstrap';
import './jquery-3.7.1.min';
import './jquery.maskedinput.min';

$('#phone').mask('+7(999) 999-99-99');

$("#task_button").click(function () {
    $("#tasks_menu").slideToggle(300); // 300ms - длительность анимации
});

$("#burger_task_button").click(function () {
    $("#burger_tasks_menu").slideToggle(300); // 300ms - длительность анимации
});

//открытие модального окна добавления
var openModal = document.getElementById("add_task");
var closeModal = document.getElementById("close_modal");
var modal = document.getElementById("modal");

openModal.addEventListener('click', function (event) {
    modal.style.display = "block";
});

closeModal.addEventListener("click", function (event) {
    modal.style.display = "none";
});

//открытие модального окна формы изменения

var closeUpdateModal = document.getElementById("close_update_modal");
var updateModal = document.getElementById("update_modal");

document.querySelectorAll('.edit-btn').forEach(btn => {
    btn.addEventListener('click', function () {
        const contactId = this.getAttribute('data-id');
        // AJAX-запрос для получения данных
        fetch(`/contacts/${contactId}/edit`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('contactId').value = data.id;
                document.getElementById('contact_surname').value = data.surname;
                document.getElementById('contact_name').value = data.name;
                document.getElementById('contact_email').value = data.email;
                document.getElementById('contact_phone').value = data.phone;
                document.getElementById('contact_firm').value = data.firm;
                const form = document.getElementById('update_form');
                const routeTemplate = document.getElementById('updateRouteTemplate').value;
                form.action = routeTemplate.replace(':id', data.id); // Подставляем реальный ID

                updateModal.style.display = 'block';
            });
    });
});

closeUpdateModal.addEventListener("click", function () {
    updateModal.style.display = "none";
});

window.onclick = function (event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }

    if (event.target == updateModal) {
        updateModal.style.display = "none";
    }
}

var taskDate = document.getElementById("task_date");

var today = new Date().toISOString().split('T')[0];

if (taskDate) {
    taskDate.setAttribute('min', today);
}

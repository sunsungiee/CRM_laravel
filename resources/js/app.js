import './bootstrap';
// import $ from 'jquery';
// import 'jquery-mask-plugin';

// window.$ = $;
// window.jQuery = $;

// $(document).ready(function () {
//     $('#phone').mask('+7 (000) 000-00-00');
// });

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
var openUpdateModal = document.getElementById("open_update_modal");
var closeUpdateModal = document.getElementById("close_update_modal");
var updateModal = document.getElementById("update_modal");



document.querySelectorAll('.edit-btn').forEach(btn => {
    btn.addEventListener('click', function () {
        const contactId = this.getAttribute('data-id');

        // AJAX-запрос для получения данных
        fetch(`/contacts/${contactId}/edit`)
            .then(response => response.json())
            .then(data => {
                console.log(data);
                document.getElementById('contactId').value = data.id;
                document.getElementById('contact_surname').value = data.surname;
                document.getElementById('contact_name').value = data.name;
                document.getElementById('contact_email').value = data.email;
                document.getElementById('contact_phone').value = data.phone;
                document.getElementById('contact_firm').value = data.firm;

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

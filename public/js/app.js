$('#phone').mask('+7(999) 999-99-99');

$("#task_button").click(function () {
    $("#tasks_menu").slideToggle(300); // 300ms - длительность анимации
});

$("#burger_task_button").click(function () {
    $("#burger_tasks_menu").slideToggle(300);
});

$("#deal_button").click(function () {
    $("#deals_menu").slideToggle(300); // 300ms - длительность анимации
});

$("#burger_deal_button").click(function () {
    $("#burger_deals_menu").slideToggle(300);
});

$("#save_as").click(function () {
    $("#save_as_menu").toggle();
})

//открытие модального окна добавления
var openModal = document.getElementById("add_task");
var closeModal = document.getElementById("close_modal");
var modal = document.getElementById("modal");

if (openModal) {
    openModal.addEventListener('click', function (event) {
        modal.style.display = "block";
    });
}

if (closeModal) {
    closeModal.addEventListener("click", function (event) {
        modal.style.display = "none";
    });
}


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
                form.action = routeTemplate.replace(':id', data.id);

                updateModal.style.display = 'block';
            });
    });
});

document.querySelectorAll('.edit-btn_task').forEach(btn => {
    btn.addEventListener('click', function () {
        const taskId = this.getAttribute('data-id');
        // AJAX-запрос для получения данных
        fetch(`/tasks/${taskId}/edit`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('taskId').value = data.id;
                document.getElementById('task_subject').value = data.subject;
                document.getElementById('task_description_update').value = data.description;
                document.getElementById('task_date_update').value = data.date || '';
                document.getElementById('task_time_update').value = data.time ? data.time.substring(0, 5) : '';

                document.querySelectorAll('.task_priority_id').forEach(radio => {
                    if (radio.value == data.priority_id) {
                        radio.checked = true;
                    } else {
                        radio.checked = false;
                    }
                });

                document.querySelectorAll('.task_status_id').forEach(radio => {
                    if (radio.value == data.status_id) {
                        radio.checked = true;
                    } else {
                        radio.checked = false;
                    }
                });

                document.querySelectorAll('.task_contact').forEach(option => {
                    if (option.value == data.contact_id) {
                        option.selected = true;
                    } else {
                        option.selected = false;
                    }
                });
                const form = document.getElementById('update_form');
                const routeTemplate = document.getElementById('updateRouteTemplate').value;
                form.action = routeTemplate.replace(':id', data.id);

                updateModal.style.display = 'block';
            });

    });
});

document.querySelectorAll('.edit-btn_deal').forEach(btn => {
    btn.addEventListener('click', function () {
        const dealId = this.getAttribute('data-id');
        // AJAX-запрос для получения данных
        fetch(`/deals/${dealId}/edit`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('dealId').value = data.id;
                document.getElementById('deal_subject').value = data.subject;
                document.getElementById('deal_date_update').value = data.end_date || '';
                document.getElementById('deal_time_update').value = data.end_time;
                document.getElementById('sum_update').value = data.sum;

                document.querySelectorAll('.deal_phase_id').forEach(radio => {
                    if (radio.value == data.phase_id) {
                        radio.checked = true;
                    } else {
                        radio.checked = false;
                    }
                });

                document.querySelectorAll('.task_contact').forEach(option => {
                    if (option.value == data.contact_id) {
                        option.selected = true;
                    } else {
                        option.selected = false;
                    }
                });

                const form = document.getElementById('update_form');
                const routeTemplate = document.getElementById('updateRouteTemplate').value;
                form.action = routeTemplate.replace(':id', data.id);

                updateModal.style.display = 'block';
            });

    });
});

if (updateModal) {
    closeUpdateModal.addEventListener("click", function () {
        updateModal.style.display = "none";
    });
}

window.onclick = function (event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }

    if (event.target == updateModal) {
        updateModal.style.display = "none";
    }
}

var taskDate = document.getElementById("task_date");
var dealDate = document.getElementById("deal_date");

var today = new Date().toISOString().split('T')[0];

if (taskDate) {
    taskDate.setAttribute('min', today);
}

if (dealDate) {
    dealDate.setAttribute('min', today);
}

document.querySelectorAll(".delete_form").forEach(form => {
    form.addEventListener("submit", function (event) {
        if (!confirm('Подтвердите действие на сайте')) {
            event.preventDefault(); // Останавливаем отправку формы
        }
    });
});



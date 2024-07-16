'use strict';

/**
 * Staff
 */
function appointmentGlobalStaffEditModal(el, staff = null) {
    createModalWrapper();
    let userId = $(el).attr('id');

    $("#modal_wrapper").load(`${admin_url}appointly/appointments/modal_internal_crm`, {
        slug: 'create'
    }, function (res) {
        preventDoubleClicksModal();
        if ($('#newAppointmentModal').is(':hidden')) {
            $('#newAppointmentModal').modal({
                show: true
            });
        }
        if (userId) {
            $('#newAppointmentModal').find('select option[value=' + userId + ']').attr('selected', true);
        }
    });
}

/**
 * Open staff edit modal for new appointment with staff
 * @param object el
 */
function appointmentGlobalStaffModal(el) {
    var id = $(el).data('id');
    $("#modal_wrapper").load(`${admin_url}appointly/appointments/modal_internal_crm`, {
        slug: 'edit',
        appointment_id: id
    }, function () {
        preventDoubleClicksModal();
        if ($('#appointmentModal').is(':hidden')) {
            $('#appointmentModal').modal({
                show: true
            });
        }
    });
}

/**
 * Internal Staff Appointment
 */
$('#createInternal').click(function () {
    $("#modal_wrapper").load(`${admin_url}appointly/appointments/modal_internal_crm`, {
        slug: 'create'
    }, function () {
        preventDoubleClicksModal();
        if ($('#newAppointmentModal').is(':hidden')) {
            $('#newAppointmentModal').modal({
                show: true
            });
        }
    });
});

/**
 * Init dates for staff appointments
 */
function initAppointmentScheduledDatesStaff() {
    var dateFormat = app.options.date_format;
    var appointmentDatePickerOptions = {
        dayOfWeekStart: app.options.calendar_first_day,
        minDate: 0,
        format: dateFormat,
        defaultTime: "09:00",
        closeOnDateSelect: 0,
        closeOnTimeSelect: 1,
        validateOnBlur: false
    };

    if (app.options.time_format == 24) {
        dateFormat = dateFormat + ' H:i';
    } else {
        dateFormat = dateFormat + ' g:i A';
        appointmentDatePickerOptions.formatTime = 'g:i A';
    }

    appointmentDatePickerOptions.format = dateFormat;

    $('.appointment-date').datetimepicker(appointmentDatePickerOptions);
}

/**
 * Leads
 */
function appointmentGlobalLeadsContactsModalNew(el) {
    let type = $(el).attr('data-type');

    createModalWrapper();
    $("#modal_wrapper").load(`${admin_url}appointly/appointments/modal_leads_contacts_crm`, {
        user_id: $(el).attr('id'),
        type: type
    }, function (res) {
        preventDoubleClicksModal();
        if ($('#leadAppointmentModal').is(':hidden')) {
            $('#leadAppointmentModal').modal({
                show: true
            });
        }
    });
}

/**
 * Mutual funcions
 */
$('body').on('submit', '#appointment-internal-crm-form, #appointment-leads-contacts-crm-form, #appointment-contacts-crm-form', function (e) {
    e.preventDefault();
});

/**
 * Appointment type checkboxes
 */
$('body').on('change', '#appointment_select_type', function (e) {
    let selectedColorType = $(this).children("option:selected").data('color');
    $('#appointment_color_type').attr('style', 'background-color:' + selectedColorType)
});


/**
 * Clear data after modals are closed
 */
$('.modal').on('hidden.bs.modal', function (e) {
    $('.xdsoft_datetimepicker').remove();
    $(this).removeData();
});

/**
 * Email and SMS Reminders checkboxes
 */
$('body').on('change', '#by_sms, #by_email', function () {
    var anyChecked = $('#by_sms').prop('checked') || $('#by_email').prop('checked');
    if (anyChecked) {
        $('.appointment-reminder').removeClass('hide');
    } else {
        $('.appointment-reminder').addClass('hide');
    }
});

/**
 * Create modal wrapper for appointments
 */
function createModalWrapper() {
    let modalWrapper = document.createElement('div');
    modalWrapper.setAttribute("id", "modal_wrapper");
    document.getElementsByTagName('body')[0].appendChild(modalWrapper);
}

/**
 * Prevenet doubleclicking on modal btn to open multiple backdrop background that block the open modal
 */
function preventDoubleClicksModal() {
    if ($('.modal-backdrop.fade').hasClass('in')) {
        $('.modal-backdrop.fade').remove();
    }
}

function renderCalendar2(month, year) {
    const calendar = document.getElementById('calendar');
    const currentMonthYear = document.getElementById('current-month-year');
    const prevMonthButton = document.getElementById('prev-month');
    const nextMonthButton = document.getElementById('next-month');
    const daysOfWeek = ['MON', 'TUE', 'WED', 'THU', 'FRI', 'SAT', 'SUN'];
    const monthNames = [
        'January', 'February', 'March', 'April', 'May', 'June',
        'July', 'August', 'September', 'October', 'November', 'December'
    ];
    let date = new Date();
    calendar.innerHTML = '';
    const firstDayOfMonth = new Date(year, month, 1).getDay();
    const daysInMonth = new Date(year, month + 1, 0).getDate();

    currentMonthYear.textContent = `${monthNames[month]} ${year}`;

    // Add days of week headers
    daysOfWeek.forEach(day => {
        const dayElement = document.createElement('div');
        dayElement.className = 'disabled';
        dayElement.textContent = day;
        calendar.appendChild(dayElement);
    });

    // Add empty elements for days before the first day of the month
    const startDay = (firstDayOfMonth + 6) % 7; // Adjust for Sunday start
    for (let i = 0; i < startDay; i++) {
        const emptyElement = document.createElement('div');
        emptyElement.className = 'disabled';
        calendar.appendChild(emptyElement);
    }

    // Add days of the month
    for (let i = 1; i <= daysInMonth; i++) {
        const dayElement = document.createElement('div');
        dayElement.textContent = i;
        dayElement.setAttribute('data-day', i);
        calendar.appendChild(dayElement);

        dayElement.addEventListener('click', function () {
            document.querySelectorAll('.calendar div[data-day]').forEach(d => d.classList.remove('selected'));
            this.classList.add('selected');
        });
    }
}


<?php defined('BASEPATH') or exit('No direct script access allowed');
// Means module is disabled
if (!function_exists('get_appointment_types')) {
    access_denied();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="user-scalable=no, width=device-width, initial-scale=1, maximum-scale=1">
    <title><?php echo hooks()->apply_filters('appointments_form_title', _l('appointment_create_new_appointment')); ?>
    </title>

    <?php app_external_form_header($form); ?>

    <link href="<?= module_dir_url('appointly', 'assets/css/appointments_external_form.css'); ?>" rel="stylesheet"
        type="text/css">
</head>
<style>
      div#lead-appointments-content #wrapper{
        margin: 0;
    }
    div#lead-appointments-content .container{
    width: inherit !important;
    background: #ffff;
}

</style>

<body class="appointments-external-form" <?php if (is_rtl(true)) {
    echo ' dir="rtl"';
} ?>>
    <?php
    $clientUserData = $this->session->userdata();
    applyAdditionalCssStyles($clientUserData);
    ?>
    <div id="wrapper">
        <div id="content" class = "thankyou-in-lead">
            <div id="response"></div>
            <div class="container flex">
                <?php if (!isset($booking_page['id'])) { ?>
                    <p>Booking page not found</p>
                <?php } else { ?>

                    <?php echo form_open('appointly/appointments_public/create_external_appointment_booking_page/' . $booking_page['url'], ['id' => 'appointments-form']); ?>

                    <input type="text" hidden name="rel_type" value="booking_page">
                    <input type="text" hidden name="booking_page_id" value="<?= $booking_page['id'] ?>">

                    <div class="row">
                        <div class="mbot20 <?= ($this->input->get('col')) ? $this->input->get('col') : 'col-md-12'; ?>">

                            <div class="row">
                                <div class="col-md-4" style="margin-bottom:15px">
                                    <br>
                                    <div id="logo" class="tw-py-2 tw-px-2 tw-max-h-[100px] tw-max-w-[200px] tw-flex tw-items-center">
                                        <?php echo get_company_logo(get_admin_uri() . '/', '!tw-mt-0') ?>
                                    </div>
                                    <br>
                                    <hr>
                                    <h3 style="font-size:35px"><?= $booking_page['name'] ?></h3>
                                    <?php if (isset($booking_page['duration_minutes'])) { ?>
                                        <p style="color:#a1a1a1"><i class="far fa-clock"
                                                style="color: #a1a1a1; margin-right:5px;"></i><?= $booking_page['duration_minutes'] ?> min</p>
                                    <?php } ?>
                                    <span style="color:#a1a1a1">Description: </span>
                                    <span>
                                        <strong> <?= $booking_page['description'] ?></strong>
                                    </span>

                                    <div id="appointmentsContainer"></div>

                                </div>

                                <div id="step1" class="mbot20 col-md-8" style="border-left: 1px solid #eee">

                                    <?php $appointment_types = get_appointment_types();

                                    if (count($appointment_types) > 0) { ?>
                                        <div class="form-group appointment_type_holder">
                                            <label for="appointment_select_type"
                                                class="control-label"><?= _l('appointments_type_heading'); ?></label>
                                            <select class="form-control selectpicker" name="type_id"
                                                id="appointment_select_type">
                                                <option value=""><?= _l('dropdown_non_selected_tex'); ?></option>
                                                <?php foreach ($appointment_types as $app_type) { ?>
                                                    <option class="form-control" data-color="<?= $app_type['color']; ?>"
                                                        value="<?= $app_type['id']; ?>"><?= $app_type['type']; ?>
                                                    </option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div class=" clearfix mtop15"></div>
                                    <?php } ?>
                                            <div class="form-group">
                                                <div class="g-recaptcha"
                                                    data-sitekey="<?php echo get_option('recaptcha_site_key'); ?>"></div>
                                                <div id="recaptcha_response_field" class="text-danger"></div>

                                                <h2 class="heading">Select Date and Time</h2>
                                                <div style="display:flex">
                                                    <div class="calendar-container">
                                                        <div class="month-switch">
                                                            <button type="button" id="prev-month">&lt;</button>
                                                            <span id="current-month-year"></span>
                                                            <button type="button" id="next-month">&gt;</button>
                                                        </div>
                                                        <div class="calendar" id="calendar">
                                                            <!-- Calendar days will be generated here -->
                                                        </div>
                                                    </div>

                                                    <div class="timeslots" id="timeslots" style="display:none;">

                                                        <p id="selected-date"></p>
                                                        <p id="timelabel" class="timelabel"></p>
                                                        <div id="timeslot-list" class="scroll"
                                                            style="overflow-y: auto;height: 190px;}"></div>
                                                    </div>
                                                </div>

                                                <div class="pull-right">
                                                    <button type="button" id="nextButton" onclick="nextStep()"
                                                        class="btn btn-primary"><?php echo _l('appointment_next'); ?></button>

                                                </div>
                                            </div>
                                </div>
                                <div id="step2" style="display: none;" class="col-md-7">

                                    <div class="appointment-header"><?php hooks()->do_action('appointly_form_header'); ?>
                                    </div>


                                    <div>
                                        <h4><?= _l('appointment_create_new_appointment'); ?></h4>
                                    </div>

                                    <br>
                                    <?php $subjects = get_subjects();

                                    if (count($subjects) > 0) { ?>
                                        <div class="form-group appointment_type_holder">
                                            <label for="appointment_select_type"
                                                class="control-label"><?= _l('appointment_subject'); ?></label>
                                            <select class="form-control selectpicker" name="subject"
                                                id="appointment_select_type">
                                                <option value=""><?= _l('dropdown_non_selected_tex'); ?></option>
                                                <?php foreach ($subjects as $app_type) { ?>
                                                    <option class="form-control" value="<?= $app_type['id']; ?>">
                                                        <?= $app_type['subject']; ?>
                                                    </option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div class=" clearfix mtop15"></div>
                                        <br>
                                    <?php } ?>


                                    <br>
                                    <div class="form-group">
                                        <label for="name"><?= _l('appointment_full_name'); ?></label>
                                        <input type="text" class="form-control"
                                            value="<?= (isset($clientUserData['client_logged_in'])) ? get_contact_full_name($clientUserData['contact_user_id']) : ''; ?>"
                                            name="name" id="name">
                                    </div>
                                    <div class="form-group">
                                        <label for="email"><?= _l('appointment_your_email'); ?></label>
                                        <input type="email" class="form-control"
                                            value="<?= (isset($clientUserData['client_logged_in'])) ? get_contact_detail($clientUserData['contact_user_id'], 'email') : ''; ?>"
                                            name="email" id="email">
                                    </div>


                                    <?php $appointment_types = get_appointment_types();

                                    if (count($appointment_types) > 0) { ?>
                                        <div class="form-group appointment_type_holder">
                                            <label for="appointment_select_type"
                                                class="control-label"><?= _l('appointments_type_heading'); ?></label>
                                            <select class="form-control selectpicker" name="type_id"
                                                id="appointment_select_type">
                                                <option value=""><?= _l('dropdown_non_selected_tex'); ?></option>
                                                <?php foreach ($appointment_types as $app_type) { ?>
                                                    <option class="form-control" data-color="<?= $app_type['color']; ?>"
                                                        value="<?= $app_type['id']; ?>"><?= $app_type['type']; ?>
                                                    </option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div class=" clearfix mtop15"></div>
                                    <?php } ?>



                                    <div class="form-group">
                                        <label for="phone"><?= _l('appointment_phone'); ?>
                                            (Ex: <?= _l('appointment_your_phone_example'); ?>)</label>
                                        <input type="text" class="form-control"
                                            value="<?= (isset($clientUserData['client_logged_in'])) ? get_contact_detail($clientUserData['contact_user_id'], 'phonenumber') : ''; ?>"
                                            name="phone" id="phone">
                                    </div>

                                    <label
                                        for="address"><?= _l('appointment_meeting_location') . ' ' . _l('appointment_optional'); ?></label>
                                    <input type="text" class="form-control" value="" name="address" id="address">

                                    <?php $rel_id = (isset($bookings) ? $bookings->id : false); ?>
                                    <?php echo render_custom_fields('bookings', $rel_id); ?>

                                    <!-- <?php
                                    $rel_cf_id = (isset($appointment) ? $appointment['apointment_id'] : false);
                                    echo render_custom_fields('bookings', $rel_cf_id);
                                    ?> -->
                                    <?php if (
                                        get_option('recaptcha_secret_key') != ''
                                        && get_option('recaptcha_site_key') != ''
                                        && $booking_page['appointly_appointments_recaptcha'] == 1
                                    ) { ?>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <div class="g-recaptcha"
                                                        data-sitekey="<?php echo get_option('recaptcha_site_key'); ?>"></div>
                                                    <div id="recaptcha_response_field" class="text-danger"></div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>

                                    <div class="pull-right">
                                        <button type="button" id="backButton" onclick="prevStep()"
                                            class="btn btn-primary"><?php echo _l('appointment_booking_back'); ?></button>
                                        <button type="submit" id="form_submit"
                                            class="btn btn-primary"><?php echo _l('appointment_submit'); ?></button>
                                    </div>
                                    <div class="clearfix mtop15"></div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <?php echo form_close(); ?>
                <?php } ?>
            </div>
        </div>
    </div>
    <?php
    app_external_form_footer($form);
    ?>

    <?php if (isset($form)): ?>
        <script>
            app.locale = "<?= get_locale_key($form->language); ?>";
        </script>
    <?php endif; ?>

    <!-- Javascript functionality -->
    <?php require ('modules/appointly/assets/js/appointments_external_form_booking.php'); ?>

    <!-- If callbacks is enabled load on appointments external form -->
    <?php if (isset($booking_page['callbacks_mode_enabled']) && $booking_page['callbacks_mode_enabled'] == 1)
        require ('modules/appointly/views/forms/callbacks_form.php'); ?>

    <script>

        if(typeof calendar == undefined)
            var calendar = document.getElementById('calendar');
        else document.getElementById('calendar');
        if(typeof currentMonthYear == undefined)
            var currentMonthYear = document.getElementById('current-month-year');
        else currentMonthYear = document.getElementById('current-month-year');
        if(typeof prevMonthButton == undefined)
            var prevMonthButton = document.getElementById('prev-month');
        else prevMonthButton = document.getElementById('prev-month');
        if(typeof nextMonthButton == undefined)
            var nextMonthButton = document.getElementById('next-month');
        else nextMonthButton = document.getElementById('next-month');
        if(typeof timeslots == undefined)
            var timeslots = document.getElementById('timeslots');
        else timeslots = document.getElementById('timeslots');
        if(typeof timeslotList == undefined)
            var timeslotList = document.getElementById('timeslot-list');
        else timeslotList = document.getElementById('timeslot-list');
        if(typeof selectedDateElem == undefined)
            var selectedDateElem = document.getElementById('selected-date');
        else selectedDateElem = document.getElementById('selected-date');
        if(typeof selectedLabel == undefined)
            var selectedLabel = document.getElementById('timelabel');
        else selectedLabel = document.getElementById('timelabel');
        var selectedDateTime = '';
        if(typeof selectedLabel == undefined)
            var date = new Date();
        else date = new Date();
        if(typeof selectedLabel == undefined)
            var simultaneous_appointments='';
        simultaneous_appointments = '<?= $booking_page['simultaneous_appointments'] ?>'==''?1:<?= $booking_page['simultaneous_appointments'] ?>;
        // Array of month names (zero-based index)
        var monthNames = ['January', 'February', 'March', 'April', 'May', 'June', 'July',
            'August', 'September', 'October', 'November', 'December'];

        var daysOfWeek = ['MON', 'TUE', 'WED', 'THU', 'FRI', 'SAT', 'SUN'];

        var no_of_appointments = [];
        // used to store dates selected
        var appointmentDates = [];
        // do not change this
        var total_appointments = 1;


        function createList() {
            // Clear previous content in the appointments container div
            var appointmentsContainer = document.getElementById('appointmentsContainer');
            appointmentsContainer.innerHTML = '';

            // Create a new table element
            var table = document.createElement('table');
            table.classList.add('appointments-table'); // Add a class for styling if needed
            table.style.display = 'none'
            // Create table header row
            var headerRow = table.createTHead().insertRow();
            headerRow.innerHTML = '<th>#</th><th>Date/Time</th>';

            // Create table body rows
            var tbody = table.createTBody();
            no_of_appointments.forEach(function (appointment, index) {
                var row = tbody.insertRow();
                row.setAttribute('appointment-index', index);
                row.innerHTML = `<td>${index + 1}</td><td>${appointment.dateStr}</td><td><span onclick='removeAppointment(${index})' class="close">&times;</span><input type="hidden" name="dates[]" value="${appointment.dateFormatted}"/></td>`;
            });

            // Append the table to the appointments container div
            appointmentsContainer.appendChild(table);
        }
        function removeAppointment(index) {
            let elem = $($(`.appointments-table tr[appointment-index=${index}]`)[0]);
            let content = elem.children()[1].textContent;
            no_of_appointments = no_of_appointments.filter(x => x.dateStr != content);
            elem.remove();
        }



        function renderCalendar() {
            var calendar = document.getElementById('calendar');
            var currentMonthYear = document.getElementById('current-month-year');

            var year = date.getFullYear();
            var month = date.getMonth();
            var firstDayOfMonth = new Date(year, month, 1).getDay();
            var daysInMonth = new Date(year, month + 1, 0).getDate();

            currentMonthYear.textContent = `${monthNames[month]} ${year}`;

            // Add days of week headers
            daysOfWeek.forEach(day => {
                var dayElement = document.createElement('div');
                dayElement.className = 'weekdays';
                dayElement.textContent = day;
                calendar.appendChild(dayElement);
            });

            // Add empty elements for days before the first day of the month
            var startDay = (firstDayOfMonth + 6) % 7; // Adjust for Sunday start
            for (let i = 0; i < startDay; i++) {
                var emptyElement = document.createElement('div');
                emptyElement.className = 'disabled';
                calendar.appendChild(emptyElement);
            }


            // Add days of the month
            for (let i = 1; i <= daysInMonth; i++) {

                var dayElement = document.createElement('div');
                dayElement.textContent = i;
                dayElement.setAttribute('data-day', i);
                if (<?= $booking_page['appointments_disable_weekends'] ?> && (daysOfWeek[(startDay + i - 1) % 7] == 'SUN' || daysOfWeek[(startDay + i - 1) % 7] == 'SAT')) {
                    dayElement.className = 'disabled';
                }
                if (<?= $booking_page['appointments_show_past_times'] ?>) {
                    var currentMonthYear = $($('#current-month-year')[0]).text()
                    var month = currentMonthYear.split(' ')[0];
                    var monthNumber = monthNames.indexOf(month) + 1;
                    var year = currentMonthYear.split(' ')[1];
                    let selectedDate = new Date(`${year}-${monthNumber}-${i}`);
                    if (selectedDate.getDate() < (new Date()).getDate())
                        dayElement.className = 'disabled';
                }
                calendar.appendChild(dayElement);

                dayElement.addEventListener('click', function () {
                    document.querySelectorAll('.calendar div[data-day]').forEach(d => d.classList.remove('selected'));
                    if (this.classList.contains('disabled')) return;
                    this.classList.add('selected');

                    // Display timeslots and update the selected date
                    var selectedDate = `${daysOfWeek[(startDay + i - 1) % 7]}, ${i} ${month}`;
                    selectedDateElem.textContent = selectedDate;
                    selectedLabel.textContent = "View times where you are available";
                    timeslots.style.display = 'flex';
                    // Load time slots dynamically
                    loadTimeSlots(selectedDate);
                });
            }
        }



        function submitDateTime(dateTime) {

        }

        prevMonthButton.addEventListener('click', function () {
            date.setMonth(date.getMonth() - 1);
            renderCalendar();
        });

        nextMonthButton.addEventListener('click', function () {
            date.setMonth(date.getMonth() + 1);
            renderCalendar();
        });

        renderCalendar();

    </script>
    <script>

        if(typeof calendarDays == undefined)
                var calendarDays = document.querySelectorAll('.calendar div[data-day]');
        else  calendarDays = document.querySelectorAll('.calendar div[data-day]');
        calendarDays.forEach(day => {
            day.addEventListener('click', function () {
                // Remove 'selected' class from all days
                calendarDays.forEach(d => d.classList.remove('selected'));
                // Add 'selected' class to clicked day
                this.classList.add('selected');
            });
        });
    </script>
    <script>
        function nextStep() {
            var currentMonthYear = $($('#current-month-year')[0]).text()
            var month = currentMonthYear.split(' ')[0];
            var monthNumber = monthNames.indexOf(month) + 1;
            var year = currentMonthYear.split(' ')[1];
            var date = $($('div[data-day].selected')[0]).text();
            var slot = $($('.timeslot.selected')[0]).text();
            if (slot == '' || no_of_appointments.filter(x => x.dateStr == selectedDateTime.date).length > 0) {
                return;
            }

            no_of_appointments.push({ dateStr: selectedDateTime.date, dateFormatted: `${year}-${monthNumber}-${date} ${slot}:00` });
            createList();
            document.getElementById('step1').style.display = 'none';
            document.getElementById('step2').style.display = 'block';
        }

        function prevStep() {
            no_of_appointments = [];
            createList();
            document.getElementById('step1').style.display = 'block';
            document.getElementById('step2').style.display = 'none';
        }
    </script>


</body>

</html>
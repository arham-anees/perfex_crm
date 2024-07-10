<?php defined('BASEPATH') or exit('No direct script access allowed');
// Means module is disabled
if (!function_exists('get_appointment_types')) {
    access_denied();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="user-scalable=no, width=device-width, initial-scale=1, maximum-scale=1">
    <title><?php echo hooks()->apply_filters('appointments_form_title', _l('appointment_create_new_appointment')); ?>
    </title>

    <?php app_external_form_header($form); ?>

    <link href="<?= module_dir_url('appointly', 'assets/css/appointments_external_form.css'); ?>" rel="stylesheet"
        type="text/css">
</head>

<body class="appointments-external-form" <?php if (is_rtl(true)) {
    echo ' dir="rtl"';
} ?>>
    <?php
    $clientUserData = $this->session->userdata();
    applyAdditionalCssStyles($clientUserData);
    ?>
    <div id="wrapper">
        <div id="content">
            <div class="container">

                <div id="response"></div>

                <?php echo form_open('appointly/appointments_public/create_external_appointment', ['id' => 'appointments-form']); ?>

                <input type="text" hidden name="rel_type" value="external">

                <div class="row main_wrapper">
                    <div class=" <?= 'col-md-4'; ?>">
                        <div class="logo">
                            <img src="logo.png" alt="Pôle Démarches" style="width:100%;">
                        </div>
                        <hr>
                        <br>

                        <p>Pole Demarches</p>
                        <h3>Validation of Appointment by Video</h3>
                        <p><strong>Duration:</strong> 10 min</p>
                        <p>Online Conference Confirmation</p>
                        <p><strong>Cost:</strong> 89 EUR</p>
                        <p>2:20 - 3:20, Monday 1 July, 2024</p>
                        <p>Pacafic Time - USA and Canada</p>
                        <br>
                        <p><strong>Documents to Provide:</strong></p>
                        <ul>
                            <li>Identity Documents</li>
                            <li>Proof of Residence</li>
                            <li>Any document related to your situation</li>
                        </ul>


                    </div>

                    <div id="step1" class="mbot20 <?= 'col-md-8'; ?>">

                        <h2>Select Date and Time</h2>
                        <div class="flex">
                            <div class="calendar-container">
                                <div class="month-switch">
                                    <button type="button" id="prev-month">&lt;</button>
                                    <span id="current-month-year"></span>
                                    <button type="button" id="next-month">&gt;</button>
                                </div>
                                <div class="calendar" id="calendar">
                                    <!-- Calendar days will be generated here -->
                                </div>
                                <div class="timezone">
                                    <p><strong>Time Zone:</strong></p>
                                    <p>USA and Canada (10:53)</p>
                                </div>
                            </div>

                            <div class="timeslots" id="timeslots">
                                <p id="selected-date"></p>
                                <div id="timeslot-list"></div>
                            </div>
                        </div>
                        <div class="pull-right">
                            <button type="button" id="nextButton" onclick="nextStep()"
                                class="btn btn-primary"><?php echo _l('next'); ?></button>
                        </div>
                    </div>

                    <div id="step2" style="display: none;" class="<?= 'col-md-8'; ?>">

                        <div class="appointment-header"><?php hooks()->do_action('appointly_form_header'); ?></div>


                        <div>
                            <h4><?= _l('appointment_create_new_appointment'); ?></h4>
                        </div>

                        <br>
                        <?php $subjects = get_subjects();

                        if (count($subjects) > 0) { ?>
                            <div class="form-group appointment_type_holder">
                                <label for="appointment_select_type"
                                    class="control-label"><?= _l('appointment_subject'); ?></label>
                                <select class="form-control selectpicker" name="type_id" id="appointment_select_type">
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

                        <?php echo render_textarea('description', 'appointment_description', '', ['rows' => 5]); ?>

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
                                <select class="form-control selectpicker" name="type_id" id="appointment_select_type">
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

                        <?php echo render_datetime_input('date', 'appointment_date_and_time', '', ['readonly' => "readonly", 'disabled' => 'disabled'], [], '', 'appointment-date'); ?>
                        <div class="form-group"></div>

                        <label
                            for="address"><?= _l('appointment_meeting_location') . ' ' . _l('appointment_optional'); ?></label>
                        <input type="text" class="form-control" value="" name="address" id="address">

                        <?php $rel_id = (isset($bookings) ? $bookings->id : false); ?>
                        <?php echo render_custom_fields('bookings', $rel_id); ?>

                        <?php
                        $rel_cf_id = (isset($appointment) ? $appointment['apointment_id'] : false);
                        echo render_custom_fields('appointly', $rel_cf_id);
                        ?>
                        <?php if (
                            $booking_page['recaptcha_secret_key'] != ''
                            && $booking_page['recaptcha_site_key'] != ''
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
                                class="btn btn-primary"><?php echo _l('back'); ?></button>
                            <button type="submit" id="form_submit"
                                class="btn btn-primary"><?php echo _l('appointment_submit'); ?></button>
                        </div>
                        <div class="clearfix mtop15"></div>
                    </div>
                </div>
                <?php echo form_close(); ?>
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
    <?php require ('modules/appointly/assets/js/appointments_external_form.php'); ?>

    <!-- If callbacks is enabled load on appointments external form -->
    <?php if (get_option('callbacks_mode_enabled') == 1)
        require ('modules/appointly/views/forms/callbacks_form.php'); ?>

    <script>

        const calendar = document.getElementById('calendar');
        const currentMonthYear = document.getElementById('current-month-year');
        const prevMonthButton = document.getElementById('prev-month');
        const nextMonthButton = document.getElementById('next-month');
        const timeslots = document.getElementById('timeslots');
        const timeslotList = document.getElementById('timeslot-list');
        const selectedDateElem = document.getElementById('selected-date');

        let date = new Date();

        const daysOfWeek = ['MON', 'TUE', 'WED', 'THU', 'FRI', 'SAT', 'SUN'];
        const monthNames = [
            'January', 'February', 'March', 'April', 'May', 'June',
            'July', 'August', 'September', 'October', 'November', 'December'
        ];

        function renderCalendar() {
            calendar.innerHTML = '';
            const year = date.getFullYear();
            const month = date.getMonth();
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

                    // Display timeslots and update the selected date
                    const selectedDate = `${daysOfWeek[(startDay + i - 1) % 7]}, ${i} ${monthNames[month]}`;
                    selectedDateElem.textContent = selectedDate;
                    timeslots.style.display = 'flex';
                    // Load time slots dynamically
                    loadTimeSlots(selectedDate);
                });
            }
        }
        function loadTimeSlots(date) {
            // This is a placeholder for dynamic slot loading logic.
            // You can replace it with an actual API call to fetch available time slots.
            const availableTimeSlots = ['02:40', '02:50', '03:10', '03:20', '03:40', '03:50', '04:00', '04:20'];

            timeslotList.innerHTML = '';
            availableTimeSlots.forEach(slot => {
                const slotElement = document.createElement('div');
                slotElement.className = 'timeslot';
                slotElement.textContent = slot;
                timeslotList.appendChild(slotElement);

                slotElement.addEventListener('click', function () {
                    document.querySelectorAll('.timeslot').forEach(t => t.classList.remove('selected'));
                    this.classList.add('selected');

                    // When a timeslot is selected, submit the date and time
                    const selectedDateTime = {
                        date: `${selectedDateElem.textContent.trim()} ${slot}`

                    };

                    // Send the selectedDateTime to the server via fetch or AJAX
                    submitDateTime(selectedDateTime);
                });
            });
        }

        function submitDateTime(dateTime) {
            console.log(dateTime);
            const dataInput = document.getElementById('date');
            dataInput.value = dateTime.date
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

        const calendarDays = document.querySelectorAll('.calendar div[data-day]');

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
            document.getElementById('step1').style.display = 'none';
            document.getElementById('step2').style.display = 'block';
        }

        function prevStep() {
            document.getElementById('step1').style.display = 'block';
            document.getElementById('step2').style.display = 'none';
        }
    </script>

</body>

</html>
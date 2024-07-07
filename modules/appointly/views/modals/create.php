<div class="modal fade" id="newAppointmentModal">
    <?php
    $rel_type = 'lead';
    $rel_id = '';
    ?>
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="<?= _l('close'); ?>">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><?php echo _l('appointment_new_appointment'); ?></h4>
            </div>
            <input type="hidden" id="ms-access-token" value="" />
            <input type="hidden" id="ms-outlook-event-id" value="" />
            <?php echo form_open('appointly/appointments/create', ['id' => 'appointment-form']); ?>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="checkbox pull-right mrtopmin5" id="showOutlookCheckbox">
                            <input type="checkbox" id="outlook-checkbox">
                            <label data-toggle="tooltip" for="outlook-checkbox" title="<?= _l('appointment_add_to_outlook'); ?>" for="outlook">
                                <?= _l('appointment_add_to_outlook'); ?>
                            </label>
                        </div>
                        <?php if (appointlyGoogleAuth() && get_option('appointly_google_client_secret')) : ?>
                            <div class="checkbox pull-right mright15 mtop1">
                                <input type="checkbox" name="google" id="google" checked>
                                <label data-toggle="tooltip" title="<?= _l('appointment_add_to_google_calendar'); ?>" for="google">
                                    <?= _l('appointment_add_to_google_calendar'); ?>
                                </label>
                            </div>
                        <?php endif; ?>
<div class="row-no-gutters">
    <div class="col-lg-12">
   




    <style>

    .container {
        display: flex;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        background-color: #fff;
        border-radius: 8px;
        overflow: hidden;
        width: inherit;
    }
    .left, .right {
        padding: 20px;
    }
    .left {
        width: 40%;
        border-right: 1px solid #e0e0e0;
    }
    .right {
        width: 60%;
        display:flex;
    }
    .right .calendar-container{
        width:280px;
    }
    h1 {
        font-size: 24px;
        margin-bottom: 20px;
    }
    p {
        margin: 10px 0;
    }
    .calendar {
        display: flex;
        flex-wrap: wrap;
        margin-top: 10px;
    }
    .calendar div {
        width: 30px;
        height: 30px;
        margin: 5px;
        display: flex;
        justify-content: center;
        align-items: center;
        border-radius: 50%;
        background-color: #e0e0e0;
        cursor: pointer;
    }
    .calendar div.selected {
        background-color: #4caf50;
        color: #fff;
    }
    .calendar div.disabled {
        background-color: #f0f0f0;
        cursor: not-allowed;
    }
    .calendar div.disabled:empty {
        background: transparent;
    }
    .timezone {
        margin-top: 20px;
        width: calc(100% - 290px)
    }
    .month-switch {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .month-switch button {
        background-color: transparent;
        border: none;
        cursor: pointer;
        font-size: 16px;
    }
    .timeslots {
        display: none;
        flex-direction: column;
        align-items: center;
        margin-top: 20px;
    }
    .timeslots button {
        background-color: #007bff;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 20px;
        cursor: pointer;
        margin-bottom: 10px;
    }
    .timeslot {
        border: 1px solid #007bff;
        border-radius: 5px;
        color: #007bff;
        margin: 5px 0;
        padding: 10px;
        text-align: center;
        cursor: pointer;
    }
    .timeslot.selected {
        background-color: #007bff;
        color: white;
    }
</style>

<div class="container">
    <div class="left">
        <img src="logo.png" alt="Pôle Démarches" style="width:100%;">
        <h1>Validation of Appointment by Video</h1>
        <p><strong>Duration:</strong> 10 min</p>
        <p><strong>Cost:</strong> 89 EUR</p>
        <p><strong>Documents to Provide:</strong></p>
        <ul>
            <li>Identity Documents</li>
            <li>Proof of Residence</li>
            <li>Any document related to your situation</li>
        </ul>
    </div>
    <div class="right">
        <div>
            <h2>Select Date and Time</h2>
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
                    <p>Central European Time (19:50)</p>
                </div>
            </div>
        </div>
        <div class="timeslots" id="timeslots">
            <p id="selected-date"></p>
            <div id="timeslot-list"></div>
        </div>
    </div>
</div>

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
                });
            });
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







    </div>
                        </div>
                        <!-- change the design -->
                        <?php echo render_input('subject', 'appointment_subject'); ?>
                        <?php echo render_textarea('description', 'appointment_description', '', ['rows' => 5]); ?>
                        <div class="form-group select-placeholder">
                            <label for="rel_type" class="control-label"><?php echo _l('proposal_related'); ?></label>
                            <select name="rel_type" id="rel_type" class="selectpicker" data-width="100%" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
                                <option value=""></option>
                                <option id="lead_related" value="lead_related"><?= _l('lead'); ?></option>
                                <option id="external" value="external"><?= _l('appointments_source_external_label'); ?></option>
                                <option id="internal" value="internal"><?= _l('appointment_source_internal'); ?></option>
                            </select>
                        </div>
                        <div class="form-group select-placeholder hide" id="rel_id_wrapper">
                            <input type="text" hidden name="rel_lead_type" id="rel_lead_type" value="leads">
                            <label for="rel_id"><?= _l('leads'); ?></label>
                            <div id="rel_id_select">
                                <select name="rel_id" id="rel_id" class="ajax-search" data-width="100%" data-live-search="true" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
                                    <?php
                                    if ($rel_id != '' && $rel_type != '') {
                                        $rel_data = get_relation_data($rel_type, $rel_id);
                                        $rel_val = get_relation_values($rel_data, $rel_type);
                                        echo '<option value="' . $rel_val['id'] . '" selected>' . $rel_val['name'] . '</option>';
                                    } ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group hidden" id="select_contacts">
                            <?php echo render_select('contact_id', $contacts, ['contact_id', ['firstname', 'lastname', 'company']], 'appointment_select_single_contact', '', [], [], '', '', true); ?>
                        </div>
                        <div class="form-group hidden" id="div_name">
                            <label for="name"><?= _l('appointment_name'); ?></label>
                            <input type="text" value="" class="form-control" name="name" id="name">
                        </div>
                        <div class="form-group hidden" id="div_email">
                            <label for="email"><?= _l('appointment_email'); ?></label>
                            <input type="email" value="" class="form-control" name="email" id="email">
                        </div>
                        <div class="form-group hidden" id="div_phone">
                            <label for="phone"><?= _l('appointment_phone'); ?> (Ex: <?= _l('appointment_your_phone_example'); ?>) </label>
                            <input type="text" value="" class="form-control" name="phone" id="phone">
                        </div>
                        <div class="pull-right available_times_labels">
                            <span class="available_time_info">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                            <?= _l('appointment_available_hours'); ?>
                            <span class="busy_time_info">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                            <?= _l('appointment_busy_hours'); ?>
                            <?php if (appointlyGoogleAuth()) : ?>
                                <span class="busy_time_info_google">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                                <?= _l('appointments_google_calendar'); ?>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-6 no-padding">
                            <?php echo render_datetime_input('date', 'appointment_date_and_time', '', ['readonly' => "readonly"], [], '', 'appointment-date'); ?>
                        </div>
                        <div class="clearfix"></div>

                        <div class="form-group">
                            <label for="address"><?= _l('appointment_meeting_location') . ' ' . _l('appointment_optional'); ?></label>
                            <input type="text" class="form-control" name="address" id="address">
                        </div>

                        <div class="form-group">
                            <?php echo render_select('attendees[]', $staff_members, ['staffid', ['firstname', 'lastname']], 'appointment_select_attendees', [get_staff_user_id()], ['multiple' => true], [], '', '', false); ?>
                        </div>

                        <?php $appointment_types = get_appointment_types();
                        if (count($appointment_types) > 0) { ?>
                            <div class="form-group appointment_type_holder">
                                <label for="appointment_select_type" class="control-label"><?= _l('appointments_type_heading'); ?></label>
                                <select class="form-control selectpicker" name="type_id" id="appointment_select_type">
                                    <option value=""><?= _l('dropdown_non_selected_tex'); ?></option>
                                    <?php foreach ($appointment_types as $app_type) { ?>
                                        <option class="form-control" data-color="<?= $app_type['color']; ?>" value="<?= $app_type['id']; ?>"><?= $app_type['type']; ?>
                                        </option>
                                    <?php } ?>
                                </select>
                                <small id="appointment_color_type" class="pull-right appointment_color_type" style="background:#e1e6ec"></small>
                            </div>
                            <div class=" clearfix mtop15"></div>
                            <hr>
                        <?php }
                        // recurring
                        $this->load->view('view_includes/recurring_wrapper');
                        // custom fields
                        $rel_cf_id = (isset($appointment) ? $appointment['appointment_id'] : false);
                        echo render_custom_fields('appointly', $rel_cf_id);
                        ?>
                        <div class="form-group mtop10">
                            <div class="row">
                                <div class="col-md-12 mbot5">
                                    <?= _l('appointment_modal_notification_info'); ?>
                                </div>
                                <div class="col-md-6">
                                    <div class="checkbox">
                                        <input type="checkbox" name="by_sms" id="by_sms">
                                        <label for="by_sms"><?= _l('appoontment_sms_notification'); ?></label>
                                    </div>
                                    <div class="checkbox">
                                        <input type="checkbox" name="by_email" id="by_email">
                                        <label for="by_email"><?= _l('appoontment_email_notification'); ?></label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group appointment-reminder hide">
                            <div class="row">
                                <div class="col-md-12">
                                    <label for="reminder_before"><?= _l('appointments_reminder_time_value'); ?></label><br>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <input type="number" class="form-control" name="reminder_before" value="" id="reminder_before">
                                        <span class="input-group-addon"><i class="fa fa-question-circle" data-toggle="tooltip" data-title="<?php echo _l('reminder_notification_placeholder'); ?>"></i></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <select name="reminder_before_type" id="reminder_before_type" class="selectpicker" data-width="100%">
                                        <option value="minutes"><?php echo _l('minutes'); ?></option>
                                        <option value="hours"><?php echo _l('hours'); ?></option>
                                        <option value="days"><?php echo _l('days'); ?></option>
                                        <option value="weeks"><?php echo _l('weeks'); ?></option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <span class="font-medium pleft5"><?= _l('appointment_client_notes'); ?></span>
                            </div>
                            <div class="col-md-12 mtop8">
                                <textarea name="notes" id="" cols="30" rows="10"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default close_btn" data-dismiss="modal"><?php echo _l('close'); ?></button>
                <button type="submit" class="btn btn-info"><?php echo _l('submit'); ?></button>
            </div>
            <?php echo form_close(); ?>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<?php require('modules/appointly/assets/js/modals/create_js.php'); ?>

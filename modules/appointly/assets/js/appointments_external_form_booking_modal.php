<script>
    var form_id = "#appointments-form";
        var allowedHours = <?= json_encode(json_decode($booking_page['appointly_available_hours'])); ?>;
        var appMinTime = <?= $booking_page['appointments_show_past_times']; ?>;
        var appWeekends = <?= ($booking_page['appointments_disable_weekends']) ? "[0, 6]" : "[]"; ?>;

        var busyDates = [];
        var todaysDate = new Date();
        var currentDate = todaysDate.getFullYear() + "-" + (((todaysDate.getMonth() + 1) < 10) ? "0" : "") + (todaysDate.getMonth() + 1 + "-" + ((todaysDate.getDate() < 10) ? "0" : "") + todaysDate.getDate());

    
        <?php
        if (function_exists('is_client_logged_in')) {
        if (is_client_logged_in()) { ?>

        var phone = "",
            full_name = "",
            email = "";
        var contact_id = "<?= get_contact_user_id(); ?>";
        var url = "<?= site_url('appointly/appointments_public/external_fetch_contact_data'); ?>";

        $.post(url, {
            contact_id: contact_id
        }).done(function (response) {
            full_name = response.firstname + " " + response.lastname;
            email = response.email;
            phone = response.phonenumber;

            // Add contact id field in form
            $("form").append("<input type=\"text\" name=\"contact_id\" value=\"" + contact_id + "\" hidden></input>");

            $("#name").attr("value", full_name).attr("readonly", true);
            $("#email").attr("value", email).attr("readonly", true);
            $("#phone").attr("value", phone).attr("readonly", true);
        });
        <?php
        }
        }
        ?>

        var if_isset_phone_validate = (phone !== "") ? "required" : false;

        $(form_id).appFormValidator({
            rules: {
                subject: "required",
                name: "required",
                email: "required",
                description: "required",
                date: "required",
                phone: if_isset_phone_validate,
            },
            onSubmit: function (form) {

                var formURL = $(form).attr("action");
                var formData = new FormData($(form)[0]);
                $.ajax({
                    type: $(form).attr("method"),
                    data: formData,
                    mimeType: $(form).attr("enctype"),
                    contentType: false,
                    cache: false,
                    processData: false,
                    url: formURL,
                    beforeSend: function () {
                        if ($("#recaptcha_response_field").is(":visible")) {
                            $("#recaptcha_response_field").fadeOut();
                        }
                        $("#form_submit, #pfxcbsubmit").prop("disabled", true);
                        $("#form_submit").html("<i class=\"fa fa-refresh fa-spin fa-fw\"></i>");
                    }
                }).done(function (response) {
                    response = JSON.parse(response);

                    if (response.success == true) {
                        alert_float('success', '<?= _l('lead_appointment_create')?>');
                        $('#showBookingPages').click();
                     
                    } else if (response.success == false && response.recaptcha == false) {
                        $("#recaptcha_response_field").show().html(response.message);
                        $("#pfxcbsubmit").prop("disabled", false);
                        $("#form_submit").html("<?= _l('appointment_submit'); ?>").prop("disabled", false);
                    } else {
                        alert_float('error', response.message);
                    }
                }).fail(function (data) {
                    if (data.status == 422) {
                        $("#response").html("<div class=\"alert alert-danger\">Some fields that are required are not filled properly.</div>");
                    } else {
                        $("#response").html(data.responseText);
                    }
                });
                return false;
            }
        });

        function initAppointmentScheduledDates() {
            $.post("appointly/appointments_public/busyDates").done(function (r) {
                r = JSON.parse(r);
                busyDates= r;
            });

            // jQuery.datetimepicker.setLocale(app.locale);
        }
        function loadTimeSlots(date) {
            const currentMonthYear = $($('#current-month-year')[0]).text()
            const month = currentMonthYear.split(' ')[0];
            const monthNumber = monthNames.indexOf(month) + 1;
            const year = currentMonthYear.split(' ')[1];
            const dateNumber = date.split(' ')[1];
            const busySlots = [];

            busySlots.push(...busyDates.filter(x => new Date(x.date).getDate() == new Date(`${year}-${monthNumber}-${dateNumber}`).getDate()));
            

            // This is a placeholder for dynamic slot loading logic.
            // You can replace it with an actual API call to fetch available time slots.
            const availableTimeSlots = <?= $booking_page['appointly_available_hours'] ?>;

            timeslotList.innerHTML = '';
            availableTimeSlots.forEach(slot => {
                const slotElement = document.createElement('div');
                slotElement.className = 'timeslot';
                slotElement.setAttribute('time', slot);
                slotElement.textContent = slot;
                timeslotList.appendChild(slotElement);
                if (<?= $booking_page['appointly_busy_times_enabled'] ?> && busySlots.filter(x => x.start_hour == slot).length > simultaneous_appointments) {

                    slotElement.className = 'timeslot busy_time';
                }

                slotElement.addEventListener('click', function () {
                    document.querySelectorAll('.timeslot').forEach(t => t.classList.remove('selected'));
                    this.classList.add('selected');

                    // When a timeslot is selected, submit the date and time
                    selectedDateTime = {
                        date: `${selectedDateElem.textContent.trim()} ${slot}`

                    };
                    // Send the selectedDateTime to the server via fetch or AJAX
                    submitDateTime(selectedDateTime);

                });
            });
        }

        initAppointmentScheduledDates();

</script>
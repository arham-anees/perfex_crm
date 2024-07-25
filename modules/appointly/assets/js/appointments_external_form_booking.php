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

                    const lead = document.getElementsByClassName('thankyou-in-lead');
                    // if (lead) {
                    //     fetch('<?= site_url("/appointly/appointments_public/thank_you") ?>?hash=' + response.data)
                    //         .then(response => response.text())
                    //         .then(html => {
                    //             // Get the div where you want to render the contßent
                    //             const contentDiv = document.getElementById('content');

                    //             // Set the inner HTML of the div to the response
                    //             contentDiv.innerHTML = html;
                    //             contentDiv.querySelector('.container').classList.remove('container');
                    //         })
                    //         .catch(error => console.error('Error fetching and parsing data:', error));
                    // } else {
                        window.location.href = '<?= site_url("/appointly/appointments_public/thank_you") ?>?hash=' + response.data;
                    // }

                    // $header = $(".appointment-header");
                    // $(form_id).remove();
                    // $("#response").html($header);
                    // $("#response").append("<div class=\"alert alert-success text-center\" style=\"margin:0 auto;margin-bottom:15px;\">" + response.message + "</div>");
                    // setTimeout(function () {
                    //     <?php if (is_client_logged_in()): ?>
                        //     window.location.href = "<?= base_url(); ?>";
                        //     <?php else: ?>
                        //     location.reload();
                        //     <?php endif; ?>
                    // }, 100000);
                } else if (response.success == false && response.recaptcha == false) {
                    $("#recaptcha_response_field").show().html(response.message);
                    $("#pfxcbsubmit").prop("disabled", false);
                    $("#form_submit").html("<?= _l('appointment_submit'); ?>").prop("disabled", false);
                } else {
                    $("#response").html("<div class=\"alert alert-danger\">Something went wrong...</div>");
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
            busyDates = r;
        });
    }

    function addMinutesToTime(time, minutes) {
        const timeParts = time.split(':');
        const date = new Date();
        date.setHours(parseInt(timeParts[0]), parseInt(timeParts[1]));
        date.setMinutes(date.getMinutes() + minutes);
        const hours = date.getHours().toString().padStart(2, '0');
        const mins = date.getMinutes().toString().padStart(2, '0');
        return `${hours}:${mins}`;
    }
    function loadTimeSlots(date, date2) {
    const currentMonthYear = $($('#current-month-year')[0]).text();
    const month = currentMonthYear.split(' ')[0];
    const monthNumber = monthNames.indexOf(month) + 1;
    const year = currentMonthYear.split(' ')[1];
    const dateNumber = date.split(' ')[1];
    const busySlots = [];

    busySlots.push(...busyDates.filter(x => new Date(x.date).getDate() == new Date(`${year}-${monthNumber}-${dateNumber}`).getDate()));

    // Placeholder for dynamic slot loading logic
    const availableTimeSlots = <?= $booking_page['appointly_available_hours'] ?>;

    const start = new Date(`1970-01-01T${availableTimeSlots[0]}:00Z`);
    const end = new Date(`1970-01-01T${availableTimeSlots[1]}:00Z`);

    // Calculate the difference in milliseconds
    const diffMs = end - start;

    // Convert milliseconds to minutes
    const diffMins = Math.floor(diffMs / 60000);

    timeslotList.innerHTML = '';
    availableTimeSlots.forEach(slot => {

        const parentDiv = document.createElement('div');
        parentDiv.className = 'parent';

        const slotElement = document.createElement('div');
        slotElement.className = 'timeslot';
        slotElement.setAttribute('time', slot);
        slotElement.textContent = slot;

        const nextButton = document.createElement('span');
        nextButton.textContent = 'Following';
        nextButton.className = 'btn btn-primary next-button ';
        nextButton.style.display = 'none';
        nextButton.addEventListener('click', function () {
            nextStep();
        });

        parentDiv.appendChild(slotElement);
        parentDiv.appendChild(nextButton);

        // Append the parent div to the timeslot list
        timeslotList.appendChild(parentDiv);

        if (<?= $booking_page['appointly_busy_times_enabled'] ?> && busySlots.filter(x => x.start_hour == slot).length > simultaneous_appointments) {
            slotElement.className = 'timeslot busy_time';
        }

        // Handle timeslot selection
        slotElement.addEventListener('click', function () {
            // Remove selected class from all timeslots and hide all Next buttons
            document.querySelectorAll('.timeslot').forEach(t => {
                t.classList.remove('selected');
            });
            document.querySelectorAll('.next-button').forEach(button => button.style.display = 'none');
            

            const options = { 
                weekday: 'long', 
                year: 'numeric', 
                month: 'long', 
                day: 'numeric' 
            };
    
            document.getElementById('datetime').innerText = slot +' - '+addMinutesToTime(slot,diffMins) +' '+ new Intl.DateTimeFormat('en-US', options).format(date2);
            document.getElementById('datetime-hidden').value=( slot +' - '+addMinutesToTime(slot,diffMins) +' '+ new Intl.DateTimeFormat('en-US', options).format(date2));
            // Apply selected class to the clicked timeslot and show its Next button
            this.classList.add('selected');
            this.nextElementSibling.style.display = 'block'; // Show the associated Next button

            // When a timeslot is selected, submit the date and time
            selectedDateTime = {
                date: `${selectedDateElem.textContent.trim()} ${slot}`
            };
            // Send the selectedDateTime to the server via fetch or AJAX
            submitDateTime(selectedDateTime);
        });
    });

    // Hide the "Next" button initially when time slots are loaded
    document.querySelectorAll('.next-button').forEach(button => button.style.display = 'none');
}




    initAppointmentScheduledDates();

</script>
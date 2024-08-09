<script>
    var form_id = "#invite-friend-form";
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


        $(form_id).appFormValidator({
            rules: {
                name: "required",
                email: "required",
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

                    alert_float(response.success?'success':'error', response.message);
                    if (response.success == true) {

                       

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


</script>

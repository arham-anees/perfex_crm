<script>
    var form_id = "#appointments-form";
    var is_busy_times_enabled = "<?= get_option('appointly_busy_times_enabled'); ?>";

    $(function () {

        var allowedHours = <?= json_encode(json_decode(get_option('appointly_available_hours'))); ?>;
        var appMinTime = <?= get_option('appointments_show_past_times'); ?>;
        var appWeekends = <?= (get_option('appointments_disable_weekends')) ? "[0, 6]" : "[]"; ?>;

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
                    print(response);
                    response = JSON.parse(response);

                    if (response.success == true) {
                        $header = $(".appointment-header");
                        $(form_id).remove();
                        $("#response").html($header);
                        $("#response").append("<div class=\"alert alert-success text-center\" style=\"margin:0 auto;margin-bottom:15px;\">" + response.message + "</div>");
                        setTimeout(function () {
                            <?php if (is_client_logged_in()) : ?>
                            window.location.href = "<?= base_url(); ?>";
                            <?php else : ?>
                            location.reload();
                            <?php endif; ?>
                        }, 3000);
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

    });
</script>

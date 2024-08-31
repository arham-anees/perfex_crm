<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content" style="padding: 20px; max-height:83vh;">
        <div id="confirm_zapier">
            <p><?= _l('leadevo_send_zapier_description') ?></p>
            <?php echo form_open('', ['id' => 'send_zapier_config']) ?>
            <input type="submit" value="<?= _l('leadevo_send_via_api_button') ?>" class="btn btn-primary pull-right" />
            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>"
                value="<?php echo $this->security->get_csrf_hash(); ?>">
            <div class="form-group" id="radio-buttons"></div>
            <input type="hidden" name="webhook" />
            <input type="hidden" name="lead_data" value="">
            <?php echo form_close(); ?>
        </div>
    </div>
</div>
<script>
    function openSendZapierModal(id) {
        $('#sendZapierProspectModal').modal('show');

        // First AJAX call to fetch Zapier data
        $.ajax({
            url: site_url + 'zapier/fetch',
            type: "GET",
            success: (res) => {
                try {
                    res = JSON.parse(res);
                    if (res.status === 'success') {
                        let data = JSON.parse(res.data);
                        if (data && data) {
                            let webhook = data;
                            let html = '';
                            for (let i = 0; i < webhook.length; i++) {
                                let w = webhook[i];
                                console.log(w.webhook);
                                let name = w.name != null ? w.name : w.webhook;
                                html += `<div class="radio radio-primary">
                                            <input type="radio" id="y_opt_${i}_webhook" name="webhook" value="${w.id}">
                                            <label for="y_opt_${i}_webhook">${name}</label>
                                        </div>`
                            }

                            $('#radio-buttons').html(html);
                        } else {
                            throw new Error('Webhook not found in data');
                        }
                    } else {
                        $('#generate_zapier').show();
                        $('#confirm_zapier').hide();
                        alert_float('danger', 'Failed to fetch lead data');
                    }
                } catch (error) {
                    console.error(error);
                    $('#generate_zapier').show();
                    $('#confirm_zapier').hide();
                    alert_float('danger', 'Failed to fetch lead data');
                }
            },
            error: (err) => {
                console.log(err);
                alert_float('danger', 'Failed to fetch lead data');
            }
        });

        // Second AJAX call to fetch additional data
        $.ajax({
            url: 'fetch_to_send?id=' + id,
            type: "GET",
            success: (res) => {
                try {
                    res = JSON.parse(res);
                    if (res.status === 'success') {
                        $('input[name=lead_data]').val(res.data);
                    } else {
                        alert_float('danger', 'Failed to fetch lead data');
                    }
                } catch (error) {
                    console.error(error);
                    alert_float('danger', 'Failed to fetch lead data');
                }
            },
            error: (err) => {
                console.log(err);
                alert_float('danger', 'Failed to fetch lead data');
            }
        });
    }

</script>

<script>
    $('#create_zapier_config').on('submit', function (e) {
        e.preventDefault(); // Prevent the form from submitting via the browser

        var csrfName = $('input[name="<?php echo $this->security->get_csrf_token_name(); ?>"]').attr('name');
        var csrfHash = $('input[name="<?php echo $this->security->get_csrf_token_name(); ?>"]').val();

        var webhook = $('input[name=webhook]').val();
        // validate the base url

        // format pull path of url
        var data = {};
        data[csrfName] = csrfHash;
        data['webhook'] = webhook;

        $.ajax({
            url: 'create_zapier',
            data,
            type: "POST",
            success: (res) => {
                try {
                    res = JSON.parse(res);
                    if (res.status == 'success') {
                        $.ajax({
                            url: 'fetch_zapier?id=' + id,
                            type: "GET",
                            success: (res) => {
                                res = JSON.parse(res);
                                if (res.status == 'success') {
                                    let webhook = JSON.parse(res.data).webhook;
                                    $('#confirm_zapier input[name=webhook]').val(webhook);
                                    $('#generate_zapier').hide();
                                    $('#confirm_zapier').show();
                                }
                                else {
                                    $('#generate_zapier').show();
                                    $('#confirm_zapier').hide();
                                    alert_float('danger', 'Failed to fetch lead data');
                                }
                            },
                            error: (err) => {
                                console.log(err)
                                alert_float('danger', 'Failed to fetch lead data');
                            }
                        })
                    }
                } catch (e) {

                    alert_float('danger', 'Failed to save lead data');
                }

            },
            error: (err) => {
                console.log(err);
            }
        })
    })
    $('#send_zapier_config').on('submit', function (e) {
        e.preventDefault(); // Prevent the form from submitting via the browser
        // validate the base url

        // format pull path of url
        var data = {};
        data['lead'] = $('input[name=lead_data]').val();;

        const path = $('#confirm_zapier input[name=webhook]').val();;
        $.ajax({
            url: path,
            data,
            type: "POST",
            success: (res) => {
                try {
                    // res = JSON.parse(res);
                    if (res.status == 'success') {
                        alert_float('success', 'Lead data sent successfully');
                        $('#sendZapierProspectModal').modal('hide');
                    }
                } catch (e) {

                    alert_float('danger', 'Failed to save lead data');
                }

            },
            error: (err) => {
                console.log(err);
            }
        })
    })
</script>
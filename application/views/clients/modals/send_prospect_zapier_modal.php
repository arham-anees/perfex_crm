<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content" style="padding: 20px; max-height:83vh;">
        <div id="generate_zapier" style="display:none">
            <p><?= _l('leadevo_create_zapier_description') ?></p>
            <?php echo form_open('', ['id' => 'create_zapier_config']) ?>
            <div class="form-group">
                <label for="webhook_url" class="control-label clearfix"><?= _l('leadevo_zapier_webhook') ?>
                    <input type="text" name="webhook" id="webhook_url" class="form-control" />
            </div>
            <div>
                <input type="submit" value="<?= _l('leadevo_send_via_api_button') ?>"
                    class="btn btn-primary pull-right" />
            </div>
            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>"
                value="<?php echo $this->security->get_csrf_hash(); ?>">

            <input type="hidden" name="lead_data" value="">
            <?php echo form_close(); ?>
        </div>
        <div id="confirm_zapier" style="display:none">
            <p><?= _l('leadevo_send_zapier_description') ?></p>
            <?php echo form_open('', ['id' => 'send_zapier_config']) ?>
            <input type="submit" value="<?= _l('leadevo_send_via_api_button') ?>" class="btn btn-primary pull-right" />
            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>"
                value="<?php echo $this->security->get_csrf_hash(); ?>">

            <input type="hidden" name="webhook" />
            <input type="hidden" name="lead_data" value="">
            <?php echo form_close(); ?>
        </div>
    </div>
</div>
<script>
    function openSendZapierModal(id) {
        $('#sendZapierProspectModal').modal('show');
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
        $.ajax({
            url: 'fetch_to_send?id=' + id,
            type: "GET",
            success: (res) => {
                console.log(res);
                res = JSON.parse(res);
                if (res.status == 'success') {
                    $('input[name=lead_data]').val(res.data);
                }
                else {
                    alert_float('danger', 'Failed to fetch lead data');
                }
            },
            error: (err) => {
                console.log(err)
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

        var csrfName = $('input[name="<?php echo $this->security->get_csrf_token_name(); ?>"]').attr('name');
        var csrfHash = $('input[name="<?php echo $this->security->get_csrf_token_name(); ?>"]').val();

        // validate the base url

        // format pull path of url
        var data = {};
        data[csrfName] = csrfHash;

        const path = $('#confirm_zapier input[name=webhook]').val();;
        $.ajax({
            url: path,
            data,
            type: "POST",
            success: (res) => {
                console.log(res);
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
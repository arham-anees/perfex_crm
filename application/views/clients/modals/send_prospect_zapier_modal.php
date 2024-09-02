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

        // Fetch Zapier webhooks
        fetchZapierData()
            .then(res => {
                if (res.status === 'success') {
                    let data = JSON.parse(res.data);
                    if (data && data.length > 0) {
                        let html = '';
                        data.forEach((w, i) => {
                            let name = w.name || w.webhook;
                            html += `<div class="radio radio-primary">
                                        <input type="radio" id="y_opt_${i}_webhook" name="webhook" value="${w.id}">
                                        <label for="y_opt_${i}_webhook">${name}</label>
                                    </div>`;
                        });
                        $('#radio-buttons').html(html);
                    } else {
                        throw new Error('No webhooks found');
                    }
                } else {
                    $('#generate_zapier').show();
                    $('#confirm_zapier').hide();
                    alert_float('danger', 'Failed to fetch Zapier data');
                }
            })
            .catch(error => {
                console.error(error);
                $('#generate_zapier').show();
                $('#confirm_zapier').hide();
                alert_float('danger', 'Error processing Zapier data');
            });

        // Fetch lead data
        fetchLeadData(id)
            .then(res => {
                if (res.status === 'success') {
                    $('input[name=lead_data]').val(res.data);
                } else {
                    alert_float('danger', 'Failed to fetch lead data');
                }
            })
            .catch(error => {
                console.error(error);
                alert_float('danger', 'Error processing lead data');
            });

    }

    function fetchZapierData() {
        return new Promise((resolve, reject) => {
            $.ajax({
                url: site_url + 'zapier/fetch',
                type: "GET",
                success: (res) => resolve(JSON.parse(res)),
                error: (err) => reject(err)
            });
        });
    }

    function fetchLeadData(id) {
        return new Promise((resolve, reject) => {
            $.ajax({
                url: 'fetch_to_send?id=' + id,
                type: "GET",
                success: (res) => resolve(JSON.parse(res)),
                error: (err) => reject(err)
            });
        });
    }

    function fetchSelectedWebhook() {
        let selectedWebhookId = $('input[name="webhook"]:checked').val();

        if (!selectedWebhookId) {
            alert_float('danger', 'Please select a webhook');
            return;
        }

        $.ajax({
            url: site_url + 'zapier/fetch_webhook',  // Ensure this endpoint returns the webhook URL
            type: "GET",
            data: { id: selectedWebhookId },
            success: (res) => {
                try {
                    res = JSON.parse(res);
                    if (res.status === 'success') {
                        let webhookUrl = res.webhook_url;  // Adjust based on the response structure
                        sendLeadDataToWebhook(webhookUrl);
                    } else {
                        alert_float('danger', 'Failed to fetch webhook URL');
                    }
                } catch (error) {
                    console.error(error);
                    alert_float('danger', 'Error processing webhook URL');
                }
            },
            error: (err) => {
                console.log(err);
                alert_float('danger', 'Failed to fetch webhook URL');
            }
        });
    }


    function sendLeadDataToWebhook(webhookUrl) {
        let csrfName = $('input[name="<?php echo $this->security->get_csrf_token_name(); ?>"]').attr('name');
        let csrfHash = $('input[name="<?php echo $this->security->get_csrf_token_name(); ?>"]').val();
        let leadData = $('input[name=lead_data]').val();

        let data = {};
        data[csrfName] = csrfHash;
        data['lead'] = leadData;

        $.ajax({
            url: webhookUrl,
            data: data,
            type: "POST",
            success: (res) => {
                try {
                    alert_float('success', 'Lead data sent successfully');
                    $('#sendZapierProspectModal').modal('hide');
                } catch (e) {
                    console.error(e);
                    alert_float('danger', 'Error sending lead data');
                }
            },
            error: (err) => {
                console.log(err);
                alert_float('danger', 'Error sending lead data');
            }
        });
    }


    $('#create_zapier_config').on('submit', function (e) {
        e.preventDefault();

        let csrfName = $('input[name="<?php echo $this->security->get_csrf_token_name(); ?>"]').attr('name');
        let csrfHash = $('input[name="<?php echo $this->security->get_csrf_token_name(); ?>"]').val();
        let webhook = $('input[name=webhook]').val();

        let data = {};
        data[csrfName] = csrfHash;
        data['webhook'] = webhook;

        $.ajax({
            url: site_url + 'zapier/create',
            data: data,
            type: "POST",
            success: (res) => {
                try {
                    res = JSON.parse(res);
                    if (res.status === 'success') {
                        fetchZapierData().then(res => {
                            if (res.status === 'success') {
                                let webhook = JSON.parse(res.data).webhook;
                                $('#confirm_zapier input[name=webhook]').val(webhook);
                                $('#generate_zapier').hide();
                                $('#confirm_zapier').show();
                            } else {
                                $('#generate_zapier').show();
                                $('#confirm_zapier').hide();
                                alert_float('danger', 'Failed to fetch Zapier data');
                            }
                        }).catch(err => {
                            console.log(err);
                            alert_float('danger', 'Failed to fetch Zapier data');
                        });
                    } else {
                        alert_float('danger', 'Failed to save Zapier config');
                    }
                } catch (e) {
                    alert_float('danger', 'Error processing Zapier config');
                }
            },
            error: (err) => {
                console.log(err);
            }
        });
    });

    $('#send_zapier_config').on('submit', function (e) {
    e.preventDefault();
    fetchSelectedWebhook();
});
</script>
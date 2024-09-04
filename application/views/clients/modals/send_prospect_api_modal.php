<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content" style="padding: 20px; max-height:83vh;">

        <p><?= _l('leadevo_send_api_description') ?></p>
        <?php echo form_open('', ['id' => 'send-prospect-api-form']) ?>
        <div class="form-group">
            <label for="" class="control-label clearfix"><?= _l('Target CRM') ?>
            </label>
            <div class="radio radio-primary">
                <input type="radio" name="target_crm" id="target_crm_perfex" value="perfex" checked>
                <label for="target_crm_perfex"><?php echo _l('leadevo_send_api_crm_perfex'); ?></label>
            </div>
        </div>
        <div class="form-group">
            <label for="target_base_url" class="control-label clearfix"><?= _l('Target CRM') ?>
                <div class="form-group" id="baseurl_radio-buttons"></div>
        </div>
        <div>
            <input type="submit" value="<?= _l('leadevo_send_via_api_button') ?>" class="btn btn-primary pull-right" />
        </div>
        <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>"
            value="<?php echo $this->security->get_csrf_hash(); ?>">
        <input type="hidden" name="crm_webhook" />
        <input type="hidden" name="lead_data" value="">
        <?php echo form_close(); ?>
    </div>
</div>
<script>
    function openSendApiModal(id) {
        $('#sendApiProspectModal').modal('show');
        fetchCrmData()
            .then(res => {
                if (res.status === 'success') {
                    let data = JSON.parse(res.data);
                    if (data && data.length > 0) {
                        let html = '';
                        data.forEach((w, i) => {
                            let name = w.name || w.webhook;
                            html += `<div class="radio radio-primary">
                                        <input type="radio" id="y_opt_${i}_webhook" name="crm_webhook" value="${w.id}">
                                        <label for="y_opt_${i}_webhook">${name}</label>
                                    </div>`;
                        });
                        $('#baseurl_radio-buttons').html(html);
                    } else {
                        throw new Error('No Api found');
                    }
                } else {
                    alert_float('danger', 'Failed to fetch Api data');
                }
            })
            .catch(error => {
                console.error(error);
                $('#generate_zapier').show();
                $('#confirm_zapier').hide();
                alert_float('danger', 'Error processing Api data');
            });

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

    // fetch zaper links from the database
    function fetchCrmData() {
        return new Promise((resolve, reject) => {
            $.ajax({
                url: site_url + 'Crm/fetch_crm',
                type: "GET",
                success: (res) => resolve(JSON.parse(res)),
                error: (err) => reject(err)
            });
        });
    }


    function fetchSelectedWebhook() {

    }


</script>

<script>
    $('#send-prospect-api-form').on('submit', function (e) {
        e.preventDefault(); // Prevent the form from submitting via the browser

        let selectedWebhookId = $('input[name=crm_webhook]:checked').val();

        if (!selectedWebhookId) {
            alert_float('danger', 'Please select a api');
            return;
        }

        $.ajax({
            url: site_url + 'Crm/fetch_crm_link',  // Ensure this endpoint returns the webhook URL
            type: "GET",
            data: { id: selectedWebhookId },
            success: (res) => {
                try {
                    res = JSON.parse(res);
                    if (res.status === 'success') {
                        let webhookUrl = res.api_url;  // Adjust based on the response structure
                        sendLeadDataToApi(webhookUrl);
                    } else {
                        alert_float('danger', 'Failed to fetch api URL');
                    }
                } catch (error) {
                    console.error(error);
                    alert_float('danger', 'Error processing api URL');
                }
            },
            error: (err) => {
                console.log(err);
                alert_float('danger', 'Failed to fetch api URL');
            }
        });

        function sendLeadDataToApi(apiUrl) {
            var csrfName = $('input[name="<?php echo $this->security->get_csrf_token_name(); ?>"]').attr('name');
            var csrfHash = $('input[name="<?php echo $this->security->get_csrf_token_name(); ?>"]').val();

            var targetCrm = $('input[name=target_crm]:checked').val();

            // validate the base url

            // format pull path of url
            var path = apiUrl;
            path = path + '/leadevo_api/receive';
            var data = {};
            data[csrfName] = csrfHash;
            data['lead'] = $('input[name=lead_data]').val();
            $.ajax({
                url: path,
                data,
                type: "POST",
                success: (res) => {
                    console.log("===>", res, 'end');
                    try {
                        res = JSON.parse(res);
                        if (res.status == 'success') {
                            alert_float('success', 'Lead data sent successfully');
                            $('#sendApiProspectModal').modal('hide');
                        }
                    } catch (e) {

                        alert_float('danger', 'Failed to save lead data');
                    }

                },
                error: (err) => {
                    console.log(err);
                }
            })
        }
    })


</script>
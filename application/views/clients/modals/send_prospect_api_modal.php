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
                <input type="text" name="base_url" id="target_base_url" class="form-control" />
        </div>
        <div>
            <input type="submit" value="<?= _l('leadevo_send_via_api_button') ?>" class="btn btn-primary pull-right" />
        </div>
        <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>"
            value="<?php echo $this->security->get_csrf_hash(); ?>">

        <input type="hidden" name="lead_data" value="">
        <?php echo form_close(); ?>
    </div>
</div>
<script>
    function openSendApiModal(id) {
        $('#sendApiProspectModal').modal('show');
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
    $('#send-prospect-api-form').on('submit', function (e) {
        e.preventDefault(); // Prevent the form from submitting via the browser

        var csrfName = $('input[name="<?php echo $this->security->get_csrf_token_name(); ?>"]').attr('name');
        var csrfHash = $('input[name="<?php echo $this->security->get_csrf_token_name(); ?>"]').val();

        var targetCrm = $('input[name=target_crm]:checked').val();
        var target_base_url = $('input[name=base_url]').val();
        // validate the base url

        // format pull path of url
        var path = target_base_url + '/dashboard/receive_prospect';
        var data = {};
        data[csrfName] = csrfHash;
        data['lead'] = $('input[name=lead_data]').val();
        $.ajax({
            url: path,
            data,
            type: "POST",
            success: (res) => {
                console.log(res);
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
    })
</script>
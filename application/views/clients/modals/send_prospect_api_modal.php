<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content" style="padding: 20px; max-height:83vh;">
        <div id="confirm_crm_links">
            <p><?= _l('leadevo_send_api_description') ?></p>
            <?php echo form_open('', ['id' => 'send_prospect_api_form']) ?>
            <div class="form-group">
                <label for="" class="control-label clearfix"><?= _l('Target CRM') ?>
                </label>
                <div class="radio radio-primary">
                    <input type="radio" name="target_crm" id="target_crm_perfex" value="perfex" checked>
                    <label for="target_crm_perfex"><?php echo _l('leadevo_send_api_crm_perfex'); ?></label>
                </div>
            </div>

            <!-- Displaying links as radio buttons -->
            <div class="form-group" id="radio-buttons">
            <label for="target_base_url" class="control-label clearfix"><?= _l('Target CRM') ?>
                <?php
                // Fetching 'links' from the tblleadevo_crm_links table
                $this->db->select('id, links');
                $this->db->from('tblleadevo_crm_links');
                $query = $this->db->get();
                $crm_links = $query->result();

                if (!empty($crm_links)) {
                    foreach ($crm_links as $link) {
                        echo '<div class="radio radio-primary">';
                        echo '<input type="radio" id="crm_link_' . $link->id . '" name="base_url" value="' . $link->links . '">';
                        echo '<label for="crm_link_' . $link->id . '">' . $link->links . '</label>';
                        echo '</div>';
                    }
                } else {
                    echo '<p>No CRM links found.</p>';
                }
                ?>
            </div>
            <input type="submit" value="<?= _l('leadevo_send_via_api_button') ?>" class="btn btn-primary pull-right" />
            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>"
                value="<?php echo $this->security->get_csrf_hash(); ?>">

            <input type="hidden" name="lead_data" value=""> <!-- Hidden lead data -->
            <?php echo form_close(); ?>
        </div>
    </div>
</div>

<script>
    function openSendApiModal(id) {
        $('#sendApiProspectModal').modal('show');

        // Fetch lead data for the selected ID
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

    function fetchLeadData(id) {
        return new Promise((resolve, reject) => {
            $.ajax({
                url: 'fetch_to_send?id=' + id, // The URL to fetch lead data
                type: "GET",
                success: (res) => resolve(JSON.parse(res)),
                error: (err) => reject(err)
            });
        });
    }

    $('#send_prospect_api_form').on('submit', function(e) {
        e.preventDefault(); // Prevent the default form submission

        var csrfName = $('input[name="<?php echo $this->security->get_csrf_token_name(); ?>"]').attr('name');
        var csrfHash = $('input[name="<?php echo $this->security->get_csrf_token_name(); ?>"]').val();

        var targetCrm = $('input[name=target_crm]:checked').val(); // Fetching the selected CRM radio button
        var selectedBaseUrl = $('input[name="base_url"]:checked').val(); // Fetching the selected base URL

        if (!selectedBaseUrl) {
            alert_float('danger', 'Please select a CRM link');
            return;
        }

        var leadData = $('input[name=lead_data]').val(); // Lead data from the hidden input

        // Validate base URL before sending the request
        if (!isValidUrl(selectedBaseUrl)) {
            alert_float('danger', 'Invalid base URL selected');
            return;
        }

        // Construct the full URL for the API endpoint
        var apiUrl = selectedBaseUrl + '/leadevo_api/receive'; // Append your endpoint to the selected base URL

        var data = {};
        data[csrfName] = csrfHash;
        data['lead'] = leadData;

        // Send the AJAX request to the selected CRM's API
        $.ajax({
            url: apiUrl,
            data: data,
            type: "POST",
            success: (res) => {
                try {
                    var result = JSON.parse(res);
                    if (result.status === 'success') {
                        alert_float('success', 'Lead data sent successfully');
                        $('#sendApiProspectModal').modal('hide');
                    } else {
                        alert_float('danger', 'Failed to send lead data');
                    }
                } catch (e) {
                    alert_float('danger', 'Failed to send lead data');
                }
            },
            error: (err) => {
                console.error(err);
                alert_float('danger', 'Error sending lead data');
            }
        });
    });

    // Validate the URL
    function isValidUrl(url) {
        try {
            new URL(url);
            return true;
        } catch (_) {
            return false;
        }
    }
</script>
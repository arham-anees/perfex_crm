<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="viewProspectModalLabel">Prospect Details</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <input type="hidden" name="id" />
            <!-- Prospect data will be loaded here via AJAX -->
            <div id="prospectDetails">
                <!-- Example Fields -->
                <p><strong>First Name:</strong> <span id="prospectFirstName"></span></p>
                <p><strong>Last Name:</strong> <span id="prospectLastName"></span></p>
                <p><strong>Status:</strong> <span id="prospectStatus"></span></p>
                <p><strong>Type:</strong> <span id="prospectType"></span></p>
                <p><strong>Acquisition Channel:</strong> <span id="prospectAcquisitionChannel"></span></p>
                <p><strong>Industry:</strong> <span id="prospectIndustry"></span></p>
                <p><strong>Activity log:</strong>
                <div id="activity-log"></div>
                </p>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
    </div>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        $('#viewProspectModal').on('shown.bs.modal', function () {

            let prospectId = $('#viewProspectModal input[name=id]').val();

            $.ajax({
                url: '<?= admin_url('prospects/get_prospect_data') ?>',
                type: 'GET',
                data: { id: prospectId },
                success: function (response) {
                    const res = JSON.parse(response);
                    const data = res.prospect;
                    const logs = res.logs ?? [];

                    // Populate the modal with the first name, last name, and other details
                    $('#prospectFirstName').text(data.first_name);
                    $('#prospectLastName').text(data.last_name);
                    $('#prospectStatus').text(data.status);
                    $('#prospectType').text(data.type);

                    $('#prospectAcquisitionChannel').text(data.acquisition_channel);
                    $('#prospectIndustry').text(data.industry);

                    let logsHtml = '';
                    for (let i = 0; i < logs.length; i++) {
                        const log = logs[i];
                        let type = '';
                        let name = log.staff_name;
                        if (name == null || name == '') name = log.client_name;
                        let date = log.date;
                        if (log.type == 'marked_fake') type = 'Marked as Fake';
                        if (log.type == 'remove_from_market') type = 'Remove from Marketplace';
                        if (log.type == 'auto_deliverable') type = 'Deliver Automatically';


                        logsHtml += `<div class='prospect-log-item'>${name} at ${date} - ${type}`
                        if (log.comments != null && log.comments != '') { logsHtml += ` - ${log.comments}`; }
                        logsHtml += '</div>'
                    }
                    $('#activity-log').html(logsHtml);

                    // Show the modal
                    $('#viewProspectModal').modal('show');
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert('Failed to fetch prospect data: ' + errorThrown);
                }
            });
        })

});

</script>
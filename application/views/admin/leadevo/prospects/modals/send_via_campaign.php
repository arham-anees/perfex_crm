<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
        <!-- Modal Header -->
        <div class="modal-header d-flex">
            <div></div>
            <h4 class="modal-title w-100"><?php echo _l('leadevo_sale_send_campaign_prospect_title'); ?></h4>
        </div>

        <!-- Modal Body -->
        <div class="modal-body">
            <?php echo form_open('#', ['id' => 'send-prospect-campaign-form']); ?>
            <input type="hidden" name="id" />

            <table class="table" id="matching-campaigns">
                <thead>
                    <tr>
                        <th><?= _l('leadevo_matching_campaign_client_name') ?></th>
                        <th><?= _l('leadevo_matching_campaign_progress') ?></th>
                        <th><?= _l('leadevo_matching_campaign_last_delivery') ?></th>
                        <th><?= _l('actions') ?></th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>

            <?php echo form_close(); ?>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.getElementById('send-prospect-campaign-form').addEventListener('submit', function (event) {
            event.preventDefault();
            onSubmit();
        });
        $('#send_via_campaign_modal').on('shown.bs.modal', function () {

            let prospectId = $('#send_via_campaign_modal input[name=id]').val();
            $.ajax({
                url: admin_url + 'campaigns/matching?prospect_id=' + prospectId,
                success: (res) => {
                    try {
                        res = JSON.parse(res);
                        if (res.status == 'success') {
                            let campaigns = JSON.parse(res.data);
                            const tableBody = document.querySelector('#matching-campaigns tbody');

                            // Clear existing rows
                            tableBody.innerHTML = '';

                            // Loop through the campaigns data and create rows
                            campaigns.forEach(campaign => {
                                const row = document.createElement('tr');

                                // Create the client_name cell
                                const clientNameCell = document.createElement('td');
                                clientNameCell.textContent = campaign.client_name;
                                row.appendChild(clientNameCell);

                                // Create the progress cell
                                const progressCell = document.createElement('td');
                                progressCell.textContent = campaign.progress;
                                row.appendChild(progressCell);

                                // Create the last_delivered cell
                                const lastDeliveredCell = document.createElement('td');
                                lastDeliveredCell.textContent = campaign.last_delivered;
                                row.appendChild(lastDeliveredCell);

                                // Create the actions cell
                                const actionsCell = document.createElement('td');
                                const viewButton = document.createElement('button');
                                viewButton.textContent = 'Send';
                                viewButton.className = 'btn btn-primary';
                                viewButton.addEventListener('click', function () {
                                    sendToCampaign(campaign.id, prospectId);
                                });


                                actionsCell.appendChild(viewButton);
                                row.appendChild(actionsCell);

                                // Append the row to the table body
                                tableBody.appendChild(row);
                            });
                            $('#matching-campaigns').DataTable();
                            setTimeout(() => {

                                $('#matching-campaigns_wrapper').removeClass('table-loading');
                            }, 100);
                        }
                        else {

                            alert_float('danger', 'Failed to fetch matching campaign');
                        }
                    }
                    catch (e) {
                        console.log(e)
                        alert_float('danger', 'Failed to fetch matching campaign');
                    }
                },
                error: (err) => {
                    alert_float('danger', 'Failed to fetch matching campaign');
                }
            })
        })

        function sendToCampaign(campaignId, prospectId) {
            console.log('send to campaign', campaignId);

            $.ajax({
                url: admin_url + 'prospects/send_to_campaign',
                type: 'POST',
                data: {
                    campaign_id: campaignId,
                    prospect_id: prospectId
                },
                success: (res) => {
                    try {
                        res = JSON.parse(res);
                        if (res.status == 'success') {
                            alert_float('success', res.message);
                            $('#send_via_campaign_modal').modal('hide');
                        }
                        else {
                            alert_float('danger', 'Failed to send prospect to desired campaign');
                        }
                    } catch (e) {
                        console.log(e);
                        alert_float('danger', 'Failed to send prospect to desired campaign');
                    }
                }
            })
        }

        function onSubmit() {
            return;
            // get data 
            let id = $('#send_via_campaign_modal input[name=id]').val();
            let deal = $('#send_via_campaign_modal input[name=deal]').val();
            let desired_amount = $('#send_via_campaign_modal input[name=desired_amount]').val();
            let min_amount = $('#send_via_campaign_modal input[name=min_amount]').val();

            $.ajax({
                url: admin_url + 'prospects/mark_as_available_sale',
                data: {
                    id, deal, desired_amount, min_amount
                },
                type: 'POST',
                success: (res) => {
                    try {
                        res = JSON.parse(res);
                        if (res.status == 'success') {
                            alert_float('success', res.message);
                            $('#send_via_campaign_modal').modal('hide');
                            setTimeout(() => {
                                window.location.reload();
                            }, 500);
                        }
                        else {
                            alert_float('danger', 'Failed to put the prospect to marketplace');
                        }
                    }
                    catch (e) {
                        alert_float('danger', 'Failed to put the prospect to marketplace');

                    }
                },
                error: (err) => {
                    alert_float('danger', 'Failed to put the prospect to marketplace');
                }
            })
            return false;
        }
    });
</script>
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
            <input type="hidden" name="campaign_id" />
            <table class="table" id="prospects-replacements">
                <thead>
                    <tr>
                        <th><?= _l('leadevo_replace_name') ?></th>
                        <th><?= _l('leadevo_replace_email') ?></th>
                        <th><?= _l('leadevo_replace_price') ?></th>
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
        $('#replace_prospect_modal').on('shown.bs.modal', function () {

            let prospectId = $('#replace_prospect_modal input[name=id]').val();
            let campaignId = $('#replace_prospect_modal input[name=campaign_id]').val();
            $.ajax({
                url: admin_url + 'prospects/get_replacements?id=' + prospectId,
                success: (res) => {
                    try {
                        res = JSON.parse(res);
                        if (res.status == 'success') {
                            let prospects = JSON.parse(res.data);
                            console.log(prospects);
                            const tableBody = document.querySelector('#prospects-replacements tbody');

                            // Clear existing rows
                            tableBody.innerHTML = '';

                            // Loop through the campaigns data and create rows
                            prospects.forEach(campaign => {
                                const row = document.createElement('tr');

                                // Create the client_name cell
                                const clientNameCell = document.createElement('td');
                                clientNameCell.textContent = campaign.first_name + ' ' + campaign.last_name;
                                row.appendChild(clientNameCell);

                                // Create the progress cell
                                const progressCell = document.createElement('td');
                                progressCell.textContent = campaign.email;
                                row.appendChild(progressCell);

                                // Create the last_delivered cell
                                const lastDeliveredCell = document.createElement('td');
                                lastDeliveredCell.textContent = campaign.desired_amount + "/" + campaign.min_amount;
                                row.appendChild(lastDeliveredCell);

                                // Create the actions cell
                                const actionsCell = document.createElement('td');
                                const viewButton = document.createElement('button');
                                viewButton.textContent = 'Replace';
                                viewButton.className = 'btn btn-danger';
                                viewButton.addEventListener('click', function () {
                                    replace(campaign.id, prospectId, campaignId);
                                });


                                actionsCell.appendChild(viewButton);
                                row.appendChild(actionsCell);

                                // Append the row to the table body
                                tableBody.appendChild(row);
                            });
                            $('#prospects-replacements').DataTable();
                            setTimeout(() => {

                                $('#prospects-replacements_wrapper').removeClass('table-loading');
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

        function replace(newProspectId, oldProspectId, campaignId) {
            console.log('send to campaign', newProspectId, oldProspectId, campaignId);

            $.ajax({
                url: admin_url + 'prospects/replace',
                type: 'POST',
                data: {
                    new_prospect_id: newProspectId,
                    old_prospect_id: oldProspectId,
                    campaign_id: campaignId
                },
                success: (res) => {
                    try {
                        res = JSON.parse(res);
                        if (res.status == 'success') {
                            alert_float('success', res.message);
                            $('#replace_prospect_modal').modal('hide');
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
            let id = $('#replace_prospect_modal input[name=id]').val();
            let deal = $('#replace_prospect_modal input[name=deal]').val();
            let desired_amount = $('#replace_prospect_modal input[name=desired_amount]').val();
            let min_amount = $('#replace_prospect_modal input[name=min_amount]').val();

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
                            $('#replace_prospect_modal').modal('hide');
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
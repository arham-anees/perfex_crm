<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>


<style>
    #backBtn {
        background-color: transparent;
        border: none;
        display: flex;
        align-items: center;
    }

    #backBtn i {
        margin-right: 5px;
    }

    .wizard-step {
        display: none;
    }

    .wizard-step.active {
        display: block;
    }

    .wizard-nav,
    .week {
        display: flex;
        justify-content: space-around;
        margin-bottom: 10px;
    }

    .wizard-circle,
    .days {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        background-color: #ddd;
        line-height: 30px;
        text-align: center;
        font-weight: bold;
        cursor: pointer;
    }

    .wizard-circle.active,
    .wizard-circle.completed {
        background-color: #007bff;
        color: #fff;
    }

    .wizard-buttons {
        display: flex;
        justify-content: space-between;
        align-items: center;
        /* Aligns items vertically centered if needed */
    }

    .wizard-buttons .btn {
        margin: 0;
        /* Ensures no extra margin */
    }

    #nextBtn {
        margin-left: auto;
        /* Pushes the "Next" button to the right */
    }

    .line {
        height: 2px;
        background-color: #D3D3D3;
        flex-grow: 1;
        margin: 13px;
        position: relative;
        z-index: 0;
    }

    .gridcontainer1 {
        display: flex;
        justify-content: space-between;
    }

    .grid-container {
        display: flex;
        grid-template-columns: auto auto auto auto;
        gap: 115px;
        margin-top: -2%;
        padding: 1px;
    }

    .grid-container>div {
        font-size: 12px;
    }

    .alt-text3 {
        margin-right: 30px;
    }

    /* Fixed Height for Modal */
    .modal-content {
        max-height: 80vh;
        overflow-y: auto;
    }

    /* Ensure wizard content is visible */
    .wizard-step {
        min-height: 300px;
        /* Adjust as needed */
    }
</style>
<div class="row">
    <div class="col-md-12">
        <div class="panel_s tw-mt-2 sm:tw-mt-4">
            <div class="panel-body">

                <a href="#" data-toggle="modal" data-target="#customers_bulk_action"
                    class="bulk-actions-btn table-btn hide"
                    data-table=".table-clients"><?php echo _l('bulk_actions'); ?></a>
                <div class="modal fade bulk_actions" id="customers_bulk_action" tabindex="-1" role="dialog">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title"><?php echo _l('bulk_actions'); ?></h4>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-default"
                                    data-dismiss="modal"><?php echo _l('close'); ?></button>
                                <a href="#" class="btn btn-primary"
                                    onclick="customers_bulk_action(this); return false;"><?php echo _l('confirm'); ?></a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                $table_data = [];
                $_table_data = [

                    [
                        'name' => _l('the_number_sign'),
                        'th_attrs' => ['class' => 'toggleable', 'id' => 'th-number'],
                    ],
                    [
                        'name' => _l('clients_list_company'),
                        'th_attrs' => ['class' => 'toggleable', 'id' => 'th-company'],
                    ],
                    [
                        'name' => _l('contact_primary'),
                        'th_attrs' => ['class' => 'toggleable', 'id' => 'th-primary-contact'],
                    ],
                    [
                        'name' => _l('company_primary_email'),
                        'th_attrs' => ['class' => 'toggleable', 'id' => 'th-primary-contact-email'],
                    ],
                    [
                        'name' => _l('clients_list_phone'),
                        'th_attrs' => ['class' => 'toggleable', 'id' => 'th-phone'],
                    ],
                    [
                        'name' => _l('clients_list_value'),
                        'th_attrs' => ['class' => 'toggleable', 'id' => 'th-phone'],
                    ],
                    [
                        'name' => _l('clients_list_tags'),
                        'th_attrs' => ['class' => 'toggleable', 'id' => 'th-phone'],
                    ],
                    [
                        'name' => _l('clients_list_status'),
                        'th_attrs' => ['class' => 'toggleable', 'id' => 'th-active'],
                    ],

                    [
                        'name' => _l('clients_list_source'),
                        'th_attrs' => ['class' => 'toggleable', 'id' => 'th-active'],
                    ],
                    [
                        'name' => _l('date_created'),
                        'th_attrs' => ['class' => 'toggleable', 'id' => 'th-date-created'],
                    ],
                ];
                foreach ($_table_data as $_t) {
                    array_push($table_data, $_t);
                }

                $custom_fields = get_custom_fields('customers', ['show_on_table' => 1]);

                foreach ($custom_fields as $field) {
                    array_push($table_data, [
                        'name' => $field['name'],
                        'th_attrs' => ['data-type' => $field['type'], 'data-custom-field' => 1],
                    ]);
                }
                $table_data = hooks()->apply_filters('customers_table_columns', $table_data);
                ?>
                <div class="panel-table-full">

                    <table class="table clients" id="clients">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Value</th>
                                <th>Tags</th>
                                <th>Status</th>
                                <th>Source</th>
                                <th>Created On</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($table as $prospect): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($prospect->id ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($prospect->name ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($prospect->email ?? ''); ?>
                                        <div class="row-options"><a href="#"
                                                onclick="init_lead_purchased(<?= $prospect->id ?>);return false;">View</a> |
                                            <a data-toggle="modal" data-target="#reportProspectModal"
                                                class="text-danger">Report</a> |
                                            <a onclick="openSendApiModal(<?= $prospect->id ?>)">Send via API</a> |
                                            <a data-toggle="modal" data-target="#sendZapierProspectModal">Send via
                                                Zapier</a>
                                        </div>
                                    </td>
                                    <td><?php echo htmlspecialchars($prospect->phonenumber ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($prospect->lead_value ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars(''); ?></td>
                                    <td><?php echo htmlspecialchars($prospect->status_name ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($prospect->source_name ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($prospect->dateadded ?? ''); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


<div id="reportProspectModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" style="padding: 20px; max-height:83vh;">
            <div class="wizard-nav">
                <div class="wizard-circle" data-step="1">1</div>
                <div class="line"></div>
                <div class="wizard-circle" data-step="2">2</div>
                <div class="line"></div>
                <div class="wizard-circle" data-step="3">3</div>
                <div class="line"></div>
                <div class="wizard-circle" data-step="4">4</div>

            </div>
            <div class="gridcontainer1">
                <div class="grid-container">
                    <div class="alt-text1">Reason</div>
                    <div class="alt-text2">Confirmation</div>
                </div>
                <div class="grid-container" style="gap: 5px;">
                    <div class="alt-text3">Issue evidence</div>
                    <div class="alt-text4">Report confirmation</div>
                </div>
            </div>

            <br><br>

            <!-- Reason of report  -->
            <div class="wizard-step" data-step="1">
                <h3>Select a reason for reporting prospect</h3>
                <div class="form-group text-left">
                    <label for="reason"><?php echo _l('Reasons'); ?></label>
                    <select name="industry" class="selectpicker" data-width="100%"
                        data-none-selected-text="<?php echo _l('Select Reason'); ?>">
                        <option value=""><?php echo _l('Select Reason'); ?></option>
                        <?php foreach ($industries as $industry): ?>
                            <option value="<?php echo $industry['id']; ?>"><?php echo $industry['name']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <!-- prospect confirmation -->
            <div class="wizard-step" data-step="2">
                <h3>Prospect Details</h3>
                <div class="form-group text-left">
                    <label for="details"><?php echo _l('Details'); ?></label>
                    <div class="table-responsive">
                        <table class="table table-bordered dt-table nowrap" style="width:100%">
                            <thead>
                                <tr>
                                    <th><?php echo _l('Name'); ?></th>
                                    <th><?php echo _l('Status'); ?></th>
                                    <th><?php echo _l('Type'); ?></th>
                                    <th><?php echo _l('Category'); ?></th>
                                    <th><?php echo _l('Acquisition Channels'); ?></th>
                                    <th><?php echo _l('Desired Amount'); ?></th>
                                    <th><?php echo _l('Industry'); ?></th>
                                </tr>
                            </thead>
                            <tbody>

                                <tr>
                                    <td>Name</td>
                                    <td>Name</td>
                                    <td>Name</td>
                                    <td>Name</td>
                                    <td>Name</td>
                                    <td>Name</td>
                                    <td>Name</td>

                                </tr>

                            </tbody>
                        </table>

                        <div class="form-group text-center mt-4">
                            <button type="button" class="btn btn-success" id="confirm-details">I confirm that the
                                details are correct</button>
                            <button type="button" class="btn btn-danger" id="mistake-details">Oops, I made a
                                mistake</button>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Evidence -->

            <div class="wizard-step" data-step="3">
                <h3>Evidence</h3>
                <div class="form-group text-left">
                    <label for="evidence-upload"><?php echo _l('Upload Evidence (MP3)'); ?></label>
                    <input type="file" class="form-control-file" id="evidence-upload" accept=".mp3">
                </div>
                <div class="form-group text-center mt-4">
                    <button type="button" class="btn btn-success" id="confirm-evidence">I confirm that I uploaded the
                        evidence</button>
                    <button type="button" class="btn btn-danger" id="no-evidence">Oops, I don’t have any
                        evidence</button>
                </div>
            </div>

            <!-- Thank you page -->
            <div class="wizard-step" data-step="4">
                <h3>Thank You</h3>
                <div class="text-center mt-4">
                    <p>Your report has been successfully submitted.</p>
                    <p>An agent will review your request and handle it within the next 72 hours.</p>
                    <p>Thank you for your patience.</p>
                </div>
            </div>

            <div class="wizard-buttons">
                <button class="btn btn-secondary" id="backBtn"><i class="fas fa-angle-left" style="font-size:19px"></i>
                    Back</button>
                <button class="btn btn-primary" id="nextBtn">Next</button>
            </div>
        </div>
    </div>
</div>

<div id="sendApiProspectModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <?php echo get_instance()->load->view('clients/modals/send_prospect_api_modal.php') ?>
</div>


<?php
$jsonData = json_encode($table); ?>
<script>
    var tableData = <?php echo $jsonData; ?> ?? [];
    setTimeout(() => {
        $('#clients').DataTable();
    }, 100);

    function customers_bulk_action(event) {
        var r = confirm(app.lang.confirm_action_prompt);
        if (r == false) {
            return false;
        } else {
            var mass_delete = $('#mass_delete').prop('checked');
            var ids = [];
            var data = {};
            if (mass_delete == false || typeof (mass_delete) == 'undefined') {
                data.groups = $('select[name="move_to_groups_customers_bulk[]"]').selectpicker('val');
                if (data.groups.length == 0) {
                    data.groups = 'remove_all';
                }
            } else {
                data.mass_delete = true;
            }
            var rows = $('.table-clients').find('tbody tr');
            $.each(rows, function () {
                var checkbox = $($(this).find('td').eq(0)).find('input');
                if (checkbox.prop('checked') == true) {
                    ids.push(checkbox.val());
                }
            });
            data.ids = ids;
            $(event).addClass('disabled');
            setTimeout(function () {
                $.post(site_url + 'clients/bulk_action', data).done(function () {
                    window.location.reload();
                });
            }, 50);
        }
    }


    // General helper function for $.get ajax requests
    function requestGet_purchased(uri, params) {
        params = typeof params == "undefined" ? {} : params;
        var options = {
            type: "GET",
            url: uri.indexOf(site_url) > -1 ? uri : site_url + uri,
        };
        return $.ajax($.extend({}, options, params));
    }

    // General helper function for $.get ajax requests with dataType JSON
    function requestGetJSON_purchased(uri, params) {
        params = typeof params == "undefined" ? {} : params;
        params.dataType = "json";
        return requestGet_purchased(uri, params);
    }
    function init_lead_modal_data_purchased(id, url, isEdit) {
        var requestURL =
            (typeof url != "undefined" ? url : "leads/lead/") +
            (typeof id != "undefined" ? id : "");

        if (isEdit === true) {
            var concat = "?";
            if (requestURL.indexOf("?") > -1) {
                concat += "&";
            }
            requestURL += concat + "edit=true";
        }

        requestGetJSON_purchased(requestURL)
            .done(function (response) {
                _lead_init_data(response, id);
            })
            .fail(function (data) {
                alert_float("danger", data.responseText);
            });
    }
    function init_lead_purchased(id, isEdit) {
        if ($("#task-modal").is(":visible")) {
            $("#task-modal").modal("hide");
        }
        // In case header error
        if (init_lead_modal_data_purchased(id, undefined, isEdit)) {
            $("#lead-modal").modal("show");
        }
    }
</script>


<script>
    document.addEventListener('DOMContentLoaded', function () {
        let currentStep = 1;
        const totalSteps = 4;
        const steps = document.querySelectorAll('.wizard-step');
        const circles = document.querySelectorAll('.wizard-circle');
        const backBtn = document.getElementById('backBtn');
        const nextBtn = document.getElementById('nextBtn');
        const days = document.querySelectorAll('.days');


        function showStep(step) {
            steps.forEach((element, index) => {
                element.classList.toggle('active', index === step - 1);
            });
            circles.forEach((element, index) => {
                element.classList.toggle('active', index === step - 1);
                element.classList.toggle('completed', index < step - 1);
            });

            // Hide back button
            backBtn.style.display = step === 1 ? 'none' : 'inline-flex';

            // Show Finish Button at last page
            nextBtn.textContent = step === totalSteps ? 'Finish' : 'Next';
        }

        days.forEach(day => {
            day.addEventListener('click', function () {
                this.classList.toggle('active');
            });
        });

        nextBtn.addEventListener('click', function () {
            if (currentStep < totalSteps) {
                currentStep++;
                showStep(currentStep);
            } else if (currentStep === totalSteps) {
                // Finish button action


            }
        });

        backBtn.addEventListener('click', function () {
            if (currentStep > 1) {
                currentStep--;
                showStep(currentStep);
            }
        });

        document.getElementById('confirm-details').addEventListener('click', function () {
            // Add logic here to move to the next step in the wizard
            alert('Details confirmed! Moving to the next step.');
        });

        // Close the popup and show the message for mistakes
        document.getElementById('mistake-details').addEventListener('click', function () {
            alert('Please try again using the correct details.');
            // Logic to close the report popup page, like using window.close() or custom modal close function
            window.close(); // This works if it's a popup window. For a modal, you'd use the modal close function.
        });

        // Move to the next step when confirming evidence upload
        document.getElementById('confirm-evidence').addEventListener('click', function () {
            var fileInput = document.getElementById('evidence-upload');
            if (fileInput.files.length === 0) {
                alert('Please upload an MP3 file before confirming.');
            } else {
                // Add logic here to move to the next step in the wizard
                alert('Evidence uploaded! Moving to the next step.');
            }
        });

        // Close the popup and show the message for no evidence
        document.getElementById('no-evidence').addEventListener('click', function () {
            alert('We are sorry, without any evidence we cannot handle the report request.');
            // Logic to close the report popup page, like using window.close() or custom modal close function
            window.close(); // This works if it's a popup window. For a modal, you'd use the modal close function.
        });

        showStep(currentStep);
    });
</script>
<script src="<?= site_url('assets/js/main_purchased.js') ?>"></script>
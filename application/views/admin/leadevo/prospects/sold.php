<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php
function displayStars($rating, $maxStars = 5)
{
    if ($rating == 0 || $rating == '') {
        echo '-';
        return;
    }
    for ($i = 1; $i <= $maxStars; $i++) {
        echo '<span class="star' . ($i <= $rating ? ' filled' : '') . '">&#9733;</span>';
    }
}
?>
<?php init_head(); ?>

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

    .star.filled {
        color: orange;
    }
</style>
<div id="wrapper">
    <div class="content">
        <div class="row main_row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">

                        <a href="#" data-toggle="modal" data-target="#customers_bulk_action"
                            class="bulk-actions-btn table-btn hide"
                            data-table=".table-clients"><?php echo _l('bulk_actions'); ?></a>
                        <div class="modal fade bulk_actions" id="customers_bulk_action" tabindex="-1" role="dialog">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal"
                                            aria-label="Close"><span aria-hidden="true">&times;</span></button>
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

                            [
                                'name' => _l('campaign_id'),
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
                        <div class="table-responsive">
                            <table class="table table-bordered dt-table" id="clients">
                                <thead>
                                    <tr>
                                        <th><?= _l('id') ?></th>
                                        <th><?= _l('name') ?></th>
                                        <th><?= _l('leadevo_email') ?></th>
                                        <th><?= _l('leadevo_phone') ?></th>
                                        <th><?= _l('Stars'); ?></th>
                                        <th><?= _l('leadevo_price') ?></th>
                                        <th><?= _l('lead_import_source') ?></th>
                                        <th><?= _l('invoice_dt_table_heading_status') ?></th>
                                        <th><?= _l('lead_source') ?></th>
                                        <th><?= _l('expense_dt_table_heading_date') ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($prospects as $prospect): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($prospect->id ?? ''); ?></td>
                                            <td><?php echo htmlspecialchars($prospect->name ?? ''); ?></td>
                                            <td><?php echo htmlspecialchars($prospect->email ?? ''); ?>
                                                <div class="row-options">
                                                    <a href="#"
                                                        onclick="openViewModal(<?= $prospect->prospect_id ?>)">View</a>
                                                </div>
                                            </td>
                                            <td><?php echo htmlspecialchars($prospect->phonenumber ?? 'N/A'); ?></td>
                                            <td>
                                                <div class="star-rating">
                                                    <?php
                                                    // Example usage
                                                    $userRating = $prospect->rating ?? 0; // This value could come from a database
                                                    displayStars($userRating);
                                                    ?>
                                                </div>
                                            </td>
                                            <td><?php echo htmlspecialchars($prospect->sold_price ?? ''); ?></td>
                                            <td><?php echo $prospect->campaign_id == null ? 'Cart' : 'Campaign' ?>

                                                <div class="row-options">
                                                    <?php if ($prospect->campaign_id != null): ?><a
                                                            href="<?php echo site_url('campaigns/campaign/' . $prospect->campaign_id) ?>">View</a><?php endif; ?>
                                                    <?php if ($prospect->campaign_id == null): ?><a
                                                            href="<?php echo site_url('invoice/' . $prospect->invoice_id . '/' . $prospect->invoice_hash) ?>">View
                                                            Invoice</a><?php endif; ?>
                                                </div>
                                            </td>
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
    </div>
</div>

<!-- View Prospect Modal -->
<div class="modal fade" id="viewProspectModal" tabindex="-1" role="dialog">
<?php echo get_instance()->load->view('admin/leadevo/prospects/modals/view_prospect.php') ?>
</div>


<?php $jsonData = json_encode($prospects); ?>
<script>
    var tableData = <?php echo $jsonData; ?> ?? [];

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

    function openViewModal(id) {
        document.querySelector('#viewProspectModal input[name=id]').value = id;
        $('#viewProspectModal').modal('show');
    }
</script>

<script src="<?= site_url('assets/js/main_purchased.js') ?>"></script>

<?php init_tail(); ?>
</body>

</html>
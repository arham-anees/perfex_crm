<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

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
                                <th>Company</th>
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
                                    <td><?php echo htmlspecialchars($prospect->company ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($prospect->name ?? ''); ?>
                                        <div class="row-options"><a href="http://localhost/perfex_crm/leads/index/42"
                                                onclick="init_lead(42);return false;">View</a> | <a
                                                href="http://localhost/perfex_crm/leads/index/42?edit=true"
                                                onclick="init_lead(42, true);return false;">Edit </a> | <a
                                                href="http://localhost/perfex_crm/leads/delete/42"
                                                class="_delete text-danger">Delete </a></div>
                                    </td>
                                    <td><?php echo htmlspecialchars($prospect->email ?? ''); ?></td>
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
<script src="<?= site_url('assets/js/main_purchased.js') ?>"></script>
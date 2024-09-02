<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row main_row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <div class="_buttons">
                            <a href="<?php echo admin_url('leadevo/lead_reasons/create'); ?>"
                                class="btn btn-primary pull-left display-block mleft10">
                                <i class="fa-regular fa-plus tw-mr-1"></i>
                                <?php echo _l('leadevo_new_lead_report_reason'); ?>
                            </a>
                            <a href="#" onclick="openReportHoursModal()"
                                class="btn btn-primary pull-left display-block mleft10">
                                <?php echo _l('leadevo_lead_report_hours'); ?>
                            </a>
                            <div class="clearfix"></div>
                        </div>
                        <hr class="hr-panel-heading" />
                        <?php if (!empty($reasons)): ?>
                            <table class="table dt-table scroll-responsive">
                                <thead>
                                    <tr>
                                        <th><?php echo _l('Name'); ?></th>
                                        <th><?php echo _l('Description'); ?></th>
                                        <th><?php echo _l('Status'); ?></th>
                                        <th><?php echo _l('Actions'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($reasons as $reason): ?>
                                        <tr>
                                            <td><?php echo $reason->name; ?></td>
                                            <td><?php echo $reason->description; ?></td>
                                            <td><?php echo $reason->is_active==1?'Active':'Inactive'; ?></td>
                                            <td>
                                                <a href="<?php echo admin_url('leadevo/lead_reasons/view/' . $reason->id); ?>"
                                                    class="btn btn-default btn-icon">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                                <a href="<?php echo admin_url('leadevo/lead_reasons/edit/' . $reason->id); ?>"
                                                    class="btn btn-default btn-icon">
                                                    <i class="fa fa-pencil"></i>
                                                </a>
                                                <a href="<?php echo admin_url('leadevo/lead_reasons/delete/' . $reason->id); ?>"
                                                    class="btn btn-danger btn-icon"
                                                    onclick="return confirm('Are you sure you want to delete this lead reason?');">
                                                    <i class="fa fa-remove"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php else: ?>
                            <p><?php echo _l('No lead reasons found.'); ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- modal for hours -->
<div id="report_hours_modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <style>
            #report-hours-form {
                min-height: 90px;
            }
        </style>
        <div class="modal-content" style="padding: 20px; max-height:83vh;">

            <p><?= _l('leadevo_update_report_reason_description') ?></p>
            <?php echo form_open('', ['id' => 'report-hours-form']) ?>
            <div class="form-group">
                <label for="report_hours" class="control-label clearfix"><?= _l('leadevo_report_hours_input') ?>
                    <input type="text" name="report_hours" id="report_hours" class="form-control" />
            </div>
            <div>
                <input type="button" id="submit-button" value="<?= _l('leadevo_send_via_api_button') ?>"
                    class="btn btn-primary pull-right" />
            </div>
            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>"
                value="<?php echo $this->security->get_csrf_hash(); ?>">

            <input type="hidden" name="lead_data" value="">
            <?php echo form_close(); ?>
        </div>
    </div>
</div>

<script>
    function openReportHoursModal() {
        $('#report_hours_modal').modal('show');
        $.ajax({
            url: 'lead_reasons/get_report_hours',
            type: "GET",
            success: (res) => {
                console.log(res);
                res = JSON.parse(res);
                if (res.status == 'success') {
                    $('input[name=report_hours]').val(res.data);
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
    document.addEventListener('DOMContentLoaded', function () {
        $('#submit-button').on('click', function (e) {
            e.preventDefault(); // Prevent the form from submitting via the browser

            var csrfName = $('input[name="<?php echo $this->security->get_csrf_token_name(); ?>"]').attr('name');
            var csrfHash = $('input[name="<?php echo $this->security->get_csrf_token_name(); ?>"]').val();


            var report_hours = $('input[name=report_hours]').val();
            var data = {};
            data[csrfName] = csrfHash;
            data['report_hours'] = report_hours;
            $.ajax({
                url: 'lead_reasons/set_report_hours',
                data,
                type: "POST",
                success: (res) => {
                    console.log(res);
                    try {
                        res = JSON.parse(res);
                        if (res.status == 'success') {
                            alert_float('success', 'Lead data sent successfully');
                            $('#report_hours_modal').modal('hide');
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
    })
</script>
<?php init_tail(); ?>
</body>

</html>
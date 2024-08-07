<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="row">
    <div class="col-md-12">
        <div class="panel_s">
            <div class="panel-body">
                <h4 class="no-margin"><?php echo _l('Create New Campaign'); ?></h4>
                <hr class="hr-panel-heading" />
                <?php if ($this->session->flashdata('error')): ?>
                    <div class="alert alert-danger"><?php echo $this->session->flashdata('error'); ?></div>
                <?php endif; ?>
                <?php echo form_open(admin_url('leadevo/campaigns/create')); ?>

                <div class="form-group">
                    <label for="name"><?php echo _l('Name'); ?></label>
                    <input type="text" id="name" name="name" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="description"><?php echo _l('Description'); ?></label>
                    <textarea id="description" name="description" class="form-control" required></textarea>
                </div>

                <div class="form-group">
                    <label for="start_date"><?php echo _l('Start Date'); ?></label>
                    <input type="date" id="start_date" name="start_date" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="end_date"><?php echo _l('End Date'); ?></label>
                    <input type="date" id="end_date" name="end_date" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="status_id"><?php echo _l('Status'); ?></label>
                    <select id="status_id" name="status_id" class="form-control" required>
                        <?php foreach ($statuses as $status) { ?>
                            <option value="<?php echo $status['id']; ?>"><?php echo $status['name']; ?></option>
                        <?php } ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="budget"><?php echo _l('Budget'); ?></label>
                    <input type="number" id="budget" name="budget" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="is_active"><?php echo _l('Active'); ?></label>
                    <input type="checkbox" id="is_active" name="is_active">
                </div>

                <button type="submit" class="btn btn-primary"><?php echo _l('Save'); ?></button>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>
<script>
    document.getElementById('campaign-form').addEventListener('submit', function (event) {
        var startDate = new Date(document.getElementById('start_date').value);
        var endDate = new Date(document.getElementById('end_date').value);
        var currentDate = new Date();

        if (startDate < currentDate) {
            alert('Start date cannot be before the current date.');
            event.preventDefault();
        } else if (endDate < startDate) {
            alert('End date cannot be before the start date.');
            event.preventDefault();
        } else if (endDate < currentDate) {
            alert('End date cannot be before the current date.');
            event.preventDefault();
        }
    });
</script>
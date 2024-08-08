<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <h4 class="no-margin"><?php echo _l('Create Prospect Status'); ?></h4>
                        <hr class="hr-panel-heading" />
                        <?php echo form_open(admin_url('leadEvo/prospectstatus/create')); ?>
                        <div class="form-group">
                            <label for="name"><?php echo _l('Name'); ?></label>
                            <input type="text" id="name" name="name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="description"><?php echo _l('Description'); ?></label>
                            <textarea id="description" name="description" class="form-control" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="is_active"><?php echo _l('Active'); ?></label>
                            <input type="checkbox" id="is_active" name="is_active">
                        </div>
                        <button type="submit" class="btn btn-primary"><?php echo _l('Create'); ?></button>
                        <?php echo form_close(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
</body>
</html>

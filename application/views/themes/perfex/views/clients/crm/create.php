<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="row main_row">
    <div class="col-md-12">
        <div class="panel_s">
            <div class="panel-body">
                <h4><?php echo _l('create_crm_link'); ?></h4>

                <?php echo form_open('crm/create'); ?>
                
                <div class="form-group">
                    <label for="name"><?php echo _l('name'); ?></label>
                    <input type="text" class="form-control" id="name" name="name" value="<?php echo set_value('name'); ?>" required>
                </div>

                <div class="form-group">
                    <label for="description"><?php echo _l('description'); ?></label>
                    <textarea class="form-control" id="description" name="description"><?php echo set_value('description'); ?></textarea>
                </div>

                <div class="form-group">
                    <label for="links"><?php echo _l('link'); ?></label>
                    <input type="text" class="form-control" id="links" name="links" value="<?php echo set_value('links'); ?>" required>
                </div>

                <button type="submit" class="btn btn-primary"><?php echo _l('save'); ?></button>
                <a href="<?php echo site_url('crm'); ?>" class="btn btn-default"><?php echo _l('cancel'); ?></a>

                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>

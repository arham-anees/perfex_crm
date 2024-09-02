<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="row main_row">
    <div class="col-md-12">
        <div class="panel_s">
            <div class="panel-body">
            <h4><?php echo _l('leadevo_zapier_webhook_edit'); ?></h4>
                <?php echo form_open('Zapier/edit/' . $webhook->id); ?>

                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" class="form-control" id="name" name="name" value="<?php echo set_value('name', $webhook->name); ?>" required>
                </div>

                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea class="form-control" id="description" name="description" required><?php echo set_value('description', $webhook->description); ?></textarea>
                </div>

                <div class="form-group">
                    <label for="webhook">Webhook</label>
                    <input type="text" class="form-control" id="webhook" name="webhook" value="<?php echo set_value('webhook', $webhook->webhook); ?>" required>
                </div>

                <button type="submit" class="btn btn-primary">Update</button>
                <a href="<?php echo site_url('clients/zapier'); ?>" class="btn btn-default"><?php echo _l('back_to_list'); ?></a>

                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>

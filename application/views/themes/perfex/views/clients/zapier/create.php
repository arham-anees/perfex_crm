<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="row main_row">
    <div class="col-md-12">
        <div class="panel_s">
            <div class="panel-body">
            <h4><?php echo _l('leadevo_zapier_webhook_create'); ?></h4>
                <?php echo form_open('Zapier/create'); ?>

                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>

                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea class="form-control" id="description" name="description" required></textarea>
                </div>

                <div class="form-group">
                    <label for="webhook">Webhook</label>
                    <input type="text" class="form-control" id="webhook" name="webhook" required>
                </div>

                <button type="submit" class="btn btn-primary">Submit</button>
                <a href="<?php echo site_url('clients/zapier'); ?>" class="btn btn-default"><?php echo _l('back_to_list'); ?></a>


                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>

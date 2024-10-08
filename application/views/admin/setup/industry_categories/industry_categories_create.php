<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row main_row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <h4 class="no-margin"><?php echo _l('New Industry Category'); ?></h4>
                        <hr class="hr-panel-heading" />
                        <?php echo validation_errors('<div class="alert alert-danger text-center">', '</div>'); ?>

                        <?php echo form_open(admin_url('leadevo/industry_categories/create')); ?>
                        <div class="form-group">
                            <label for="name"><?php echo _l('Name'); ?></label>
                            <input type="text" id="name" name="name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="min_price"><?php echo _l('leadevo_industry_category_min_price'); ?></label>
                            <input type="number" id="min_price" name="min_price" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label
                                for="min_market_price"><?php echo _l('leadevo_industry_category_min_market_price'); ?></label>
                            <input type="number" id="min_market_price" name="min_market_price" class="form-control"
                                required>
                        </div>
                        <div class="form-group">
                            <label for="description"><?php echo _l('Description'); ?></label>
                            <textarea id="description" name="description" class="form-control" ></textarea>
                        </div>
                        <div class="form-group">
                            <label for="description" class="control-label clearfix"><?php echo _l('Status'); ?></label>
                            <div class="radio radio-primary radio-inline">
                                <input type="radio" id="is_active" name="is_active" value="1" checked>
                                <label for="is_active" >Active</label>
                            </div>
                            <div class="radio radio-primary radio-inline">
                                <input type="radio" id="is_actives" name="is_active" value="">
                                <label for="is_actives">In Active</label>
                            </div>
                        </div>


                        <button type="submit" class="btn btn-primary"><?php echo _l('Save'); ?></button>
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

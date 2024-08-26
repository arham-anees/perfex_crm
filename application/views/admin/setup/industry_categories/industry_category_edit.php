<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row main_row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <h4 class="no-margin"><?php echo _l('Edit Industry Category'); ?></h4>
                        <hr class="hr-panel-heading" />
                        <?php echo form_open(admin_url('leadevo/industry_categories/edit/' . $category['id'])); ?>
                        <div class="form-group">
                            <label for="name"><?php echo _l('Name'); ?></label>
                            <input type="text" id="name" name="name" class="form-control"
                                value="<?php echo $category['name']; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="min_price"><?php echo _l('Name'); ?></label>
                            <input type="number" id="min_price" name="min_price" class="form-control"
                                value="<?php echo $category['min_price']; ?>" required>
                        </div>

                        <div class="form-group">
                            <label
                                for="min_market_price"><?php echo _l('leadevo_industry_category_min_market_price'); ?></label>
                            <input type="number" id="min_market_price" name="min_market_price" class="form-control"
                                value="<?php echo $category['min_market_price']; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="description"><?php echo _l('Description'); ?></label>
                            <textarea id="description" name="description" class="form-control"
                                required><?php echo $category['description']; ?></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary"><?php echo _l('Save Changes'); ?></button>
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
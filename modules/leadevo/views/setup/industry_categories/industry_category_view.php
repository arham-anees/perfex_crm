<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row main_row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <h4 class="no-margin"><?php echo _l('Industry Category Details'); ?></h4>
                        <hr class="hr-panel-heading" />
                        <p><strong><?php echo _l('Name'); ?>:</strong> <?php echo $category['name']; ?></p>
                        <p><strong><?php echo _l('Description'); ?>:</strong> <?php echo $category['description']; ?></p>
                        <p><strong><?php echo _l('Active'); ?>:</strong> <?php echo $category['is_active'] ? 'Yes' : 'No'; ?></p>
                        <a href="<?php echo admin_url('leadevo/industry_categories/edit/' . $category['id']); ?>" class="btn btn-primary"><?php echo _l('Edit'); ?></a>
                        <a href="<?php echo admin_url('leadevo/industry_categories'); ?>" class="btn btn-default"><?php echo _l('Back'); ?></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
</body>
</html>

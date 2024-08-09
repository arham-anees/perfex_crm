<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <h4 class="no-margin"><?php echo _l('View Industry'); ?></h4>
                        <hr class="hr-panel-heading" />
                        <p><strong><?php echo _l('Name'); ?>:</strong> <?php echo htmlspecialchars($industry->name); ?></p>
                        <p><strong><?php echo _l('Description'); ?>:</strong> <?php echo htmlspecialchars($industry->description); ?></p>
                        <p><strong><?php echo _l('Status'); ?>:</strong> <?php echo $industry->is_active ? _l('Active') : _l('Inactive'); ?></p>
                        <a href="<?php echo admin_url('leadevo/industries/edit/' . $industry->id); ?>" class="btn btn-primary">
                                <?php echo _l('Edit'); ?>
                            </a>
                        <a href="<?php echo admin_url('leadevo/industries'); ?>" class="btn btn-default"><?php echo _l('Back'); ?></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
</body>
</html>

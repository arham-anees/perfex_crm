<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row main_row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <h4><?php echo _l('Are you sure you want to delete this prospect type?'); ?></h4>
                        <p><?php echo _l('Name:') . ' ' . $type->name; ?></p>
                        <p><?php echo _l('Description:') . ' ' . $type->description; ?></p>
                        <a href="<?php echo admin_url('leadevo/prospect_types/delete/' . $type->id . '?confirm=true'); ?>"
                            class="btn btn-danger"><?php echo _l('Yes, delete it'); ?></a>
                        <a href="<?php echo admin_url('leadevo/prospect_types'); ?>"
                            class="btn btn-default"><?php echo _l('Cancel'); ?></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
</body>

</html>
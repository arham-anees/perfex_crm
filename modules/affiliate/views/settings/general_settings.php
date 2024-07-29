<?php defined('BASEPATH') or exit('No direct script access allowed');
$affiliate_minimum_inventory = get_option('affiliate_minimum_inventory');
$affiliate_maximum_inventory = get_option('affiliate_maximum_inventory');
?>
<?php echo form_open(admin_url('affiliate/reset_data')); ?>
<div class="row mbot10">
    <div class="col-md-12">
        <?php if (affiliate_has_permission('settings', '', 'delete')) { ?>
        	<button type="submit" class="btn btn-info _delete"><?php echo _l('reset_data'); ?></button> <label class="text-danger"><?php echo _l('affiliate_reset_button_tooltip'); ?></label>
        <?php } ?>
	</div>
</div>
<hr>
<?php echo form_close(); ?>
<?php echo form_open(admin_url('affiliate/update_setting')); ?>
<?php echo render_input('affiliate_minimum_inventory','minimum_inventory',$affiliate_minimum_inventory,'number'); ?>
<?php echo render_input('affiliate_maximum_inventory','maximum_inventory',$affiliate_maximum_inventory,'number'); ?>
<div class="modal-footer">
        <?php if (affiliate_has_permission('settings', '', 'edit')) { ?>
    <button type="submit" class="btn btn-info"><?php echo _l('submit'); ?></button>
        <?php } ?>
</div>
<?php echo form_close(); ?>

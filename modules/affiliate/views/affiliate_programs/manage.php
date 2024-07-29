<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
 <div class="content">
  <div class="col-md-12">
    <div class="panel_s">
     <div class="panel-body">
      <?php if (affiliate_has_permission('affiliate_program', '', 'create')) { ?>
        <a href="<?php echo admin_url('affiliate/affiliate_program'); ?>" class="btn btn-info mbot10"><?php echo _l('new'); ?></a>
      <?php } ?>
      <table class="table table-affiliate-program">
        <thead>
          <th><?php echo _l('name'); ?></th>
          <th><?php echo _l('from_date'); ?></th>
          <th><?php echo _l('to_date'); ?></th>
          <th><?php echo _l('priority'); ?></th>
          <th><?php echo _l('discount'); ?></th>
          <th><?php echo _l('commission'); ?></th>
          <th><?php echo _l('datecreated'); ?></th>
          <th><?php echo _l('options'); ?></th>
        </thead>
        <tbody>
          
        </tbody>
      </table>
    </div>
  </div>
</div>
</div>
</div>
<?php init_tail(); ?>
<?php require 'modules/affiliate/assets/js/manage_affiliate_program_js.php';?>

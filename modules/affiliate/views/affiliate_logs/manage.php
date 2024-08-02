<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head();?>
<div id="wrapper">
  <div class="content">
    <div class="row">
      <div class="panel_s">
        <div class="panel-body">
          <h4 class="no-margin font-bold"><?php echo _l($title); ?></h4>
          <hr />
          <div class="row">
            <div class="col-md-3">
              <?php  echo render_select('member_filter', $members, array('id', 'firstname', 'lastname'), 'member', '', array('multiple' => true, 'data-actions-box' => true), array(), '', '', false); ?>
              </div>
            <div class="col-md-3">
          <?php
                echo render_select('affiliate_programs', $affiliate_programs, array('id', 'name'), 'affiliate_programs', '', array('multiple' => true, 'data-actions-box' => true), array(), '', '', false); ?>
              </div>
            <div class="col-md-3">
              <?php echo render_date_input('from_date','from_date'); ?>
            </div>
            <div class="col-md-3">
              <?php echo render_date_input('to_date','to_date'); ?>
            </div>
          </div>
          <table class="table table-affiliate-logs">
            <thead>
              <th><?php echo _l('program_name'); ?></th>
              <th><?php echo _l('user_ip'); ?></th>
              <th><?php echo _l('type'); ?></th>
              <th><?php echo _l('description'); ?></th>
              <th><?php echo _l('date'); ?></th>
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
</body>
</html>

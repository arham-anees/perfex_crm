<?php defined('BASEPATH') or exit('No direct script access allowed');?>
<?php init_head();?>
<div id="wrapper" class="commission">
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
                $prioritys = [0 => ['id' => '3', 'name' => _l('waiting')],
                              1 => ['id' => '2', 'name' => _l('reject')],
                              2 => ['id' => '1', 'name' => _l('approve')],
                             ];
                echo render_select('approve_status', $prioritys, array('id', 'name'), 'status', '', array('multiple' => true, 'data-actions-box' => true), array(), '', '', false); ?>
              </div>
            <div class="col-md-3">
              <?php echo render_date_input('from_date','from_date'); ?>
            </div>
            <div class="col-md-3">
              <?php echo render_date_input('to_date','to_date'); ?>
            </div>
          </div>
          <table class="table table-affiliate-orders">
            <thead>
              <th><?php echo _l('order_code'); ?></th>
              <th><?php echo _l('name'); ?></th>
              <th><?php echo _l('total'); ?></th>
              <th><?php echo _l('date_add'); ?></th>
              <th><?php echo _l('status'); ?></th>
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
<?php init_tail();?>
</body>
</html>
